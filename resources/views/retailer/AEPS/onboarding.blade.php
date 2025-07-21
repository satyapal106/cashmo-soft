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
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <form id="dthForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Mobile</label>
                                    <input type="text" class="form-control" name="mobile" placeholder="Mobile" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Is_new</label>
                                    <input type="text" class="form-control" name="is_new" placeholder="Is New" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Email ID"
                                        required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Firm</label>
                                    <input type="text" class="form-control" name="firm" placeholder="Firm Name"
                                        required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="">Callback</label>
                                    <input type="text" class="form-control" name="callback" placeholder="Callback"
                                        required>
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
    <script>
        function showToast(message, type = 'success') {
            let toast = $('#toastMessage');
            toast.removeClass('bg-success bg-danger').addClass(`bg-${type}`);
            toast.find('.toast-body').text(message);
            let bsToast = new bootstrap.Toast(toast[0]); // Pass DOM element, not jQuery object
            bsToast.show();
        }

        $(document).ready(function() {
            $('#dthForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ url('retailer/onboard-merchant') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success && response.data?.redirecturl) {
                            showToast(response.data.message ||
                                "Onboarding link generated successfully", 'success');
                                
                            // Redirect after 2 seconds
                            setTimeout(function() {
                                window.open(response.data.redirecturl, '_blank');
                            }, 2000);
                        } else {
                            showToast(response.data?.message || "Something went wrong",
                                'danger');
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || "Server error occurred";
                        showToast(message, 'danger');
                    }
                });
            });
        });
    </script>

@stop
