@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h1>Invitations</h1>
                <div>
                    <a href="{{ route('invitations.invite-to-admins') }}" class="btn btn-secondary">Invite to Admins</a>
                    <a href="{{ route('invitations.send-form') }}" class="btn btn-primary">Send Invitation</a>
                </div>
            </div>

            @if($invitations->isEmpty())
                <div class="alert alert-info">No invitations found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Token</th>
                                <th>Sent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invitations as $inv)
                                <tr>
                                    <td>{{ $inv->email }}</td>
                                    <td>{{ $inv->company->name ?? '—' }}</td>
                                    <td>{{ ucfirst($inv->role) }}</td>
                                    <td>{{ ucfirst($inv->status) }}</td>
                                    <td style="max-width:200px;word-break:break-all">{{ $inv->token }}</td>
                                    <td>{{ $inv->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
