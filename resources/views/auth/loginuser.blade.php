<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register - Pendataan IGD</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    {{-- Font Awesome for Icons --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    {{-- CSS Kustom Anda --}}
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
</head>
<body>
    <div class="container py-4">
        <div class="form-container">

            <div class="card-header">
                <img src="{{ asset('Rumah_Sakit_Indriati.webp') }}" 
                     alt="Logo" 
                     class="logo">
                <h2 id="card-title" class="form-title gradient-text">
                    Pendataan IGD: Silakan Masuk
                </h2>
                <p id="card-subtitle" class="form-subtitle">
                    Sistem Pendataan Pengantar Pasien IGD
                </p>
            </div>
            
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                        <i class="fas fa-check-circle fs-5 me-3"></i>
                        <strong>{{ session('success') }}</strong>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                        <i class="fas fa-exclamation-triangle fs-5 me-3"></i>
                        <strong>{{ $errors->first() }}</strong>
                    </div>
                @endif

                <div id="login-section" class="form-step">
                    <form id="loginForm" method="POST" action="{{ route('loginuser.login') }}">
                        @csrf
                        
                        <div class="form-group">
                            <label for="login-name" class="form-label">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="login-name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap Anda" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="login-password" class="form-label">Password (Nomor HP)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="login-password" name="password" placeholder="Masukkan nomor HP Anda" required>
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="loginBtn">
                            <span class="btn-text d-flex align-items-center justify-content-center">
                                <i class="fas fa-sign-in-alt me-2"></i> Masuk
                            </span>
                            <span class="loading-spinner">
                                <i class="fas fa-spinner fa-spin me-2"></i> Memproses...
                            </span>
                        </button>
                    </form>
                </div>

                <div id="register-section" class="form-step d-none">
                    <form id="registerForm" method="POST" action="{{ route('loginuser.register') }}">
                        @csrf
                        
                        <div class="form-group">
                            <label for="register-name" class="form-label">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="register-name" name="name" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="register-phone" class="form-label">Nomor HP (Password)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="tel" class="form-control" id="register-phone" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="registerBtn">
                            <span class="btn-text d-flex align-items-center justify-content-center">
                                <i class="fas fa-user-plus me-2"></i> Daftar Akun
                            </span>
                            <span class="loading-spinner">
                                <i class="fas fa-spinner fa-spin me-2"></i> Memproses...
                            </span>
                        </button>
                    </form>
                </div>

                <div class="text-center mt-4 small">
                    <span id="switch-text" class="text-muted">Belum punya akun?</span>
                    <a id="switch-link" href="#" class="switch-link gradient-text ms-1" onclick="switchMode(event, 'register')">
                        Daftar di sini
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
       function switchMode(event, mode) {
            event.preventDefault();
            const loginSection = document.getElementById('login-section');
            const registerSection = document.getElementById('register-section');
            const switchText = document.getElementById('switch-text');
            const switchLink = document.getElementById('switch-link');
            const cardTitle = document.getElementById('card-title');
            const cardSubtitle = document.getElementById('card-subtitle');

            if (mode === 'register') {
                loginSection.classList.add('d-none');
                registerSection.classList.remove('d-none');
                cardTitle.innerHTML = 'Registrasi Akun Baru';
                cardSubtitle.textContent = 'Isi data diri untuk membuat akun baru';
                switchText.textContent = 'Sudah punya akun?';
                switchLink.textContent = 'Masuk di sini';
                switchLink.onclick = (e) => switchMode(e, 'login');
            } else {
                loginSection.classList.remove('d-none');
                registerSection.classList.add('d-none');
                cardTitle.innerHTML = 'Pendataan IGD: Silakan Masuk';
                cardSubtitle.textContent = 'Sistem Pendataan Pengantar Pasien IGD';
                switchText.textContent = 'Belum punya akun?';
                switchLink.textContent = 'Daftar di sini';
                switchLink.onclick = (e) => switchMode(e, 'register');
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const loginBtn = document.getElementById('loginBtn');
            const registerBtn = document.getElementById('registerBtn');
            if(loginForm){loginForm.addEventListener('submit',function(e){loginBtn.classList.add('loading');loginBtn.disabled=true;});}
            if(registerForm){registerForm.addEventListener('submit',function(e){registerBtn.classList.add('loading');registerBtn.disabled=true;});}
        });
    </script>
</body>
</html>