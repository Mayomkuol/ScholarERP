
<x-scholar-login-layout>

    {{-- LOGIN HEADING --}}

    <div class="scholar-heading">

        <span class="eyebrow">
            WELCOME BACK
        </span>

        <h2>Sign in to your account</h2>

        <p>
            Enter your credentials to access ScholarERP.
        </p>

    </div>


    {{-- SESSION STATUS --}}

    <x-auth-session-status
        class="scholar-alert success"
        :status="session('status')"
    />


    {{-- LOGIN FORM --}}

    <form method="POST" action="{{ route('login') }}">

        @csrf


        {{-- EMAIL ADDRESS --}}

        <div class="scholar-form-group">

            <label for="email">
                Email address
            </label>

            <div class="scholar-input-wrapper">

                {{-- Email Icon --}}

                <svg class="scholar-input-icon"
                     viewBox="0 0 24 24"
                     fill="none">

                    <rect x="3" y="5"
                          width="18" height="14"
                          rx="2"
                          stroke="currentColor"
                          stroke-width="1.7"/>

                    <path d="M3 7L12 13L21 7"
                          stroke="currentColor"
                          stroke-width="1.7"/>

                </svg>


                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter your email address"
                    autocomplete="username"
                    required
                    autofocus
                    class="{{ $errors->has('email') ? 'scholar-invalid' : '' }}"
                >

            </div>


            {{-- Email Validation Error --}}

            <x-input-error
                :messages="$errors->get('email')"
                class="scholar-field-error"
            />

        </div>


        {{-- PASSWORD --}}

        <div class="scholar-form-group">

            <label for="password">
                Password
            </label>

            <div class="scholar-input-wrapper">

                {{-- Password Icon --}}

                <svg class="scholar-input-icon"
                     viewBox="0 0 24 24"
                     fill="none">

                    <rect x="4" y="10"
                          width="16" height="11"
                          rx="2"
                          stroke="currentColor"
                          stroke-width="1.7"/>

                    <path d="M8 10V7a4 4 0 0 1 8 0v3"
                          stroke="currentColor"
                          stroke-width="1.7"/>

                </svg>


                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    required
                    class="{{ $errors->has('password') ? 'scholar-invalid' : '' }}"
                >


                {{-- Show/Hide Password --}}

                <button
                    type="button"
                    class="scholar-toggle-password"
                    id="togglePassword"
                    aria-label="Show password"
                    aria-pressed="false"
                >

                    <svg viewBox="0 0 24 24"
                         fill="none">

                        <path
                            d="M2 12S5.5 5 12 5s10 7 10 7-3.5 7-10 7S2 12 2 12Z"
                            stroke="currentColor"
                            stroke-width="1.7"
                        />

                        <circle
                            cx="12"
                            cy="12"
                            r="3"
                            stroke="currentColor"
                            stroke-width="1.7"
                        />

                    </svg>

                </button>

            </div>


            {{-- Password Validation Error --}}

            <x-input-error
                :messages="$errors->get('password')"
                class="scholar-field-error"
            />

        </div>


        {{-- REMEMBER ME AND FORGOT PASSWORD --}}

        <div class="scholar-form-options">

            <label class="scholar-remember"
                   for="remember_me">

                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    value="1"
                    {{ old('remember') ? 'checked' : '' }}
                >

                <span>Remember me</span>

            </label>


            {{-- Existing Laravel Password Reset Route --}}

            @if (Route::has('password.request'))

                <a
                    href="{{ route('password.request') }}"
                    class="scholar-forgot"
                >
                    Forgot password?
                </a>

            @endif

        </div>


        {{-- LOGIN BUTTON --}}

        <button
            type="submit"
            class="scholar-submit"
        >

            <span>Sign in to ScholarERP</span>

            <svg viewBox="0 0 24 24"
                 fill="none">

                <path
                    d="M5 12H19M12 5L19 12L12 19"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />

            </svg>

        </button>

    </form>


    {{-- SECURITY MESSAGE --}}

    <div class="scholar-security">

        <svg viewBox="0 0 24 24"
             fill="none">

            <path
                d="M12 3L20 6V11C20 16 16.5 19.5 12 21C7.5 19.5 4 16 4 11V6L12 3Z"
                stroke="currentColor"
                stroke-width="1.7"
            />

            <path
                d="M9 12L11 14L15 10"
                stroke="currentColor"
                stroke-width="1.7"
            />

        </svg>

        <span>
            Your account is protected and secure.
        </span>

    </div>


    {{-- COPYRIGHT --}}

    <div class="scholar-copyright">

        Powered by <strong>ScholarERP</strong>

    </div>


    {{-- PASSWORD VISIBILITY SCRIPT --}}

    @push('scripts')

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const toggle = document.getElementById('togglePassword');
                const password = document.getElementById('password');

                if (toggle && password) {

                    toggle.addEventListener('click', function () {

                        const showPassword = password.type === 'password';

                        password.type = showPassword ? 'text' : 'password';

                        toggle.setAttribute(
                            'aria-label',
                            showPassword ? 'Hide password' : 'Show password'
                        );

                        toggle.setAttribute(
                            'aria-pressed',
                            String(showPassword)
                        );

                    });

                }

            });
        </script>

    @endpush

</x-scholar-login-layout>
