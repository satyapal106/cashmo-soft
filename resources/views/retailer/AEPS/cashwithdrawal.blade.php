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
                            <li class="breadcrumb-item"><a href="javascript:void(0);">AEPS</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Title -->
        
        <div class="row">
            <!-- DTH Recharge Form -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <form id="dthForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Mobile Number</label>
                                    <input type="text" class="form-control" name="electricity_board" placeholder="Mobile Number" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Aadhaar Number</label>
                                    <input type="text" class="form-control" name="aadhaar_number" placeholder="Aadhaar Number" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Amount</label>
                                    <input type="text" class="form-control" name="amount" placeholder="Amount" required>
                                </div>
                               <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Select Bank</label>
                                    <select class="form-control">
                                        <option value="">Select Bank</option>
                                        <option value="">Bank Of India</option>
                                        <option value="">SBI Bank</option>
                                        <option value="">Bank Of Baroda</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-success w-100" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8"></div>
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
    <script>
        $(document).ready(function() {
            $('#operator').on('change', function() {
                var operator_id = $(this).val();
                if (operator_id) {
                    $.ajax({
                        url: '{{ url('retailer/get-dth-plans') }}/' + operator_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#dthPlanTableBody').empty();
                            if (data.length > 0) {
                                $.each(data, function(key, plan) {
                                    let quality = plan.channel_quality ? JSON.parse(plan.channel_quality).join(', ') : 'N/A';
                                    $('#dthPlanTableBody').append(`
                                        <tr>
                                            <td>${plan.plan_name}</td>
                                            <td>${plan.validity ? plan.validity : 'N/A'}</td>
                                            <td>${quality}</td>
                                            <td>
                                                <button class="btn btn-sm border-primary selectAmount" data-amount="${plan.amount}">
                                                    ₹${plan.amount}
                                                </button>
                                            </td>
                                        </tr>
                                    `);
                                });
                                
                                $('.selectAmount').on('click', function() {
                                    var selectedAmount = $(this).data('amount');
                                    $('input[name="amount"]').val(selectedAmount);
                                });
                            } else {
                                $('#dthPlanTableBody').append(`
                                    <tr>
                                        <td colspan="5" class="text-center">No plans available for this operator.</td>
                                    </tr>
                                `);
                            }
                        }
                    });
                } else {
                    $('#dthPlanTableBody').empty().append(`
                        <tr>
                            <td colspan="5" class="text-center">Please select an operator.</td>
                        </tr>
                    `);
                }
            });
        });
    </script>
@stop