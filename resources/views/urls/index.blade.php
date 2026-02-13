@extends('layouts.app')

@php
    $isSuperAdmin = Auth::user()->role === \App\Models\User::ROLE_SUPERADMIN;
@endphp

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Generated Short URLs</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Original URL</th>
                                @if($isSuperAdmin)
                                    <th>Company</th>
                                @endif
                                <th>Short</th>
                                <th>Clicks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($urls as $u)
                                <tr>
                                    <td style="max-width:400px;overflow:hidden;text-overflow:ellipsis">{{ $u->original_url }}</td>
                                    @if($isSuperAdmin)
                                        <td>{{ $u->company->name ?? '-' }}</td>
                                    @endif
                                    <td><a href="{{ url('/s/'.$u->short_code) }}" target="_blank">{{ url('/s/'.$u->short_code) }}</a></td>
                                    <td>{{ $u->clicks }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('urls.destroy', $u->id) }}" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            @if(!$isSuperAdmin)
                <div class="card">
                    <div class="card-header">Create Short URL</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('urls.store') }}">
                            @csrf
                            <div class="form-group">
                                <label>Original URL</label>
                                <input name="original_url" type="url" class="form-control" required />
                            </div>
                            <button class="btn btn-primary mt-2">Generate</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
