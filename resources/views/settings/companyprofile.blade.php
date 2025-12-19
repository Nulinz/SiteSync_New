@extends('layouts.app')

@section('content')
    <div class="body-div px-4 py-1 mb-3">

        <!-- Tabs -->
        @include('settings.tabs')

        <div class="container-fluid px-0">
            <div class="row my-3">
                <div class="w-100 col-sm-12 col-md-2 col-xl-2 pe-2 settingbuttondiv">
                    <a href="{{ route('settings.index') }}">
                        <button class="listbtn">
                            <i class="fas fa-pen pe-2"></i> Edit Company
                        </button>
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Left Side -->
                <div class="col-sm-12 col-md-6 col-xl-6 pe-2 settingleft">
                    <div class="settingform-div mb-2">
                        <div class="settingheader">
                            <div>
                                <h5 class="mb-2">Company Information</h5>
                                <h6 class="mb-3">Manage and update your organization's essential information.</h6>
                                @if ($company->logo)
                                    <img src="{{ Storage::disk('s3')->url($company->logo) }}" 
                                         height="50px" class="mb-2" alt="Company Logo" />
                                @endif
                            </div>
                        </div>

                        <div class="settingtable mt-2">
                            <table class="table table-striped">
                                <tbody>
                                    <tr><th>Registered Name</th><td>{{ $company->name }}</td></tr>
                                    <tr><th>Address</th><td>{{ $company->address }}</td></tr>
                                    <tr><th>District</th><td>{{ $company->district }}</td></tr>
                                    <tr><th>State</th><td>{{ $company->state }}</td></tr>
                                    <tr><th>Pincode</th><td>{{ $company->pincode }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Side -->
                <div class="col-sm-12 col-md-6 col-xl-6 pe-2 settingright">
                    <div class="settingform-div mb-2">
                        <div class="settingheader">
                            <div>
                                <h5 class="mb-2">Bank Details</h5>
                                <h6 class="mb-2">Manage your company's banking information for transactions and payments.</h6>
                            </div>
                        </div>
                        <div class="settingtable mt-1">
                            <table class="table table-striped">
                                <tbody>
                                    <tr><th>Bank Name</th><td>{{ $company->bank_name }}</td></tr>
                                    <tr><th>Account Holder Name</th><td>{{ $company->account_holder_name }}</td></tr>
                                    <tr><th>Account Number</th><td>{{ $company->account_number }}</td></tr>
                                    <tr><th>IFSC Code</th><td>{{ $company->ifsc_code }}</td></tr>
                                    <tr><th>Branch Name</th><td>{{ $company->branch_name }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Document Details -->
                    <div class="settingform-div mb-2">
                        <div class="settingheader">
                            <div>
                                <h5 class="mb-2">Company Documents</h5>
                                <h6 class="mb-2">View and manage organization's information along with essential documents.</h6>
                            </div>
                        </div>

                        <div class="settingtable mt-1">
                            <table class="table table-striped">
                                <tbody>
                                    <tr><th>GST Number</th><td>{{ $company->gst_number }}</td></tr>
                                    <tr><th>PAN Number</th><td>{{ $company->pan_number }}</td></tr>
                                    
                                    <tr>
                                        <th>GST Attachment</th>
                                        <td>
                                            @if ($company->gst_attachment)
                                                <a href="{{ Storage::disk('s3')->url($company->gst_attachment) }}" 
                                                   target="_blank" data-bs-toggle="tooltip" title="View File">
                                                    <i class="fa-solid fa-cloud-arrow-down"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">Not uploaded</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>PAN Attachment</th>
                                        <td>
                                            @if ($company->pancard_attachment)
                                                <a href="{{ Storage::disk('s3')->url($company->pancard_attachment) }}" 
                                                   target="_blank" data-bs-toggle="tooltip" title="View File">
                                                    <i class="fa-solid fa-cloud-arrow-down"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">Not uploaded</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr><th>MSME Number</th><td>{{ $company->msme_number }}</td></tr>

                                    <tr>
                                        <th>MSME Attachment</th>
                                        <td>
                                            @if ($company->msme_attachment)
                                                <a href="{{ Storage::disk('s3')->url($company->msme_attachment) }}" 
                                                   target="_blank" data-bs-toggle="tooltip" title="View File">
                                                    <i class="fa-solid fa-cloud-arrow-down"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">Not uploaded</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
