{{-- @role('editor')
    <p>You are an employee.</p>
@endrole --}}

@can('edit posts')
    <button>Export to Excel {{ auth()->user()->getRoleNames()->first() }}</button>
@endcan
{{-- <button>Export to Excel {{ auth()->user()->getRoleNames()->first() }}</button> --}}

{{-- @dd(auth()->user()->getRoleNames()); --}}
{{-- @if (auth()->user()->hasRole('admin'))
    <p>Hello Admin!</p>
@endif --}}
