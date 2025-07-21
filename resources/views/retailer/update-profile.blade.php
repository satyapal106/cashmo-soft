@extends('retailer.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="position-relative mx-n4 mt-n4">
            <div class="profile-wid-bg profile-setting-img">
                <img src="{{ asset('assets') }}/images/profile-bg.jpg" class="profile-wid-img" alt="">
                <div class="overlay-content">
                    <div class="text-end p-3">
                        <div class="p-0 ms-auto rounded-circle profile-photo-edit">
                            <input id="profile-foreground-img-file-input" type="file"
                                class="profile-foreground-img-file-input">
                            <label for="profile-foreground-img-file-input" class="profile-photo-edit btn btn-light">
                                <i class="ri-image-edit-line align-bottom me-1"></i> Change Cover
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-3">
                <div class="card mt-n5">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                @if (Auth::guard('retailer')->check() && Auth::guard('retailer')->user()->profile_image)
                                    <img src="{{ asset(Auth::guard('retailer')->user()->profile_image) }}"
                                        class="rounded-circle avatar-xl img-thumbnail user-profile-image"
                                        alt="user-profile-image">
                                @else
                                    <img class="rounded-circle header-profile-user"
                                        src="{{ asset('assets') }}/images/users/avatar-1.jpg" alt="Header Avatar">
                                @endif

                                <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                    <input id="profile-img-file-input" type="file" class="profile-img-file-input">
                                    <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                        <span class="avatar-title rounded-circle bg-light text-body">
                                            <i class="ri-camera-fill"></i>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <h5 class="fs-16 mb-1">{{ Auth::guard('retailer')->user()->name }}</h5>
                        </div>
                    </div>
                </div>
                <!--end card-->
            </div>
            <!--end col-->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="col-xxl-9">
                <div class="card mt-xxl-n5">
                    <div class="card-header">
                        <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                    <i class="fas fa-home"></i> Personal Details
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                    <i class="far fa-user"></i> Change Password
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4">
                        <div class="tab-content">
                            <div class="tab-pane active" id="personalDetails" role="tabpanel">
                                <form action="javascript:void(0);">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="firstnameInput" class="form-label">Name</label>
                                                <input type="text" class="form-control" id="firstnameInput"
                                                    placeholder="Enter your firstname"
                                                    value="{{ Auth::guard('retailer')->user()->name }}"
                                                    readonly>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="emailInput" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="emailInput"
                                                    placeholder="Enter your email"
                                                    value="{{ Auth::guard('retailer')->user()->email }}"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="phonenumberInput" class="form-label">Phone Number</label>
                                                <input type="text" class="form-control" id="phonenumberInput"
                                                    placeholder="Enter your phone number"
                                                    value="{{ Auth::guard('retailer')->user()->phone_number }}"
                                                    readonly>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="designationInput" class="form-label">Aadhar Number.</label>
                                                <input type="text" class="form-control" id="designationInput"
                                                    placeholder="Designation"
                                                    value="{{ Auth::guard('retailer')->user()->aadhar_number }}"
                                                    readonly>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="websiteInput1" class="form-label">Pan Card No.</label>
                                                <input type="text" class="form-control" id="websiteInput1"
                                                    placeholder="www.example.com"
                                                    value="{{ Auth::guard('retailer')->user()->pan_number }}" 
                                                    readonly/>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="cityInput" class="form-label">Pincode</label>
                                                <input type="text" class="form-control" id="cityInput"
                                                    placeholder="Pincode"
                                                    value="{{ Auth::guard('retailer')->user()->pincode }}" />
                                            </div>
                                        </div>
                                        @php
                                            $selectedStateId = Auth::guard('retailer')->user()->state_id ?? '';
                                            $selectedDistrictId = Auth::guard('retailer')->user()->district_id ?? '';
                                        @endphp
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <label for="userstate" class="form-label">Select State <span
                                                    class="text-danger">*</span></label>
                                            <select name="state_id" id="state" class="form-control" disabled>
                                                <option value="">Select State</option>
                                                @foreach ($state as $row)
                                                    <option value="{{ $row->id }}"
                                                        {{ $selectedStateId == $row->id ? 'selected' : '' }}>
                                                        {{ $row->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <label for="userstate" class="form-label">Select District
                                                <span class="text-danger">*</span></label>
                                            <select name="district_id" id="district" class="form-control" disabled>
                                                <option value="">Select District</option>
                                            </select>
                                            <input type="hidden" id="selected_district"
                                                value="{{ $selectedDistrictId }}">
                                        </div>
                                        <!--end col-->
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="cityInput" class="form-label">Tehsil</label>
                                                <input type="text" class="form-control" id="cityInput"
                                                    placeholder="Pincode"
                                                    value="{{ Auth::guard('retailer')->user()->tehsil }}" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="cityInput" class="form-label">Firm or Shop Name</label>
                                                <input type="text" class="form-control" id="cityInput"
                                                    placeholder="Pincode"
                                                    value="{{ Auth::guard('retailer')->user()->shop_name }}" />
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-3 pb-2">
                                                <label for="exampleFormControlTextarea" class="form-label">Full
                                                    Address</label>
                                                <textarea class="form-control" id="exampleFormControlTextarea" placeholder="Enter your description" rows="3">
                                                    {{ Auth::guard('retailer')->user()->address }}
                                                </textarea>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        {{-- <div class="col-lg-12">
                                            <div class="hstack gap-2 justify-content-end">
                                                <button type="submit" class="btn btn-primary">Updates</button>
                                                <button type="button" class="btn btn-soft-success">Cancel</button>
                                            </div>
                                        </div> --}}
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>
                            <!--end tab-pane-->
                            <div class="tab-pane" id="changePassword" role="tabpanel">
                                <form action="{{ url('retailer/change-password') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-2">
                                        <div class="col-lg-4">
                                            <div>
                                                <label for="oldpasswordInput" class="form-label">Old Password*</label>
                                                <input type="password" name="old_password" class="form-control"
                                                    id="oldpasswordInput" placeholder="Enter current password">
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-lg-4">
                                            <div>
                                                <label for="newpasswordInput" class="form-label">New Password*</label>
                                                <input type="password" name="new_password" class="form-control"
                                                    id="newpasswordInput" placeholder="Enter new password">
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-lg-4">
                                            <div>
                                                <label for="confirmpasswordInput" class="form-label">Confirm
                                                    Password*</label>
                                                <input type="password" name="new_password_confirmation"
                                                    class="form-control" id="confirmpasswordInput"
                                                    placeholder="Confirm password">
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <!--end col-->
                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-success">Change
                                                    Password</button>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>
                            <!--end tab-pane-->
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
@stop
@section('scripts')
    <script>
        $(document).ready(function() {
            let selectedDistrict = $('#selected_district').val();

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
                                let isSelected = (value.id == selectedDistrict) ?
                                    'selected' : '';
                                $('#district').append(
                                    '<option value="' + value.id + '" ' +
                                    isSelected + '>' + value.name + '</option>'
                                );
                            });
                        }
                    });
                } else {
                    $('#district').empty().append('<option value="">Select District</option>');
                }
            });

            // Trigger change on page load if state is already selected (edit case)
            if ($('#state').val()) {
                $('#state').trigger('change');
            }
        });
    </script>
@stop
