@extends('masterauth')
@section('title') 
    <title>Sign up - HillSync Email Warmupper</title>
@endsection

@section('content')
    <div class="container-fluid h-75 d-flex justify-content-center align-items-center">
        <div class="col-sm-4">
            @if(session('error'))
                <p style="color:red">{{ session('error') }}</p>
            @endif
            <form action="register" class="form-h p-4 border rounded shadow" id="registrationForm" method="POST">
                <div class="mb-3">
                    @csrf
                    <label for="name" class="col-form-label">Enter Full name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="col-form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <span id="emailError" style="color:red"></span>
                </div>
                <div class="mb-3">
                    <label for="password1" class="col-form-label">Enter password</label>
                    <input type="password" class="form-control" id="password1" name="password1" required>
                    <span id="password1Error" style="color:red"></span>
                </div>
                <div class="mb-3">
                    <label for="password2" class="col-form-label">Confirm password</label>
                    <input type="password" class="form-control" id="password2" name="password2" required>
                    <span id="password2Error" style="color:red"></span>
                    <a href="login" class="nav-link">Already have an account?</a>
                </div>
                <button class="btn btn-primary">Sign up</button>
            </form>
        </div>
    </div>
<script>
    document.getElementById('registrationForm').addEventListener('submit', function(event) {
        let isValid = true;

        // Email validation
        const email = document.getElementById('email');
        const emailError = document.getElementById('emailError');
        const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

        if (!emailPattern.test(email.value)) {
            emailError.textContent = "Please enter a valid email address.";
            isValid = false;
        } else {
            emailError.textContent = "";
        }

        //   Password validation
        const password = document.getElementById('password1');
        const passwordError = document.getElementById('password1Error');

        if (password.value.length < 8) {
            passwordError.textContent = "Password must be at least 8 characters long.";
            isValid = false;
        } else {
            passwordError.textContent = "";
        }

        // Confirm Password validation
        const confirmPassword = document.getElementById('password2');
        const confirmPasswordError = document.getElementById('password2Error');

        if (confirmPassword.value !== password.value) {
            confirmPasswordError.textContent = "Passwords do not match.";
            isValid = false;
        } else {
            confirmPasswordError.textContent = "";
        }

        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
        }
    });
</script>
@endsection