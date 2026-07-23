@extends('frontend.layouts.master')
@section('title', $page->title . ' - ' . ($setting->site_name ?? 'Buzz Bangladesh'))

@push('styles')
<style>
    :root { --brand: #9A0002; --brand-dark: #6b0001; }

    .about-section { padding: 80px 0; }
    .about-section.alt { background: #f8f8f8; }

    .section-tag {
        display: inline-block;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: var(--brand);
        margin-bottom: 12px;
    }
    .section-heading {
        font-size: 2.25rem;
        font-weight: 800;
        line-height: 1.2;
        color: #111;
        margin-bottom: 16px;
    }

    /* ---- Who We Are ---- */
    .about-intro-grid {
        display: grid;
        grid-template-columns: 1fr 1.1fr;
        gap: 60px;
        align-items: center;
    }
    .about-intro-img {
        border-radius: 24px;
        overflow: hidden;
        position: relative;
        aspect-ratio: 4/3;
        box-shadow: 0 20px 60px rgba(0,0,0,0.12);
        background: #eee;
    }
    .about-intro-img img { width: 100%; height: 100%; object-fit: cover; }
    .about-intro-img .img-badge {
        position: absolute;
        bottom: 24px;
        left: 24px;
        background: var(--brand);
        color: white;
        padding: 14px 20px;
        border-radius: 14px;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.3;
        box-shadow: 0 8px 24px rgba(154,0,2,0.4);
    }
    .about-intro-img .img-badge span { display: block; font-size: 28px; font-weight: 800; }

    /* page content prose */
    .about-prose { font-size: 15px; color: #555; line-height: 1.9; }
    .about-prose p { margin-bottom: 16px; }
    .about-prose h1,.about-prose h2,.about-prose h3 { color: #111; font-weight: 700; margin: 24px 0 12px; }
    .about-prose ul { list-style: disc; padding-left: 20px; margin-bottom: 16px; }
    .about-prose ol { list-style: decimal; padding-left: 20px; margin-bottom: 16px; }
    .about-prose li { margin-bottom: 6px; }
    .about-prose strong { color: #111; }
    .about-prose a { color: var(--brand); }

    /* ---- Contact Info Cards ---- */
    .info-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        max-width: 900px;
        margin: 40px auto 0;
    }
    .info-card {
        text-align: center;
        padding: 32px 20px;
        background: #fff;
        border-radius: 16px;
        border: 0.5px solid rgba(154,0,2,0.15);
        transition: all 0.3s;
    }
    .info-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(154,0,2,0.1); }
    .info-card-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        background: rgba(154,0,2,0.08);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
        color: var(--brand);
        font-size: 24px;
    }
    .info-card-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--brand);
        margin-bottom: 8px;
    }
    .info-card-value { font-size: 14px; color: #555; margin: 0; word-break: break-word; }

    /* ---- Social Links ---- */
    .about-socials {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 28px;
    }
    .about-social-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        border: 0.5px solid rgba(154,0,2,0.3);
        border-radius: 8px;
        color: var(--brand);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .about-social-link:hover {
        background: var(--brand);
        color: white;
        border-color: var(--brand);
    }
    .about-social-link i { font-size: 16px; }

    /* ---- Map ---- */
    .about-map-wrap {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        border: 0.5px solid rgba(154,0,2,0.15);
        margin-top: 48px;
    }
    .about-map-wrap iframe { display: block; width: 100%; border: 0; }

    /* ---- CTA ---- */
    .about-cta {
        background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
        padding: 72px 0;
        text-align: center;
    }
    .about-cta h2 { font-size: 2rem; font-weight: 800; color: white; margin-bottom: 14px; }
    .about-cta p { font-size: 15px; color: rgba(255,255,255,0.75); margin-bottom: 32px; max-width: 500px; margin-left: auto; margin-right: auto; }
    .cta-btn-white {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 13px 32px; background: white; color: var(--brand);
        border-radius: 10px; font-size: 15px; font-weight: 700;
        text-decoration: none; margin: 6px;
        transition: all 0.25s;
    }
    .cta-btn-white:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.2); color: var(--brand); }
    .cta-btn-outline {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 13px 32px; background: transparent; color: white;
        border: 1.5px solid rgba(255,255,255,0.5); border-radius: 10px;
        font-size: 15px; font-weight: 700; text-decoration: none; margin: 6px;
        transition: all 0.25s;
    }
    .cta-btn-outline:hover { background: rgba(255,255,255,0.12); border-color: white; color: white; }

    @media(max-width: 900px) {
        .about-intro-grid { grid-template-columns: 1fr; gap: 32px; }
        .section-heading { font-size: 1.75rem; }
        .about-section { padding: 56px 0; }
    }
</style>
@endpush

@section('content')

{{-- ===== BANNER ===== --}}
<div class="breadcrumb-block style-img">
    <div class="breadcrumb-main bg-linear overflow-hidden relative">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="text-content">
                    <div class="heading2 text-center">{{ $page->title }}</div>
                    <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                        <a href="{{ route('frontend.home') }}">Homepage</a>
                        <i class="ph ph-caret-right text-sm text-secondary2"></i>
                        <div class="text-secondary2 capitalize">{{ $page->title }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== MAIN CONTENT (from backend page editor) ===== --}}
