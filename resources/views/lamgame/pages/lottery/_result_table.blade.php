{{-- Result table partial --}}
@php
    // Support both single result and multiple results per draw
    $resultsList = $draw->relationLoaded('results') && $draw->results->count() > 0
        ? $draw->results
        : ($draw->result ? collect([$draw->result]) : collect());
@endphp

@forelse($resultsList as $result)
@if(!empty($result->prize_data))
<div class="lt-result-table">
    @if($result->province)
        <div class="lt-result-province">{{ $result->province->name }}</div>
    @endif
    @php $prizes = $result->prize_data; @endphp
    @if(isset($prizes['numbers']))
        <div class="lt-balls">
            @foreach($prizes['numbers'] as $num)
                <span class="lt-ball">{{ $num }}</span>
            @endforeach
        </div>
    @else
        <table class="lt-table">
            @php
                $prizeMap = [
                    'giai_db' => 'ĐB', 'dac_biet' => 'ĐB',
                    'giai_1' => 'G1', 'giai_nhat' => 'G1',
                    'giai_2' => 'G2', 'giai_nhi' => 'G2',
                    'giai_3' => 'G3', 'giai_ba' => 'G3',
                    'giai_4' => 'G4', 'giai_tu' => 'G4',
                    'giai_5' => 'G5', 'giai_nam' => 'G5',
                    'giai_6' => 'G6', 'giai_sau' => 'G6',
                    'giai_7' => 'G7', 'giai_bay' => 'G7',
                    'giai_8' => 'G8',
                ];
                $displayOrder = ['giai_db', 'dac_biet', 'giai_1', 'giai_nhat', 'giai_2', 'giai_nhi', 'giai_3', 'giai_ba', 'giai_4', 'giai_tu', 'giai_5', 'giai_nam', 'giai_6', 'giai_sau', 'giai_7', 'giai_bay', 'giai_8'];
                $shown = [];
            @endphp
            @foreach($displayOrder as $key)
                @if(isset($prizes[$key]) && !empty($prizes[$key]) && !in_array($prizeMap[$key] ?? '', $shown))
                    @php $shown[] = $prizeMap[$key]; @endphp
                    <tr>
                        <th>{{ $prizeMap[$key] ?? $key }}</th>
                        <td class="{{ in_array($key, ['giai_db', 'dac_biet']) ? 'lt-table__db' : '' }}">
                            @if(is_array($prizes[$key]))
                                {{ implode(' - ', $prizes[$key]) }}
                            @else
                                {{ $prizes[$key] }}
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
    @endif
</div>
@endif
@empty
    {{-- No results --}}
@endforelse
