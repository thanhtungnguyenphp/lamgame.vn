{{-- Venues Section --}}
<section class="wc26-venues" id="venues">
    <div class="container">
        <h2 class="wc26-section__title">🏟️ Sân vận động</h2>
        <p class="wc26-section__desc">16 sân vận động tại 3 quốc gia</p>

        <div class="wc26-venues__grid">
            @php
            $venues = [
                ['name' => 'MetLife Stadium', 'city' => 'New York/New Jersey', 'country' => '🇺🇸', 'capacity' => '82,500', 'note' => 'Trận chung kết'],
                ['name' => 'AT&T Stadium', 'city' => 'Dallas', 'country' => '🇺🇸', 'capacity' => '80,000', 'note' => 'Bán kết'],
                ['name' => 'SoFi Stadium', 'city' => 'Los Angeles', 'country' => '🇺🇸', 'capacity' => '70,000', 'note' => 'Bán kết'],
                ['name' => 'Estadio Azteca', 'city' => 'Mexico City', 'country' => '🇲🇽', 'capacity' => '87,000', 'note' => 'Trận khai mạc'],
                ['name' => 'BMO Field', 'city' => 'Toronto', 'country' => '🇨🇦', 'capacity' => '45,000', 'note' => ''],
                ['name' => 'Hard Rock Stadium', 'city' => 'Miami', 'country' => '🇺🇸', 'capacity' => '65,000', 'note' => 'Tứ kết'],
                ['name' => 'Lumen Field', 'city' => 'Seattle', 'country' => '🇺🇸', 'capacity' => '69,000', 'note' => ''],
                ['name' => 'NRG Stadium', 'city' => 'Houston', 'country' => '🇺🇸', 'capacity' => '72,000', 'note' => ''],
            ];
            @endphp

            @foreach($venues as $venue)
            <div class="wc26-venue">
                <div class="wc26-venue__info">
                    <h3 class="wc26-venue__name">{{ $venue['name'] }}</h3>
                    <p class="wc26-venue__city">{{ $venue['country'] }} {{ $venue['city'] }}</p>
                    <span class="wc26-venue__capacity">👥 {{ $venue['capacity'] }}</span>
                    @if($venue['note'])
                    <span class="wc26-venue__note">⭐ {{ $venue['note'] }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
