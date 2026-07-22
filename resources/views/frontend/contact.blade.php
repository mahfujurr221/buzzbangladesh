@extends('frontend.layouts.master')
@section('title', 'Contact Us - ' . ($setting->site_name ?? 'Buzz Bangladesh'))

@push('styles')
<style>
    /* ===== CONTACT PAGE STYLES ===== */
    .contact-hero {
        position: relative;
        overflow: hidden;
    }
    .contact-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.50);
        z-index: 1;
    }
    .contact-hero > * {
        position: relative;
        z-index: 2;
    }

    /* Info Panel */
    .contact-info-panel {
        background: linear-gradient(160deg, #9A0002 0%, #6b0001 100%);
        border-radius: 20px;
        padding: 48px 40px;
        height: 100%;
        color: white;
        display: flex;
        flex-direction: column;
        gap: 0;
    }
    .contact-info-panel .panel-tag {
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.6);
        margin-bottom: 12px;
    }
    .contact-info-panel h2 {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 16px;
        color: #fff;
    }
    .contact-info-panel .panel-desc {
        font-size: 15px;
        color: rgba(255,255,255,0.7);
        line-height: 1.7;
        margin-bottom: 40px;
    }
    .contact-info-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 20px 0;
        border-bottom: 1px solid rgba(255,255,255,0.12);
    }
    .contact-info-item:last-of-type {
        border-bottom: none;
    }
    .contact-info-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(255,255,255,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 20px;
        color: white;
    }
    .contact-info-item h4 {
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.5);
        margin: 0 0 4px;
    }
    .contact-info-item p {
        font-size: 15px;
        color: #fff;
        margin: 0;
        font-weight: 500;
    }
    .contact-social-links {
        display: flex;
        gap: 10px;
        margin-top: 36px;
        padding-top: 36px;
        border-top: 1px solid rgba(255,255,255,0.12);
    }
    .contact-social-link {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(255,255,255,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        text-decoration: none;
        transition: background 0.2s;
    }
    .contact-social-link:hover {
        background: rgba(255,255,255,0.3);
        color: white;
    }

    /* Form Panel */
    .contact-form-panel {
        background: #fff;
        border-radius: 20px;
        padding: 48px 40px;
        box-shadow: 0 4px 40px rgba(154,0,2,0.08);
        border: 0.5px solid #9A0002;
    }
    .contact-form-panel .form-tag {
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #9A0002;
        margin-bottom: 12px;
    }
    .contact-form-panel h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111;
        margin-bottom: 8px;
    }
    .contact-form-panel .form-desc {
        font-size: 14px;
        color: #888;
        margin-bottom: 32px;
    }
    .contact-form-group {
        margin-bottom: 20px;
    }
    .contact-form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #444;
        margin-bottom: 8px;
        letter-spacing: 0.3px;
    }
    .contact-form-group input,
    .contact-form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 0.5px solid #ddd;
        border-radius: 10px;
        font-size: 14px;
        color: #222;
        background: #fafafa;
        outline: none;
        transition: all 0.2s;
        font-family: inherit;
    }
    .contact-form-group input::placeholder,
    .contact-form-group textarea::placeholder {
        color: #bbb;
    }
    .contact-form-group input:focus,
    .contact-form-group textarea:focus {
        border-color: #9A0002;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(154,0,2,0.08);
    }
    .contact-form-group textarea {
        resize: vertical;
        min-height: 130px;
    }
    .contact-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    @media (max-width: 600px) {
        .contact-form-row { grid-template-columns: 1fr; }
        .contact-info-panel { padding: 32px 24px; }
        .contact-form-panel { padding: 32px 24px; }
    }
    .contact-submit-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 36px;
        background: #9A0002;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s;
        width: 100%;
        justify-content: center;
        margin-top: 8px;
    }
    .contact-submit-btn:hover {
        background: #7a0001;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(154,0,2,0.3);
    }
    .contact-submit-btn i { font-size: 18px; }

    /* Layout wrapper */
    .contact-body-section {
        background: #f8f8f8;
        padding: 80px 0;
    }
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 32px;
        max-width: 1100px;
        margin: 0 auto;
        align-items: start;
    }
    @media (max-width: 900px) {
        .contact-grid { grid-template-columns: 1fr; }
    }

    /* Success message */
    .contact-success {
        background: #f0fdf4;
        border: 1px solid #86efac;
        color: #15803d;
        padding: 14px 18px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 24px;
        font-size: 14px;
        font-weight: 500;
    }
</style>
@endpush

@section('content')

