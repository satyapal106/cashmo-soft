@extends('retailer.layouts.master')

@section('styles')
    <!-- Sweet Alert css-->
    <link href="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <style>
        
    </style>
@stop
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">{{ $title }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Recharge Plans</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <form id="RechargeForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <input type="text" class="form-control" name="mobile_number"
                                        placeholder="Mobile Number" required>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <select class="form-control" name="provider_id" id="operator">
                                        <option value="">Select Operator</option>
                                        @foreach ($operator as $oper)
                                            <option value="{{ $oper->id }}">{{ $oper->provider_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <select class="form-control" name="state_id">
                                        <option value="">Select State</option>
                                        @foreach ($state as $circle)
                                            <option value="{{ $circle->id }}">{{ $circle->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <input type="text" class="form-control" name="amount" placeholder="Amount" required>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-success w-100" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!--end col-->
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Browse Plan</h4>
                    </div>
                    <div class="card-body">
                        <div class="overflow-auto flex-nowrap d-flex" style="white-space: nowrap;" id="planCategoryWrapper">
                            <ul class="nav nav-tabs" id="planCategoryTabs" role="tablist" style="flex-wrap: nowrap;">
                                <!-- Tabs will be injected here -->
                            </ul>
                        </div>

                        <!-- Tab Content -->
                        <div class="tab-content pt-3" id="planCategoryTabsContent">
                            <!-- Plan tables will be dynamically injected -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Toast Container -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
        <div id="toastMessage" class="toast align-items-center text-white bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <!-- Sweet Alerts js -->
    <script src="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.js"></script>
    <!-- Sweet alert init js-->
    <script src="{{ asset('assets') }}/js/pages/sweetalerts.init.js"></script>
    <script>
        $(document).ready(function () {
            $('#operator').on('change', function () {
                var operator_id = $(this).val();

                if (operator_id) {
                    $.ajax({
                        url: '{{ url('retailer/get-recharge-plans') }}/' + operator_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            $('#planCategoryTabs').empty();
                            $('#planCategoryTabsContent').empty();

                            let tabIndex = 0;

                            $.each(data, function (category, plans) {
                                // Tab Button
                                $('#planCategoryTabs').append(`
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link ${tabIndex === 0 ? 'active' : ''}" id="tab-${tabIndex}" data-bs-toggle="tab" data-bs-target="#tab-pane-${tabIndex}" type="button" role="tab">${category}</button>
                                    </li>
                                `);

                                // Tab Pane Content
                                let rows = '';
                                $.each(plans, function (key, plan) {
                                    rows += `
                                        <tr>
                                            <td>${plan.data_renewal === "other" ? (plan.other_data_renewal ?? "NA") : `${plan.data ?? 'N/A'} ${plan.data_renewal}`}</td>
                                            <td>${plan.calling_options ?? 'N/A'}</td>
                                            <td>${plan.sms_count ?? 'N/A'}</td>
                                            <td>${plan.time_duration === "other" ? (plan.other_duration ?? "NA") : `${plan.validity} ${plan.time_duration}`}</td>
                                            <td>
                                                <button class="btn btn-sm border-primary selectAmount" data-amount="${plan.amount}">
                                                    ₹${plan.amount}
                                                </button>
                                            </td>
                                        </tr>
                                    `;
                                });

                                $('#planCategoryTabsContent').append(`
                                    <div class="tab-pane fade ${tabIndex === 0 ? 'show active' : ''}" id="tab-pane-${tabIndex}" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-nowrap align-middle mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Data</th>
                                                        <th>Calling</th>
                                                        <th>SMS</th>
                                                        <th>Validity</th>
                                                        <th>Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>${rows}</tbody>
                                            </table>
                                        </div>
                                    </div>
                                `);

                                tabIndex++;
                            });

                            // Event binding for price selection
                            $(document).on('click', '.selectAmount', function () {
                                var selectedAmount = $(this).data('amount');
                                $('input[name="amount"]').val(selectedAmount);
                            });
                        }
                    });
                } else {
                    $('#planCategoryTabs').empty();
                    $('#planCategoryTabsContent').empty();
                }
            });

            //recharge

            $('#RechargeForm').on('submit', function (e) {
            e.preventDefault();

            let form = $(this);
            let formData = form.serialize();

            $.ajax({
                url: '{{ url("retailer/mobile-recharge-payment") }}',
                method: 'POST',
                data: formData,
                success: function (response) {
                    Swal.fire('Success!', 'Recharge successful. TXN: ' + response.transaction_id, 'success');

                    $('.toast-body').text('Recharge successful: ₹' + $('input[name="amount"]').val());
                    new bootstrap.Toast($('#toastMessage')).show();

                    form.trigger("reset");
                },
                error: function (xhr) {
                    const error = xhr.responseJSON?.message || 'Something went wrong!';
                    Swal.fire('Error!', error, 'error');
                }
            });
        });
        });
    </script>
@stop
