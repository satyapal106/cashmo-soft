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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <form id="RegForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4 col-md-4 mb-3">
                                    <label for="">Adhaar Number</label>
                                    <input type="number" class="form-control" name="adhaarnumber"
                                        placeholder="XXXXXXXX1234" required>
                                </div>
                                <div class="col-lg-4 col-md-4 mb-3">
                                    <label for="">Mobile Number</label>
                                    <input type="number" class="form-control" name="mobilenumber" placeholder="9900000099"
                                        required>
                                </div>
                                <div class="col-lg-4 col-md-4 mb-3">
                                    <label for="">Latitude</label>
                                    <input type="text" class="form-control" id="latitude"  name="latitude" placeholder="22.44543"
                                        required>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label for="">Longitude</label>
                                    <input type="text" class="form-control" name="longitude" id="longitude" placeholder="77.434"
                                        required>
                                </div>
                                {{-- <div class="col-lg-4 col-md-4 mb-3">
                                    <label for="">Reference No</label>
                                    <input type="text" class="form-control" name="referenceno"
                                        placeholder="Unique txn value" required>
                                </div> --}}
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label for="">Sub Merchant ID</label>
                                    <input type="text" class="form-control" name="submerchantid"
                                        placeholder="Merchant code" required>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label for="">Is Iris</label>
                                    <select class="form-control" name="is_iris" required>
                                        <option value="">Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                        <option value="face_rd">face_rd (Only for bank2)</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label for="">Timestamp</label>
                                    <input type="datetime-local" class="form-control" name="timestamp" required>
                                </div>
                                {{-- <div class="col-lg-6 col-md-6 mb-3">
                                    <label for="">IP Address</label>
                                    <input type="text" class="form-control" name="ipaddress" placeholder="9.9.9.9"
                                        required>
                                </div> --}}
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Device Data (XML)</label>
                                    <textarea class="form-control" name="data" placeholder="Device Data XML" rows="4" required></textarea>
                                </div>
                            </div>

                            <div class="mt-2">
                                <button class="btn btn-success w-100" type="submit">Submit</button>
                            </div>
                        </form>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        $('#latitude').val(position.coords.latitude);
                        $('#longitude').val(position.coords.longitude);
                    },
                    function(error) {
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                alert("❌ Please allow location access.");
                                break;
                            case error.POSITION_UNAVAILABLE:
                                alert("❌ Location unavailable.");
                                break;
                            case error.TIMEOUT:
                                alert("❌ Location request timed out.");
                                break;
                            default:
                                alert("❌ An unknown error occurred.");
                                break;
                        }
                    }
                );
            } else {
                alert("❌ Your browser doesn't support Geolocation.");
            }
        });
    </script>
@stop
