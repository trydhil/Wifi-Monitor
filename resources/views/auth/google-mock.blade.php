<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Sign in - Google Accounts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #ffffff;
        }
        @media (min-width: 640px) {
            body {
                background-color: #f0f4f9;
            }
        }
        .google-card {
            box-shadow: none;
            background-color: transparent;
        }
        @media (min-width: 640px) {
            .google-card {
                box-shadow: 0 4px 16px rgba(0,0,0,0.08);
                background-color: #ffffff;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between sm:justify-center items-center py-6 sm:py-12 px-4">
    <!-- Center Card -->
    <div class="google-card w-full max-w-[450px] rounded-3xl p-6 sm:p-10 flex flex-col items-center">
        <!-- Google Logo -->
        <svg class="h-10 mb-6" viewBox="0 0 24 24" width="96" height="48" xmlns="http://www.w3.org/2000/svg">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"></path>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
        </svg>

        <!-- Title & Subtitle -->
        <h1 class="text-2xl font-normal text-[#1f1f1f] text-center mb-2">Pilih akun</h1>
        <p class="text-[16px] text-[#444746] text-center mb-8">untuk melanjutkan ke <span class="font-medium text-[#0051d5]">NETRA</span></p>

        <!-- Dynamic Account List (Pre-seeded in DB) -->
        <div class="w-full space-y-1 mb-6">
            @foreach($users as $usr)
                <form method="POST" action="{{ route('auth.google.mock-login') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $usr->email }}">
                    <input type="hidden" name="name" value="{{ $usr->name }}">
                    <button type="submit" class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-[#f2f2f2] active:bg-[#e6e6e6] transition-colors border border-transparent hover:border-gray-200">
                        <div class="flex items-center space-x-3 text-left">
                            <div class="w-8 h-8 rounded-full bg-[#0051d5] text-white flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr($usr->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-[#1f1f1f]">{{ $usr->name }}</div>
                                <div class="text-xs text-[#5f6368]">{{ $usr->email }}</div>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-[#5f6368] text-[20px]">chevron_right</span>
                    </button>
                </form>
            @endforeach

            <!-- Option: Use another account -->
            <button onclick="toggleCustomForm()" class="w-full flex items-center space-x-3 p-3 rounded-lg hover:bg-[#f2f2f2] active:bg-[#e6e6e6] transition-colors text-left border border-transparent hover:border-gray-200" type="button">
                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                    <span class="material-symbols-outlined text-[20px]">account_circle</span>
                </div>
                <div class="text-sm font-medium text-[#1a73e8]">Gunakan akun lain</div>
            </button>
        </div>

        <!-- Custom Account Entry Form (Collapsible) -->
        <div id="customAccountForm" class="w-full hidden border-t border-gray-200 pt-6 mt-4 animate-fadeIn">
            <form method="POST" action="{{ route('auth.google.mock-login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" placeholder="John Doe" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#1a73e8] focus:border-[#1a73e8]">
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Alamat Email Google</label>
                    <input type="email" name="email" placeholder="johndoe@gmail.com" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#1a73e8] focus:border-[#1a73e8]">
                </div>

                <div class="flex justify-between items-center pt-2">
                    <button onclick="toggleCustomForm()" class="text-sm font-medium text-[#1a73e8] hover:text-blue-800" type="button">Batal</button>
                    <button type="submit" class="bg-[#1a73e8] text-white px-5 py-2 rounded-md font-medium text-sm hover:bg-blue-700 active:bg-blue-800 transition-colors shadow-sm">Lanjutkan</button>
                </div>
            </form>
        </div>

        <!-- Notice details -->
        <p class="text-xs text-[#5f6368] text-left w-full mt-8 leading-relaxed">
            Untuk melanjutkan, Google akan membagikan nama, alamat email, preferensi bahasa, dan foto profil Anda dengan NETRA.
        </p>
    </div>

    <!-- Footer -->
    <div class="w-full max-w-[450px] sm:max-w-[700px] flex flex-col sm:flex-row justify-between items-center text-xs text-[#5f6368] mt-6 px-4 space-y-3 sm:space-y-0">
        <div>Indonesia</div>
        <div class="flex space-x-6">
            <a href="#" class="hover:underline">Bantuan</a>
            <a href="#" class="hover:underline">Privasi</a>
            <a href="#" class="hover:underline">Ketentuan</a>
        </div>
    </div>

    <script>
        function toggleCustomForm() {
            const form = document.getElementById('customAccountForm');
            if (form.classList.contains('hidden')) {
                form.classList.remove('hidden');
                form.scrollIntoView({ behavior: 'smooth' });
            } else {
                form.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
