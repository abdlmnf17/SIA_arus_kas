@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('error'))
            <div class="alert alert-success" role="alert">
                {{ session('error') }}
            </div>
        @endif
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">


                    Berhasil login.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
