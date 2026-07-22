@extends('frontend.layouts.master')

@section('title', 'Sign In - Buzz Bangladesh')

@section('content')
<style>
    .auth-section {
        padding: 60px 0;
        background: #fdfdfd;
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
    }
    .auth-container {
        max-width: 450px;
        margin: 0 auto;
        background: #fff;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        border: 1px solid #f0f0f0;
    }
    .auth-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .auth-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #111;
        margin-bottom: 8px;
    }
    .auth-header p {
        color: #666;
        font-size: 15px;
    }
    .auth-form-group {
        margin-bottom: 20px;
        position: relative;
    }
    .auth-form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #444;
        margin-bottom: 8px;
    }
    .auth-form-group input {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid #e5e5e5;
        border-radius: 10px;
        font-size: 15px;
        color: #222;
        background: #fafafa;
        outline: none;
        transition: all 0.2s;
        font-family: inherit;
        box-sizing: border-box;
    }
    .auth-form-group input:focus {
        border-color: #9A0002;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(154,0,2,0.08);
    }
    .auth-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        font-size: 14px;
    }
    .auth-options label {
        display: flex;
        align-items: center;
        color: #555;
        cursor: pointer;
        margin: 0;
    }
    .auth-options input[type="checkbox"] {
        margin-right: 8px;
        accent-color: #9A0002;
        width: 16px;
        height: 16px;
        cursor: pointer;
    }
    .auth-options a {
        color: #9A0002;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.2s;
    }
    .auth-options a:hover {
        text-decoration: underline;
    }
    .auth-submit-btn {
        width: 100%;
        padding: 14px;
        background: #9A0002;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    .auth-submit-btn:hover {
        background: #7a0001;
        box-shadow: 0 4px 12px rgba(154,0,2,0.2);
    }
    .auth-footer {
        margin-top: 25px;
        text-align: center;
        font-size: 14px;
        color: #666;
    }
    .auth-footer a {
        color: #9A0002;
        font-weight: 600;
        text-decoration: none;
    }
    .auth-footer a:hover {
        text-decoration: underline;
    }
    .error-msg {
        color: #e74c3c;
        font-size: 13px;
        margin-top: 5px;
        display: block;
    }
    @media (max-width: 600px) {
        .auth-container {
            padding: 30px 20px;
            margin: 0 15px;
        }
    }
</style>

<div class="auth-section">
    <div class="container mx-auto px-4">
        <div class="auth-container">
            <div class="auth-header">
                <h1>Welcome Back</h1>
                <p>Sign in to access your account and track orders</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="auth-form-group">
                    <label for="login">Phone Number</label>
                    <input type="text" id="login" name="login" value="{{ old('login') }}" required autofocus placeholder="01XXXXXXXXX" oninput="validatePhone()">
                    <span id="login-error" class="error-msg" style="display: none;"></span>
                    @error('login')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="auth-form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••" oninput="validatePassword()">
                    <span id="password-error" class="error-msg" style="display: none;"></span>
                    @error('password')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="auth-options">
                    <label>
                        <input id="remember" name="remember" type="checkbox">
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Forgot password?</a>
                    @endif
                </div>
                
                <button type="submit" class="auth-submit-btn" id="login-btn">
                    Sign In
                </button>
            </form>

            <div class="auth-footer">
                Don't have an account? 
                <a href="{{ route('register') }}">Create Account</a>
            </div>
        </div>
    </div>
</div>

<script>
    const phoneInput = document.getElementById('login');
    const passwordInput = document.getElementById('password');
    const phoneError = document.getElementById('login-error');
    const passwordError = document.getElementById('password-error');
    const form = document.querySelector('form');

    function validatePhone() {
        const phone = phoneInput.value.trim();
        const phoneRegex = /^01[3-9]\d{8}$/;
        
        if (phone === '') {
            phoneError.textContent = 'Phone number is required.';
            phoneError.style.display = 'block';
            phoneInput.style.borderColor = '#e74c3c';
            return false;
        } else if (!phoneRegex.test(phone)) {
            phoneError.textContent = 'Enter a valid 11-digit phone number (e.g. 01712345678).';
            phoneError.style.display = 'block';
            phoneInput.style.borderColor = '#e74c3c';
            return false;
        } else {
            phoneError.style.display = 'none';
            phoneInput.style.borderColor = '#2ecc71';
            return true;
        }
    }

    function validatePassword() {
        const password = passwordInput.value;
        if (password.length < 6) {
            passwordError.textContent = 'Password must be at least 6 characters.';
            passwordError.style.display = 'block';
            passwordInput.style.borderColor = '#e74c3c';
            return false;
        } else {
            passwordError.style.display = 'none';
            passwordInput.style.borderColor = '#2ecc71';
            return true;
        }
    }

    form.addEventListener('submit', function(e) {
        const isPhoneValid = validatePhone();
        const isPasswordValid = validatePassword();

        if (!isPhoneValid || !isPasswordValid) {
            e.preventDefault();
        }
    });

    phoneInput.addEventListener('blur', validatePhone);
    passwordInput.addEventListener('blur', validatePassword);
</script>
@endsection
