@extends('admin.layouts.master')
@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{$title}}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">All Retailer</a></li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">{{$title}}</h5>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex flex-wrap align-items-start gap-2">
                                <button class="btn btn-soft-danger" id="remove-actions" onClick="deleteMultiple()"><i
                                        class="ri-delete-bin-2-line"></i></button>
                                <a href="{{url('admin/add-retailer')}}" class="btn btn-success add-btn"><i
                                        class="ri-add-line align-bottom me-1"></i> Add Retailer</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border-bottom-dashed border-bottom">
                    <form>
                        <div class="row g-3">
                            <div class="col-xl-6">
                                <div class="search-box">
                                    <input type="text" class="form-control search"
                                        placeholder="Search for customer, email, phone, status or something...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xl-6">
                                <div class="row g-3">
                                    <div class="col-sm-4">
                                        <div class="">
                                            <input type="text" class="form-control" id="datepicker-range"
                                                data-provider="flatpickr" data-date-format="d M, Y"
                                                data-range-date="true" placeholder="Select date">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-sm-4">
                                        <div>
                                            <select class="form-control" data-plugin="choices" data-choices
                                                data-choices-search-false name="choices-single-default" id="idStatus">
                                                <option value="">Status</option>
                                                <option value="all" selected>All</option>
                                                <option value="Active">Active</option>
                                                <option value="Block">Block</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-sm-4">
                                        <div>
                                            <button type="button" class="btn btn-primary w-100" onclick="SearchData();">
                                                <i class="ri-equalizer-fill me-2 align-bottom"></i>Filters</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                            </div>
                        </div>
                        <!--end row-->
                    </form>
                </div>
                <div class="card-body">
                    @if(count($user) > 0)
                    <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th data-ordering="false">#</th>
                                <th data-ordering="false">Name</th>
                                <th data-ordering="false">Phone</th>
                                <th data-ordering="false">Aadhar</th>
                                <th data-ordering="false">Pan Card</th>
                                <th data-ordering="false">Shop Name</th>
                                <th data-ordering="false">Address</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user as $key=>$row)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$row->name}}</td>
                                <td>{{$row->phone_number}}</td>
                                <td>{{$row->aadhar_number}}</td>
                                <td>{{$row->pan_number}}</td>
                                <td>{{$row->shop_name}}</td>
                                <td>{{$row->address}}</td>
                                @if($row->status === "pending")
                                <td><span class="badge bg-danger text-white">Pending</span></td>
                                @elseif($row->status === "approve")
                                <td><span class="badge bg-info text-white">Approved</span></td>
                                @else($row->status === "reject")
                                <td><span class="badge bg-warning text-white">Rejected</span></td>
                                @endif
                                <td>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item status-update"
                                                    data-id="{{ $row->id }}" data-status="approve">
                                                    <i class="ri-check-fill align-bottom me-2 text-muted"></i> Approve
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item status-update"
                                                    data-id="{{ $row->id }}" data-status="pending">
                                                    <i class="ri-time-fill align-bottom me-2 text-muted"></i> Pending
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item status-update"
                                                    data-id="{{ $row->id }}" data-status="reject">
                                                    <i class="ri-close-fill align-bottom me-2 text-muted"></i> Reject
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ url('admin/view-user/' .$row->id) }}"
                                                    class="dropdown-item view-user-btn">
                                                    <i class="ri-eye-fill align-bottom me-2 text-muted"></i> View
                                                    Details
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ url('admin/kyc-verification/' .$row->id) }}"
                                                    class="dropdown-item kyc-verify-btn">
                                                    <i class="ri-checkbox-circle-line"></i> KYC Verification
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{url('admin/add-retailer/' .$row->id)}}"
                                                    class="dropdown-item edit-item-btn">
                                                    <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item remove-item-btn">
                                                    <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="noresult">
                        <div class="text-center">
                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                            <h5 class="mt-2">Sorry! No Result Found</h5>
                        </div>
                    </div>
                    @endif
                    <div class="d-flex justify-content-end">
                        <div class="pagination-wrap hstack gap-2">
                            <a class="page-item pagination-prev disabled" href="#">
                                Previous
                            </a>
                            <ul class="pagination listjs-pagination mb-0"></ul>
                            <a class="page-item pagination-next" href="#">
                                Next
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
</div>
<!-- container-fluid -->
@stop

@section('scripts')

<script>
    $(document).ready(function () {
        $(".status-update").click(function () {
            var retailerId = $(this).data("id");
            var status = $(this).data("status");
    
            $.ajax({
                url: "{{ url('admin/update-retailer-status') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: retailerId,
                    status: status
                },
                success: function (response) {
                    if (response.success) {
                        location.reload(); 
                    } else {
                        alert(response.message);
                    }
                },
                error: function () {
                    alert("Something went wrong. Please try again.");
                }
            });
        });
    });
</script>
@stop