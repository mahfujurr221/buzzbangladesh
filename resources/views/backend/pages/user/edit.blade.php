@extends('backend.layouts.master')

@section('title', 'Edit User')

@section('content')
<div class="row justify-content-center g-4">
    <div class="col-xl-10">
        <x-modern.card class="border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center px-4 pt-4 pb-0">
                <h5 class="fw-bold mb-0 d-flex align-items-center">
                    <span class="bg-primary p-1 rounded-circle me-2" style="width: 8px; height: 8px;"></span>
                    Edit User: {{ $user->fname }} {{ $user->lname }}
                </h5>
                <x-modern.actions.button tag="a" href="{{ route('users.index') }}" actionType="back" label="User List" size="sm" />
            </div>

            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body p-4">
                    <div class="alert alert-soft-info border-0 d-flex align-items-center mb-4">
                        <i class="bx bx-edit font-size-22 me-2"></i>
                        <div>Update account details and manage system permissions for this user account.</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="position-relative">
                                    <img id="imagePreview" 
                                        src="{{ $user->image ? asset('backend/images/users/' . $user->image) : asset('backend/images/users/avatar-1.jpg') }}"
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
                                    <p class="text-muted mb-0 font-size-12">Current profile picture shown. Click to change.</p>
                                </div>
                            </div>
                            @error('image') <div class="text-danger font-size-12 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <x-modern.input label="First Name" name="fname" icon="bx bx-user" placeholder="Enter first name" :value="$user->fname" required />
                        </div>

                        <div class="col-md-6">
                            <x-modern.input label="Last Name" name="lname" icon="bx bx-user" placeholder="Enter last name" :value="$user->lname" />
                        </div>

                        <div class="col-md-6">
                            <x-modern.input label="Email Address" type="email" name="email" icon="bx bx-envelope" placeholder="example@email.com" :value="$user->email" required />
                        </div>

                        <div class="col-md-6">
                            <x-modern.input label="Phone Number" name="phone" icon="bx bx-phone" placeholder="+880..." :value="$user->phone" required />
                        </div>

                        <div class="col-md-12">
                            <x-modern.select2 label="Assign Roles" name="role" placeholder="Select one or more roles" multiple required allowSelectAll="true">
                                @php
                                    $userRoles = $user->roles->pluck('name')->toArray();
                                @endphp
                                @foreach ($roles as $role)
                                <option {{ in_array($role->name, $userRoles) ? 'selected' : '' }} value="{{ $role->name }}">
                                    {{ $role->name }}
                                </option>
                                @endforeach
                            </x-modern.select2>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-12 text-end">
                            <x-modern.actions.button type="submit" label="Update User Account" variant="primary" icon="bx bx-save" />
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