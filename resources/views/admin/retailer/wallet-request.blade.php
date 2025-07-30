@extends('admin.layouts.master')

@section('content')
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Retailer</th>
            <th>Amount</th>
            <th>Transaction ID</th>
            <th>Screenshot</th>
            <th>Status</th>
            <th>Remarks</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($requests as $index => $request)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $request->retailer->name ?? 'N/A' }}</td>
                <td>₹{{ $request->amount }}</td>
                <td>{{ $request->transaction_id }}</td>
                <td>
                    @if($request->screenshot)
                        <a href="{{ asset($request->screenshot) }}" target="_blank">View</a>
                    @else
                        No Image
                    @endif
                </td>
                <td><span class="badge bg-{{ $request->status == 'approved' ? 'success' : ($request->status == 'rejected' ? 'danger' : 'warning') }}">
                    {{ ucfirst($request->status) }}
                </span></td>
                <td>{{ $request->remarks }}</td>
                <td>{{ $request->created_at->format('d M Y, h:i A') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@stop