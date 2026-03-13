@extends('layout.app')

@section('title', 'Payment Checkout')

@section('body')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Payment Checkout</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.courses.browse') }}">Browse Courses</a></li>
                        <li class="breadcrumb-item active">Checkout</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                {{-- Course Summary --}}
                <div class="col-md-4">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-shopping-cart mr-2"></i>Order Summary</h3>
                        </div>
                        <div class="card-body">
                            <h5 class="font-weight-bold">{{ $course->title }}</h5>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-chalkboard-teacher mr-1"></i> {{ $course->instructor->name }}
                            </p>
                            <p class="text-muted small">{{ Str::limit($course->description, 120) }}</p>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Course Price</span>
                                <span class="h4 mb-0 text-success font-weight-bold">${{ number_format($course->price, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card card-outline card-info">
                        <div class="card-body text-center">
                            <i class="fas fa-shield-alt fa-2x text-info mb-2"></i>
                            <p class="small text-muted mb-0">Your payment is secure and encrypted. You will be enrolled immediately after payment.</p>
                        </div>
                    </div>
                </div>

                {{-- Payment Form --}}
                <div class="col-md-8">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-credit-card mr-2"></i>Payment Details</h3>
                        </div>
                        <form action="{{ route('student.payment.process', $course->id) }}" method="POST" id="paymentForm">
                            @csrf
                            <div class="card-body">
                                {{-- Payment Method Selector --}}
                                <div class="form-group">
                                    <label>Payment Method</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="method_card" name="payment_method" value="credit_card" checked>
                                                <label for="method_card" class="custom-control-label">
                                                    <i class="fas fa-credit-card text-primary mr-1"></i> Credit Card
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="method_paypal" name="payment_method" value="paypal">
                                                <label for="method_paypal" class="custom-control-label">
                                                    <i class="fab fa-paypal text-info mr-1"></i> PayPal
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="method_bank" name="payment_method" value="bank_transfer">
                                                <label for="method_bank" class="custom-control-label">
                                                    <i class="fas fa-university text-secondary mr-1"></i> Bank Transfer
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('payment_method')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <hr>

                                {{-- Credit Card Fields --}}
                                <div id="cardFields">
                                    <div class="form-group">
                                        <label for="cardholder_name">Cardholder Name</label>
                                        <input type="text" name="cardholder_name" id="cardholder_name" class="form-control @error('cardholder_name') is-invalid @enderror" placeholder="John Doe" value="{{ old('cardholder_name') }}">
                                        @error('cardholder_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="card_number">Card Number</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                            </div>
                                            <input type="text" name="card_number" id="card_number" class="form-control @error('card_number') is-invalid @enderror" placeholder="4242 4242 4242 4242" maxlength="19" value="{{ old('card_number') }}">
                                            @error('card_number')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="expiry">Expiry Date</label>
                                                <input type="text" name="expiry" id="expiry" class="form-control @error('expiry') is-invalid @enderror" placeholder="MM/YY" maxlength="5" value="{{ old('expiry') }}">
                                                @error('expiry')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cvv">CVV</label>
                                                <input type="text" name="cvv" id="cvv" class="form-control @error('cvv') is-invalid @enderror" placeholder="123" maxlength="4" value="{{ old('cvv') }}">
                                                @error('cvv')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- PayPal / Bank Transfer info --}}
                                <div id="altPaymentInfo" style="display: none;">
                                    <div class="callout callout-info">
                                        <h5><i class="fas fa-info-circle"></i> Note</h5>
                                        <p id="altPaymentText" class="mb-0">You will be redirected to complete your payment.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('student.courses.browse') }}" class="btn btn-default">
                                        <i class="fas fa-arrow-left mr-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-lock mr-1"></i> Pay ${{ number_format($course->price, 2) }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('input[name="payment_method"]').change(function() {
            var method = $(this).val();
            if (method === 'credit_card') {
                $('#cardFields').show();
                $('#altPaymentInfo').hide();
            } else {
                $('#cardFields').hide();
                $('#altPaymentInfo').show();
                if (method === 'paypal') {
                    $('#altPaymentText').text('Your PayPal account will be charged ${{ number_format($course->price, 2) }}. Click Pay to confirm.');
                } else {
                    $('#altPaymentText').text('A bank transfer of ${{ number_format($course->price, 2) }} will be initiated. Click Pay to confirm.');
                }
            }
        });

        // Card number formatting
        $('#card_number').on('input', function() {
            var value = $(this).val().replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            var formatted = value.match(/.{1,4}/g);
            $(this).val(formatted ? formatted.join(' ') : '');
        });

        // Expiry formatting
        $('#expiry').on('input', function() {
            var value = $(this).val().replace(/\//g, '').replace(/[^0-9]/gi, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2);
            }
            $(this).val(value);
        });
    });
</script>
@endpush
