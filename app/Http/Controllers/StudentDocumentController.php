<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentDocumentController extends Controller
{
    public function index(Student $student)
{
    Gate::authorize('viewStudent', [StudentDocument::class, $student]);

    $documents = $student->documents()->latest()->get();

    return view('student-documents.index', compact('student', 'documents'));
}

    public function create(Student $student)
    {
        Gate::authorize('create', [StudentDocument::class, $student]);

        return view('student-documents.create', compact('student'));
    }

    public function store(Request $request, Student $student)
    {
        Gate::authorize('create', [StudentDocument::class, $student]);

        $validated = $request->validate([
            'document_type' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'file' => [
                'required',
                'file',
                'max:5120',
                'mimes:pdf,jpg,jpeg,png,doc,docx',
            ],
        ]);

        $uploadedFile = $request->file('file');

        $path = $uploadedFile->store(
            'student-documents/' . $student->id,
            'private'
        );

        try {
            StudentDocument::create([
                'student_id' => $student->id,
                'document_type' => $validated['document_type'],
                'title' => $validated['title'],
                'remarks' => $validated['remarks'] ?? null,
                'file' => $path,
            ]);
        } catch (\Throwable $exception) {
            Storage::disk('private')->delete($path);

            throw $exception;
        }

        return redirect()
            ->route('students.documents.index', $student)
            ->with('success', 'Student document uploaded successfully.');
    }

    public function download(StudentDocument $studentDocument)
    {
        Gate::authorize('view', [
            $studentDocument,
            $studentDocument->student,
        ]);

        $disk = Storage::disk('private');

        abort_unless($disk->exists($studentDocument->file), 404);

        $path = $disk->path($studentDocument->file);

        $mimeType = mime_content_type($path) ?: 'application/octet-stream';

        $allowedMimeTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        abort_unless(in_array($mimeType, $allowedMimeTypes, true), 403);

        $headers = [
            'Content-Type' => $mimeType,
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ];

        if (in_array($mimeType, ['application/pdf', 'image/jpeg', 'image/png'], true)) {
            return response()->file($path, $headers);
        }

        return response()->download(
            $path,
            basename($studentDocument->title) . '.' . Str::afterLast($studentDocument->file, '.'),
            $headers
        );
    }

    public function destroy(StudentDocument $studentDocument)
    {
        Gate::authorize('delete', $studentDocument);

        Storage::disk('private')->delete($studentDocument->file);

        $studentDocument->delete();

        return redirect()
            ->route('students.documents.index', $studentDocument->student_id)
            ->with('success', 'Student document deleted successfully.');
    }
}
