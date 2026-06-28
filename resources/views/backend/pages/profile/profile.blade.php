@extends('backend.layouts.master')

@section('title', 'My Profile')

@section('content')
<style>
    .main-content {
        min-height: 0 !important;
    }

    .profile-photo-container {
        position: relative;
        z-index: 2;
    }

    .avatar-xs {
        width: 28px;
        height: 28px;
    }

    .avatar-title {
        font-size: 14px;
    }

    h4,
    h5 {
        font-size: 1.1rem;
    }

    h6 {
        font-size: 0.9rem;
    }

    .nav-pills .nav-link {
        font-size: 0.85rem;
    }

    .card-body {
        padding: 1.25rem !important;
    }
</style>

<div class="row g-4">
    <!-- Left Column: Profile Summary -->
    <div class="col-xl-4 col-lg-5">
        <x-modern.card class="text-center h-100 shadow-sm border-0 overflow-hidden">
            <div class="position-relative mb-4">
                <div class="profile-bg-gradient py-5"
                    style="background: linear-gradient(135deg, #018a3b 0%, #015f28 100%);"></div>
                <div class="profile-photo-container mt-n5">
                    <img id="profileImagePreview" 
                        src="{{ auth()->user()->image ? asset('backend/images/users/' . auth()->user()->image) : asset('backend/images/users/avatar-1.jpg') }}" 
                        alt="Profile"
                        class="rounded-circle border border-4 border-white shadow" height="110" width="110"
                        style="object-fit: cover; margin-top: -55px;">
                </div>
            </div>

            <h4 class="fw-bold mt-2 mb-1">{{ auth()->user()->fname }} {{ auth()->user()->lname }}</h4>
            <div class="d-flex justify-content-center gap-2 mb-4">
                @foreach(auth()->user()->roles as $role)
                <span class="badge bg-soft-success text-success px-3 py-2 rounded-pill font-size-12">
                    <i class="bx bxs-shield-alt-2 me-1"></i>{{ ucwords(str_replace('-', ' ', $role->name)) }}
                </span>
                @endforeach
            </div>

            <div class="text-start px-4 pb-4">
                <div class="mb-3 d-flex align-items-center">
                    <div class="avatar-xs me-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-16">
                            <i class="bx bx-envelope"></i>
                        </span>
                    </div>
                    <div>
                        <p class="text-muted mb-0 font-size-12">Email Address</p>
                        <h6 class="mb-0 text-break">{{ auth()->user()->email }}</h6>
                    </div>
                </div>
                <div class="mb-3 d-flex align-items-center">
                    <div class="avatar-xs me-3">
                        <span class="avatar-title rounded-circle bg-soft-success text-success font-size-16">
                            <i class="bx bx-phone"></i>
                        </span>
                    </div>
                    <div>
                        <p class="text-muted mb-0 font-size-12">Phone Number</p>
                        <h6 class="mb-0">{{ auth()->user()->phone }}</h6>
                    </div>
                </div>
            </div>
        </x-modern.card>
    </div>

    <!-- Right Column: Interactive Tabs -->
    <div class="col-xl-8 col-lg-7">
        <x-modern.card class="border-0 shadow-sm">
            <ul class="nav nav-pills nav-justified bg-light p-1 rounded-pill mb-4" id="profileTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active rounded-pill px-4" id="overview-tab" data-bs-toggle="tab"
                        data-bs-target="#profile-overview" type="button" role="tab">
                        <i class="bx bx-info-circle me-1"></i> Overview
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-4" id="edit-tab" data-bs-toggle="tab"
                        data-bs-target="#profile-edit" type="button" role="tab">
                        <i class="bx bx-edit me-1"></i> Edit Profile
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-4" id="password-tab" data-bs-toggle="tab"
                        data-bs-target="#profile-change-password" type="button" role="tab">
                        <i class="bx bx-key me-1"></i> Security
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="profile-overview" role="tabpanel">
                    <div class="alert alert-soft-primary border-0 d-flex align-items-center mb-4">
                        <i class="bx bx-bolt-circle font-size-22 me-2"></i>
                        <div>Manage your account information and security settings from here.</div>
                    </div>

                    <h5 class="fw-bold mb-4 d-flex align-items-center">
                        <span class="bg-primary p-1 rounded-circle me-2" style="width: 8px; height: 8px;"></span>
                        Account Statistics
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light">
                                <p class="text-muted mb-1 font-size-13">Registered On</p>
                                <h6 class="mb-0 fw-bold">{{ auth()->user()->created_at ?
                                    auth()->user()->created_at->format('d M, Y') : 'N/A' }}</h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light">
                                <p class="text-muted mb-1 font-size-13">Last Updated</p>
                                <h6 class="mb-0 fw-bold">{{ auth()->user()->updated_at ?
                                    auth()->user()->updated_at->format('d M, Y') : 'N/A' }}</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Profile Tab -->
                <div class="tab-pane fade" id="profile-edit" role="tabpanel">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="position-relative">
                                        <img id="editImagePreview" 
                                            src="{{ auth()->user()->image ? asset('backend/images/users/' . auth()->user()->image) : asset('backend/images/users/avatar-1.jpg') }}"
                                            alt="Preview" class="rounded-circle border" width="70" height="70"
                                            style="object-fit: cover;">
                                        <label for="image"
                                            class="position-absolute bottom-0 end-0 btn btn-sm btn-primary rounded-circle p-1"
                                            style="width: 20px; height: 20px;">
                                            <i class="bx bx-camera font-size-12"></i>
                                            <input type="file" name="image" id="image" class="d-none"
                                                accept="image/*" onchange="previewProfileImage(event)">
                                        </label>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Profile Photo</h6>
                                        <p class="text-muted mb-0 font-size-12">Recommended size: 300x300, max 2MB.</p>
                                    </div>
                                </div>
                                @error('image') <div class="text-danger font-size-12 mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <x-modern.input label="First Name" name="fname" icon="bx bx-user"
                                    :value="auth()->user()->fname" required />
                            </div>

                            <div class="col-md-6">
                                <x-modern.input label="Last Name" name="lname" icon="bx bx-user"
                                    :value="auth()->user()->lname" />
                            </div>

                            <div class="col-md-6">
                                <x-modern.input label="Email Address" type="email" name="email" icon="bx bx-envelope"
                                    :value="auth()->user()->email" required />
                            </div>

                            <div class="col-md-6">
                                <x-modern.input label="Phone Number" name="phone" icon="bx bx-phone"
                                    :value="auth()->user()->phone" required />
                            </div>

                            <div class="col-12 text-end mt-4">
                                <x-modern.actions.button type="submit" label="Save Changes" variant="primary"
                                    icon="bx bx-save" />
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Password Tab -->
                <div class="tab-pane fade" id="profile-change-password" role="tabpanel">
                    <form action="{{ route('profile.reset') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <x-modern.input label="Current Password" type="password" name="current_password"
                                    icon="bx bx-lock-alt" required />
                            </div>
                            <div class="col-md-6">
                                <x-modern.input label="New Password" type="password" name="password" icon="bx bx-key"
                                    required />
                            </div>
                            <div class="col-md-6">
                                <x-modern.input label="Confirm New Password" type="password"
                                    name="password_confirmation" icon="bx bx-check-shield" required />
                            </div>
                            <div class="col-12 text-end mt-4">
                                <x-modern.actions.button type="submit" label="Update Password" variant="primary"
                                    icon="bx bx-lock-open-alt" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </x-modern.card>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewProfileImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const preview1 = document.getElementById('profileImagePreview');
            const preview2 = document.getElementById('editImagePreview');
            if(preview1) preview1.src = reader.result;
            if(preview2) preview2.src = reader.result;
        }
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        // 1. Check if there are validation errors for password fields
        @if($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
            var passwordTab = document.querySelector('#password-tab');
            if (passwordTab) {
                bootstrap.Tab.getOrCreateInstance(passwordTab).show();
            }

        // 2. Check if there are validation errors for profile edit fields
        @elseif($errors->has('fname') || $errors->has('lname') || $errors->has('email') || $errors->has('phone') || $errors->has('image'))
            var editTab = document.querySelector('#edit-tab');
            if (editTab) {
                bootstrap.Tab.getOrCreateInstance(editTab).show();
            }
        @endif

        // Optional: Keep tab active if user manually refreshed (using Hash in URL)
        let hash = window.location.hash;
        if (hash) {
            let activeTab = document.querySelector('button[data-bs-target="' + hash + '"]');
            if (activeTab) {
                bootstrap.Tab.getOrCreateInstance(activeTab).show();
            }
        }

        // Add hash to URL when tab is clicked so refresh stays on tab
        document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tabEl => {
            tabEl.addEventListener('shown.bs.tab', function (event) {
                window.location.hash = event.target.getAttribute('data-bs-target');
            });
        });
    });
</script>
@endpush