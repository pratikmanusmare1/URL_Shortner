@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Account & Accept Invitation to {{ $invitation->company->name }}</div>

                <div class="card-body">
                    <p class="text-muted mb-3">Create your account to accept this invitation.</p>
                    
                    <form method="POST" action="{{ url('/invitations/' . $invitation->token . '/accept') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input class="form-control" value="{{ $invitation->email }}" disabled />
                            <small class="text-muted">This account will be created with this email</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input name="name" class="form-control" value="" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input name="password" type="password" class="form-control" required />
                            <small class="text-muted">Minimum 8 characters</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input name="password_confirmation" type="password" class="form-control" required />
                        </div>

                        <button class="btn btn-primary" type="submit">Create Account & Accept</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
