<x-guest-layout>
    <style>
        body {
            background: linear-gradient(to bottom, #e0f7ff, #b3e5fc, #81d4fa) !important;
            background-image: url('https://i.pinimg.com/736x/1e/ae/49/1eae490d5ef25f8917bda4f850769d28.jpg') !important;
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
        }
        .min-h-screen {
            background: transparent !important;
        }

        .tab-container {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 2px solid #e5e7eb;
        }

        .tab-btn {
            padding: 12px 24px;
            background: none;
            border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            color: #6b7280;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            outline: none;
        }

        .tab-btn:hover {
            color: #4b5563;
        }

        .tab-btn.active {
            color: #4f46e5;
            border-bottom-color: #4f46e5;
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-in;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .form-divider {
            text-align: center;
            margin: 20px 0;
            font-size: 14px;
            color: #9ca3af;
        }

        .form-link {
            text-align: center;
            margin-top: 16px;
            font-size: 14px;
        }

        .form-link a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .form-link a:hover {
            color: #4338ca;
            text-decoration: underline;
        }
    </style>

    <!-- Tab Navigation -->
    <div class="tab-container">
        <button type="button" class="tab-btn active" onclick="switchTab('login')">
            {{ __('Login') }}
        </button>
        <button type="button" class="tab-btn" onclick="switchTab('register')">
            {{ __('Register') }}
        </button>
    </div>

    <!-- Login Form -->
    <div id="login" class="tab-content active">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email_login" :value="__('Email')" />
                <x-text-input id="email_login" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password_login" :value="__('Password')" />

                <x-text-input id="password_login" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="ms-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <!-- Register Form -->
    <div id="register" class="tab-content">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email_register" :value="__('Email')" />
                <x-text-input id="email_register" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password_register" :value="__('Password')" />

                <x-text-input id="password_register" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tab contents
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active class from all buttons
            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tabName).classList.add('active');

            // Add active class to clicked button
            event.target.classList.add('active');

            // Focus first input of new tab
            const firstInput = document.getElementById(tabName).querySelector('input');
            if (firstInput) {
                firstInput.focus();
            }
        }

        // Check if there are validation errors
        document.addEventListener('DOMContentLoaded', function() {
            const errors = document.querySelectorAll('.text-red-600, .text-red-500');
            if (errors.length > 0) {
                // Find which form has errors
                const loginForm = document.getElementById('login');
                const registerForm = document.getElementById('register');

                const loginHasErrors = loginForm.querySelectorAll('.text-red-600, .text-red-500').length > 0;
                const registerHasErrors = registerForm.querySelectorAll('.text-red-600, .text-red-500').length > 0;

                if (registerHasErrors && !loginHasErrors) {
                    switchTab('register');
                }
            }
        });
    </script>
</x-guest-layout>
