<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body class="app app-login p-0">
    <div class="row g-0 app-auth-wrapper">
        <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
            <div class="d-flex flex-column align-content-end">
                <div class="app-auth-body mx-auto">
                    <div class="app-auth-branding mb-4">
                        <a class="app-logo" href="{{ route('login') }}">
                            <img class="logo-icon me-2" src="{{ asset('assets/images/dq.png') }}" style="border-radius: 50%;" alt="logo">
                        </a>
                    </div>
                    <h2 class="auth-heading text-center mb-5">Enter OTP to Verify</h2>

                    <div class="auth-form-container text-start">
                        <form class="auth-form login-form" action="{{ route('verify.otp') }}" method="POST">
                            @csrf
                            <div class="otp mb-3">
                                <label class="sr-only" for="otp">OTP</label>
                                <input id="otp" name="otp" type="text" class="form-control" placeholder="Enter OTP" required="required">
                            </div><!--//form-group-->

                            <!-- Hidden email field -->
                            <input type="hidden" name="email" value="{{ session('email') }}">

                            <div class="text-center">
                                <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Verify OTP</button>
                            </div>
                        </form>
                    </div><!--//auth-form-container-->
                </div><!--//auth-body-->
            </div><!--//flex-column-->
        </div><!--//auth-main-col-->

        <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
            <div class="auth-background-holder"></div>
            <div class="auth-background-mask"></div>
            <div class="auth-background-overlay p-3 p-lg-5">
                <div class="d-flex flex-column align-content-end h-100">
                    <div class="h-100"></div>
                    <div class="overlay-content p-3 p-lg-4 rounded">
                        <h5 class="mb-3 overlay-title">Explore DQ Admin Dashboard</h5>
                        <div>DQ is a premium restaurant management system developed by SVINFOTECH
                            <a href="https://svinfotech.co.in">here</a>.
                        </div>
                    </div>
                </div>
            </div><!--//auth-background-overlay-->
        </div><!--//auth-background-col-->
    </div><!--//row-->
</body>
</html>
