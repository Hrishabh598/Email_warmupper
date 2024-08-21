@extends('masterauth')
@section('title') 
<title> Verify otp code - HillSync Email Warmupper</title>
@endsection
@section('content')
<div class="container-fluid h-75 d-flex justify-content-center align-items-center">
    <div class="col-sm-4">
    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif
        <form action="verify_forget" class="form-h p-4 border rounded shadow" method="POST">
            <div class="mb-3">
                @csrf
                <label for="otp" class="col-form-label">Enter the otp</label>
                <input type="text" class="form-control" id="otp" name="otp" required>
            </div>
            <a href="register" class="nav-link">Create New Account</a>
            <button class="btn btn-primary">Verify code</button>
        </form>
    </div>
</div>
@endsection