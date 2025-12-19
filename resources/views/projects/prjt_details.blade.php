<!-- Left Content -->
<div class="contentleft">
    @can('project_edit')
        <div class="lefthead">
            <a href="{{ route('project.edit', $project->id) }}"><button class="listbtn"><i class="fas fa-pen pe-2"></i>Edit
                    Project</button></a>
        </div>
    @endcan
    <div class="cards mt-2">
        <div class="contentleft">
            <div class="basicdetails mb-2">
                <div class="maincard leftcard row">
                    <div class="cardshead">
                        <div class="col-12 cardsh5">
                            <h5 class="mb-0">Project Details</h5>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                            <h6 class="mb-0 text-start"><i class="fas fa-id-badge"></i>&nbsp; Project
                                Code</h6>
                            {{-- @dd($project); --}}
                            <h5 class="mb-0 text-end">{{ $project->project_id }}</h5>
                        </div>
                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                            <h6 class="mb-0 text-start"><i class="fas fa-city"></i>&nbsp; Project Name
                            </h6>
                            <h5 class="mb-0 text-end">{{ $project->project_name }}</h5>
                        </div>
                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                            <h6 class="mb-0 text-start"><i class="fas fa-user"></i>&nbsp; Client Name
                            </h6>
                            <h5 class="mb-0 text-end">{{ $project->client_name }}</h5>
                        </div>
                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                            <h6 class="mb-0 text-start"><i class="fas fa-phone"></i>&nbsp; Contact
                                Number</h6>
                            <h5 class="mb-0 text-end">{{ $project->contact_number }}</h5>
                        </div>
                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                            <h6 class="mb-0 text-start"><i class="fas fa-envelope"></i>&nbsp; Email ID
                            </h6>
                            <h5 class="mb-0 text-end">{{ $project->email_id }}</h5>
                        </div>
                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                            <h6 class="mb-0 text-start"><i class="fas fa-location"></i>&nbsp; Address
                            </h6>
                            <h5 class="mb-0 text-end">{{ $project->address }}</h5>
                        </div>
                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                            <h6 class="mb-0 text-start"><i class="fas fa-ruler"></i>&nbsp; Plot Size
                            </h6>
                            <h5 class="mb-0 text-end">{{ $project->plot_size }} {{ $project->plot_unit }}</h5>
                        </div>
                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                            <h6 class="mb-0 text-start"><i class="fas fa-chart-area"></i>&nbsp; Total
                                Buildup Area</h6>
                            <h5 class="mb-0 text-end">{{ $project->total_building_area }}
                                {{ $project->building_area_unit }}
                            </h5>
                        </div>
                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                            <h6 class="mb-0 text-start"><i class="fas fa-ruler"></i>&nbsp; Project address
                            </h6>
                            <h5 class="mb-0 text-end">{{ $project->pro_address }} </h5>
                        </div>
                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                            <h6 class="mb-0 text-start"><i class="fas fa-ruler"></i>&nbsp; City
                            </h6>
                            <h5 class="mb-0 text-end">{{ $project->pro_city }} </h5>
                        </div>
                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                            <h6 class="mb-0 text-start"><i class="fas fa-ruler"></i>&nbsp; State
                            </h6>
                            <h5 class="mb-0 text-end">{{ $project->pro_state }}</h5>
                        </div>
                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                            <h6 class="mb-0 text-start"><i class="fas fa-ruler"></i>&nbsp; Pincode
                            </h6>
                            <h5 class="mb-0 text-end">{{ $project->pro_pincode }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="filedetails mb-2">
                <div class="maincard leftcard row">
                    <div class="cardshead">
                        <div class="col-12 cardsh5">
                            <h5 class="mb-0">Final Agreed Specification</h5>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                            <div class="d-flex align-items-center justify-content-start gap-2">
                                <img src="{{ asset('assets/images/pdf.png') }}" alt="">
                                <div>
                                    <h5 class="mb-1">{{ $project->file_name }}</h5>
                                    <h6 class="mb-0"></h6>
                                </div>
                            </div>
                                    <div>
                                        {{-- Download Button --}}
                                        <a class="listtdbtn"
                                        href="{{ Storage::disk('s3')->url('pro_docs/' . $project->file_attachment) }}"
                                        download
                                        data-bs-toggle="tooltip"
                                        data-bs-title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>

                                        {{-- Preview Button --}}
                                        <button
                                            onclick="window.open('{{ Storage::disk('s3')->url('pro_docs/' . $project->file_attachment) }}', '_blank')"
                                            class="listtdbtn"
                                            data-bs-toggle="tooltip"
                                            data-bs-title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-block mx-auto brdr"></div>
        <div class="contentright">
            <div class="assigndetails">
                <div class="maincard leftcard row">
                    <div class="cardshead">
                        <div class="col-12 cardsh5">
                            <h5 class="mb-0">Assigned Project Users</h5>
                        </div>
                    </div>
                    @foreach ($project_employees as $project_employee)
                        <div class="row mt-2">
                            <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                <div class="d-flex align-items-center justify-content-start gap-2">
                                    @if ($project_employee->image_path)
                                        <img src="{{ asset($project_employee->image_path) }}"
                                            onerror="this.onerror=null; this.src='{{ asset('assets/images/avatar.png') }}';"
                                            alt="">
                                    @else
                                        <img src="{{ asset('assets/images/avatar.png') }}" alt="">
                                    @endif
                                    <div>
                                        <h5 class="mb-1">{{ $project_employee->name }}</h5>
                                        <h6 class="mb-0">{{ $project_employee->designation_name }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