{{-- Breadcrumb Banner --}}
<div class="breadcrumb-block style-img">
    <div class="breadcrumb-main bg-linear overflow-hidden relative">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="text-content">
                    <div class="heading2 text-center">Contact Us</div>
                    <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                        <a href="{{ route('frontend.home') }}">Homepage</a>
                        <i class="ph ph-caret-right text-sm text-secondary2"></i>
                        <div class="text-secondary2 capitalize">Contact Us</div>
                    </div>
                </div>
            </div>
            @if($setting?->contact_bg)
                <div class="bg-img absolute top-0 right-0 w-full h-full z-[0] opacity-20">
                    <img src="{{ asset($setting->contact_bg) }}" alt="Contact Background" class="w-full h-full object-cover" />
                </div>
            @endif
            <!-- Dark Overlay -->
            <div class="absolute inset-0 bg-black opacity-50 z-[0]"></div>
        </div>
    </div>
</div>

{{-- Contact Body --}}
<section class="contact-body-section">
    <div class="container">
        <div class="contact-grid">

            {{-- LEFT: Info Panel --}}
            <div class="contact-info-panel">
                <p class="panel-tag">Reach Out</p>
                <h2>Let's Talk About Everything!</h2>
                <p class="panel-desc">Have a question, feedback, or just want to say hello? We're here to help and would love to hear from you.</p>

                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <i class="ph ph-map-pin"></i>
                    </div>
                    <div>
                        <h4>Our Location</h4>
                        <p>{{ $setting->address ?? 'Dhaka, Bangladesh' }}</p>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <i class="ph ph-phone"></i>
                    </div>
                    <div>
                        <h4>Phone Number</h4>
                        <p>{{ $setting->phone ?? '+880 123 456 789' }}</p>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <i class="ph ph-envelope-simple"></i>
                    </div>
                    <div>
                        <h4>Email Address</h4>
                        <p>{{ $setting->email ?? 'support@buzzbangladesh.com' }}</p>
                    </div>
                </div>

                @if($setting->working_hours ?? false)
                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <i class="ph ph-clock"></i>
                    </div>
                    <div>
                        <h4>Working Hours</h4>
                        <p>{{ $setting->working_hours }}</p>
                    </div>
                </div>
                @else
                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <i class="ph ph-clock"></i>
                    </div>
                    <div>
                        <h4>Working Hours</h4>
                        <p>Sat–Thu: 9:00 AM – 6:00 PM</p>
                    </div>
                </div>
                @endif

                {{-- Social Links --}}
                <div class="contact-social-links">
                    @if($setting->facebook ?? false)
                    <a href="{{ $setting->facebook }}" class="contact-social-link" target="_blank">
                        <i class="ph ph-facebook-logo"></i>
                    </a>
                    @endif
                    @if($setting->instagram ?? false)
                    <a href="{{ $setting->instagram }}" class="contact-social-link" target="_blank">
                        <i class="ph ph-instagram-logo"></i>
                    </a>
                    @endif
                    @if($setting->twitter ?? false)
                    <a href="{{ $setting->twitter }}" class="contact-social-link" target="_blank">
                        <i class="ph ph-twitter-logo"></i>
                    </a>
                    @endif
                    @if(!($setting->facebook ?? false) && !($setting->instagram ?? false))
                    <a href="#" class="contact-social-link"><i class="ph ph-facebook-logo"></i></a>
                    <a href="#" class="contact-social-link"><i class="ph ph-instagram-logo"></i></a>
                    <a href="#" class="contact-social-link"><i class="ph ph-twitter-logo"></i></a>
                    @endif
                </div>
            </div>

            {{-- RIGHT: Form Panel --}}
            <div class="contact-form-panel">
                <p class="form-tag">Send a Message</p>
                <h2>We'd Love to Hear From You</h2>
                <p class="form-desc">Fill out the form below and our team will get back to you within 24 hours.</p>

                @if(session('success'))
                    <div class="contact-success">
                        <i class="ph ph-check-circle" style="font-size:20px;"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('frontend.contact.submit') }}" method="POST">
                    @csrf
                    <div class="contact-form-row">
                        <div class="contact-form-group">
                            <label>Your Name <span style="color:#9A0002;">*</span></label>
                            <input type="text" name="name" placeholder="e.g. Rafiq Ahmed" required value="{{ old('name') }}">
                        </div>
                        <div class="contact-form-group">
                            <label>Email Address <span style="color:#9A0002;">*</span></label>
                            <input type="email" name="email" placeholder="you@example.com" required value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="contact-form-row">
                        <div class="contact-form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" placeholder="+880 1X XX XX XXXX" value="{{ old('phone') }}">
                        </div>
                        <div class="contact-form-group">
                            <label>Subject</label>
                            <input type="text" name="subject" placeholder="How can we help?" value="{{ old('subject') }}">
                        </div>
                    </div>
                    <div class="contact-form-group">
                        <label>Your Message <span style="color:#9A0002;">*</span></label>
                        <textarea name="message" placeholder="Write your message here..." required>{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="contact-submit-btn">
                        <i class="ph ph-paper-plane-tilt"></i>
                        Send Message
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

@endsection
