@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('assets/css/settingsprofile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

    <div class="body-div px-4 py-1 mb-3">
        <div class="body-head">
            <div class="body-h6">
                <h6 class="head1h6"><a href="{{ route('dashboard.index') }}">Dashboard /</a></h6>
                <h6 class="head1h6"><a href="{{ route('settings.index') }}">Settings /</a></h6>
                <h6 class="head2h6"><a href="{{ route('settings.qccreate') }}">Create QC</a></h6>
            </div>
        </div>

        <div class="container-fluid px-0">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('settings.qcstore') }}" method="POST" id="set_qc">
                @csrf
                <div class="row mt-3">
                    <!-- Basic Information -->
                    <div class="col-sm-12 col-md-12 col-xl-12 pe-2 settingleft">
                        <div class="settingform-div mb-2">
                            <div class="settingheader">
                                <div>
                                    <h5 class="mb-2">Basic Information</h5>
                                    <h6 class="mb-0">Survey to gather feedback on construction project quality, timelines,
                                        and customer satisfaction for improvements.</h6>
                                </div>
                            </div>
                            <div class="settingform">
                                <div class="container-fluid p-0 form-div border-0">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3 pe-2 inputs">
                                            <label for="qctitle">QC Title</label>
                                            <input type="text" class="form-control" name="title" id="qctitle"
                                                required>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3 pe-2 inputs">
                                            <label for="qcdescp">QC Description</label>
                                            <textarea rows="3" class="form-control" name="description" id="qcdescp" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QC Checklist -->
                    <div class="col-sm-12 col-md-12 col-xl-12 pe-2 settingright">
                        <div class="settingform-div mb-2">
                            <div class="settingheader">
                                <div>
                                    <h5 class="mb-2">Add QC Checklist</h5>
                                    <h6 class="mb-0">This section allows you to create and customize all the questions for
                                        your survey.</h6>
                                </div>
                                <div>
                                    <button type="button" class="settingheadbtn" onclick="addChecklist()">+ Add
                                        Checklist</button>
                                </div>
                            </div>

                            <div class="settingform">
                                <div class="container-fluid p-0 form-div border-0">
                                    <div class="row" id="checklist-container">
                                        <!-- Dynamic Checklist Items Will Be Added Here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="col-12 d-flex justify-content-center align-items-center mt-3">
                        <button type="submit" class="formbtn">Save</button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <!-- Add QC Checklist Modal -->
    <div class="modal fade" id="qcchecklist" tabindex="-1" aria-labelledby="qcchecklistLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="usericon">
                        <h5 class="mb-0"><i class="fa-solid fa-clipboard-list"></i></h5>
                    </div>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="modal-title mb-2 fs-5" id="qcchecklistLabel">Create QC Checklist</h4>
                    <form id="sub_qc">
                        <div class="qc-container row form-div py-1 px-2 mb-1">
                            <div class="col-sm-12 col-md-12 mb-1">
                                <label for="question" class="col-form-label">Question</label>
                                <input type="text" class="form-control" name="question" id="question" required>
                            </div>
                        </div>

                        <div id="qc-list"></div>

                        <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                            <button type="button" class="modalbtn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src={{ asset('assets/js/form_script.js') }}></script>

    <script>
        function addChecklist() {
            let container = document.getElementById('checklist-container');
            let div = document.createElement("div");
            div.classList.add("col-sm-12", "col-md-12", "col-xl-12", "mb-2", "pe-2", "inputs", "inpflex");

            let input = document.createElement("input");
            input.type = "text";
            input.name = "questions[]"; // Make it an array for multiple questions
            input.classList.add("form-control", "border-0");
            input.placeholder = "Enter checklist question";
            input.required = true;

            let removeBtn = document.createElement("i");
            removeBtn.classList.add("fas", "fa-trash-alt", "text-danger");
            removeBtn.style.cursor = "pointer";
            removeBtn.onclick = function() {
                container.removeChild(div);
            };

            div.appendChild(input);
            div.appendChild(removeBtn);
            container.appendChild(div);
        }
    </script>

@endsection
