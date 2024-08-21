@extends('masterauth')
@section('title') 
<title>Sign up - HillSync Email Warmupper</title>
@endsection

@section('content')
<div class="container-fluid h-75 d-flex justify-content-center align-items-center">
    <div class="col-sm-4">
        <form action="register" class="form-h p-4 border rounded shadow" method="POST">
            <div class="mb-3">
                @csrf
                <label for="name" class="col-form-label">Enter Full name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                @csrf
                <label for="email" class="col-form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password1" class="col-form-label">Enter password</label>
                <input type="password" class="form-control" id="password1" name="password1" required>
            </div>
            <div class="mb-3">
                <label for="password2" class="col-form-label">Confirm password</label>
                <input type="password" class="form-control" id="password2" name="password2" required>
                <a href="login" class="nav-link">Already have an account?</a>
            </div>
            <button class="btn btn-primary">Sign up</button>
        </form>
    </div>
</div>
@endsection