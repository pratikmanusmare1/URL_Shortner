@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-8">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">

                <div class="card-header">Dashboard</div>

                <div class="card-body text-center fs-4 fw-bold">
                    welcome {{ auth()->user()->name }} <br>
                    Please select a Company
                </div>

            </div>

        </div>

    </div>

</div>

@endsection