@extends('app')
@section('body')
    <div class="flex-center position-ref full-height">
        <div class="outer">
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="container">
                    <ul class="nav navbar-nav">
                        <li><a href="/payment_application/make_payment">Send Payment</a></li>
                        <li><a href="#">Check Status</a></li>
                </div>
            </nav>

            @yield('content')
        </div>
    </div>
@endsection