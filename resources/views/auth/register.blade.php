@extends('frontend.layouts.master')

@section('title', 'Create Account - Buzz Bangladesh')

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
        max-width: 550px;
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
    .auth-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
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
        margin-top: 10px;
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
        .auth-form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
    }
</style>

<div class="auth-section">
    <div class="container mx-auto px-4">
        <div class="auth-container">
            <div class="auth-header">
                <h1>Create an Account</h1>
                <p>Join Buzz Bangladesh to track orders and checkout faster</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="auth-form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe" oninput="validateName()">
                    <span id="name-error" class="error-msg" style="display: none;"></span>
                    @error('name')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="auth-form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="01XXXXXXXXX" oninput="validatePhone()">
                    <span id="phone-error" class="error-msg" style="display: none;"></span>
                    @error('phone')
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
                
                <div class="auth-form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••" oninput="validateConfirmPassword()">
                    <span id="confirm-password-error" class="error-msg" style="display: none;"></span>
                </div>
                
                <button type="submit" class="auth-submit-btn" id="register-btn">
                    Create Account
                </button>
            </form>

            <div class="auth-footer">
                Already have an account? 
                <a href="{{ route('login') }}">Sign In here</a>
            </div>
        </div>
    </div>
</div>

<script>
    const nameInput = document.getElementById('name');
    const phoneInput = document.getElementById('phone');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    
    const nameError = document.getElementById('name-error');
    const phoneError = document.getElementById('phone-error');
    const passwordError = document.getElementById('password-error');
    const confirmPasswordError = document.getElementById('confirm-password-error');
    
    const form = document.querySelector('form');

    function validateName() {
        const name = nameInput.value.trim();
        if (name === '') {
            nameError.textContent = 'Full Name is required.';
            nameError.style.display = 'block';
            nameInput.style.borderColor = '#e74c3c';
            return false;
        } else if (name.length < 3) {
            nameError.textContent = 'Name must be at least 3 characters long.';
            nameError.style.display = 'block';
            nameInput.style.borderColor = '#e74c3c';
            return false;
        } else {
            nameError.style.display = 'none';
            nameInput.style.borderColor = '#2ecc71';
            return true;
        }
    }

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
        if (password.length < 8) {
            passwordError.textContent = 'Password must be at least 8 characters.';
            passwordError.style.display = 'block';
            passwordInput.style.borderColor = '#e74c3c';
            return false;
        } else {
            passwordError.style.display = 'none';
            passwordInput.style.borderColor = '#2ecc71';
            validateConfirmPassword(); // Re-validate confirmation if main password changes
            return true;
        }
    }

    function validateConfirmPassword() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (confirmPassword === '') {
            confirmPasswordError.textContent = 'Please confirm your password.';
            confirmPasswordError.style.display = 'block';
            confirmPasswordInput.style.borderColor = '#e74c3c';
            return false;
        } else if (password !== confirmPassword) {
            confirmPasswordError.textContent = 'Passwords do not match.';
            confirmPasswordError.style.display = 'block';
            confirmPasswordInput.style.borderColor = '#e74c3c';
            return false;
        } else {
            confirmPasswordError.style.display = 'none';
            confirmPasswordInput.style.borderColor = '#2ecc71';
            return true;
        }
    }

    form.addEventListener('submit', function(e) {
        const isNameValid = validateName();
        const isPhoneValid = validatePhone();
        const isPasswordValid = validatePassword();
        const isConfirmPasswordValid = validateConfirmPassword();

        if (!isNameValid || !isPhoneValid || !isPasswordValid || !isConfirmPasswordValid) {
            e.preventDefault();
        }
    });

    nameInput.addEventListener('blur', validateName);
    phoneInput.addEventListener('blur', validatePhone);
    passwordInput.addEventListener('blur', validatePassword);
    confirmPasswordInput.addEventListener('blur', validateConfirmPassword);
</script>
@endsection
