@extends('layout.app')

@section('title','signup')

@section('content')

    <div class="auth-page-wrapper pt-5">
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>
            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <div class="auth-page-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4 card-bg-fill">
                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Reset Password</h5>
                                    <p class="text-muted">Enter your new password below</p>
                                    <lord-icon src="https://cdn.lordicon.com/tyounuzx.json"
                                               trigger="loop"
                                               colors="primary:#0ab39c"
                                               class="avatar-xl"></lord-icon>
                                </div>

                                {{-- Success & Error Messages --}}
                                @if (session('success'))
                                    <div class="alert alert-success text-center mb-2 mx-2">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger text-center mb-2 mx-2">
                                        {{ $errors->first() }}
                                    </div>
                                @endif

                                <div class="p-2">
                                    <form action="{{ route('password.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $token }}">

                                        <div class="mb-3">
                                            <label class="form-label">New Password</label>
                                            <input type="password" name="password" class="form-control" placeholder="Enter new password" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Confirm Password</label>
                                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password" required>
                                        </div>

                                        <div class="text-center mt-4">
                                            <button class="btn btn-success w-100" type="submit">Reset Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <p class="mb-0">
                                Remembered your password?
                                <a href="{{ route('auth.login') }}" class="fw-semibold text-primary text-decoration-underline">Login here</a>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @include('include/footer')
    </div>
@endsection
