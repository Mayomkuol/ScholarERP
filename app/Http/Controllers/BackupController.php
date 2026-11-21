<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Services\AuditLogger;

class BackupController extends Controller
{
    /**
     * XAMPP MySQL/MariaDB binary directory.
     */
    private string $mysqlBinPath = 'C:\\xampp\\mysql\\bin';

    /**
     * Backup directory.
     */
    private string $backupDirectory = 'backups';


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

public function index()
{
    $backupPath = storage_path(
        'app/private/' . $this->backupDirectory
    );

    if (!File::exists($backupPath)) {
        File::makeDirectory(
            $backupPath,
            0755,
            true
        );
    }

    $backups = collect(File::files($backupPath))
        ->filter(function ($file) {
            return strtolower($file->getExtension()) === 'sql';
        })
        ->sortByDesc(function ($file) {
            return $file->getMTime();
        })
        ->values()
        ->map(function ($file) {

            $fileName = $file->getFilename();

            $isSafety = str_starts_with(
                $fileName,
                'scholarerp_before_restore_'
            );

            $fileSize = $file->getSize();

            return [
                'name' => $fileName,

                'size' => $fileSize,
                'size_human' => $this->formatBytes($fileSize),

                'created_at' => date(
                    'd M Y, h:i A',
                    $file->getMTime()
                ),

                'path' => $file->getPathname(),

                'is_safety' => $isSafety,
            ];
        });

    return view('backups.index', [
        'backups' => $backups,
        'database' => $this->databaseConfig()['database'],
        'databaseConnected' => $this->testDatabaseConnection(),
    ]);
}

    /*
    |--------------------------------------------------------------------------
    | CREATE BACKUP
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $config = $this->databaseConfig();

        $backupDirectory = storage_path(
            'app/private/' . $this->backupDirectory
        );

        if (!File::exists($backupDirectory)) {
            File::makeDirectory(
                $backupDirectory,
                0755,
                true
            );
        }

        $fileName =
            'scholarerp_backup_' .
            now()->format('Y-m-d_H-i-s') .
            '.sql';

        $backupPath =
            $backupDirectory .
            DIRECTORY_SEPARATOR .
            $fileName;

        try {

            /*
             * Build mysqldump arguments.
             *
             * IMPORTANT:
             * We DO NOT build one giant Windows command string.
             */
            $arguments = [
                '--host=127.0.0.1',
                '--port=3306',
                '--user=' . $config['username'],
            ];

            /*
             * Add password only if configured.
             */
            if ($config['password'] !== '') {
                $arguments[] =
                    '--password=' . $config['password'];
            }

            /*
             * Database.
             */
            $arguments[] = $config['database'];

            /*
             * Backup options.
             */
            $arguments[] = '--single-transaction';
            $arguments[] = '--routines';
            $arguments[] = '--triggers';
            $arguments[] = '--events';
            $arguments[] = '--no-tablespaces';

            /*
             * Execute mysqldump and write directly to file.
             */
            $result = $this->executeProcessToFile(
                $this->mysqldumpPath(),
                $arguments,
                $backupPath
            );

            if (!$result['success']) {

                if (File::exists($backupPath)) {
                    File::delete($backupPath);
                }

                throw new \RuntimeException(
                    'mysqldump failed: ' .
                    $result['error']
                );
            }

            /*
             * Verify backup.
             */
            if (
                !File::exists($backupPath) ||
                File::size($backupPath) === 0
            ) {

                if (File::exists($backupPath)) {
                    File::delete($backupPath);
                }

                throw new \RuntimeException(
                    'The backup file was created but is empty.'
                );
            }

