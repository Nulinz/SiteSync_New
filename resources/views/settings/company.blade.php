@extends('layouts.app')

@section('content')
<div class="body-div px-4 py-1 mb-3">

    <!-- Tabs -->
    @include('settings.tabs')

    <div class="tab-content mt-3" id="myTabContent">
        <!-- Company Tab -->
        <div class="tab-pane show active">
            <form method="POST" action="{{ route('settings.companystore') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-div">
                    <div class="row">
                        <!-- Logo Upload -->
                        <div class="col-sm-12 col-md-4 col-xl-4 mb-3 px-2 inputs">
                            <label for="file_upload">Logo</label>
                            <label class="custom-file-upload" for="file_upload">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="" viewBox="0 0 24 24">
                                        <path fill="" d="M10 1C9.73478 1 9.48043 1.10536 9.29289 1.29289L3.29289 7.29289C3.10536 7.48043 3 7.73478 3 8V20C3 21.6569 4.34315 23 6 23H7C7.55228 23 8 22.5523 8 22C8 21.4477 7.55228 21 7 21H6C5.44772 21 5 20.5523 5 20V9H10C10.5523 9 11 8.55228 11 8V3H18C18.5523 3 19 3.44772 19 4V9C19 9.55228 19.4477 10 20 10C20.5523 10 21 9.55228 21 9V4C21 2.34315 19.6569 1 18 1H10ZM9 7H6.41421L9 4.41421V7ZM14 15.5C14 14.1193 15.1193 13 16.5 13C17.8807 13 19 14.1193 19 15.5V16V17H20C21.1046 17 22 17.8954 22 19C22 20.1046 21.1046 21 20 21H13C11.8954 21 11 20.1046 11 19C11 17.8954 11.8954 17 13 17H14V16V15.5ZM16.5 11C14.142 11 12.2076 12.8136 12.0156 15.122C10.2825 15.5606 9 17.1305 9 19C9 21.2091 10.7909 23 13 23H20C22.2091 23 24 21.2091 24 19C24 17.1305 22.7175 15.5606 20.9844 15.122C20.7924 12.8136 18.858 11 16.5 11Z"></path>
                                    </svg>
                                </div>
                                <div class="text">
                                    <span id="file-text" class="text-center">Click to upload image</span>
                                </div>
                                <input type="file" name="logo" id="file_upload"
                                       onchange="updateFileName('file_upload', 'file-text')"
                                       {{ $company->logo ? '' : 'required' }}>
                            </label>
                        </div>

                        <!-- Logo Preview -->
                        <div class="col-sm-12 col-md-4 col-xl-4 mb-3 px-2 inputs">
                            <label for="preview">Preview</label><br>
                            @if ($company->logo)
                                <img src="{{ Storage::disk('s3')->url($company->logo) }}" alt="Logo" height="50px" />
                            @endif
                        </div>
                    </div>

                    <hr>

                    <!-- Company Details -->
                    <div class="body-head mt-2 mb-3">
                        <h4 class="m-0">Company Details</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3 px-2 inputs">
                            <label for="regname">Registered Name</label>
                            <input type="text" class="form-control" name="name" id="regname"
                                   value="{{ $company->name }}" required>
                        </div>
                        <div class="col-md-4 mb-3 px-2 inputs">
                            <label for="address">Address</label>
                            <textarea rows="1" class="form-control" name="address" id="address" required>{{ $company->address }}</textarea>
                        </div>
                        <div class="col-md-4 mb-3 px-2 inputs">
                            <label for="district">District</label>
                            <input type="text" class="form-control" name="district" id="district"
                                   value="{{ $company->district }}" required>
                        </div>
                        <div class="col-md-4 mb-3 px-2 inputs">
                            <label for="state">State</label>
                            <input type="text" class="form-control" name="state" id="state"
                                   value="{{ $company->state }}" required>
                        </div>
                        <div class="col-md-4 mb-4 px-2 inputs">
                            <label for="pincode">Pincode</label>
                            <input type="number" class="form-control" name="pincode" id="pincode"
                                   value="{{ $company->pincode }}" required>
                        </div>
                    </div>

                    <hr>

                    <!-- Document Details -->
                    <div class="body-head mt-1 mb-3">
                        <h4 class="m-0">Documents Details</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3 px-2 inputs">
                            <label for="gst_number">GST Number</label>
                            <input type="text" class="form-control" name="gst_number" id="gst_number"
                                   value="{{ $company->gst_number }}" required>
                        </div>
                        <div class="col-md-4 mb-3 px-2 inputs">
                            <label for="pan_number">Pan Number</label>
                            <input type="text" class="form-control" name="pan_number" id="pan_number"
                                   value="{{ $company->pan_number }}" required>
                        </div>
                        <div class="col-md-4 mb-3 px-2 inputs">
                            <label for="msme_number">MSME Number</label>
                            <input type="text" class="form-control" name="msme_number" id="msme_number"
                                   value="{{ $company->msme_number }}" required>
                        </div>

                        <!-- GST Attachment -->
                        <div class="col-md-4 mb-3 px-2 inputs">
                            <label for="gst_attachment">GST Attachment</label>
                            <input type="file" class="form-control" name="gst_attachment" id="gst_attachment"
                                   {{ $company->gst_attachment ? '' : 'required' }}>
                            @if ($company->gst_attachment)
                                <a href="{{ Storage::disk('s3')->url($company->gst_attachment) }}" target="_blank">
                                    <i class="fas fa-cloud-arrow-down"></i> View GST File
                                </a>
                            @endif
                        </div>

                        <!-- PAN Attachment -->
                        <div class="col-md-4 mb-3 px-2 inputs">
                            <label for="pancard_attachment">Pancard Attachment</label>
                            <input type="file" class="form-control" name="pancard_attachment" id="pancard_attachment"
                                   {{ $company->pancard_attachment ? '' : 'required' }}>
                            @if ($company->pancard_attachment)
                                <a href="{{ Storage::disk('s3')->url($company->pancard_attachment) }}" target="_blank">
                                    <i class="fas fa-cloud-arrow-down"></i> View PAN File
                                </a>
                            @endif
                        </div>

                        <!-- MSME Attachment -->
                        <div class="col-md-4 mb-3 px-2 inputs">
                            <label for="msme_attachment">MSME Attachment</label>
                            <input type="file" class="form-control" name="msme_attachment" id="msme_attachment"
                                   {{ $company->msme_attachment ? '' : 'required' }}>
                            @if ($company->msme_attachment)
                                <a href="{{ Storage::disk('s3')->url($company->msme_attachment) }}" target="_blank">
                                    <i class="fas fa-cloud-arrow-down"></i> View MSME File
                                </a>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <!-- Bank Details -->
                    <div class="body-head mt-1 mb-3">
                        <h4 class="m-0">Bank Details</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3 px-2 inputs">
                            <label for="bank_name">Bank Name</label>
                            <input type="text" class="form-control" name="bank_name" id="bank_name"
                                   value="{{ $company->bank_name }}" required>
                        </div>
                        <div class="col-md-4 mb-3 px-2 inputs">
                            <label for="account_holder_name">Account Holder Name</label>
                            <input type="text" class="form-control" name="account_holder_name"
                                   id="account_holder_name" value="{{ $company->account_holder_name }}" required>
                        </div>
                        <div class="col-md-4 mb-3 px-2 inputs">
                            <label for="account_number">Account Number</label>
                            <input type="text" class="form-control" name="account_number" id="account_number"
                                   value="{{ $company->account_number }}" required>
                        </div>
                        <div class="col-md-4 mb-3 px-2 inputs">
                            <label for="ifsc_code">IFSC Code</label>
                            <input type="text" class="form-control" name="ifsc_code" id="ifsc_code"
                                   value="{{ $company->ifsc_code }}" required>
                        </div>
                        <div class="col-md-4 mb-3 px-2 inputs">
                            <label for="branchname">Branch Name</label>
                            <input type="text" class="form-control" name="branch_name" id="branchname"
                                   value="{{ $company->branch_name }}" required>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center align-items-center mt-3">
                    <button type="submit" class="formbtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
