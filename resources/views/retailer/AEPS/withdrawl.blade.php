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
                                    <label for="">Latitude</label>
                                    <input type="text" class="form-control" name="latitude" placeholder="22.44543" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Longitude</label>
                                    <input type="text" class="form-control" name="longitude" placeholder="77.434" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Mobile Number</label>
                                    <input type="tel" class="form-control" name="mobilenumber" placeholder="9900000099" maxlength="10" pattern="[0-9]{10}" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Reference No</label>
                                    <input type="text" class="form-control" name="referenceno" placeholder="43542343434 (unique txn value)" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">IP Address</label>
                                    <input type="text" class="form-control" name="ipaddress" placeholder="122.44.443.00" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Aadhaar Number</label>
                                    <input type="text" class="form-control" name="adhaarnumber" placeholder="XXXXXXXX1234" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Access Mode Type</label>
                                    <input type="text" class="form-control" name="accessmodetype" placeholder="APP or SITE" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">National Bank Identification</label>
                                    <input type="number" class="form-control" name="nationalbankidentification" placeholder="Enter Bank Code">
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Request Remarks</label>
                                    <input type="text" class="form-control" name="requestremarks" placeholder="Optional remarks">
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Device Data (XML)</label>
                                    <textarea class="form-control" name="data" placeholder="Fingerprint XML Data" rows="4" required></textarea>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Pipe</label>
                                    <select class="form-control" name="pipe" required>
                                        <option value="">Select Pipe</option>
                                        <option value="bank1">bank1 (UAT only)</option>
                                        <option value="bank2">bank2</option>
                                        <option value="bank3">bank3</option>
                                        <option value="bank5">bank5</option>
                                    </select>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Timestamp</label>
                                    <input type="datetime-local" class="form-control" name="timestamp" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Transaction Type</label>
                                    <input type="text" class="form-control" name="transactiontype" placeholder="CW" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Sub Merchant ID</label>
                                    <input type="text" class="form-control" name="submerchantid" placeholder="1" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Amount</label>
                                    <input type="number" class="form-control" name="amount" placeholder="Enter Amount" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Is Iris</label>
                                    <select class="form-control" name="is_iris" required>
                                        <option value="">Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                        <option value="face_rd">face_rd (Only for bank2)</option>
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
   