            return redirect()
                ->route('backups.index')
                ->with(
                    'success',
                    'Database backup created successfully: ' .
                    $fileName
                );

        } catch (\Throwable $e) {

            if (File::exists($backupPath)) {
                File::delete($backupPath);
            }

            return redirect()
                ->route('backups.index')
                ->with(
                    'error',
                    'Backup failed: ' .
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD BACKUP
    |--------------------------------------------------------------------------
    */

    public function download(
        string $backup
    ): BinaryFileResponse {

        /*
         * Prevent path traversal.
         */
        $backup = basename($backup);

        $path = storage_path(
            'app/private/' .
            $this->backupDirectory .
            DIRECTORY_SEPARATOR .
            $backup
        );

        if (
            !File::exists($path) ||
            strtolower(File::extension($path)) !== 'sql'
        ) {
            abort(
                404,
                'Backup file not found.'
            );
        }

        return response()->download(
            $path,
            $backup,
            [
                'Content-Type' => 'application/sql',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RESTORE
    |--------------------------------------------------------------------------
    */

    public function restore(string $backup)
    {
        /*
         * Prevent path traversal.
         */
        $backup = basename($backup);

        $backupPath = storage_path(
            'app/private/' .
            $this->backupDirectory .
            DIRECTORY_SEPARATOR .
            $backup
        );

        /*
         * Check backup exists.
         */
        if (
            !File::exists($backupPath) ||
            strtolower(File::extension($backupPath)) !== 'sql'
        ) {
            return redirect()
                ->route('backups.index')
                ->with(
                    'error',
                    'The selected backup file could not be found.'
                );
        }

        /*
         * Check backup is not empty.
         */
        if (File::size($backupPath) === 0) {
            return redirect()
                ->route('backups.index')
                ->with(
                    'error',
                    'The selected backup file is empty.'
                );
        }

        /*
         * Create safety backup first.
         */
        $safetyBackup = $this->createSafetyBackup();

        if ($safetyBackup === false) {

            return redirect()
                ->route('backups.index')
                ->with(
                    'error',
                    'Restore cancelled. ScholarERP could not create a safety backup before restoring.'
                );
        }

        $config = $this->databaseConfig();

        try {

            /*
             * Build mysql arguments.
             */
            $arguments = [
                '--host=127.0.0.1',
                '--port=3306',
                '--user=' . $config['username'],
            ];

            if ($config['password'] !== '') {
                $arguments[] =
                    '--password=' . $config['password'];
            }

            /*
             * Database.
             */
            $arguments[] = $config['database'];

            /*
             * Execute mysql using the SQL backup
             * as STDIN.
             */
            $result = $this->executeProcessFromFile(
                $this->mysqlPath(),
                $arguments,
                $backupPath
            );

            if (!$result['success']) {

                throw new \RuntimeException(
                    'mysql restore failed: ' .
                    $result['error']
                );
            }

            /*
             * Clear Laravel caches.
             */
            try {
                \Artisan::call(
                    'optimize:clear'
                );
            } catch (\Throwable $e) {
                /*
                 * Database restore already succeeded.
                 */
            }

            return redirect()
                ->route('backups.index')
                ->with(
                    'success',
                    'Database restored successfully from ' .
                    $backup .
                    '. A safety backup was created before the restore.'
                );

        } catch (\Throwable $e) {

            return redirect()
                ->route('backups.index')
                ->with(
                    'error',
                    'Restore failed: ' .
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE BACKUP
    |--------------------------------------------------------------------------
    */

    public function destroy(string $backup)
    {
        /*
         * Prevent path traversal.
         */
        $backup = basename($backup);

        $path = storage_path(
            'app/private/' .
            $this->backupDirectory .
            DIRECTORY_SEPARATOR .
            $backup
        );

        if (
            !File::exists($path) ||
            strtolower(File::extension($path)) !== 'sql'
        ) {
            return redirect()
                ->route('backups.index')
                ->with(
                    'error',
                    'Backup file not found.'
                );
        }

        try {

            File::delete($path);

            return redirect()
                ->route('backups.index')
                ->with(
                    'success',
                    'Backup deleted successfully.'
                );

        } catch (\Throwable $e) {

            return redirect()
                ->route('backups.index')
                ->with(
                    'error',
                    'Could not delete backup: ' .
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SAFETY BACKUP
    |--------------------------------------------------------------------------
    */

    private function createSafetyBackup(): string|false
    {
        $config = $this->databaseConfig();

        $backupDirectory = storage_path(
            'app/private/' . $this->backupDirectory
        );

        if (!File::exists($backupDirectory)) {
            File::makeDirectory(
                $backupDirectory,
                0755,
                true
            );
        }

        $fileName =
            'scholarerp_before_restore_' .
            now()->format('Y-m-d_H-i-s') .
            '.sql';

        $backupPath =
            $backupDirectory .
            DIRECTORY_SEPARATOR .
            $fileName;

        try {

            $arguments = [
                '--host=127.0.0.1',
                '--port=3306',
                '--user=' . $config['username'],
            ];

            if ($config['password'] !== '') {
                $arguments[] =
                    '--password=' . $config['password'];
            }

            $arguments[] =
                $config['database'];

            $arguments[] =
                '--single-transaction';

            $arguments[] =
                '--routines';

            $arguments[] =
                '--triggers';

            $arguments[] =
                '--events';

            $arguments[] =
                '--no-tablespaces';

            $result = $this->executeProcessToFile(
                $this->mysqldumpPath(),
                $arguments,
                $backupPath
            );

            if (
                !$result['success'] ||
                !File::exists($backupPath) ||
                File::size($backupPath) === 0
            ) {

                if (File::exists($backupPath)) {
                    File::delete($backupPath);
                }

                return false;
            }

            return $backupPath;

        } catch (\Throwable $e) {

            if (File::exists($backupPath)) {
                File::delete($backupPath);
            }

            return false;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | EXECUTE PROCESS -> FILE
    |--------------------------------------------------------------------------
    */

    private function executeProcessToFile(
        string $executable,
        array $arguments,
        string $outputFile
    ): array {

        if (!File::exists($executable)) {

            return [
                'success' => false,
                'error' =>
                    'Executable not found: ' .
                    $executable,
            ];
        }

        /*
         * Build command safely.
         *
         * Only the executable path is quoted here.
         * Arguments are passed separately by proc_open.
         */
        $command = '"' . $executable . '"';

        foreach ($arguments as $argument) {

            $command .= ' ' .
                $this->windowsArgument(
                    $argument
                );
        }

        /*
         * Open output file.
         */
        $outputHandle = fopen(
            $outputFile,
            'wb'
        );

        if ($outputHandle === false) {

            return [
                'success' => false,
                'error' =>
                    'Could not create backup file.',
            ];
        }

        /*
         * Temporary error file.
         */
        $errorFile = storage_path(
            'app/private/' .
            $this->backupDirectory .
            DIRECTORY_SEPARATOR .
            'process_error_' .
            uniqid() .
            '.txt'
        );

        $errorHandle = fopen(
            $errorFile,
            'wb'
        );

        if ($errorHandle === false) {

            fclose($outputHandle);

            return [
                'success' => false,
                'error' =>
                    'Could not create temporary error file.',
            ];
        }

        $descriptors = [
            0 => [
                'pipe',
                'rb',
            ],

            1 => [
                'file',
                $outputFile,
                'wb',
            ],

            2 => [
                'file',
                $errorFile,
                'wb',
            ],
        ];

        /*
         * Close handles we opened ourselves.
         */
        fclose($outputHandle);
        fclose($errorHandle);

        $process = proc_open(
            $command,
            $descriptors,
            $pipes,
            base_path()
        );

        if (!is_resource($process)) {

            if (File::exists($errorFile)) {
                File::delete($errorFile);
            }

            return [
                'success' => false,
                'error' =>
                    'Could not start mysqldump process.',
            ];
        }

        /*
         * Close STDIN because mysqldump does not
         * need input during backup.
         */
        fclose($pipes[0]);

        /*
         * Wait for completion.
         */
        $exitCode = proc_close($process);

        /*
         * Read error.
         */
        $error = '';

        if (File::exists($errorFile)) {

            $error = trim(
                File::get($errorFile)
            );

            File::delete($errorFile);
        }

        return [
            'success' => $exitCode === 0,
            'error' => $error !== ''
                ? $error
                : 'Process exited with code ' .
                    $exitCode,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | EXECUTE PROCESS <- FILE
    |--------------------------------------------------------------------------
    */

    private function executeProcessFromFile(
        string $executable,
        array $arguments,
        string $inputFile
    ): array {

        if (!File::exists($executable)) {

            return [
                'success' => false,
                'error' =>
                    'Executable not found: ' .
                    $executable,
            ];
        }

        if (!File::exists($inputFile)) {

            return [
                'success' => false,
                'error' =>
                    'SQL backup file not found.',
            ];
        }

        /*
         * Build command.
         */
        $command = '"' . $executable . '"';

        foreach ($arguments as $argument) {

            $command .= ' ' .
                $this->windowsArgument(
                    $argument
                );
        }

        /*
         * Error file.
         */
        $errorFile = storage_path(
            'app/private/' .
            $this->backupDirectory .
            DIRECTORY_SEPARATOR .
            'restore_error_' .
            uniqid() .
            '.txt'
        );

        $descriptors = [
            0 => [
                'file',
                $inputFile,
                'rb',
            ],

            1 => [
                'pipe',
                'w',
            ],

            2 => [
                'file',
                $errorFile,
                'wb',
            ],
        ];

        $process = proc_open(
            $command,
            $descriptors,
            $pipes,
            base_path()
        );

        if (!is_resource($process)) {

            return [
                'success' => false,
                'error' =>
                    'Could not start mysql restore process.',
            ];
        }

        /*
         * Read stdout so the process does not block.
         */
        if (isset($pipes[1])) {
            stream_get_contents(
                $pipes[1]
            );

            fclose($pipes[1]);
        }

        /*
         * Wait for mysql.
         */
        $exitCode = proc_close($process);

        /*
         * Read error.
         */
        $error = '';

        if (File::exists($errorFile)) {

            $error = trim(
                File::get($errorFile)
            );

            File::delete($errorFile);
        }

        return [
            'success' => $exitCode === 0,
            'error' => $error !== ''
                ? $error
                : 'Process exited with code ' .
                    $exitCode,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | DATABASE CONFIGURATION
    |--------------------------------------------------------------------------
    */

    private function databaseConfig(): array
    {
        return [
            /*
             * IMPORTANT:
             * Do NOT use localhost.
             */
            'host' => '127.0.0.1',

            'port' => 3306,

            'database' => config(
                'database.connections.mysql.database',
                'scholarerp'
            ),

            'username' => config(
                'database.connections.mysql.username',
                'root'
            ),

            'password' => config(
                'database.connections.mysql.password',
                ''
            ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | TEST DATABASE CONNECTION
    |--------------------------------------------------------------------------
    */

    private function testDatabaseConnection(): bool
    {
        $config = $this->databaseConfig();

        $arguments = [
            '--host=127.0.0.1',
            '--port=3306',
            '--user=' . $config['username'],
        ];

        if ($config['password'] !== '') {
            $arguments[] =
                '--password=' .
                $config['password'];
        }

        $arguments[] =
            $config['database'];

        $arguments[] =
            '-e';

        $arguments[] =
            'SELECT 1;';

        try {

            $command =
                '"' .
                $this->mysqlPath() .
                '"';

            foreach ($arguments as $argument) {

                $command .= ' ' .
                    $this->windowsArgument(
                        $argument
                    );
            }

            $descriptors = [
                0 => [
                    'pipe',
                    'r',
                ],

                1 => [
                    'pipe',
                    'w',
                ],

                2 => [
                    'pipe',
                    'w',
                ],
            ];

            $process = proc_open(
                $command,
                $descriptors,
                $pipes,
                base_path()
            );

            if (!is_resource($process)) {
                return false;
            }

            fclose($pipes[0]);

            stream_get_contents(
                $pipes[1]
            );

            stream_get_contents(
                $pipes[2]
            );

            fclose($pipes[1]);
            fclose($pipes[2]);

            $exitCode =
                proc_close($process);

            return $exitCode === 0;

        } catch (\Throwable $e) {

            return false;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | WINDOWS ARGUMENT
    |--------------------------------------------------------------------------
    */

    private function windowsArgument(
        string $argument
    ): string {

        /*
         * Escape double quotes.
         */
        $argument = str_replace(
            '"',
            '\"',
            $argument
        );

        return '"' .
            $argument .
            '"';
    }


    /*
    |--------------------------------------------------------------------------
    | EXECUTABLE PATHS
    |--------------------------------------------------------------------------
    */

    private function mysqldumpPath(): string
    {
        return $this->mysqlBinPath .
            DIRECTORY_SEPARATOR .
            'mysqldump.exe';
    }

    private function mysqlPath(): string
    {
        return $this->mysqlBinPath .
            DIRECTORY_SEPARATOR .
            'mysql.exe';
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT FILE SIZE
    |--------------------------------------------------------------------------
    */

    private function formatBytes(
        int $bytes
    ): string {

        if ($bytes === 0) {
            return '0 Bytes';
        }

        $units = [
            'Bytes',
            'KB',
            'MB',
            'GB',
            'TB',
        ];

        $power = floor(
            log($bytes, 1024)
        );

        $power = min(
            $power,
            count($units) - 1
        );

        return number_format(
            $bytes /
            pow(1024, $power),
            2
        ) .
        ' ' .
        $units[$power];
    }
}