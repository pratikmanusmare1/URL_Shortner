@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Send Invitation</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('invitations.send') }}">
                        @csrf

                        <div class="form-group">
                            <label>Company</label>
                            <select name="company_id" class="form-control" required>
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input name="email" type="email" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                @if(auth()->user()->role === 2)
                                    {{-- SuperAdmin can only invite as Admin --}}
                                    <option value="admin" selected>Admin</option>
                                @else
                                    {{-- Company Admin can invite as Member or Admin --}}
                                    <option value="member">Member</option>
                                    <option value="admin">Admin</option>
                                @endif
                            </select>
                        </div>

                        <button class="btn btn-primary" type="submit">Send Invitation</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
