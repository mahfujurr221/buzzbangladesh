@extends('backend.layouts.master')

@section('title', 'Website Settings')

@section('content')
    <div class="row settings-main">
        <div class="col-12 settings-body">
            <form action="{{ route('settings.website.update') }}" method="POST" enctype="multipart/form-data"
                class="h-100 d-flex flex-column">
                @csrf
                @method('PUT')

                <!-- Header Area -->
                <div class="d-flex justify-content-between align-items-center mb-4 mt-2 px-1">
                    <div>
                        <h2 class="fw-bold mb-1 text-dark">Website Configuration</h2>
                        <p class="text-muted mb-0 small">Manage your site branding, SEO, and legal policies</p>
                    </div>
                    <button type="submit" class="btn btn-primary shadow-sm rounded-3 px-4 d-flex align-items-center">
                        <i class="bi bi-save me-2"></i> Save All Changes
                    </button>
                </div>

                <div class="row g-3 flex-grow-1 overflow-hidden">
                    <!-- Navigation Sidebar -->
                    <div class="col-xl-3 col-lg-4 h-100">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body p-2">
                                <div class="nav flex-column nav-pills modern-pills" id="v-pills-tab" role="tablist">
                                    <button
                                        class="nav-link active text-start py-2 px-3 rounded-3 mb-1 fw-semibold d-flex align-items-center"
                                        id="nav-brand-tab" data-bs-toggle="pill" data-bs-target="#nav-brand" type="button"
                                        role="tab">
                                        <i class="bi bi-info-circle me-2"></i> Brand Info
                                    </button>
                                    <button
                                        class="nav-link text-start py-2 px-3 rounded-3 mb-1 fw-semibold d-flex align-items-center"
                                        id="nav-logos-tab" data-bs-toggle="pill" data-bs-target="#nav-logos" type="button"
                                        role="tab">
                                        <i class="bi bi-image me-2"></i> Identity & Assets
                                    </button>
                                    <button
                                        class="nav-link text-start py-2 px-3 rounded-3 mb-1 fw-semibold d-flex align-items-center"
                                        id="nav-social-tab" data-bs-toggle="pill" data-bs-target="#nav-social" type="button"
                                        role="tab">
                                        <i class="bi bi-share me-2"></i> Social Media
                                    </button>
                                    <button
                                        class="nav-link text-start py-2 px-3 rounded-3 mb-1 fw-semibold d-flex align-items-center"
                                        id="nav-seo-tab" data-bs-toggle="pill" data-bs-target="#nav-seo" type="button"
                                        role="tab">
                                        <i class="bi bi-search me-2"></i> SEO & Metadata
                                    </button>
                                    <button class="nav-link text-start py-2.5 px-3 rounded-3 mb-1"
                                        id="nav-promo-tab" data-bs-toggle="pill" data-bs-target="#nav-promo" type="button"
                                        role="tab">
                                        <i class="bi bi-images me-2"></i> Promo Banners
                                    </button>
                                    <button class="nav-link text-start py-2.5 px-3 rounded-3"
                                        id="nav-backgrounds-tab" data-bs-toggle="pill" data-bs-target="#nav-backgrounds" type="button"
                                        role="tab">
                                        <i class="bi bi-image-fill me-2"></i> Page Backgrounds
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="col-xl-9 col-lg-8 h-100">
                        <div class="tab-content border-0 h-100 tab-content-scroll" id="v-pills-tabContent">

                            {{-- Brand Info --}}
                            <div class="tab-pane fade show active" id="nav-brand" role="tabpanel">
                                <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-building me-2"></i>Business
                                        Information</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label fw-bold text-dark small">Site Name</label>
                                                <input type="text" class="form-control form-control-sm" name="site_name"
                                                    value="{{ $setting->site_name }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label fw-bold text-dark small">Site Slogan /
                                                    Title (En)</label>
                                                <input type="text" class="form-control form-control-sm" name="site_title"
                                                    value="{{ $setting->site_title }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label fw-bold text-dark small">Site Slogan /
                                                    Title (Bn)</label>
                                                <input type="text" class="form-control form-control-sm" name="site_title_bn"
                                                    value="{{ $setting->site_title_bn }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label fw-bold text-dark small">Official Phone</label>
                                                <input type="text" class="form-control form-control-sm" name="phone"
                                                    value="{{ $setting->phone }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label fw-bold text-dark small">Support Email</label>
                                                <input type="email" class="form-control form-control-sm" name="email"
                                                    value="{{ $setting->email }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold text-dark small">Full Address (En)</label>
                                                <textarea class="form-control form-control-sm" name="address"
                                                    rows="2">{{ $setting->address }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold text-dark small">Full Address (Bn)</label>
                                                <textarea class="form-control form-control-sm" name="address_bn"
                                                    rows="2">{{ $setting->address_bn }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold text-dark small">Google Map Embed
                                                    Code</label>
                                                <input type="text" class="form-control form-control-sm" name="google_map"
                                                    value="{{ $setting->google_map }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Identity & Assets --}}
                            <div class="tab-pane fade" id="nav-logos" role="tabpanel">
                                <div class="card border-0 shadow-sm rounded-4 p-3 mb-3 text-center">
                                    <h5 class="fw-bold mb-3 text-start text-primary"><i class="bi bi-image me-2"></i>Media &
                                        Identity</h5>
                                    <div class="row g-4 text-center justify-content-center">
                                        <div class="col-md-6">
                                            <div class="p-3 rounded-4 border-2 border-dashed border-light-subtle bg-light">
                                                <h6 class="fw-bold mb-2 small text-dark">Main Site Logo</h6>
                                                <div
                                                    class="preview-box mb-2 p-2 bg-white rounded border d-inline-block shadow-sm">
                                                    <img id="logo-preview"
                                                        src="{{ asset('frontend/assets/images/logo.png') }}"
                                                        onerror="this.onerror=null;this.src='{{ asset('backend/images/products/placeholder.png') }}'"
                                                        class="img-fluid" style="max-height: 80px;">
                                                </div>
                                                <input type="file" class="form-control form-control-sm mt-2" name="logo"
                                                    id="logo-input" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 rounded-4 border-2 border-dashed border-light-subtle bg-light">
                                                <h6 class="fw-bold mb-2 small text-dark">Site Favicon</h6>
                                                <div
                                                    class="preview-box mb-2 p-2 bg-white rounded border d-inline-block shadow-sm">
                                                    <img id="favicon-preview"
                                                        src="{{ asset('frontend/assets/images/favicon.png') }}"
                                                        onerror="this.onerror=null;this.src='{{ asset('backend/images/products/placeholder.png') }}'"
                                                        class="img-fluid" style="width: 32px; height: 32px;">
                                                </div>
                                                <input type="file" class="form-control form-control-sm mt-2" name="favicon"
                                                    id="favicon-input" accept="image/*">
                                            </div>
                                        </div>
                                        </div>
                                </div>
                            </div>

                            {{-- Social Media --}}
                            <div class="tab-pane fade" id="nav-social" role="tabpanel">
                                <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-share me-2"></i>Social Reach</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small">Facebook URL</label>
                                            <input type="text" class="form-control form-control-sm" name="facebook"
                                                value="{{ $setting->facebook }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small">Twitter URL</label>
                                            <input type="text" class="form-control form-control-sm" name="twitter"
                                                value="{{ $setting->twitter }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small">Instagram URL</label>
                                            <input type="text" class="form-control form-control-sm" name="instagram"
                                                value="{{ $setting->instagram }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small">WhatsApp Number</label>
                                            <input type="text" class="form-control form-control-sm" name="whatsapp_number"
                                                value="{{ $setting->whatsapp_number }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small">YouTube URL</label>
                                            <input type="text" class="form-control form-control-sm" name="youtube"
                                                value="{{ $setting->youtube }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small">LinkedIn URL</label>
                                            <input type="text" class="form-control form-control-sm" name="linkedin"
                                                value="{{ $setting->linkedin }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small">Pinterest URL</label>
                                            <input type="text" class="form-control form-control-sm" name="pinterest"
                                                value="{{ $setting->pinterest }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SEO --}}
                            <div class="tab-pane fade" id="nav-seo" role="tabpanel">
                                <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-search me-2"></i>SEO Optimization
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small">Meta Title (En)</label>
                                            <input type="text" class="form-control form-control-sm" name="meta_title"
                                                value="{{ $setting->meta_title }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small">Meta Title (Bn)</label>
                                            <input type="text" class="form-control form-control-sm" name="meta_title_bn"
                                                value="{{ $setting->meta_title_bn }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small">Meta Keywords (En) (Comma
                                                separated)</label>
                                            <input type="text" class="form-control form-control-sm" name="meta_keywords"
                                                value="{{ is_array($setting->meta_keywords) ? implode(', ', $setting->meta_keywords) : $setting->meta_keywords }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small">Meta Keywords (Bn) (Comma
                                                separated)</label>
                                            <input type="text" class="form-control form-control-sm" name="meta_keywords_bn"
                                                value="{{ is_array($setting->meta_keywords_bn) ? implode(', ', $setting->meta_keywords_bn) : $setting->meta_keywords_bn }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small">Meta Description (En)</label>
                                            <textarea class="form-control form-control-sm" name="meta_description"
                                                rows="3">{{ $setting->meta_description }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small">Meta Description (Bn)</label>
                                            <textarea class="form-control form-control-sm" name="meta_description_bn"
                                                rows="3">{{ $setting->meta_description_bn }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Promo Banners --}}
                            <div class="tab-pane fade" id="nav-promo" role="tabpanel">
                                <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-images me-2"></i>Promo Banners (Home Page)</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="p-3 rounded-4 border-2 border-dashed border-light-subtle bg-light">
                                                <h6 class="fw-bold mb-2 small text-dark">Promo Banner 1 (Left)</h6>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-dark small">Title</label>
                                                    <input type="text" class="form-control form-control-sm" name="promo_banner_1_title" value="{{ $setting->promo_banner_1_title ?? 'Best Sellers' }}">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-dark small">Link</label>
                                                    <input type="text" class="form-control form-control-sm" name="promo_banner_1_link" value="{{ $setting->promo_banner_1_link ?? route('frontend.shop') }}">
                                                </div>

                                                <label class="form-label fw-bold text-dark small">Background Image</label>
                                                <div class="preview-box mb-2 p-2 bg-white rounded border d-inline-block shadow-sm w-100">
                                                    <img id="promo1-preview"
                                                        src="{{ $setting->promo_banner_1 ? asset($setting->promo_banner_1) : asset('backend/images/products/placeholder.png') }}"
                                                        class="img-fluid" style="width: auto; height: 120px;">
                                                </div>
                                                <input type="file" class="form-control form-control-sm mt-2" name="promo_banner_1"
                                                    id="promo1-input" accept="image/*">
                                                <small class="text-muted">Recommended size: 690x380 px</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 rounded-4 border-2 border-dashed border-light-subtle bg-light">
                                                <h6 class="fw-bold mb-2 small text-dark">Promo Banner 2 (Right)</h6>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-dark small">Title</label>
                                                    <input type="text" class="form-control form-control-sm" name="promo_banner_2_title" value="{{ $setting->promo_banner_2_title ?? 'New Arrivals' }}">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-dark small">Link</label>
                                                    <input type="text" class="form-control form-control-sm" name="promo_banner_2_link" value="{{ $setting->promo_banner_2_link ?? route('frontend.shop') }}">
                                                </div>

                                                <label class="form-label fw-bold text-dark small">Background Image</label>
                                                <div class="preview-box mb-2 p-2 bg-white rounded border d-inline-block shadow-sm w-100">
                                                    <img id="promo2-preview"
                                                        src="{{ $setting->promo_banner_2 ? asset($setting->promo_banner_2) : asset('backend/images/products/placeholder.png') }}"
                                                        class="img-fluid" style="width: auto; height: 120px;">
                                                </div>
                                                <input type="file" class="form-control form-control-sm mt-2" name="promo_banner_2"
                                                    id="promo2-input" accept="image/*">
                                                <small class="text-muted">Recommended size: 690x380 px</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Page Backgrounds --}}
                            <div class="tab-pane fade" id="nav-backgrounds" role="tabpanel">
                                <div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
                                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-image-fill me-2"></i>Page Backgrounds</h5>
                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <div class="p-3 rounded-4 border-2 border-dashed border-light-subtle bg-light text-center">
                                                <h6 class="fw-bold mb-2 small text-dark">Shop Page Background</h6>
                                                <div class="preview-box mb-2 p-2 bg-white rounded border d-inline-block shadow-sm w-100">
                                                    <img id="shop-bg-preview" src="{{ $setting->shop_bg ? asset($setting->shop_bg) : asset('backend/images/products/placeholder.png') }}"
                                                        alt="Shop Background" class="img-fluid rounded" style="max-height: 100px; object-fit: cover; width: 100%;">
                                                </div>
                                                <input type="file" class="form-control form-control-sm mt-2" name="shop_bg" accept="image/*"
                                                    onchange="document.getElementById('shop-bg-preview').src = window.URL.createObjectURL(this.files[0])">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-3 rounded-4 border-2 border-dashed border-light-subtle bg-light text-center">
                                                <h6 class="fw-bold mb-2 small text-dark">About Us Background</h6>
                                                <div class="preview-box mb-2 p-2 bg-white rounded border d-inline-block shadow-sm w-100">
                                                    <img id="about-bg-preview" src="{{ $setting->about_bg ? asset($setting->about_bg) : asset('backend/images/products/placeholder.png') }}"
                                                        alt="About Background" class="img-fluid rounded" style="max-height: 100px; object-fit: cover; width: 100%;">
                                                </div>
                                                <input type="file" class="form-control form-control-sm mt-2" name="about_bg" accept="image/*"
                                                    onchange="document.getElementById('about-bg-preview').src = window.URL.createObjectURL(this.files[0])">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-3 rounded-4 border-2 border-dashed border-light-subtle bg-light text-center">
                                                <h6 class="fw-bold mb-2 small text-dark">Contact Us Background</h6>
                                                <div class="preview-box mb-2 p-2 bg-white rounded border d-inline-block shadow-sm w-100">
                                                    <img id="contact-bg-preview" src="{{ $setting->contact_bg ? asset($setting->contact_bg) : asset('backend/images/products/placeholder.png') }}"
                                                        alt="Contact Background" class="img-fluid rounded" style="max-height: 100px; object-fit: cover; width: 100%;">
                                                </div>
                                                <input type="file" class="form-control form-control-sm mt-2" name="contact_bg" accept="image/*"
                                                    onchange="document.getElementById('contact-bg-preview').src = window.URL.createObjectURL(this.files[0])">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .settings-main {
            height: calc(100vh - 165px);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .settings-body {
            flex: 1;
            overflow: hidden;
        }

        .tab-content-scroll {
            height: 100%;
            overflow-y: auto;
            padding-right: 8px;
        }

        /* Custom Scrollbar */
        .tab-content-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .tab-content-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .tab-content-scroll::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .tab-content-scroll:hover::-webkit-scrollbar-thumb {
            background: #cbd5e1;
        }

        .modern-pills .nav-link {
            color: #64748b;
            background: transparent;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .modern-pills .nav-link:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .modern-pills .nav-link.active {
            background: rgba(var(--bs-primary-rgb), 0.05) !important;
            color: var(--bs-primary) !important;
            border-color: rgba(var(--bs-primary-rgb), 0.2);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .form-control-sm,
        .form-select-sm {
            border-radius: 8px;
        }

        .rounded-4 {
            border-radius: 0.85rem !important;
        }

        .border-dashed {
            border-style: dashed !important;
        }

        .note-editor.note-frame {
            border-radius: 12px !important;
            overflow: hidden;
            border: 1px solid #e2e8f0 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            // Restore active tab from localStorage
            var activeTab = localStorage.getItem('website_setting_active_tab');
            if (activeTab) {
                var tabTrigger = new bootstrap.Tab(document.querySelector(activeTab));
                tabTrigger.show();
            }

            // Save active tab to localStorage on change
            var navLinks = document.querySelectorAll('button[data-bs-toggle="pill"]');
            navLinks.forEach(function (link) {
                link.addEventListener('shown.bs.tab', function (event) {
                    var tabId = '#' + event.target.id;
                    localStorage.setItem('website_setting_active_tab', tabId);
                });
            });

            // Logo Preview
            const logoInput = document.getElementById('logo-input');
            if(logoInput) {
                logoInput.onchange = function (evt) {
                    const [file] = this.files;
                    if (file) {
                        document.getElementById('logo-preview').src = URL.createObjectURL(file);
                    }
                }
            }
            // Favicon Preview
            const faviconInput = document.getElementById('favicon-input');
            if(faviconInput) {
                faviconInput.onchange = function (evt) {
                    const [file] = this.files;
                    if (file) {
                        document.getElementById('favicon-preview').src = URL.createObjectURL(file);
                    }
                }
            }
            // English Font Logo Preview
            const englishFontLogoInput = document.getElementById('english-font-logo-input');
            if(englishFontLogoInput) {
                englishFontLogoInput.onchange = function (evt) {
                    const [file] = this.files;
                    if (file) {
                        document.getElementById('english-font-logo-preview').src = URL.createObjectURL(file);
                    }
                }
            }
            // Bangla Font Logo Preview
            const banglaFontLogoInput = document.getElementById('bangla-font-logo-input');
            if(banglaFontLogoInput) {
                banglaFontLogoInput.onchange = function (evt) {
                    const [file] = this.files;
                    if (file) {
                        document.getElementById('bangla-font-logo-preview').src = URL.createObjectURL(file);
                    }
                }
            }
            
            // Promo Banner 1 Preview
            const promo1Input = document.getElementById('promo1-input');
            if(promo1Input) {
                promo1Input.onchange = function (evt) {
                    const [file] = this.files;
                    if (file) {
                        document.getElementById('promo1-preview').src = URL.createObjectURL(file);
                    }
                }
            }

            // Promo Banner 2 Preview
            const promo2Input = document.getElementById('promo2-input');
            if(promo2Input) {
                promo2Input.onchange = function (evt) {
                    const [file] = this.files;
                    if (file) {
                        document.getElementById('promo2-preview').src = URL.createObjectURL(file);
                    }
                }
            }

            // Campaign Landing Image Preview
            const campaignLandingInput = document.getElementById('campaign-landing-input');
            if (campaignLandingInput) {
                campaignLandingInput.onchange = function (evt) {
                    const [file] = this.files;
                    if (file) {
                        const preview = document.getElementById('campaign-landing-preview');
                        const placeholder = document.getElementById('campaign-landing-placeholder');
                        preview.src = URL.createObjectURL(file);
                        preview.classList.remove('d-none');
                        if (placeholder) placeholder.classList.add('d-none');
                    }
                }
            }

            if($('.summernote').length > 0) {
                $('.summernote').summernote({
                    height: 150,
                    tabsize: 2,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['view', ['fullscreen', 'codeview']]
                    ]
                });
            }
        });
    </script>
@endpush