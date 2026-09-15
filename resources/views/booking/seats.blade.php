@extends('layouts.app')

@section('title', 'Pilih Kursi — NordicRail')

@section('content')
<div class="bg-[#FCFBF8] min-h-screen py-10" x-data="{
    selectedSeats: [],
    maxSeats: {{ $passengers }},
    pricePerSeat: {{ $schedule->base_price ?? 0 }},
    toggleSeat(id, number) {
        if (this.selectedSeats.find(s => s.id == id)) {
            this.selectedSeats = this.selectedSeats.filter(s => s.id != id);
        } else if (this.selectedSeats.length < this.maxSeats) {
            this.selectedSeats.push({id, number});
        }
    },
    isSelected(id) { 
        return this.selectedSeats.some(s => s.id == id); 
    },
    get totalPrice() {
        return this.selectedSeats.length * this.pricePerSeat;
    },
    formatPrice(price) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
    }
}">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Step Progress Header -->
        <div class="mb-10 text-center">
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#121212]">
                Pilih Kursi Kereta
            </h1>
            <p class="text-sm text-[#5E5C56] mt-2">Pilih kursi yang tersedia sesuai preferensi kenyamanan Anda.</p>
        </div>

        <!-- Trip Summary Header Card -->
        <div class="bg-white border border-[#DAD6CD] rounded-2xl p-6 mb-8 shadow-xs flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <span class="font-serif text-2xl font-bold text-[#121212]">{{ $schedule->train->name ?? 'Kereta Api' }}</span>
                    <span class="text-xs px-2.5 py-1 rounded bg-[#F5F2EA] border border-[#DAD6CD] text-[#5E5C56] font-medium">
                        {{ $schedule->train->number ?? '-' }}
                    </span>
                </div>
                <p class="text-sm text-[#5E5C56]">
                    Kelas {{ ucfirst($schedule->class_type) }} &bull; {{ \Carbon\Carbon::parse($schedule->travel_date)->translatedFormat('l, d F Y') }}
                </p>
            </div>

            <div class="flex items-center gap-8 text-center md:text-left">
                <div>
                    <span class="text-xs text-[#5E5C56] block">Keberangkatan</span>
                    <span class="text-xl font-bold text-[#121212]">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }}</span>
                    <span class="text-xs text-[#5E5C56] block font-medium">{{ $schedule->originStation->name }}</span>
                </div>
                <div class="text-[#8B887F] text-sm">&rarr;</div>
                <div>
                    <span class="text-xs text-[#5E5C56] block">Kedatangan</span>
                    <span class="text-xl font-bold text-[#121212]">{{ \Carbon\Carbon::parse($schedule->arrival_time)->format('H:i') }}</span>
                    <span class="text-xs text-[#5E5C56] block font-medium">{{ $schedule->destinationStation->name }}</span>
                </div>
            </div>
        </div>

        <!-- Main Layout: Seat Map & Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left: Seat Map Layout -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-2xl border border-[#DAD6CD] p-6 sm:p-8 shadow-xs">
                    
                    <!-- Seat Map Header & Legend -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-[#EFECE3] mb-8 gap-4">
                        <div>
                            <h2 class="font-serif text-xl font-bold text-[#121212]">Gerbong Eksekutif 1</h2>
                            <p class="text-xs text-[#5E5C56]">Pilih {{ $passengers }} kursi untuk melanjutkan pesanan.</p>
                        </div>

                        <!-- Legend -->
                        <div class="flex items-center gap-4 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-4 rounded border border-[#DAD6CD] bg-white"></span>
                                <span class="text-[#5E5C56]">Tersedia</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-4 rounded bg-[#121212]"></span>
                                <span class="text-[#121212] font-semibold">Dipilih</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-4 rounded bg-[#EFECE3] border border-[#DAD6CD]"></span>
                                <span class="text-[#8B887F]">Terisi</span>
                            </div>
                        </div>
                    </div>

                    <!-- Coach Cabin Layout -->
                    <div class="bg-[#F5F2EA] border border-[#DAD6CD] rounded-2xl p-6 sm:p-8 max-w-md mx-auto">
                        
                        <!-- Coach Direction Indicator -->
                        <div class="text-center pb-3 mb-4 border-b border-[#DAD6CD] text-xs text-[#5E5C56]">
                            Bagian Depan Gerbong
                        </div>

                        <!-- Column Letters Header -->
                        <div class="flex justify-between mb-4 px-2 text-xs font-semibold text-[#5E5C56]">
                            <div class="flex gap-3">
                                <div class="w-10 text-center">A</div>
                                <div class="w-10 text-center">B</div>
                            </div>
                            <div class="w-8 text-center text-[#8B887F] text-xs">Lorong</div>
                            <div class="flex gap-3">
                                <div class="w-10 text-center">C</div>
                                <div class="w-10 text-center">D</div>
                            </div>
                        </div>

                        <!-- Seats Rows -->
                        <div class="space-y-3">
                            @php
                                $groupedSeats = $seats->groupBy('seat_row');
                            @endphp

                            @foreach($groupedSeats as $row => $rowSeats)
                                <div class="flex justify-between items-center bg-white p-2 rounded-xl border border-[#DAD6CD]">
                                    <!-- A & B -->
                                    <div class="flex gap-3">
                                        @foreach(['A', 'B'] as $col)
                                            @php
                                                $seat = $rowSeats->where('seat_column', $col)->first();
                                                $isBooked = in_array($seat->id ?? 0, $bookedSeatIds);
                                            @endphp
                                            @if($seat)
                                                <button type="button"
                                                        @if(!$isBooked)
                                                            @click="toggleSeat({{ $seat->id }}, '{{ $seat->seat_number }}')"
                                                        @endif
                                                        :class="{
                                                            'bg-[#121212] text-white border-[#121212] font-bold': isSelected({{ $seat->id }}),
                                                            'border-[#DAD6CD] text-[#121212] hover:border-[#121212] bg-white': !isSelected({{ $seat->id }}) && !{{ $isBooked ? 'true' : 'false' }},
                                                            'bg-[#EFECE3] text-[#8B887F] border-[#EFECE3] cursor-not-allowed': {{ $isBooked ? 'true' : 'false' }}
                                                        }"
                                                        class="w-10 h-10 rounded-lg border text-xs font-mono transition flex items-center justify-center"
                                                        {{ $isBooked ? 'disabled' : '' }}>
                                                    {{ $seat->seat_number }}
                                                </button>
                                            @else
                                                <div class="w-10 h-10"></div>
                                            @endif
                                        @endforeach
                                    </div>

                                    <!-- Aisle Row Number -->
                                    <div class="w-8 text-center font-mono text-xs text-[#8B887F]">
                                        {{ $row }}
                                    </div>

                                    <!-- C & D -->
                                    <div class="flex gap-3">
                                        @foreach(['C', 'D'] as $col)
                                            @php
                                                $seat = $rowSeats->where('seat_column', $col)->first();
                                                $isBooked = in_array($seat->id ?? 0, $bookedSeatIds);
                                            @endphp
                                            @if($seat)
                                                <button type="button"
                                                        @if(!$isBooked)
                                                            @click="toggleSeat({{ $seat->id }}, '{{ $seat->seat_number }}')"
                                                        @endif
                                                        :class="{
                                                            'bg-[#121212] text-white border-[#121212] font-bold': isSelected({{ $seat->id }}),
                                                            'border-[#DAD6CD] text-[#121212] hover:border-[#121212] bg-white': !isSelected({{ $seat->id }}) && !{{ $isBooked ? 'true' : 'false' }},
                                                            'bg-[#EFECE3] text-[#8B887F] border-[#EFECE3] cursor-not-allowed': {{ $isBooked ? 'true' : 'false' }}
                                                        }"
                                                        class="w-10 h-10 rounded-lg border text-xs font-mono transition flex items-center justify-center"
                                                        {{ $isBooked ? 'disabled' : '' }}>
                                                    {{ $seat->seat_number }}
                                                </button>
                                            @else
                                                <div class="w-10 h-10"></div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Coach Rear Indicator -->
                        <div class="text-center pt-3 mt-4 border-t border-[#DAD6CD] text-xs text-[#5E5C56]">
                            Bagian Belakang Gerbong (Pintu Keluar)
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right: Selection Summary & Form Submission -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-2xl border border-[#DAD6CD] p-6 shadow-xs sticky top-24">
                    <h3 class="font-serif text-lg font-bold text-[#121212] pb-4 mb-4 border-b border-[#EFECE3]">
                        Ringkasan Pilihan
                    </h3>

                    <!-- Selected Seats Badges -->
                    <div class="mb-6">
                        <span class="text-xs font-medium text-[#5E5C56] block mb-2">Kursi Dipilih</span>
                        <div class="flex flex-wrap gap-2 min-h-[36px] items-center">
                            <template x-for="seat in selectedSeats" :key="seat.id">
                                <span class="px-3 py-1 rounded-lg bg-[#121212] text-white font-mono text-xs font-bold" x-text="seat.number"></span>
                            </template>
                            <p x-show="selectedSeats.length === 0" class="text-xs text-[#8B887F]">
                                Klik kursi pada denah gerbong.
                            </p>
                        </div>
                    </div>

                    <!-- Price Calculation -->
                    <div class="border-t border-[#EFECE3] pt-4 mb-6 space-y-2 text-xs text-[#5E5C56]">
                        <div class="flex justify-between items-center">
                            <span>Tarif Tiket:</span>
                            <span class="font-mono" x-text="`${selectedSeats.length} x ${formatPrice(pricePerSeat)}`"></span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-[#EFECE3] text-sm">
                            <span class="font-bold text-[#121212]">Subtotal:</span>
                            <span class="font-mono font-bold text-base text-[#121212]" x-text="formatPrice(totalPrice)"></span>
                        </div>
                    </div>

                    <!-- Submission Form (Proper 'seats[]' and 'passengers') -->
                    <form action="{{ route('booking.storeSeats', $schedule->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="passengers" value="{{ $passengers }}">
                        
                        <template x-for="seat in selectedSeats" :key="seat.id">
                            <input type="hidden" name="seats[]" :value="seat.id">
                        </template>

                        <button type="submit" 
                                class="w-full py-3.5 px-4 rounded-xl text-sm font-semibold text-white transition shadow-xs"
                                :class="selectedSeats.length === maxSeats ? 'bg-[#121212] hover:bg-black' : 'bg-[#DAD6CD] text-[#8B887F] cursor-not-allowed'"
                                :disabled="selectedSeats.length !== maxSeats">
                            Lanjutkan ke Data Penumpang &rarr;
                        </button>

                        <p x-show="selectedSeats.length !== maxSeats" class="text-xs text-center text-[#8B887F] mt-2">
                            Pilih <span class="font-bold text-[#121212]" x-text="maxSeats - selectedSeats.length"></span> kursi lagi untuk melanjutkan.
                        </p>
                    </form>

                    <div class="mt-6 pt-4 border-t border-[#EFECE3] text-center">
                        <a href="{{ route('search', ['origin' => $schedule->origin_station_id, 'destination' => $schedule->destination_station_id, 'date' => $schedule->travel_date->format('Y-m-d'), 'passengers' => $passengers]) }}" 
                           class="text-xs text-[#5E5C56] hover:text-[#121212] transition">
                            &larr; Ganti Jadwal Kereta
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
