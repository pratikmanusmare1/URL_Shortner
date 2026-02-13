@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            
                <div class="mb-3">
                    <a href="{{ route('invitations.index') }}" class="btn btn-secondary">← Back</a>
                    <h1 class="mt-3">Invite to Admins</h1>
                </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($users->isEmpty())
                <div class="alert alert-info">No admins available to invite.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Admin of Company</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $admin)
                                <tr>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->admin_companies }}</td>
                                    <td>
                                        <!-- Get user's own company where they are admin -->
                                        @php
                                            $myAdminCompanyId = auth()->user()->companies()
                                                ->wherePivot('role', 'admin')
                                                ->first()?->id;
                                        @endphp

                                        @if($myAdminCompanyId)
                                            <form action="{{ route('invitations.send-to-admin') }}" method="POST" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $admin->id }}">
                                                <input type="hidden" name="company_id" value="{{ $myAdminCompanyId }}">
                                                <button type="submit" class="btn btn-sm btn-primary">Invite</button>
                                            </form>
                                        @else
                                            <span class="text-muted">Not an admin</span>
                                        @endif
                                    </td>
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
