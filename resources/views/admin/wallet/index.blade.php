@extends('admin.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-white">
                    <h5 class="mb-0">Admin Wallet</h5>
                </div>
                <div class="card-body">
                    <p>Current Balance: <strong>Rs. {{ number_format($wallet->balance, 2) }}</strong></p>

                    <form action="{{ url('admin/add-wallet-balance') }}" method="POST">
                        @csrf
                        <input type="hidden" name="admin_id" value="{{ $wallet->admin_id }}">

                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control" required>
                                <option value="credit">Add Fund</option>
                                <option value="debit">Deduct Fund</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="success">Success</option>
                                <option value="pending">Pending</option>
                                <option value="failed">Failed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="refunded">Refunded</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-success w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header text-white">
                    <h5 class="mb-0">Transaction History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Opening</th>
                                    <th>Amount</th>
                                    <th>Closing</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $txn)
                                <tr>
                                    <td>{{ $txn->created_at }}</td>
                                    <td>₹{{ number_format($txn->before_balance, 2) }}</td>
                                    <td>₹{{ number_format($txn->amount, 2) }}</td>
                                    <td>₹{{ number_format($txn->after_balance, 2) }}</td>
                                    <td>{{ ucfirst($txn->type) }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($txn->status == 'success') bg-success 
                                            @elseif($txn->status == 'pending') bg-warning text-dark 
                                            @elseif($txn->status == 'failed') bg-danger 
                                            @elseif($txn->status == 'cancelled') bg-secondary 
                                            @elseif($txn->status == 'refunded') bg-info 
                                            @endif">
                                            {{ ucfirst($txn->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $txn->description }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No transactions found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop