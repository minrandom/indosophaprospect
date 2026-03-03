<div class="row text-dark">

    {{-- LEFT: IB INFORMATION --}}
    <div class="col-12 col-lg-6 mb-4">

        <h4 class="text-uppercase text-center mb-4">IB Information</h4>

        <div class="text-center mb-3">
            <div class="small text-uppercase">Total Equipment</div>
            <div class="display-4 font-weight-bold">
                {{ $market['ib_total'] ?? 0 }}
            </div>
        </div>

        <h6 class="text-uppercase">Our Installbase
        </h6>

        <table class="table table-bordered text-dark">
            <thead>
                <tr>
                    <th>Brand</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($market['ib_brands'] ?? []) as $b)
                    <tr>
                        <td>{{ $b['brand'] }}</td>
                        <td>{{ $b['count'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">No Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>


    {{-- RIGHT: MARKET INFORMATION --}}
    <div class="col-12 col-lg-6 mb-4">

        <h4 class="text-uppercase text-center mb-4">Market Information</h4>

        <div class="text-center mb-3">
            <div class="small text-uppercase">Total Equipment</div>
            <div class="display-4 font-weight-bold">
                {{ $market['market_total'] ?? 0 }}
            </div>
        </div>

        <h6 class="text-uppercase">Our Competitor</h6>

        <table class="table table-bordered text-dark">
            <thead>
                <tr>
                    <th>Brand</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($market['market_brands'] ?? []) as $b)
                    <tr>
                        <td>{{ $b['brand'] }}</td>
                        <td>{{ $b['count'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">No Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>
