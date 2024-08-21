@extends('master')

@section('title')
<title>Dashboard - HillSync Email warmupper</title>
@endsection

@section('content')
    <div class="container mvh-100">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Sent Emails</h5>
                        <p class="card-text">{{ $sentEmails }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Email Ids</h5>
                        <p class="card-text">{{ $totalEmails }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Spam Emails</h5>
                        <p class="card-text">{{ $spamEmails }}</p>
                    </div>
                </div>
            </div>
        </div>
        @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
        @endif
        <div class="card">
            <div class="card-header">
                Email Details
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Sent</th>
                            <th>Next in</th>
                            <th>Landed in spam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($emails as $email)
                            <tr>
                                <td>{{ $email->email }}</td>
                                <td>{{ $email->sent }}</td>
                                <td>{{ $email->next_in }}</td>
                                <td>{{ $email->landed_in_spam }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button class="btn btn-primary "><a class="nav-link" href="add_email"> + add email </a> </button>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection