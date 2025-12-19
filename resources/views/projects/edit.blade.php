@extends ('layouts.app')

@section('content')
    <style>
        .custom-file-upload {
            height: 150px;
        }
    </style>

    <div class="body-div px-4 py-1 mb-3">
        <div class="body-head">
            <div class="body-h6 mb-3">
                <h6 class="head1h6"><a href="{{ route('dashboard.index') }}">Dashboard /</a></h6>
                <h6 class="head1h6"><a href="{{ route('project.index') }}">My Projects /</a></h6>
                <h6 class="head2h6"><a href="{{ route('project.edit', $project->id) }}">Edit Project</a></h6>
            </div>
        </div>

        <div class="body-head mb-3">
            <h4 class="m-0">Edit Project</h4>
        </div>

        <form action="{{ route('project.store') }}" method="post" onsubmit="return validateForm()" id="edit_project"
            enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $project->id ?? '' }}" />
            @csrf
            <div class="form-div pt-0">
                <!-- Project Details -->
                <div class="body-head my-3">
                    <div>
                        <h4 class="mb-1">Project Details</h4>
                        <h6 class="m-0 head1h6">Define the unique identity of the project, including its name, ID, and
                            associated client, ensuring seamless tracking and management.</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-xl-12 mb-3 inputs">
                        <label for="project_name">Project Name</label>
                        <input type="text" class="form-control" name="project_name" id="project_name"
                            value="{{ $project->project_name ?? '' }}" required>
                        <small class="text-danger" id="project_name-error"></small>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-6 mb-3 inputs">
                        <label for="project_id">Project ID</label>
                        <input type="text" class="form-control" name="project_id" id="project_id"
                            value="{{ $project->project_id ?? '' }}" required>
                        <small class="text-danger" id="project_id-error"></small>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-6 mb-3 inputs">
                        <label for="client_name">Client Name</label>
                        <input type="text" class="form-control" name="client_name" id="client_name"
                            value="{{ $project->client_name ?? '' }}" required>
                        <small class="text-danger" id="client_name-error"></small>
                    </div>

                    <!-- Client Contact Information -->
                    <div class="body-head my-3">
                        <div>
                            <h4 class="m-0">Client Contact Information</h4>
                            <h6 class="m-0 head1h6">Store essential client details such as phone number and email to
                                facilitate smooth communication and project coordination.</h6>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="contact_number">Contact Number</label>
                        <input type="number" class="form-control" name="contact_number" id="contact_number"
                            value="{{ $project->contact_number ?? '' }}" oninput="validate_contact(this)" min="6000000000"
                            max="9999999999" required>
                        <small class="text-danger" id="contact_number-error"></small>
                    </div>
                    <!-- <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                                                                                        <label for="alternate_contact_number">Alternate Contact Number</label>
                                                                                        <input type="number" class="form-control" name="alternate_contact_number"
                                                                                            id="alternate_contact_number" value="{{ $project->alternate_contact_number ?? '' }}"
                                                                                            oninput="validate_contact(this)" min="6000000000" max="9999999999">
                                                                                        <small class="text-danger" id="alternate_contact_number-error"></small>
                                                                                    </div> -->
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="email_id">Email ID</label>
                        <input type="email" class="form-control" name="email_id" id="email_id"
                            value="{{ $project->email_id ?? '' }}" required>
                        <small class="text-danger" id="email_id-error"></small>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="client_address">Address</label>
                        <textarea rows="1" class="form-control" name="client_address" id="client_address" required>{{ $project->address ?? '' }}</textarea>
                        <small class="text-danger" id="address-error"></small>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="client_city">City</label>
                        <input type="text" class="form-control" name="client_city" id="client_city"
                            value="{{ $project->city ?? '' }}" required>
                        <small class="text-danger" id="city-error"></small>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="client_state">State</label>
                        <input type="text" class="form-control" name="client_state" id="client_state"
                            value="{{ $project->state ?? '' }}" required>
                        <small class="text-danger" id="state-error"></small>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="client_pincode">Pincode</label>
                        <input type="number" class="form-control" name="client_pincode" id="client_pincode"
                            value="{{ $project->pincode ?? '' }}" oninput="validate_pin(this)" min="000000"
                            max="999999" required>
                        <small class="text-danger" id="pincode-error"></small>
                    </div>

                    <!-- Project Location -->
                    <div class="body-head my-3">
                        <div>
                            <h4 class="mb-1">Project Location</h4>
                            <h6 class="m-0 head1h6">Specify the complete address, including city, state, and pin code,
                                ensuring accurate site identification and accessibility.</h6>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-xl-12 mb-3 inputs">
                        <label for="prjt_address">Address</label>
                        <textarea rows="1" class="form-control" name="pro_address" id="prjt_address" required>{{ $project->pro_address ?? '' }}</textarea>
                        <small class="text-danger" id="address-error"></small>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="prjt_city">City</label>
                        <input type="text" class="form-control" name="pro_city" id="prjt_city"
                            value="{{ $project->pro_city ?? '' }}" required>
                        <small class="text-danger" id="city-error"></small>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="prjt_state">State</label>
                        <input type="text" class="form-control" name="pro_state" id="prjt_state"
                            value="{{ $project->pro_state ?? '' }}" required>
                        <small class="text-danger" id="state-error"></small>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="prjt_pincode">Pincode</label>
                        <input type="number" class="form-control" name="pro_pincode" id="prjt_pincode"
                            value="{{ $project->pro_pincode ?? '' }}" oninput="validate_pin(this)" min="000000"
                            max="999999" required>
                        <small class="text-danger" id="pincode-error"></small>
                    </div>

                    <!-- Project Specifications -->
                    <div class="body-head my-3">
                        <div>
                            <h4 class="mb-1">Project Specifications</h4>
                            <h6 class="m-0 head1h6">Outline key structural and technical details such as plot size, total
                                built-up area, and other essential parameters to define project scope.</h6>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-6 mb-3 inputs">
                        <label for="plot_size">Plot Size</label>
                        <div class="inpflex">
                            <input type="text" class="form-control border-0" name="plot_size" id="plot_size"
                                value="{{ $project->plot_size ?? '' }}" required>
                            <select class="form-select border-0" name="plot_unit" id="plot_unit">
                                <option {{ $project->plot_unit == 'sq.ft' ? 'selected' : '' }}>sq.ft</option>
                            </select>
                        </div>
                        <small class="text-danger" id="plot_size-error"></small>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-6 mb-3 inputs">
                        <label for="total_building_area">Total Building Area</label>
                        <div class="inpflex">
                            <input type="text" class="form-control border-0" name="total_building_area"
                                id="total_building_area" value="{{ $project->total_building_area ?? '' }}" required>
                            <select class="form-select border-0" name="building_area_unit" id="building_area_unit">
                                <option {{ $project->building_area_unit == 'sqft' ? 'selected' : '' }}>sqft</option>
                            </select>
                        </div>
                        <small class="text-danger" id="total_building_area-error"></small>
                    </div>

                    <!-- Additional Information -->
                    <div class="body-head my-3">
                        <div>
                            <h4 class="mb-1">Additional Information</h4>
                            <h6 class="m-0 head1h6">Include supporting documents, file attachments, and assigned users to
                                ensure proper documentation and team collaboration.</h6>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-12 mb-3 px-2 inputs">
                        <label class="custom-file-upload w-100 shadow-none" for="file_upload">
                            <div class="icon">
                                <img src="{{ asset('assets/images/upload.png') }}" height="60px" alt="">
                            </div>
                            <div class="text">
                                <span id="file-text"
                                    class="text-center">{{ $project->file_name ?? 'Choose a file (JPEG, PNG, PDF, and MP4 formats, upto 50MB)' }}
                                </span>
                            </div>
                            <input type="file" id="file_upload" name="file_attachment"
                                onchange="updateFileName('file_upload', 'file-text')">
                        </label>
                    </div>
                    <div class="col-sm-12 col-md-12 col-xl-12 mb-3 inputs">
                        <label for="assigned_to">Assign To</label>
                        <div class="col-sm-12 col-md-12 col-xl-12">
                            <div class="col-sm-12 col-md-12 col-xl-12">
                                <div class="dropdown-center">
                                    <button class="w-100 text-start form-select checkdrp" type="button"
                                        data-bs-toggle="dropdown" id="assignto" aria-expanded="false">
                                        Select an employee
                                    </button>
                                    <ul class="dropdown-menu w-100 px-2" id="roleDropdownMenu">
                                        @foreach ($employees as $employee)
                                            {{-- @if ($employee->id != auth()->user()->id) --}}
                                            <div class="d-flex align-items-center w-100 mt-1">
                                                <input type="checkbox"
                                                    {{ in_array($employee->id, $project->assigned_to) ? 'checked' : '' }}
                                                    class="me-2 checkbox" name="assigned_to[]"
                                                    value="{{ $employee->id }}">
                                                <label for="checkbox_1" class="my-auto">{{ $employee->name }}</label>
                                            </div>
                                            {{-- @endif --}}
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <small class="text-danger" id="assigned_to-error"></small>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center align-items-center mt-3">
                <button type="submit" class="formbtn">Update Project</button>
            </div>
        </form>


    </div>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>

    <script>
        function validateForm() {
            let isValid = true;

            const fields = [{
                    id: 'project_name',
                    message: 'Project Name is required.'
                },
                {
                    id: 'project_id',
                    message: 'Project ID is required.'
                },
                {
                    id: 'client_name',
                    message: 'Client Name is required.'
                },
                {
                    id: 'contact_number',
                    message: 'Valid Contact Number is required.',
                    pattern: /^\d{10}$/
                },
                {
                    id: 'alternate_contact_number',
                    message: 'Valid Contact Number is required.',
                    pattern: /^\d{10}$/
                },
                {
                    id: 'email_id',
                    message: 'Valid Email ID is required.',
                    pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
                },
                {
                    id: 'address',
                    message: 'Address is required.'
                },
                {
                    id: 'city',
                    message: 'City is required.'
                },
                {
                    id: 'state',
                    message: 'State is required.'
                },
                {
                    id: 'pincode',
                    message: 'Valid Pincode is required.',
                    pattern: /^\d{6}$/
                },
                {
                    id: 'plot_size',
                    message: 'Plot Size is required.'
                },
                {
                    id: 'total_building_area',
                    message: 'Total Building Area is required.'
                },
                {
                    id: 'assigned_to',
                    message: 'Please select an employee.'
                }
            ];

            fields.forEach(field => {
                const input = document.getElementById(field.id);
                const errorLabel = document.getElementById(field.id + '-error');
                console.log(input + " " + errorLabel);

                if (!input.value || (field.pattern && !field.pattern.test(input.value))) {
                    errorLabel.textContent = field.message;
                    isValid = false;
                } else {
                    errorLabel.textContent = '';
                }
            });

            return isValid;
        }
    </script>
@endsection
