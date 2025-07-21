@extends('retailer.layouts.master')

@section('styles')
    <!-- Sweet Alert CSS -->
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('content')
    <div class="container-fluid">
        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">{{ $title }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Retailer</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div> 
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Wallet + Transactions Row -->
        <div class="row">
            <!-- Wallet Form -->
            <div class="col-md-4 mb-3">
                @if($wallet)
                    <h4 class="fw-bold">
                        Wallet Balance:
                        <span class="text-success">₹{{ number_format($wallet->balance, 2) }}</span>
                    </h4>
                @else
                    <div class="alert alert-warning">
                        Wallet not found for this user.
                    </div >
                @endif

                <!-- Add Balance Form -->
                <form method="POST" action="{{ url('retailer/add-balance') }}">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user_id }}">

                    <!-- Amount Field -->
                    <div class="mb-3">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" placeholder="Enter Amount" required>
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label class="form-label">Description (Optional)</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Write a note (if any)"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-success w-100">Add Balance</button>
                </form>
            </div>

            <!-- Transaction History Table -->
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Transaction History</h4>
                    </div>
                    <div class="card-body">
                        @if($wallet && $wallet->transactions->count())
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Opening (₹)</th>
                                            <th>Amount (₹)</th>
                                            <th>Closing (₹)</th>
                                            <th>Description</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($wallet->transactions as $key=>$txn)
                                            <tr>
                                                <td>{{ $txn->created_at ? $txn->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') : '-' }}</td>
                                                <td>{{ $key + 1 }}</td>
                                                <td>₹{{ number_format($txn->amount, 2) }}</td>
                                                <td>₹{{ number_format($txn->before_balance, 2) }}</td>
                                                <td>₹{{ number_format($txn->after_balance, 2) }}</td>
                                                <td>{{ $txn->description ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $txn->type === 'credit' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($txn->type) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $txn->status === 'success' ? 'primary' : ($txn->status === 'pending' ? 'warning' : 'secondary') }}">
                                                        {{ ucfirst($txn->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">No transactions available.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Toast Container -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
        <div id="toastMessage" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <!-- Sweet Alerts JS -->
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
@stop
