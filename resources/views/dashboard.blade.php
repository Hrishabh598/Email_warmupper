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
        @if(session('running'))
            <a style="width:100px" class='nav-link' href="stop_warmupping"><button class="button-17" role="button"><i class="fa fa-stop"></i><pre> </pre>Stop</button></a>
        @endif
        @if(session('stopped'))
            <a style="width:100px" class='nav-link' href="start_warmupping"><button class="button-17" role="button"><i class="fa fa-play"></i><pre> </pre>Start</button></a>
        @endif
        @if(session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif
        @if(session('error'))
            <p style="color: red;">{{ session('error') }}</p>
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
                <a style="width:130px" class="nav-link" href="add_email"><button class="button-17" role="button">+ Add Email</button></a>
            </div>
        </div>
    </div>

@endsection