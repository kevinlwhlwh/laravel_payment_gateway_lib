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
                    <div class="panel-heading">Payment Gateway</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" id="payment-form" role="form"
                              action="{!! URL::route('paypal') !!}">
                            {{csrf_field()}}
                            <div class="form-group">
                                <div class="col-md-10">
                                    <label for="customerName">Customer Name</label>
                                    <input type="text" class="form-control" id="customerName" name="customerName" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-10">
                                    <label for="customerPhone">Customer Phone No.</label>
                                    <input type="text" class="form-control" id="customerPhone" name="customerPhone" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-10">
                                    <label for="currency">Payment Currency</label>
                                    <select id='currency' name='currency' class="form-control">
                                        <option value="HKD">HKD</option>
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                        <option value="AUD">AUD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-10">
                                    <label for="amount">Payment Price</label>
                                    <input type="number" class="form-control" id="amount" name="amount" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-10">
                                    <label for="cardType">Card Type</label>
                                    <select id='cardType' name='cardType' class="form-control">
                                        <option value="visa">Visa</option>
                                        <option value="mastercard">Master</option>
                                        <option value="amex">AMEX</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-10">
                                    <label for="cardNumber">Card Number</label>
                                    <input type="number" class="form-control" id="cardNumber" name="cardNumber"
                                           placeholder="e.g 4123456701236789"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="expireMonth">Card Expire Month</label>
                                    <select id='expireMonth' name='expireMonth' class="form-control">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="expireYear">Card Expire Year</label>
                                    <select id='expireYear' name='expireYear' class="form-control">
                                        @for($i = date('Y'); $i <= date('Y')+100; $i++)
                                            <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="cardCvv2">CVV</label>
                                    <input type="password" class="form-control" id="cardCvv2" name="cardCvv2" placeholder="CVV">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-10">
                                    <label for="firstName">Card Holder First Name</label>
                                    <input type="text" class="form-control" id="firstName" name="firstName"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-10">
                                    <label for="lastName">Card Holder Last Name</label>
                                    <input type="text" class="form-control" id="lastName" name="lastName"/>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        Pay with Paypal
                                    </button>
                                    <button type="submit" class="btn btn-primary disabled">
                                        Pay with Braintree
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