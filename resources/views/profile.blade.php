@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header" style="background-color: #b11116; color: #fff;">
                    <h4 class="mb-0">{{ $pageTitle }}</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar" style="width: 80px; height: 80px; background-color: #ddd; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold; color: #555;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="ms-4">
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="mb-0 text-muted">{{ $user->email }}</p>
                        </div>
                    </div>
                    <hr>
                    <h6 class="text-muted">Account Details</h6>
                    <ul class="list-unstyled">
                        <li><strong>Email:</strong> {{ $user->email }}</li>
                    </ul>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('logout') }}" class="btn"
                       style="background-color: #b11116; color: #fff;"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
