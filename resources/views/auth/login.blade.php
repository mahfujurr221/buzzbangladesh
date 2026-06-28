@extends('frontend.layouts.auth')

@section('title', 'Sign In - Buzz')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    .buzz-login-main {
        font-family: 'Outfit', sans-serif;
        min-height: 100vh;
        width: 100vw;
        display: flex;
        align-items: center;
        justify-content: center;
        background-image: url('{{ asset('frontend/assets/img/login/buzz-bg-light.png') }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        position: relative;
        margin-top: -100px;
        padding-top: 100px;
    }

    .buzz-login-main::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255, 255, 255, 0.3);
        z-index: 1;
    }

    .glass-card {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 440px;
        padding: 45px 40px;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 24px;
        box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.1);
        color: #333;
        animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        margin: 0 20px;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .glass-card h3 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
        text-align: center;
        color: #1a1a2e;
    }

    .glass-card p {
        text-align: center;
        color: #5a5a6e;
        margin-bottom: 35px;
        font-size: 15px;
    }

    .input-group {
        margin-bottom: 20px;
        position: relative;
    }

    .input-group input {
        width: 100%;
        padding: 16px 20px;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        color: #333;
        font-size: 16px;
        outline: none;
        transition: all 0.3s ease;
    }

    .input-group input::placeholder {
        color: #999;
    }

    .input-group input:focus {
        border-color: #ff69b4;
        box-shadow: 0 0 15px rgba(255, 105, 180, 0.2);
        background: #ffffff;
    }

    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        font-size: 14px;
    }

    .form-options label {
        display: flex;
        align-items: center;
        cursor: pointer;
        color: #555;
        margin: 0;
    }

    .form-options input[type="checkbox"] {
        margin-right: 8px;
        accent-color: #ff69b4;
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .form-options a {
        color: #ff69b4;
        text-decoration: none;
        transition: color 0.3s;
        font-weight: 500;
    }

    .form-options a:hover {
        color: #ff1493;
        text-decoration: underline;
    }

    .btn-login {
        width: 100%;
        padding: 16px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #ff69b4 0%, #8a2be2 100%);
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(138, 43, 226, 0.3);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .error-msg {
        color: #ff4d4d;
        font-size: 13px;
        margin-top: 8px;
        display: block;
        padding-left: 5px;
    }
</style>

<main class="buzz-login-main">
    <div class="glass-card">
        <h3>Welcome to Buzz</h3>
        <p>Sign in to access your dashboard</p>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="input-group">
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required autofocus>
                @error('email')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
                @error('password')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-options">
                <label>
                    <input type="checkbox" name="remember">
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot Password?</a>
                @endif
            </div>
            
            <button type="submit" class="btn-login">Sign In</button>
        </form>
    </div>
</main>
@endsection