@if($page->content)
<section class="about-section">
    <div class="container">
        <div class="about-intro-grid">
            {{-- Left: About Bg Image --}}
            <div class="about-intro-img">
                @if($setting?->about_bg)
                    <img src="{{ asset($setting->about_bg) }}" alt="{{ $setting->site_name ?? 'Buzz Bangladesh' }}">
                @else
                    <img src="{{ asset('frontend/images/slider/bg1-2.png') }}" alt="{{ $page->title }}">
                @endif
                <div class="img-badge">
                    <span>{{ $setting->site_name ?? 'Buzz' }}</span>
                    Bangladesh
                </div>
            </div>

            {{-- Right: Dynamic page content from backend --}}
            <div>
                <span class="section-tag">About Us</span>
                <h2 class="section-heading">{{ $page->title }}</h2>
                <div class="about-prose">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ===== CONTACT INFO (from Backend Settings) ===== --}}
@php
    $gs = $generalSetting ?? null;
    $hasAnyInfo = $gs && ($gs->address || $gs->phone || $gs->email);
    $hasSocials = $setting && ($setting->facebook || $setting->instagram || $setting->twitter || $setting->youtube || $setting->linkedin || $setting->whatsapp_number);
@endphp

@if($hasAnyInfo || $hasSocials || ($setting && $setting->google_map))
<section class="about-section alt">
    <div class="container">
        <div style="text-align:center;max-width:600px;margin:0 auto;">
            <span class="section-tag">Find Us</span>
            <h2 class="section-heading">Our Contact Info</h2>
        </div>

        {{-- Info Cards --}}
        @if($hasAnyInfo)
        <div class="info-cards-grid">
            @if($gs->address)
            <div class="info-card">
                <div class="info-card-icon"><i class="ph ph-map-pin"></i></div>
                <p class="info-card-label">Address</p>
                <p class="info-card-value">{{ $gs->address }}</p>
            </div>
            @endif
            @if($gs->phone)
            <div class="info-card">
                <div class="info-card-icon"><i class="ph ph-phone"></i></div>
                <p class="info-card-label">Phone</p>
                <p class="info-card-value">{{ $gs->phone }}</p>
            </div>
            @endif
            @if($gs->email)
            <div class="info-card">
                <div class="info-card-icon"><i class="ph ph-envelope-simple"></i></div>
                <p class="info-card-label">Email</p>
                <p class="info-card-value">{{ $gs->email }}</p>
            </div>
            @endif
        </div>
        @endif

        {{-- Social Links --}}
        @if($hasSocials)
        <div style="text-align:center;margin-top:36px;">
            <p style="font-size:13px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:#999;margin-bottom:16px;">Follow Us</p>
            <div class="about-socials" style="justify-content:center;">
                @if($setting->facebook)
                <a href="{{ $setting->facebook }}" class="about-social-link" target="_blank">
                    <i class="ph ph-facebook-logo"></i> Facebook
                </a>
                @endif
                @if($setting->instagram)
                <a href="{{ $setting->instagram }}" class="about-social-link" target="_blank">
                    <i class="ph ph-instagram-logo"></i> Instagram
                </a>
                @endif
                @if($setting->twitter)
                <a href="{{ $setting->twitter }}" class="about-social-link" target="_blank">
                    <i class="ph ph-twitter-logo"></i> Twitter
                </a>
                @endif
                @if($setting->youtube)
                <a href="{{ $setting->youtube }}" class="about-social-link" target="_blank">
                    <i class="ph ph-youtube-logo"></i> YouTube
                </a>
                @endif
                @if($setting->linkedin)
                <a href="{{ $setting->linkedin }}" class="about-social-link" target="_blank">
                    <i class="ph ph-linkedin-logo"></i> LinkedIn
                </a>
                @endif
                @if($setting->whatsapp_number)
                <a href="https://wa.me/{{ preg_replace('/\D/', '', $setting->whatsapp_number) }}" class="about-social-link" target="_blank">
                    <i class="ph ph-whatsapp-logo"></i> WhatsApp
                </a>
                @endif
            </div>
        </div>
        @endif

        {{-- Google Map Embed --}}
        @if($setting && $setting->google_map)
        <div class="about-map-wrap">
            {!! $setting->google_map !!}
        </div>
        @endif

    </div>
</section>
@endif

{{-- ===== CTA ===== --}}
<div class="about-cta">
    <div class="container">
        <h2>Ready to Start Shopping?</h2>
        <p>
            @if($setting && $setting->site_name)
                Discover the best products at {{ $setting->site_name }}. Join thousands of happy customers today.
            @else
                Discover thousands of premium products at the best prices. Join our happy customers today.
            @endif
        </p>
        <div>
            <a href="{{ route('frontend.shop') }}" class="cta-btn-white">
                <i class="ph ph-shopping-bag"></i>
                Shop Now
            </a>
            <a href="{{ route('frontend.contact') }}" class="cta-btn-outline">
                <i class="ph ph-chat-dots"></i>
                Contact Us
            </a>
        </div>
    </div>
</div>

@endsection
