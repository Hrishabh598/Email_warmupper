@extends('master')

@section('title')
<title>Add Email - HillSync Email warmupper</title>
@endsection

@section('content')
<div class="container mt-5">
        <h1 class="mb-4">Add email</h1>
        @if(session('error'))
            <p style="color: red;">{{ session('error') }}</p>
        @endif
        <form action="{{ route('email.otp') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="mailer">Mailer:</label>
                <input type="text" class="form-control" id="mailer" name="mailer" placeholder="SMTP or any other" required>
            </div>
            <div class="mb-3">
                <label for="host">Host:</label>
                <input type="text" class="form-control" id="host" name="host" placeholder="gmail.com or any other" required>
            </div>
            <div class="mb-3">
                <label for="port_no">Port Number:</label>
                <input type="number" class="form-control" id="port_no" name="port_no" value="587" required>
            </div>
            <div class="mb-3">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="please enter your app password" required>
            </div>
            <div class="mb-3">
                <label for="encryption">Encryption:</label>
                <input type="text" class="form-control" id="encryption" name="encryption" value="tls" required>
            </div>
            <div class="mb-3">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="asdfasdf@example.com" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection