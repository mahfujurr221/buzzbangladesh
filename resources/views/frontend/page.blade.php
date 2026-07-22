@extends('frontend.layouts.master')
@section('title', $page->title . ' - ' . ($setting->site_name ?? 'Buzz Bangladesh'))
@section('content')
<div class="breadcrumb-block style-shared">
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
            @if($page->slug == 'about-us' && $setting?->about_bg)
                <div class="bg-img absolute top-0 right-0 w-full h-full z-[0] opacity-20">
                    <img src="{{ asset($setting->about_bg) }}" alt="About Background" class="w-full h-full object-cover" />
                </div>
            @elseif($page->slug == 'contact-us' && $setting?->contact_bg)
                <div class="bg-img absolute top-0 right-0 w-full h-full z-[0] opacity-20">
                    <img src="{{ asset($setting->contact_bg) }}" alt="Contact Background" class="w-full h-full object-cover" />
                </div>
            @endif
        </div>
    </div>
</div>

<div class="container mx-auto py-16 px-4 md:px-0">
    <div class="prose max-w-4xl mx-auto text-secondary2 leading-relaxed">
        {!! $page->content !!}
    </div>
</div>
@endsection
