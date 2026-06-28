@extends('backend.layouts.master')

@section('title', 'Create New User')

@section('content')
<div class="row justify-content-center g-4">
    <div class="col-xl-10">
        <x-modern.card class="border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center px-4 pt-4 pb-0">
                <h5 class="fw-bold mb-0 d-flex align-items-center">
                    <span class="bg-primary p-1 rounded-circle me-2" style="width: 8px; height: 8px;"></span>
                    Create New User
                </h5>
                <x-modern.actions.button tag="a" href="{{ route('users.index') }}" actionType="back" label="User List" size="sm" />
            </div>

            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body p-4">
                    <div class="alert alert-soft-primary border-0 d-flex align-items-center mb-4">
                        <i class="bx bx-info-circle font-size-22 me-2"></i>
                        <div>Fill in the details below to create a new system user with specific roles and permissions.</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="position-relative">
                                    <img id="imagePreview" src="{{ asset('backend/images/users/avatar-1.jpg') }}"
                                        alt="Preview" class="rounded-circle border" width="70" height="70"
                                        style="object-fit: cover;">
                                    <label for="image" class="position-absolute bottom-0 end-0 btn btn-sm btn-primary rounded-circle p-1"
                                        style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bx bx-camera font-size-14"></i>
                                        <input type="file" name="image" id="image" class="d-none" accept="image/*" onchange="previewImage(event)">
                                    </label>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">Profile Photo</h6>
                                    <p class="text-muted mb-0 font-size-12">Click the camera icon to upload a photo.</p>
                                </div>
                            </div>
                            @error('image') <div class="text-danger font-size-12 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <x-modern.input label="First Name" name="fname" icon="bx bx-user" placeholder="Enter first name" :value="old('fname')" required />
                        </div>

                        <div class="col-md-6">
                            <x-modern.input label="Last Name" name="lname" icon="bx bx-user" placeholder="Enter last name" :value="old('lname')" />
                        </div>

                        <div class="col-md-6">
                            <x-modern.input label="Email Address" type="email" name="email" icon="bx bx-envelope" placeholder="example@email.com" :value="old('email')" required />
                        </div>

                        <div class="col-md-6">
                            <x-modern.input label="Phone Number" name="phone" icon="bx bx-phone" placeholder="+880..." :value="old('phone')" required />
                        </div>

                        <div class="col-md-6">
                            <x-modern.input label="Password" type="password" name="password" icon="bx bx-lock-alt" placeholder="••••••••" required />
                        </div>

                        <div class="col-md-6">
                            <x-modern.input label="Confirm Password" type="password" name="password_confirmation" icon="bx bx-check-shield" placeholder="••••••••" required />
                        </div>

                        <div class="col-md-12">
                            <x-modern.select2 label="Assign Roles" name="role" placeholder="Select one or more roles" multiple required allowSelectAll="true">
                                @foreach ($roles as $role)
                                <option {{ (is_array(old('role')) && in_array($role->name, old('role'))) ? 'selected' : '' }} value="{{ $role->name }}">
                                    {{ $role->name }}
                                </option>
                                @endforeach
                            </x-modern.select2>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-12 text-end">
                            <x-modern.actions.button type="submit" label="Create User Account" variant="primary" icon="bx bx-user-plus" />
                        </div>
                    </div>
                </div>
            </form>
        </x-modern.card>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const preview = document.getElementById('imagePreview');
            if(preview) preview.src = reader.result;
        }
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endpush