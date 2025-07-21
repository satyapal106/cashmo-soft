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
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Recharge Plans</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Title -->
        
        <div class="row">
            <!-- DTH Recharge Form -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <form id="dthForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Merchant_id</label>
                                    <input type="text" class="form-control" name="merchant_id" placeholder="Merchant ID" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Partner_id</label>
                                    <input type="text" class="form-control" name="partner_id" placeholder="Partner ID" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Request_id</label>
                                    <input type="text" class="form-control" name="request_id" placeholder="Request ID" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="amount" placeholder="Amount" required>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-success w-100" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Browse Plans Table -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title mb-0 flex-grow-1">Browse Plan</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-nowrap align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Plan Name</th>
                                        <th scope="col">Validity</th>
                                        <th scope="col">Pack</th>
                                        {{-- <th scope="col">Description</th> --}}
                                        <th scope="col">Price</th>
                                    </tr>
                                </thead>
                                <tbody id="dthPlanTableBody">
                                    <!-- Data will be loaded dynamically -->
                                </tbody>
                            </table>
                        </div>
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
    