@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container mt-4 profile-page">
    <h2 class="mb-3 text-dark">
        <i class="fas fa-user-circle me-2 text-primary"></i> Profile Settings
    </h2>
    <p class="text-muted mb-4">Update your personal and company information</p>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="row g-3 profile-form">
        @csrf
        @method('PUT')

        {{-- Full Name --}}
        <div class="col-md-6">
            <label class="form-label"><i class="fas fa-user me-1 text-primary"></i> Full Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
        </div>

        {{-- Email --}}
        <div class="col-md-6">
            <label class="form-label"><i class="fas fa-envelope me-1 text-primary"></i> Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required>
        </div>

        {{-- New Password --}}
        <div class="col-md-6">
            <label class="form-label"><i class="fas fa-lock me-1 text-primary"></i> New Password</label>
            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
        </div>

        {{-- Confirm Password --}}
        <div class="col-md-6">
            <label class="form-label"><i class="fas fa-lock me-1 text-primary"></i> Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        {{-- Company Logo --}}
        <div class="col-12">
            <label class="form-label"><i class="fas fa-image me-1 text-primary"></i> Company Logo</label>
            <input type="file" name="company_logo" class="form-control" id="companyLogoInput">

            <div class="mt-3 text-center">
                @if (Auth::user()->company_logo)
                    <img id="logoPreview" src="{{ asset('storage/' . Auth::user()->company_logo) }}" alt="Logo" class="rounded" style="max-height: 120px;">
                @else
                    <img id="logoPreview" src="" alt="Logo Preview" class="rounded d-none" style="max-height: 120px;">
                @endif
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="col-12">
            <button type="submit" class="btn btn-primary w-100 fw-semibold">
                <i class="fas fa-save me-1"></i> Update Profile
            </button>
        </div>
    </form>
</div>

{{-- Logo Preview Script --}}
<script>
    document.getElementById('companyLogoInput').addEventListener('change', function(event) {
        const input = event.target;
        const preview = document.getElementById('logoPreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
</script>

{{-- Page Scoped CSS --}}
<style>
/* ---------- Profile Page Scoped CSS ---------- */
.profile-page .form-label {
    font-weight: 600;
}
.profile-page input.form-control,
.profile-page select.form-select {
    border-radius: 6px;
    border: 1px solid #ced4da;
    padding: 0.5rem 0.75rem;
    transition: all 0.3s;
}
.profile-page input.form-control:focus,
.profile-page select.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 6px rgba(13, 110, 253, 0.2);
}

.profile-page #logoPreview {
    max-height: 120px;
    border-radius: 8px;
    border: 1px solid #ced4da;
    transition: all 0.3s;
}
.profile-page #logoPreview:hover {
    transform: scale(1.05);
    box-shadow: 0 3px 10px rgba(0,0,0,0.15);
}

.profile-page .alert-success {
    border-radius: 6px;
    font-weight: 500;
}
</style>
@endsection
