@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
                <div class="col-md-8">
            <h1>Companies</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('companies.create') }}" class="btn btn-primary">Create Company</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($companies->isEmpty())
        <div class="alert alert-info">No companies found. <a href="{{ route('companies.create') }}">Create one</a></div>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Admins</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $company)
                        <tr>
                            <td>{{ $company->name }}</td>
                            <td>
                                @php
                                    $admins = $company->users->filter(function($u) {
                                        return isset($u->pivot) && $u->pivot->role === 'admin';
                                    })->pluck('name')->toArray();
                                @endphp
                                @if(count($admins) === 0)
                                    -
                                @else
                                    {{ implode(', ', array_slice($admins, 0, 2)) }}@if(count($admins) > 2)...@endif
                                @endif
                            </td>
                            <td>{{ $company->email }}</td>
                            <td>
                                <a href="{{ route('companies.edit', $company) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('companies.destroy', $company) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
