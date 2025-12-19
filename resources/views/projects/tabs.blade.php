<li class="nav-item" role="presentation">
    <button class="profiletabs active" role="tab" data-bs-toggle="tab" type="button" data-bs-target="#project"
        aria-selected="true"><i class="fas fa-folder-open pe-1"></i> Project Details</button>
</li>
@can('tab-survey')
    <li class="nav-item" role="presentation">
        <button class="profiletabs" role="tab" data-bs-toggle="tab" type="button" data-bs-target="#survey"
            aria-selected="true"><i class="fas fa-file-pen pe-1"></i> Survey</button>
    </li>
@endcan
@can('tab-drawing')
    <li class="nav-item" role="presentation">
        <button class="profiletabs" role="tab" data-bs-toggle="tab" type="button" data-bs-target="#drawing"
            aria-selected="false"><i class="fas fa-compass-drafting pe-1"></i> Drawings</button>
    </li>
@endcan
<!-- <li class="nav-item" role="presentation">
    <button class="profiletabs" role="tab" data-bs-toggle="tab" type="button" data-bs-target="#budget" aria-selected="false"><i
        class="fas fa-tag pe-1"></i>
        Budget / BOQ</button>
</li>
<li class="nav-item" role="presentation">
    <button class="profiletabs" role="tab" data-bs-toggle="tab" type="button" data-bs-target="#payment" aria-selected="false"><i
            class="fas fa-credit-card pe-1"></i>
        Payments</button>
</li> -->
@can('tab-progress')
    <li class="nav-item" role="presentation">
        <button class="profiletabs" role="tab" data-bs-toggle="tab" type="button" data-bs-target="#progress"
            aria-selected="false"><i class="fas fa-gauge-simple-high pe-1"></i> Progress</button>
    </li>
@endcan
@can('tab-qc')
    <li class="nav-item" role="presentation">
        <button class="profiletabs" role="tab" data-bs-toggle="tab" type="button" data-bs-target="#qc"
            aria-selected="false"><i class="fas fa-clipboard-list pe-1"></i> QC</button>
    </li>
@endcan
@can('tab-snags')
    <li class="nav-item" role="presentation">
        <button class="profiletabs" role="tab" data-bs-toggle="tab" type="button" data-bs-target="#snag"
            aria-selected="false"><i class="fas fa-magnifying-glass-chart pe-1"></i> Snags</button>
    </li>
@endcan
@can('tab-docs/link')
    <li class="nav-item" role="presentation">
        <button class="profiletabs" role="tab" data-bs-toggle="tab" type="button" data-bs-target="#document"
            aria-selected="false"><i class="fas fa-file pe-1"></i> Documents & Links</button>
    </li>
@endcan
