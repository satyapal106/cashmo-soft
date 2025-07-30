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
                        <form action="{{ url('retailer/wallet/request') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group mb-3">
                                    <label for="amount">Amount (₹)</label>
                                    <input type="number" name="amount" class="form-control" required min="10">
                                    @error('amount')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="transaction_id">Transaction ID</label>
                                    <input type="text" name="transaction_id" class="form-control" required>
                                    @error('transaction_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="screenshot">Payment Screenshot (image)</label>
                                    <input type="file" name="screenshot" class="form-control" accept="image/*" required>
                                    @error('screenshot')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="remarks">Remarks (optional)</label>
                                    <textarea name="remarks" class="form-control" rows="2"></textarea>
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

