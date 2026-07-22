@extends('frontend.layouts.auth')

@section('title', 'Admin Sign In - Buzz')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    :root {
        --primary: #c32148;
        --secondary: #ff4d4d;
        --dark: #2b0b14;
        --glass: rgba(20, 10, 15, 0.65);
        --glass-border: rgba(255, 255, 255, 0.08);
        --text-main: #ffffff;
        --text-muted: #e0b0b0;
    }

    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        font-family: 'Outfit', sans-serif;
        background-color: var(--dark);
    }

    .admin-login-wrapper {
        min-height: 100vh;
        width: 100vw;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        margin-top: -100px;
        padding-top: 100px;
        background: var(--dark);
    }

    /* Animated background blobs */
    .blob {
        position: absolute;
        filter: blur(80px);
        z-index: 0;
        opacity: 0.6;
        animation: float 10s infinite ease-in-out alternate;
    }
    
    .blob-1 {
        top: -10%;
        left: -10%;
        width: 50vw;
        height: 50vw;
        background: radial-gradient(circle, rgba(195,33,72,0.4) 0%, rgba(0,0,0,0) 70%);
        animation-delay: 0s;
    }

    .blob-2 {
        bottom: -20%;
        right: -10%;
        width: 60vw;
        height: 60vw;
        background: radial-gradient(circle, rgba(255,77,77,0.3) 0%, rgba(0,0,0,0) 70%);
        animation-delay: -5s;
    }

    @keyframes float {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(30px, 50px) scale(1.1); }
    }

    /* Glassmorphism Card */
    .admin-card {
        position: relative;
        z-index: 10;
        width: 100%;
        max-width: 420px;
        padding: 50px 40px;
        background: var(--glass);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255,255,255,0.1);
        animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
        transform: translateY(30px);
    }

    @keyframes slideUpFade {
        to { opacity: 1; transform: translateY(0); }
    }

    .admin-card-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .admin-card-header h2 {
        font-size: 32px;
        font-weight: 700;
        margin: 0 0 10px 0;
        background: linear-gradient(135deg, #fff 0%, #a0a0b0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -0.5px;
    }

    .admin-card-header p {
        color: var(--text-muted);
        font-size: 15px;
        margin: 0;
        font-weight: 400;
    }

    .form-group {
        margin-bottom: 24px;
        position: relative;
    }

    /* Floating Labels for Modern Inputs */
    .form-control {
        width: 100%;
        padding: 16px 20px;
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        color: var(--text-main);
        font-size: 15px;
        font-family: inherit;
        outline: none;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: var(--primary);
        background: rgba(0, 0, 0, 0.4);
        box-shadow: 0 0 0 4px rgba(195, 33, 72, 0.15);
    }

    .form-control::placeholder {
        color: transparent;
    }

    .form-label {
        position: absolute;
        left: 20px;
        top: 16px;
        color: var(--text-muted);
        font-size: 15px;
        pointer-events: none;
        transition: all 0.2s ease;
        background: transparent;
    }

    .form-control:focus ~ .form-label,
    .form-control:not(:placeholder-shown) ~ .form-label {
        top: -10px;
        left: 15px;
        font-size: 12px;
        color: var(--primary);
        background: var(--dark);
        padding: 0 5px;
        border-radius: 4px;
        font-weight: 500;
    }

    .error-feedback {
        color: #ff4d4f;
        font-size: 13px;
        margin-top: 6px;
        display: block;
        padding-left: 4px;
    }

    /* Checkbox & Links */
    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        font-size: 14px;
    }

    .custom-checkbox {
        display: flex;
        align-items: center;
        color: var(--text-muted);
        cursor: pointer;
        user-select: none;
    }

    .custom-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkmark {
        height: 18px;
        width: 18px;
        background-color: rgba(255,255,255,0.05);
        border: 1px solid var(--glass-border);
        border-radius: 4px;
        margin-right: 10px;
        position: relative;
        transition: all 0.2s;
    }

    .custom-checkbox:hover input ~ .checkmark {
        background-color: rgba(255,255,255,0.1);
    }

    .custom-checkbox input:checked ~ .checkmark {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
        left: 5px;
        top: 2px;
        width: 4px;
        height: 8px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .custom-checkbox input:checked ~ .checkmark:after {
        display: block;
    }

    .forgot-link {
        color: var(--secondary);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }

    .forgot-link:hover {
        color: #fff;
        text-shadow: 0 0 8px rgba(255, 105, 180, 0.4);
    }

    /* Button */
    .btn-submit {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px -10px rgba(195, 33, 72, 0.5);
    }

    .btn-submit::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: all 0.5s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 25px -10px rgba(195, 33, 72, 0.7);
    }

    .btn-submit:hover::before {
        left: 100%;
    }

    .btn-submit:active {
        transform: translateY(1px);
    }
</style>

<div class="admin-login-wrapper">
    <!-- Animated background blobs -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h2>Admin Portal</h2>
            <p>Sign in to manage Buzz</p>
        </div>
        
        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            
            <div class="form-group">
                <input type="email" id="login" name="login" class="form-control" value="{{ old('login') }}" placeholder=" " required autofocus>
                <label for="login" class="form-label">Email Address</label>
                @error('login')
                    <span class="error-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <input type="password" id="password" name="password" class="form-control" placeholder=" " required>
                <label for="password" class="form-label">Password</label>
                @error('password')
                    <span class="error-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-options">
                <label class="custom-checkbox">
                    <input type="checkbox" name="remember">
                    <span class="checkmark"></span>
                    Remember me
                </label>
                
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                @endif
            </div>
            
            <button type="submit" class="btn-submit">Sign In As Admin</button>
        </form>
    </div>
</div>
@endsection
