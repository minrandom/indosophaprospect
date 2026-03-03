<div class="card shadow border-0" style="border-radius:1.5rem;">
    <div class="card-body">

        <h5 class="text-white text-uppercase mb-4 text-center">
            Key People
        </h5>

        <div class="table-responsive">
            <table class="table table-striped mb-0">

                <thead style=" border-radius:20px;">
                    <tr class="text-dark text-uppercase text-center">
                        <th style="width:80px;"></th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Phone</th>
                        <th>Influence Type</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse(($hr['people'] ?? []) as $p)
                        <tr class="align-middle text-center">
                            <td>
                                <img src="{{ $p['avatar'] ?? 'https://via.placeholder.com/48' }}"
                                     class="rounded-circle"
                                     width="50"
                                     height="50">
                            </td>
                            <td>{{ $p['name'] }}</td>
                            <td class="text-uppercase">{{ $p['role'] }}</td>
                            <td class="text-uppercase">{{ $p['department'] }}</td>
                            <td>{{ $p['phone'] }}</td>
                            <td class="text-uppercase">{{ $p['influence'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No Data Available
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>
