@extends('masterauth')
@section('title') 
<title>login - HillSync Email Warmupper</title>
@endsection
@section('content')
<div class="container-fluid h-75 d-flex justify-content-center align-items-center">
    <div class="col-sm-4">
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif
        <form action="login" class="form-h p-4 border rouded shadow" method="POST">
            <div class="mb-3">
                @csrf
                <label for="email" class="col-form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="col-form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <a href="forget" class="nav-link">Forget password?</a>
            </div>
            <a href="register" class="nav-link">Create New Account</a>
            <button class="btn btn-primary" type="submit">Log in</button>
        </form>
    </div>
</div>
@endsection