@extends('admin.layouts.master')

@section('styles')

@stop

@section('content')
    <div class="container-fluid">
        <div class="profile-foreground position-relative mx-n4 mt-n4">
            <div class="profile-wid-bg">
                <img src="{{ asset('assets') }}/images/profile-bg.jpg" alt="" class="profile-wid-img" />
            </div>
        </div>
        <div class="pt-4 mb-4 mb-lg-3 pb-lg-4">
            <div class="row g-4">
                <div class="col-auto">
                    <div class="avatar-lg">
                        <img src="{{ asset(Auth::guard('admin')->user()->profile_image) }}" alt="user-img"
                            class="img-thumbnail rounded-circle" />
                    </div>
                </div>
                <!--end col-->
                <div class="col">
                    <div class="p-2">
                        <h3 class="text-white mb-1">{{ Auth::guard('admin')->user()->name }}</h3>
                        <div class="hstack text-white-50 gap-1">
                            <div class="me-2"><i
                                    class="ri-map-pin-user-line me-1 text-white-75 fs-16 align-middle"></i>{{ Auth::guard('admin')->user()->address }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div>
                    <div class="d-flex">
                        <!-- Nav tabs -->
                        <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist"></ul>
                        <div class="flex-shrink-0">
                            <a href="{{ url('admin/update-profile') }}" class="btn btn-success"><i
                                    class="ri-edit-box-line align-bottom"></i> Edit Profile</a>
                        </div>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content pt-4 text-muted">
                        <div class="tab-pane active" id="overview-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-xxl-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">Personal Info</h5>
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                           
                                                            <th class="ps-0" scope="row">Name :</th>
                                                            <td class="text-muted">
                                                                {{ Auth::guard('admin')->user()->name }}</td>
                                                            <th class="ps-0" scope="row">Aadhaar No. :</th>
                                                            
                                                        </tr>
                                                        <tr>
                                                            
                                                            <th class="ps-0" scope="row">Mobile :</th>
                                                            <td class="text-muted">
                                                                {{ Auth::guard('admin')->user()->phone_number }}</td>
                                                            <th class="ps-0" scope="row">Pan Card No. :</th>
                                                            <td class="text-muted">
                                                                </td> 
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">E-mail :</th>
                                                            <td class="text-muted">
                                                                {{ Auth::guard('admin')->user()->email }}</td>
                                                            <th class="ps-0" scope="row">Pincode :</th>
                                                            <td class="text-muted">
                                                                {{ Auth::guard('admin')->user()->pincode }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">Location :</th>
                                                            <td class="text-muted">
                                                                {{ Auth::guard('admin')->user()->address }}</td>
                                                            <th class="ps-0" scope="row">Tehsil :</th>
                                                            <td class="text-muted">
                                                                {{ Auth::guard('admin')->user()->tehsil }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">State :</th>
                                                            <td class="text-muted">
                                                                {{ Auth::guard('admin')->user()->state?->name }}</td>
                                                            <th class="ps-0" scope="row">District :</th>
                                                            <td class="text-muted">
                                                                {{ Auth::guard('admin')->user()->district?->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">Joining Date :</th>
                                                            <td class="text-muted">
                                                                {{ Auth::guard('admin')->user()->created_at->format('d M Y') }}
                                                            </td>
                                                            <th class="ps-0" scope="row">Firm or Shop Name :</th>
                                                            <td class="text-muted">
                                                                {{ Auth::guard('admin')->user()->shop_name }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                    </div>
                    <!--end tab-content-->
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

    </div><!-- container-fluid -->
@stop

@section('scripts')

@stop
