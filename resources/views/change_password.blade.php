@extends('masterauth')
@section('title') 
<title>Change Password - HillSync Email Warmupper</title>
@endsection

@section('content')
<div class="container-fluid h-75 d-flex justify-content-center align-items-center">
    <div class="col-sm-4">
        <form action="change_password" class="form-h p-4 border rounded shadow" method="POST">
            <div class="mb-3">
                @csrf
                <label for="password1" class="col-form-label">Enter New password</label>
                <input type="password" class="form-control" id="password1" name="password1" required>
            </div>
            <div class="mb-3">
                <label for="password2" class="col-form-label">Confirm New password</label>
                <input type="password" class="form-control" id="password2" name="password2" required>
                <a href="login" class="nav-link">Already have an account?</a>
            </div>
            <button class="btn btn-primary">Change Password</button>
        </form>
    </div>
</div>
@endsection