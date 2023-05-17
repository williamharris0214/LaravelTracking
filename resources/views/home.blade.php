@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row" style="margin-left:0.5rem; margin-top:1rem;">
            <div class="col-lg-12 col-md-6 mb-5" style="padding-right:2rem">
                <div class="card card-raised border-top border-4 border-primary h-100">
                    <div class="card-body p-5">
                        <div class="overline text-muted mb-4">Dashboard</div>
                        <h1>Welcome, {{$user}}</h1>
                        <p class="card-text" style="margin-bottom:200px;">Thank you for visiting!</p>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
