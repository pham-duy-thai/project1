@extends('layouts.app')

@section('title', __('common.edit_profile') . ' - Cloody')

@push('styles')
<style>
    .card {
        margin-bottom: 1.5rem !important;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        height: auto !important;
    }
    .card-block {
        display: block !important;
    }
    .card-stretch {
        display: block !important;
    }
    .card-height {
        height: auto !important;
        min-height: auto !important;
    }
    .card-body {
        padding: 1.5rem !important;
        display: block !important;
    }
    .form-group {
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ri-check-line mr-2"></i>{{ session('status') === 'profile-updated' ? 'Hồ sơ đã được cập nhật thành công.' : session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ri-error-warning-line mr-2"></i>{{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ri-error-warning-line mr-2"></i>
                    <strong>Có lỗi xảy ra:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Update Profile Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">{{ __('common.profile_information') }}</h4>
                </div>
                <div class="card-body">
                    <form id="profileUpdateForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Avatar Upload -->
                        <div class="form-group text-center">
                            <div class="mb-3">
                                <img src="{{ $user->avatar_url }}" 
                                     alt="avatar" 
                                     class="avatar-130 rounded-circle" 
                                     id="avatarPreview"
                                     style="width: 130px; height: 130px; object-fit: cover; border-radius: 50% !important; display: block; margin-left: auto; margin-right: auto;"
                                     onerror="this.onerror=null; this.src='{{ asset('assets/images/user/user-1.jpg') }}';">
                            </div>
                            <div class="custom-file" style="max-width: 400px; margin: 0 auto;">
                                <input type="file" class="custom-file-input @error('avatar') is-invalid @enderror" id="avatar" name="avatar" accept="image/*" onchange="previewAvatar(event)">
                                <label class="custom-file-label" for="avatar">{{ __('common.choose_avatar') }}</label>
                                @error('avatar')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted d-block mt-2">{{ __('common.max_2mb_jpg_png_gif') }}</small>
                        </div>

                        <div class="form-group">
                            <label for="name">{{ __('common.name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">{{ __('common.email') }}</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if ($user->email_verified_at === null)
                                <small class="text-warning">{{ __('common.email_unverified') }}</small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="phone">{{ __('common.phone') }}</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+1 234 567 890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address">{{ __('common.address') }}</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" placeholder="{{ __('common.your_address') }}">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="bio">{{ __('common.bio') }}</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="4" placeholder="{{ __('common.tell_us_about_yourself') }}">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{ __('common.max_1000_characters') }}</small>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary mr-2">{{ __('common.cancel') }}</a>
                            <button type="submit" class="btn btn-primary">{{ __('common.save_changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';
    
    // Ensure form submission is not prevented
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('profileUpdateForm');
        if (form) {
            // Remove any existing submit handlers that might prevent default
            form.addEventListener('submit', function(e) {
                // Only prevent if there's a validation error
                const fileInput = document.getElementById('avatar');
                if (fileInput && fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    const maxSize = 2 * 1024 * 1024; // 2MB
                    if (file.size > maxSize) {
                        e.preventDefault();
                        alert('File ảnh quá lớn. Vui lòng chọn file nhỏ hơn 2MB.');
                        return false;
                    }
                    // Check file type
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                    if (!allowedTypes.includes(file.type)) {
                        e.preventDefault();
                        alert('Định dạng file không được hỗ trợ. Vui lòng chọn file JPG, PNG hoặc GIF.');
                        return false;
                    }
                }
                // Allow form to submit normally
                return true;
            }, false);
        }
    });
    
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            // Validate file size
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (file.size > maxSize) {
                alert('File ảnh quá lớn. Vui lòng chọn file nhỏ hơn 2MB.');
                event.target.value = ''; // Clear the input
                return;
            }
            
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Định dạng file không được hỗ trợ. Vui lòng chọn file JPG, PNG hoặc GIF.');
                event.target.value = ''; // Clear the input
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                if (preview) {
                    preview.src = e.target.result;
                }
            };
            reader.onerror = function() {
                alert('Lỗi khi đọc file. Vui lòng thử lại.');
                event.target.value = ''; // Clear the input
            };
            reader.readAsDataURL(file);
            
            // Update label
            const fileName = file.name;
            const label = event.target.nextElementSibling;
            if (label && label.classList.contains('custom-file-label')) {
                label.textContent = fileName;
            }
        }
    }
    
    // Make previewAvatar globally available
    window.previewAvatar = previewAvatar;
    
    // Bootstrap custom file input label update
    $(document).ready(function() {
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Chọn file...');
        });
    });
})();
</script>
@endpush
