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
               <li class="breadcrumb-item"><a href="javascript: void(0);">Recharge Plans</a></li>
               <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
<div class="row">
<div class="col-lg-12">
   <div class="card">
      <div class="card-header align-items-center d-flex">
         <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
      </div>
      
      <!-- end card header -->
      <div class="card-body">
        <form id="rechargePlanForm">
            @csrf
            <div class="row">
                <input type="hidden" class="form-control" value="1" name="service_id" required>
                <input type="hidden" id="planId" value="{{ $rechargePlan->id ?? '' }}">
                <div class="col-lg-12 col-md-12 mb-3">
                    <label for="operator" class="form-label">Select Provider <span class="text-danger">*</span></label>
                    <select class="form-control" name="provider_id" id="operatorSelect">
                        <option value="">Select Provider</option>
                        @foreach($filteredProviders as $provider)
                            <option value="{{ $provider['id'] }}" {{ isset($rechargePlan) && $rechargePlan->provider_id == $provider->id ? 'selected' : '' }}>{{ $provider['provider_name'] }}</option>
                        @endforeach
                    </select>
                </div>
        
                <div class="col-lg-12 col-md-12 mb-3">
                    <label for="categories" class="form-label">Select Plan Categories <span class="text-danger">*</span></label>
                    <div class="row border p-2" id="category-container">
                        <!-- Categories will be loaded here via AJAX -->
                    </div>
                </div>
        
                <div class="col-lg-12 col-md-12 mb-3">
                    <label for="states" class="form-label">Select States (Circle) <span class="text-danger">*</span></label>
                    <div class="row border p-2" id="state-container">
                        <div class="col-lg-12 col-md-12 mb-3">
                            <input class="form-check-input" type="checkbox" id="state">
                            <label class="form-check-label" for="state"> All State </label>
                        </div>
                        @foreach($state as $row)
                            <div class="col-lg-4 col-md-4 mb-3">
                                <input class="form-check-input" type="checkbox" id="state_{{ $row->id }}" name="states[]" value="{{ $row->name }}"
                                    {{ isset($rechargePlan) && is_array($rechargePlan->states) && in_array($row->name, $rechargePlan->states) ? 'checked' : '' }}>
                                <label class="form-check-label" for="state_{{ $row->id }}"> {{ $row->name }} </label>
                            </div>
                        @endforeach
                    </div>
                </div>
        
                <div class="col-lg-6 col-md-6 mb-3">
                    <label class="form-label">Plan Amount <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="amount" value="{{ $rechargePlan->amount ?? '' }}" placeholder="Enter Plan Amount/Price" required>
                </div>
        
                <div class="col-lg-6 col-md-6 mb-3">
                    <label class="form-label">Plan Name </label>
                    <input type="text" class="form-control" name="plan_name" value="{{ $rechargePlan->plan_name ?? '' }}" placeholder="Enter Plan Name">
                </div>
        
                <div class="col-lg-6 col-md-6 mb-3">
                    <label class="form-label">Plan Validity</label>
                    <input type="text" class="form-control" name="validity" value="{{ $rechargePlan->validity ?? '' }}" placeholder="Plan Validity">
                </div>
        
                <div class="col-lg-6 col-md-6 mb-3">
                    <label class="form-label">Time Duration</label>
                    <select name="time_duration" class="form-control" id="timeDuration">
                        <option value="days" {{ isset($rechargePlan) && $rechargePlan->time_duration == 'days' ? 'selected' : '' }}>Days</option>
                        <option value="months" {{ isset($rechargePlan) && $rechargePlan->time_duration == 'months' ? 'selected' : '' }}>Months</option>
                        <option value="other" {{ isset($rechargePlan) && $rechargePlan->time_duration == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 mb-3" id="otherDurationBox" style="display: none">
                    {{-- <label class="form-label">Calling Options</label> --}}
                    <input type="text" name="other_duration" class="form-control" placeholder="Other Duration" value="{{ $rechargePlan->other_duration ?? '' }}"/>
                </div>
                <div class="col-lg-6 col-md-6 mb-3">
                    <label class="form-label">Data (GB/MB)</label>
                    <input type="text" name="data" class="form-control" value="{{ $rechargePlan->data ?? '' }}" placeholder="Data (GB/MB)">
                </div>
                <div class="col-lg-6 col-md-6 mb-3">
                    <label class="form-label">Data Renewal</label>
                    <select name="data_renewal" class="form-control" id="dataRenewal" required>
                        <option value="per day" {{ isset($rechargePlan) && $rechargePlan->data_renewal == 'per day' ? 'selected' : '' }}>Per Day</option>
                        <option value="per plan" {{ isset($rechargePlan) && $rechargePlan->data_renewal == 'per plan' ? 'selected' : '' }}>Per Plan</option>
                        <option value="other" {{ isset($rechargePlan) && $rechargePlan->data_renewal == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 mb-3" id="otherDataRenewalBox" style="display: none">
                    {{-- <label class="form-label">Calling Options</label> --}}
                    <input type="text" name="other_data_renewal" class="form-control" placeholder="Other Data Renewal" value="{{ $rechargePlan->other_data_renewal ?? '' }}"/>
                </div>
                <div class="col-lg-4 col-md-4 mb-3">
                    <label class="form-label">Calling Options</label>
                    <input type="text" name="calling_options" class="form-control" placeholder="Calling Options" value="{{ $rechargePlan->calling_options ?? '' }}"/>
                </div>
        
                <div class="col-lg-4 col-md-4 mb-3">
                    <label class="form-label">5G Unlimited </label>
                    <select name="unlimited_5g" class="form-control">
                        <option value="yes" {{ isset($rechargePlan) && $rechargePlan->unlimited_5g == 'yes' ? 'selected' : '' }}>Yes</option>
                        <option value="no" {{ isset($rechargePlan) && $rechargePlan->unlimited_5g == 'no' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
        
                <div class="col-lg-4 col-md-4 mb-3">
                    <label class="form-label">Enter Number of SMS</label>
                    <input type="text" name="sms_count" class="form-control" value="{{ $rechargePlan->sms_count ?? '' }}" placeholder="Enter Number of SMS">
                </div>
        
                {{-- <div class="col-lg-6 col-md-6 mb-3">
                    <label class="form-label">SMS Renewal </label>
                    <select name="sms_renewal" class="form-control">
                        <option value="per day" {{ isset($rechargePlan) && $rechargePlan->sms_renewal == 'per day' ? 'selected' : '' }}>Per Day</option>
                        <option value="per plan" {{ isset($rechargePlan) && $rechargePlan->sms_renewal == 'per plan' ? 'selected' : '' }}>Per Plan</option>
                    </select>
                </div> --}}
        
                <div class="col-lg-12 col-md-12 mb-3">
                    <label class="form-label">Additional Benefits:</label>
                    <textarea name="additional_benefits" class="form-control" rows="3">{{ $rechargePlan->additional_benefits ?? '' }}</textarea>
                </div>
            </div>
            
            <div class="mt-4">
                <button class="btn btn-success w-100" id="btnSubmit" type="submit">Submit</button>
            </div>
        </form>
        
            <!-- end col -->
        </div>
      </div>
      <!--end col-->
   </div>
   <!--end row-->
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
<script>
     $(document).ready(function() {
        
        function toggleInput(selectId, boxId) {
            if ($(selectId).val() === "other") {
                $(boxId).show();
            } else {
                $(boxId).hide();
            }
        }
        // Page Load par check kare
        toggleInput("#dataRenewal", "#otherDataRenewalBox");
        toggleInput("#timeDuration", "#otherDurationBox");

        // Select change hone par check kare
        $("#dataRenewal, #timeDuration").on("change", function() {
            toggleInput("#dataRenewal", "#otherDataRenewalBox");
            toggleInput("#timeDuration", "#otherDurationBox");
        });

        var selectedCategories = @json($rechargePlan ? $rechargePlan->plan_category : []);
        var selectedStates = @json($rechargePlan ? $rechargePlan->states : []);

        // Load Plan Categories via AJAX
        $('#operatorSelect').on('change', function () {
            var operatorId = $(this).val();
            $('#category-container').html('<p>Loading...</p>');

            if (operatorId) {
                $.ajax({
                    url: "{{ url('admin/get-plan-categories') }}/" + operatorId,
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            var categoriesHtml = '';
                            response.categories.forEach(function (category) {
                                var checked = selectedCategories.includes(category.name) ? 'checked' : '';
                                categoriesHtml += `
                                    <div class="col-lg-6 col-md-6 mb-3">
                                        <input class="form-check-input" type="checkbox" name="plan_category[]" 
                                            value="${category.name}" id="category_${category.id}" ${checked}>
                                        <label class="form-check-label" for="category_${category.id}">${category.name}</label>
                                    </div>`;
                            });
                            $('#category-container').html(categoriesHtml);
                        } else {
                            $('#category-container').html('<p>No categories found.</p>');
                        }
                    },
                    error: function () {
                        $('#category-container').html('<p>Error fetching categories.</p>');
                    }
                });
            } else {
                $('#category-container').html('<p>Please select an operator.</p>');
            }
        });

        // Load saved categories when editing
        $('#operatorSelect').trigger('change');

        // Auto check states (circles)
        selectedStates.forEach(function (state) {
            $('#state-container input[value="' + state + '"]').prop('checked', true);
        });

            // Handle "All State" checkbox functionality
        $('#state').on('change', function() {
            var isChecked = $(this).is(':checked');
            $('#state-container input[type="checkbox"]').prop('checked', isChecked);
        });

        // If any state is unchecked, uncheck "All State"
        $('#state-container').on('change', 'input[type="checkbox"]', function() {
            if (!$(this).is(':checked')) {
                $('#state').prop('checked', false);
            } else {
                // If all checkboxes are checked, check "All State"
                if ($('#state-container input[type="checkbox"]:not(:checked)').length === 0) {
                    $('#state').prop('checked', true);
                }
            }
        });


        $('#rechargePlanForm').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            let submitButton = $('#btnSubmit');
            let planId = $('#planId').val();
            submitButton.prop('disabled', true).text('Submitting...');

            let url = planId ? "{{ url('admin/insert-rechargeplan') }}/" + planId : "{{ url('admin/insert-rechargeplan') }}";
            let method = planId ? "POST" : "POST"; 
          
            if (planId) formData.append('_method', 'POST');

            $.ajax({
                url: url,
                type: "POST", 
                data: formData,
                contentType: false,
                processData: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, 
                success: function (response) {
                    showToast(response.message, 'success'); 
                    $('#rechargePlanForm')[0].reset(); 
                    $('#planId').val(''); 
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON?.errors;
                    $('.error-message').remove();
                    if (errors) {
                        $.each(errors, function (key, value) {
                            let inputField = $('[name="' + key + '"]');
                            inputField.after('<div class="error-message text-danger">' + value[0] + '</div>');
                            showToast(value[0], 'danger'); 
                        });
                    } else {
                        showToast("Something went wrong!", 'danger');
                    }
                },
                complete: function () {
                    submitButton.prop('disabled', false).text('Submit');
                }
            });
        });
        
        function showToast(message, type = 'success') {
            let toast = $('#toastMessage');
            toast.removeClass('bg-success bg-danger').addClass(`bg-${type}`);
            toast.find('.toast-body').text(message);
            let bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }
    });
</script>

@stop
