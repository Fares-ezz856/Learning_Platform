@extends('layout.app')

@section('title', 'Payment History')

@section('body')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Payments</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Payments</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fas fa-check"></i> {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-receipt mr-2"></i>Transaction History</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Transaction ID</th>
                                        <th>Course</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at->format('M d, Y h:i A') }}</td>
                                        <td><code>{{ $payment->transaction_id }}</code></td>
                                        <td>{{ $payment->course->title ?? 'Deleted Course' }}</td>
                                        <td class="font-weight-bold text-success">${{ number_format($payment->amount, 2) }}</td>
                                        <td>
                                            @if($payment->payment_method == 'credit_card')
                                                <i class="fas fa-credit-card text-primary mr-1"></i> Credit Card
                                            @elseif($payment->payment_method == 'paypal')
                                                <i class="fab fa-paypal text-info mr-1"></i> PayPal
                                            @else
                                                <i class="fas fa-university text-secondary mr-1"></i> Bank Transfer
                                            @endif
                                        </td>
                                        <td>
                                            @if($payment->status == 'completed')
                                                <span class="badge badge-success">Completed</span>
                                            @elseif($payment->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($payment->status == 'failed')
                                                <span class="badge badge-danger">Failed</span>
                                            @else
                                                <span class="badge badge-info">Refunded</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if($payments->isEmpty())
                                <div class="text-center py-4">
                                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">You have no payment records yet. Browse courses and enroll to get started!</p>
                                    <a href="{{ route('student.courses.browse') }}" class="btn btn-primary">
                                        <i class="fas fa-search mr-1"></i> Browse Courses
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
