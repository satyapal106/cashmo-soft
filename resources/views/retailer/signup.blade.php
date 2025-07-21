<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">


<!-- Mirrored from themesbrand.com/velzon/html/master/auth-signup-cover.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 15 Feb 2025 09:54:27 GMT -->

<head>

    <meta charset="utf-8" />
    <title>Sign Up | Cashmo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.ico">

    <!-- Layout config Js -->
    <script src="{{ asset('assets') }}/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets') }}/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets') }}/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets') }}/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('assets') }}/css/custom.min.css" rel="stylesheet" type="text/css" />
    <style>
        .auth-one-bg .bg-overlay {
            background: linear-gradient(to right, #36457494, #4051899e);
            opacity: .9;
        }

        .toast {
            border-radius: 8px;
            padding: 8px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            border-left: 5px solid #28a745;
            transition: all 0.5s ease-in-out;
        }

        .toast-header {
            font-weight: bold;
            padding-bottom: 5px;
            color: #444;
        }

        .btn-close {
            font-size: 12px;
            cursor: pointer;
            border: none;
            background: transparent;
        }

        .p-lg-5 {
            padding: 1rem !important;
        }
    </style>

</head>

<body>

    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper d-flex justify-content-center align-items-center min-vh-100">
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card overflow-hidden m-0 card-bg-fill galaxy-border-none">
                            <div class="row justify-content-center g-0">
                                <div class="col-lg-12">
                                    <div class="p-lg-5 p-1 auth-one-bg h-100">
                                        <div class="bg-overlay"></div>
                                        <div class="position-relative h-100 d-flex flex-column">
                                            <div class="mb-4">
                                                <a href="{{ url('/signup') }}" class="d-block">
                                                    <img src="{{ asset('assets') }}/images/logo-light.png"
                                                        alt="" height="100">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="p-lg-5 p-1">
                                        <div>
                                            <h5 class="text-primary">Register Account</h5>
                                            <p class="text-muted">Create Your <b>CASHMO</b> Account</p>
                                        </div>
                                        <div class="mt-4">
                                            <form id="registerForm" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 mb-3">
                                                        <label for="userenname" class="form-label">Name <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="name"
                                                            placeholder="Enter Name" required>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 mb-3">
                                                        <label for="useremail" class="form-label">Email <span
                                                                class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" name="email"
                                                            placeholder="Enter email address" required>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 mb-3">
                                                        <label for="userephone" class="form-label">Phone No. <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="phone_number"
                                                            placeholder="Enter Phone No." required>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 mb-3">
                                                        <label for="useraadhar" class="form-label">Aadhaar No. <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="aadhar_number"
                                                            placeholder="Enter Aadhaar No." required>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 mb-3">
                                                        <label for="useraadhar" class="form-label">Pan Card No. <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="pan_number"
                                                            placeholder="Enter Pan Card No." required>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 mb-3">
                                                        <label for="userpincode" class="form-label">Pincode <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="pincode"
                                                            placeholder="Enter Pincode" required>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 mb-3">
                                                        <label for="userstate" class="form-label">Select State <span
                                                                class="text-danger">*</span></label>
                                                        <select name="state_id" id="state" class="form-control">
                                                            <option value="">Select State</option>
                                                            @foreach ($state as $row)
                                                                <option value="{{ $row->id }}">{{ $row->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 mb-3">
                                                        <label for="userstate" class="form-label">Select District
                                                            <span class="text-danger">*</span></label>
                                                        <select name="district_id" id="district"
                                                            class="form-control">
                                                            <option value="">Select District</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 mb-3">
                                                        <label for="userTehsil" class="form-label">Tehsil
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="tehsil"
                                                            placeholder="Enter Tehsil" required>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 mb-3">
                                                        <label for="userpincode" class="form-label">Firm or Shop Name
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="shop_name"
                                                            placeholder="Enter Shop Name" required>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 mb-3">
                                                        <label for="userpincode" class="form-label">Profile Image
                                                            <span class="text-danger">*</span></label>
                                                        <input type="file" class="form-control"
                                                            name="profile_image" required>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 mb-3"">
                                                        <label class="form-label" for="password-input">Password <span
                                                                class="text-danger">*</span></label>
                                                        <div class="position-relative auth-pass-inputgroup">
                                                            <input type="password" name="password"
                                                                class="form-control pe-5 password-input"
                                                                onpaste="return false" placeholder="Enter password"
                                                                id="password-input" aria-describedby="passwordInput"
                                                                required>
                                                            <button
                                                                class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                                type="button" id="password-addon"><i
                                                                    class="ri-eye-fill align-middle"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 mb-3"">
                                                        <label class="form-label" for="password-input">Confirm
                                                            Password <span class="text-danger">*</span></label>
                                                        <div class="position-relative auth-pass-inputgroup">
                                                            <input type="password" name="password_confirmation"
                                                                class="form-control pe-5 password-input"
                                                                onpaste="return false" placeholder="Enter password"
                                                                id="password-input" aria-describedby="passwordInput"
                                                                required>
                                                            <button
                                                                class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                                type="button" id="password-addon"><i
                                                                    class="ri-eye-fill align-middle"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 mb-3"">
                                                        <label class="form-label" for="password-input">Full
                                                            Address</label>
                                                        <div class="position-relative auth-pass-inputgroup">
                                                            <textarea name="address" cols="166" rows="3" class="p-3" placeholder="Enter Full Address"
                                                                width="100%"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <button class="btn btn-success w-100" type="submit">Sign
                                                        Up</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="mt-2 text-center">
                                            <p class="mb-0">Already have an account ? <a href="{{ url('/') }}"
                                                    class="fw-semibold text-primary text-decoration-underline">
                                                    Signin</a> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->
    </div>
    <!-- end auth-page-wrapper -->

    <!-- Toast Notification -->
    <div id="toast" class="toast" style="position: fixed; top: 20px; right: 20px; z-index: 1050; display:none;">
        <div class="toast-header">
            <strong class="me-auto" id="toast-title"></strong>
            <button type="button" class="btn-close" onclick="$('#toast').fadeOut();"></button>
        </div>
        <div class="toast-body" id="toast-body" style="padding: 10px;"></div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets') }}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets') }}/libs/simplebar/simplebar.min.js"></script>
    <script src="{{ asset('assets') }}/libs/node-waves/waves.min.js"></script>
    <script src="{{ asset('assets') }}/libs/feather-icons/feather.min.js"></script>
    <script src="{{ asset('assets') }}/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="{{ asset('assets') }}/js/plugins.js"></script>
    <!-- validation init -->
    <script src="{{ asset('assets') }}/js/pages/form-validation.init.js"></script>
    <!-- password create init -->
    <script src="{{ asset('assets') }}/js/pages/passowrd-create.init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            $('#state').change(function() {
                var stateID = $(this).val();
                if (stateID) {
                    $.ajax({
                        url: "{{ url('/get-districts') }}/" + stateID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#district').empty().append(
                                '<option value="">Select District</option>');
                            $.each(data, function(key, value) {
                                $('#district').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#district').empty().append('<option value="">Select District</option>');
                }
            });



            //Signup Form
            // $("#registerForm").on("submit", function(e) {
            //     e.preventDefault();

            //     $.ajax({
            //         url: "{{ url('/retailer-signup') }}",
            //         type: "POST",
            //         data: $(this).serialize(),
            //         dataType: "json",
            //         beforeSend: function() {
            //             Swal.fire({
            //                 title: "Processing...",
            //                 text: "Please wait while we sign you up.",
            //                 icon: "info",
            //                 showConfirmButton: false,
            //                 allowOutsideClick: false,
            //             });
            //         },
            //         success: function(response) {
            //             if (response.status) {
            //                 Swal.fire({
            //                     title: "Success!",
            //                     text: response.message,
            //                     icon: "success",
            //                     timer: 2000,
            //                     showConfirmButton: false
            //                 }).then(() => {
            //                     window.location.href = "{{ url('/login') }}";
            //                 });
            //                 $("#registerForm")[0].reset();
            //             } else {
            //                 Swal.fire("Error", response.message, "error");
            //             }
            //         },
            //         error: function(xhr) {
            //             let errors = xhr.responseJSON.errors;
            //             let errorMsg = "";
            //             $.each(errors, function(key, value) {
            //                 errorMsg += value[0] + "<br>";
            //             });

            //             Swal.fire({
            //                 title: "Validation Error",
            //                 html: errorMsg,
            //                 icon: "error"
            //             });
            //         }
            //     });
            // });

            $("#registerForm").on("submit", function(e) {
                e.preventDefault();

                // Create FormData object for file upload
                let formData = new FormData(this);

                $.ajax({
                    url: "{{ url('/retailer-signup') }}",
                    type: "POST",
                    data: formData,
                    contentType: false, // Important for file upload
                    processData: false, // Important for FormData
                    dataType: "json",
                    beforeSend: function() {
                        Swal.fire({
                            title: "Processing...",
                            text: "Please wait while we sign you up.",
                            icon: "info",
                            showConfirmButton: false,
                            allowOutsideClick: false,
                        });
                    },
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                title: "Success!",
                                text: response.message,
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "{{ url('/') }}";
                            });
                            $("#registerForm")[0].reset();
                        } else {
                            Swal.fire("Error", response.message, "error");
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = "";
                        $.each(errors, function(key, value) {
                            errorMsg += value[0] + "<br>";
                        });

                        Swal.fire({
                            title: "Validation Error",
                            html: errorMsg,
                            icon: "error"
                        });
                    }
                });
            });

        });
    </script>

</body>

</html>
