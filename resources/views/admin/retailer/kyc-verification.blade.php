@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
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
        <div class="row">
            <!-- Profile Card -->
            <div class="col-md-4">
                <div class="card text-center shadow">
                    <div class="card-header bg-success text-white rounded-top">
                        <img src="{{ asset($user->profile_image) }}" alt="Profile"
                            class="rounded-circle mt-3" width="80" height="80">
                    </div>
                    <div class="card-body">
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <small class="text-muted d-block">Retailer</small>
                        <p class="mb-2">KYC Status: <span class="badge bg-success">{{ $user->status }}</span></p>
                        <div class="mt-3">
                            <p class="mb-1"><i class="bi bi-envelope"></i> {{ $user->email }}</p>
                            <p class="mb-1"><i class="bi bi-telephone"></i> {{ $user->phone_number }}</p>
                            <p class="mb-0"><i class="bi bi-calendar"></i> {{ $user->created_at->format('Y-m-d') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shop Photo Card -->
            @foreach ($document as $data)
                <div class="col-md-4">
                    <div class="card shadow">
                        <div class="card-header bg-success text-white text-uppercase text-center fw-bold">
                            {{ $data->name }}
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ $data->user_document->file ?? 'https://mrspay.in/assets/img/no_image_available.jpeg' }}" class="img-fluid mb-3" style="width: 100%;height:170px;" alt="Shop Photo">
                            
                            <!-- Button for opening file upload -->
                            <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $data->id }}"><i class="bi bi-download"></i> Upload Document</a>

                            <!-- Modal for file upload -->
                            <div class="modal fade" id="uploadModal{{ $data->id }}" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="uploadModalLabel">Upload Image for {{ $data->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Image Upload Form -->
                                            <form action="{{ url('admin/user-document') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                <input type="hidden" name="document_id" value="{{ $data->id }}">
                                                <div class="mb-3">
                                                    <input type="file" name="file" class="form-control" required>
                                                </div>
                                                <button type="submit" class="btn btn-success">Upload</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop
