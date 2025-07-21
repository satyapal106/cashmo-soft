@extends('admin.layouts.master')
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">{{ $title }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">All Retailer</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">

            <div class="col-lg-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ Session('success') }}</strong>
                    </div>
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ Session('error') }}</strong>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                    </div>

                    <!-- end card header -->
                    <div class="card-body">
                        <form method="POST" action="{{ url('admin/add-retailer/' . ($user->id ?? '')) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4 col-md-4 mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name', $user->name ?? '') }}" placeholder="Enter Name" required>
                                </div>
                                <div class="col-lg-4 col-md-4 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email"
                                        value="{{ old('email', $user->email ?? '') }}" placeholder="Enter email address"
                                        required>
                                </div>
                                <div class="col-lg-4 col-md-4 mb-3">
                                    <label class="form-label">Phone No.</label>
                                    <input type="text" class="form-control" name="phone_number"
                                        value="{{ old('phone_number', $user->phone_number ?? '') }}"
                                        placeholder="Enter Phone No.">
                                </div>
                                <div class="col-lg-4 col-md-4 mb-3">
                                    <label class="form-label">Aadhaar No. <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="aadhar_number"
                                        value="{{ old('aadhar_number', $user->aadhar_number ?? '') }}"
                                        placeholder="Enter Aadhaar No." required>
                                </div>
                                <div class="col-lg-4 col-md-4 mb-3">
                                    <label class="form-label">Pan Card No.</label>
                                    <input type="text" class="form-control" name="pan_number"
                                        value="{{ old('pan_number', $user->pan_number ?? '') }}"
                                        placeholder="Enter Pan Card No.">
                                </div>
                                <div class="col-lg-4 col-md-4 mb-3">
                                    <label class="form-label">Pincode <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="pincode"
                                        value="{{ old('pincode', $user->pincode ?? '') }}" placeholder="Enter Pincode"
                                        required>
                                </div>
                                <div class="col-lg-4 col-md-4 mb-3">
                                    <label class="form-label">Select State <span class="text-danger">*</span></label>
                                    <select name="state_id" id="state" class="form-control form-select">
                                        <option value="">Select State</option>
                                        @foreach ($state as $row)
                                            <option value="{{ $row->id }}"
                                                {{ old('state_id', $user->state_id ?? '') == $row->id ? 'selected' : '' }}>
                                                {{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4 mb-3">
                                    <label class="form-label">Select District</label>
                                    <select name="district_id" id="district" class="form-control form-select">
                                        <option value="">Select District</option>
                                        {{-- Add logic to populate districts dynamically --}}
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4 mb-3">
                                    <label class="form-label">Firm or Shop Name</label>
                                    <input type="text" class="form-control" name="shop_name"
                                        value="{{ old('shop_name', $user->shop_name ?? '') }}"
                                        placeholder="Enter Shop Name">
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">Agent Type</label>
                                    <select name="member_type" class="form-control form-select" id="">
                                        <option value="">Select Agent Type</option>
                                        @foreach ($agentType as $agent)
                                            <option value="{{$agent->id}}"
                                                {{ old('package_id', $user->member_type ?? '') == $agent->id ? 'selected' : '' }}>{{$agent->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">Package</label>
                                    <div class="input-group">
                                        <select name="package_id" class="form-select" id="packageSelect">
                                            <option value="">Select Package</option>
                                            @foreach ($packages as $package)
                                                <option value="{{ $package->id }}" 
                                                    {{ old('package_id', $user->package_id ?? '') == $package->id ? 'selected' : '' }}>
                                                    {{ $package->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <!-- Create Button -->
                                        <a href="{{ url('admin/add-package') }}" target="_blank" class="btn btn-danger"
                                            type="button">
                                            <i class="ri-add-circle-line"></i> Create
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">Tehsil</label>
                                    <input type="text" class="form-control" name="tehsil"
                                        value="{{ old('tehsil', $user->tehsil ?? '') }}" placeholder="Enter Tehsil">
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">Profile Image</label>
                                    <input type="file" name="profile_image" class="form-control">
                                    @if (isset($user->profile_image))
                                        <img src="{{ asset($user->profile_image) }}" alt="Profile Image" width="100"
                                            class="mt-2">
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Enter password">
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="Enter password again">
                                </div>
                                <div class="col-lg-12 col-md-12 mb-3">
                                    <label class="form-label">Full Address</label>
                                    <textarea name="address" class="form-control p-3" rows="3" placeholder="Enter Full Address">{{ old('address', $user->address ?? '') }}</textarea>
                                </div>

                                <div class="col-lg-12 col-md-12 mb-3">
                                    <label class="form-label">Select Services</label>
                                    <div class="row">
                                        @foreach ($services as $service)
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                                        class="form-check-input" id="service_{{ $service->id }}"
                                                        {{ isset($user) && $user->services && $user->services->contains($service->id) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="service_{{ $service->id }}">
                                                        {{ $service->service_name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                            <div class="mt-4">
                                <button class="btn btn-success w-100" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @stop
    @section('scripts')
        <script src="{{ asset('assets') }}/js/pages/passowrd-create.init.js"></script>
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
            });
        </script>
    @stop
