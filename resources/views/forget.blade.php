@extends('masterauth')
@section('title') 
<title> Forget password - HillSync Email Warmupper</title>
@endsection
@section('content')
<div class="container-fluid h-75 d-flex justify-content-center align-items-center">
    <div class="col-sm-4">
    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif
        <form action="forget" class="form-h p-4 border rounded shadow" method="POST">
            <div class="mb-3">
                @csrf
                <label for="email" class="col-form-label">Enter your email</label>
                <input type="text" class="form-control" id="email" name="email" required>
            </div>
            <a href="register" class="nav-link">Create New Account</a>
            <button class="btn btn-primary">submit</button>
        </form>
    </div>
</div>
@endsection