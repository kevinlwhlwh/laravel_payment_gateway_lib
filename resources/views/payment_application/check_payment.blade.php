@extends('payment_application.frame')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    @if ($message = Session::get('success'))
                        <div class="custom-alerts alert alert-success fade in">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                            {!! $message !!}
                        </div>
                        <?php Session::forget('success');?>
                    @endif
                    @if ($message = Session::get('payment_details'))
                            <ul class="list-group">
                                @foreach(Session::get('payment_details') as $key => $value)
                                    @if($key === 'customer_name')
                                        <li class="list-group-item list-group-item-info">Customer Name : {{$value}}</li>
                                    @elseif($key === 'customer_Phone')
                                        <li class="list-group-item list-group-item-info">Customer Phone : {{$value}}</li>
                                    @elseif($key === 'currency')
                                        <li class="list-group-item list-group-item-info">Currency : {{$value}}</li>
                                    @elseif($key === 'amount')
                                        <li class="list-group-item list-group-item-info">Amount : {{$value}}</li>
                                    @endif
                                @endforeach
                            </ul>
                        <?php Session::forget('payment_details');?>
                    @endif

                    @if ($message = Session::get('error'))
                        <div class="custom-alerts alert alert-danger fade in">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                            {!! $message !!}
                        </div>
                        <?php Session::forget('error');?>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="panel-heading">Payment Checking Gateway</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="GET" id="payment-form" role="form"
                              action="{!! URL::route('check') !!}">
                            {{csrf_field()}}
                            <div class="form-group">
                                <div class="col-md-10">
                                    <label for="customerName">Customer Name</label>
                                    <input type="text" class="form-control" id="customerName" name="customerName" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-10">
                                    <label for="cardNumber">Reference ID</label>
                                    <input type="text" class="form-control" id="refNumber" name="refNumber"
                                           placeholder="PAL-10000000000"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        Check Status
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection