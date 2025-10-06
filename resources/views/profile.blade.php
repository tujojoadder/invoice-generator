@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="card p-4 shadow-sm" style="max-width: 600px; margin: 0 auto;">
        <h4 class="mb-3">Profile</h4>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}"
                    required>
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}"
                    required>
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control"
                    placeholder="Leave blank to keep current password">
            </div>

            {{-- Confirm Password --}}
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            {{-- Company Logo --}}
            <div class="mb-3">
                <label class="form-label">Company Logo</label>
                <input type="file" name="company_logo" class="form-control" id="companyLogoInput">

                <div class="mt-2">
                    @if (Auth::user()->company_logo)
                        <img id="logoPreview" src="{{ asset('storage/' . Auth::user()->company_logo) }}" alt="Logo"
                            class="img-fluid" style="max-height: 100px;">
                    @else
                        <img id="logoPreview" src="" alt="Logo Preview" class="img-fluid d-none"
                            style="max-height: 100px;">
                    @endif
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    {{-- JS for logo preview --}}
    <script>
        document.getElementById('companyLogoInput').addEventListener('change', function(event) {
            const input = event.target;
            const preview = document.getElementById('logoPreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none'); // show preview if hidden
                }
                reader.readAsDataURL(input.files[0]);
            }
        });
    </script>
@endsection
