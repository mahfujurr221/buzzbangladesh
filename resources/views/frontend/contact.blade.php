@extends('frontend.layouts.master')
@section('title', 'Contact Us - ' . ($setting->site_name ?? 'Buzz Bangladesh'))

@section('content')
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
        </div>
    </div>
</div>

<div class="contact-us-section py-20 bg-white">
    <div class="container mx-auto px-4 md:px-0">
        <div class="flex flex-col lg:flex-row gap-12 max-w-6xl mx-auto">
            <!-- Contact Info -->
            <div class="lg:w-1/3">
                <h3 class="text-3xl font-bold text-black mb-6">Get in Touch</h3>
                <p class="text-gray-600 mb-8 text-lg">Have a question or need assistance? We'd love to hear from you. Reach out to our team!</p>
                
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full bg-black flex items-center justify-center flex-shrink-0">
                        <i class="ph ph-map-pin text-white text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg text-black">Office Address</h4>
                        <p class="text-gray-500 mt-1">{{ $setting->address ?? 'Dhaka, Bangladesh' }}</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full bg-black flex items-center justify-center flex-shrink-0">
                        <i class="ph ph-phone text-white text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg text-black">Phone Number</h4>
                        <p class="text-gray-500 mt-1">{{ $setting->phone ?? '+880 123 456 789' }}</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-black flex items-center justify-center flex-shrink-0">
                        <i class="ph ph-envelope-simple text-white text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg text-black">Email Address</h4>
                        <p class="text-gray-500 mt-1">{{ $setting->email ?? 'support@buzzbangladesh.com' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="lg:w-2/3">
                <div class="bg-gray-50 p-8 md:p-10 rounded-2xl shadow-sm border border-gray-100 relative">
                    <h3 class="text-2xl font-bold text-black mb-6">Send us a Message</h3>
                    
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-lg border border-green-200 flex items-center gap-3">
                            <i class="ph ph-check-circle text-2xl"></i>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('frontend.contact.submit') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Your Name *</label>
                                <input type="text" name="name" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-black focus:border-black transition-colors" placeholder="John Doe" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" name="email" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-black focus:border-black transition-colors" placeholder="john@example.com" required>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <input type="text" name="subject" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-black focus:border-black transition-colors" placeholder="How can we help?">
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                            <textarea name="message" rows="5" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-black focus:border-black transition-colors" placeholder="Your message here..." required></textarea>
                        </div>
                        <button type="submit" class="px-8 py-3 bg-black text-white rounded-lg font-semibold hover:bg-gray-800 transition-colors w-full md:w-auto flex items-center justify-center gap-2">
                            <span>Send Message</span>
                            <i class="ph ph-paper-plane-tilt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
