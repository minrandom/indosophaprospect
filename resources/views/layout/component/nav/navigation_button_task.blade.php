<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div class="btn-group" role="group">
        {{-- Visit List --}}
        <a href="{{ route('missions.pool') }}"
           class="btn btn-sm btn-outline-primary {{ request()->routeIs('missions.pool') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt mr-1"></i> Visit List
        </a>

        {{-- Task List --}}
        <a href="{{ route('missions.taskPool') }}"
           class="btn btn-sm btn-outline-primary {{ request()->routeIs('missions.taskPool') ? 'active' : '' }}">
            <i class="fas fa-tasks mr-1"></i> Task List
        </a>

        {{-- Visit Detail --}}
        @isset($run)
            <a href="{{ route('missions.runs.show', $run->id) }}"
               class="btn btn-sm btn-outline-primary {{ request()->routeIs('missions.runs.show') ? 'active' : '' }}">
                <i class="fas fa-map-marker-alt mr-1"></i> Visit Detail
            </a>
        @endisset
    </div>

    {{-- Back Button --}}
    <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
</div>
