@extends('layouts.app')

@section('title', 'Data Penumpang & Layanan — NordicRail')

@section('content')
<div class="bg-[#FCFBF8] min-h-screen py-10" 
     x-data="{
        passengerCount: {{ $passengerCount }},
        basePriceTotal: {{ $schedule->base_price * $passengerCount }},
        serviceFee: 10000,
        addonMeal: false,
        mealPricePerPax: 45000,
        promoCode: '',
        appliedPromo: '',
        discountAmount: 0,
        promoMessage: '',
        promoError: '',
        isCheckingPromo: false,

        get addonTotal() {
            return this.addonMeal ? (this.mealPricePerPax * this.passengerCount) : 0;
        },
        get subtotal() {
            return this.basePriceTotal + this.serviceFee + this.addonTotal;
        },
        get totalPrice() {
            return Math.max(0, this.subtotal - this.discountAmount);
        },
        formatRupiah(val) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
        },
        applyPromo() {
            if (!this.promoCode.trim()) return;
            this.isCheckingPromo = true;
            this.promoMessage = '';
            this.promoError = '';

            fetch('{{ route('promotions.validate') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: this.promoCode,
                    amount: this.subtotal
                })
            })
            .then(res => res.json())
            .then(data => {
                this.isCheckingPromo = false;
                if (data.valid) {
                    this.discountAmount = data.discount;
                    this.appliedPromo = data.code;
                    this.promoMessage = data.message;
                } else {
                    this.discountAmount = 0;
                    this.appliedPromo = '';
                    this.promoError = data.message || 'Kode promo tidak dapat digunakan.';
                }
            })
            .catch(() => {
                this.isCheckingPromo = false;
                this.promoError = 'Gagal memverifikasi kode promo. Silakan periksa koneksi.';
            });
        },
        removePromo() {
            this.discountAmount = 0;
            this.appliedPromo = '';
            this.promoCode = '';
            this.promoMessage = '';
            this.promoError = '';
        }
     }">

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Step Progress Header -->
        <div class="mb-10 text-center">
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#121212]">
                Informasi Penumpang & Preferensi
            </h1>
            <p class="text-sm text-[#5E5C56] mt-2">Lengkapi data identitas penumpang sesuai kartu tanda pengenal resmi.</p>
        </div>

        <!-- Trip Summary Strip -->
        <div class="bg-white border border-[#DAD6CD] rounded-2xl p-6 mb-8 shadow-xs flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <span class="font-serif text-xl font-bold text-[#121212]">{{ $schedule->train->name ?? 'Kereta Api' }}</span>
                    <span class="text-xs px-2.5 py-1 rounded bg-[#F5F2EA] border border-[#DAD6CD] text-[#5E5C56] font-medium">
                        {{ $schedule->train->number }}
                    </span>
                    <span class="text-sm text-[#5E5C56]">
                        Kelas {{ ucfirst($schedule->class_type) }}
                    </span>
                </div>
                <p class="text-xs text-[#5E5C56] mt-1">
                    {{ \Carbon\Carbon::parse($schedule->travel_date)->translatedFormat('l, d F Y') }} &bull; Kursi: 
                    @foreach($seats as $seat)
                        <strong class="font-mono text-[#121212]">{{ $seat->seat_number }}</strong>{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </p>
            </div>

            <div class="text-sm font-medium text-[#121212]">
                <span>{{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }} {{ $schedule->originStation->city }}</span>
                <span class="text-[#8B887F] mx-2">&rarr;</span>
                <span>{{ \Carbon\Carbon::parse($schedule->arrival_time)->format('H:i') }} {{ $schedule->destinationStation->city }}</span>
            </div>
        </div>

        <!-- Form Begin -->
        <form action="{{ route('booking.storePassengers', $schedule->id) }}" method="POST">
            @csrf

            <!-- Hidden Add-on & Promo inputs that will be submitted -->
            <input type="hidden" name="addon_meal" :value="addonMeal ? 1 : 0">
            <input type="hidden" name="promo_code" :value="appliedPromo">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left: Passenger Inputs & Add-on Selection -->
                <div class="lg:col-span-8 space-y-6">
                    
                    <!-- Passenger Cards -->
                    @for($i = 0; $i < $passengerCount; $i++)
                        <div class="bg-white rounded-2xl border border-[#DAD6CD] p-6 sm:p-8 shadow-xs">
                            <div class="flex items-center justify-between pb-4 mb-6 border-b border-[#EFECE3]">
                                <div>
                                    <h2 class="font-serif text-lg font-bold text-[#121212]">Penumpang {{ $i + 1 }}</h2>
                                </div>
                                <span class="font-mono text-xs font-bold px-3 py-1 bg-[#F5F2EA] border border-[#DAD6CD] rounded-lg text-[#121212]">
                                    Nomor Kursi: {{ $seats[$i]->seat_number }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-[#121212] mb-1.5">
                                        Nama Lengkap (Sesuai Kartu Identitas)
                                    </label>
                                    <input type="text" 
                                           name="passengers[{{ $i }}][name]" 
                                           value="{{ old("passengers.{$i}.name", ($i === 0 ? auth()->user()->name : '')) }}" 
                                           required 
                                           class="w-full rounded-xl border border-[#DAD6CD] bg-[#FCFBF8] py-2.5 px-3 text-sm text-[#121212] focus:bg-white focus:ring-1 focus:ring-[#121212]">
                                    @error("passengers.{$i}.name")
                                         <p class="text-xs text-rose-700 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-[#121212] mb-1.5">
                                        Jenis Identitas
                                    </label>
                                    <select name="passengers[{{ $i }}][id_type]" class="w-full rounded-xl border border-[#DAD6CD] bg-[#FCFBF8] py-2.5 px-3 text-sm text-[#121212] focus:bg-white focus:ring-1 focus:ring-[#121212]">
                                        <option value="KTP" selected>KTP (NIK)</option>
                                        <option value="Paspor">Paspor</option>
                                        <option value="SIM">SIM</option>
                                        <option value="KIA">KIA / Akta Lahir</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-[#121212] mb-1.5">
                                        Nomor Identitas
                                    </label>
                                    <input type="text" 
                                           name="passengers[{{ $i }}][identity_number]" 
                                           value="{{ old("passengers.{$i}.identity_number") }}" 
                                           placeholder="16 digit NIK atau nomor paspor"
                                           required 
                                           class="w-full rounded-xl border border-[#DAD6CD] bg-[#FCFBF8] py-2.5 px-3 text-sm text-[#121212] focus:bg-white focus:ring-1 focus:ring-[#121212]">
                                    @error("passengers.{$i}.identity_number")
                                         <p class="text-xs text-rose-700 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-[#121212] mb-1.5">
                                        Nomor Telepon
                                    </label>
                                    <input type="tel" 
                                           name="passengers[{{ $i }}][phone]" 
                                           value="{{ old("passengers.{$i}.phone", ($i === 0 ? auth()->user()->phone : '')) }}" 
                                           placeholder="08xxxxxxxxxx"
                                           required 
                                           class="w-full rounded-xl border border-[#DAD6CD] bg-[#FCFBF8] py-2.5 px-3 text-sm text-[#121212] focus:bg-white focus:ring-1 focus:ring-[#121212]">
                                    @error("passengers.{$i}.phone")
                                         <p class="text-xs text-rose-700 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-[#121212] mb-1.5">
                                        Alamat Email
                                    </label>
                                    <input type="email" 
                                           name="passengers[{{ $i }}][email]" 
                                           value="{{ old("passengers.{$i}.email", ($i === 0 ? auth()->user()->email : '')) }}" 
                                           required 
                                           class="w-full rounded-xl border border-[#DAD6CD] bg-[#FCFBF8] py-2.5 px-3 text-sm text-[#121212] focus:bg-white focus:ring-1 focus:ring-[#121212]">
                                    @error("passengers.{$i}.email")
                                         <p class="text-xs text-rose-700 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-[#121212] mb-1.5">
                                        Tipe Penumpang
                                    </label>
                                    <select name="passengers[{{ $i }}][passenger_type]" class="w-full rounded-xl border border-[#DAD6CD] bg-[#FCFBF8] py-2.5 px-3 text-sm text-[#121212] focus:bg-white focus:ring-1 focus:ring-[#121212]">
                                        <option value="dewasa" selected>Dewasa</option>
                                        <option value="anak">Anak-anak (&lt; 12 thn)</option>
                                        <option value="bayi">Bayi (&lt; 3 thn)</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    @endfor

                    <!-- Travel Add-ons (Nordic Dining Package) -->
                    <div class="bg-white rounded-2xl border border-[#DAD6CD] p-6 sm:p-8 shadow-xs">
                        <div class="flex items-start justify-between pb-4 mb-4 border-b border-[#EFECE3]">
                            <div>
                                <h2 class="font-serif text-lg font-bold text-[#121212]">Paket Kuliner On-Board (Nordic Dining)</h2>
                                <p class="text-xs text-[#5E5C56] mt-0.5">Layanan hidangan hangat selama perjalanan</p>
                            </div>
                            <span class="font-mono text-xs font-bold text-[#121212] bg-[#F5F2EA] px-2.5 py-1 rounded border border-[#DAD6CD]">
                                Rp 45.000 / pax
                            </span>
                        </div>

                        <p class="text-xs text-[#5E5C56] leading-relaxed mb-4">
                            Nikmati sajian hidangan hangat pilihan koki disajikan langsung di tempat duduk Anda lengkap dengan minuman artisan dan peralatan ramah lingkungan.
                        </p>

                        <label class="flex items-center gap-3 p-4 rounded-xl border border-[#DAD6CD] bg-[#FCFBF8] cursor-pointer hover:border-[#121212] transition">
                            <input type="checkbox" 
                                   x-model="addonMeal" 
                                   class="w-4 h-4 rounded text-[#121212] focus:ring-[#121212] border-[#DAD6CD]">
                            <div class="flex-1 text-xs">
                                <span class="font-bold text-[#121212] block">Tambahkan Paket Kuliner untuk {{ $passengerCount }} Penumpang</span>
                                <span class="text-[#8B887F]" x-text="addonMeal ? 'Diterapkan: + ' + formatRupiah(addonTotal) : 'Klik untuk menyertakan hidangan onboard'"></span>
                            </div>
                        </label>
                    </div>

                </div>

                <!-- Right: Price Calculator & Promo Code Section -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl border border-[#DAD6CD] p-6 shadow-xs sticky top-24 space-y-6">
                        
                        <div>
                            <div class="flex items-center justify-between pb-3 border-b border-[#EFECE3]">
                                <h3 class="font-serif text-lg font-bold text-[#121212]">
                                    Rincian Pembayaran
                                </h3>
                            </div>
                        </div>

                        <!-- Price Line Items -->
                        <div class="space-y-2.5 text-xs text-[#5E5C56]">
                            <div class="flex justify-between items-center">
                                <span>Tiket Dasar (x{{ $passengerCount }}):</span>
                                <span class="font-mono text-[#121212] font-semibold" x-text="formatRupiah(basePriceTotal)"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Biaya Layanan:</span>
                                <span class="font-mono text-[#121212]" x-text="formatRupiah(serviceFee)"></span>
                            </div>
                            <div class="flex justify-between items-center" x-show="addonMeal">
                                <span>Nordic Dining (x{{ $passengerCount }}):</span>
                                <span class="font-mono text-[#121212]" x-text="formatRupiah(addonTotal)"></span>
                            </div>
                            <div class="flex justify-between items-center text-emerald-800" x-show="discountAmount > 0">
                                <span>Potongan Kode Promo:</span>
                                <span class="font-mono font-bold" x-text="'- ' + formatRupiah(discountAmount)"></span>
                            </div>

                            <!-- Live Price Total -->
                            <div class="border-t border-[#EFECE3] pt-4 mt-4 flex justify-between items-baseline">
                                <span class="font-serif text-sm font-bold text-[#121212]">Total Tagihan</span>
                                <span class="font-mono text-2xl font-bold text-[#121212]" x-text="formatRupiah(totalPrice)"></span>
                            </div>
                        </div>

                        <!-- Promo Code Input Form -->
                        <div class="border-t border-[#EFECE3] pt-4">
                            <label class="block text-sm font-medium text-[#121212] mb-1.5">
                                Kode Promo / Voucher
                            </label>
                            
                            <div class="flex gap-2">
                                <input type="text" 
                                       x-model="promoCode" 
                                       :disabled="appliedPromo !== ''"
                                       placeholder="Cth: WEEKEND15"
                                       class="flex-1 rounded-xl border border-[#DAD6CD] bg-[#FCFBF8] uppercase font-mono text-xs py-2 px-3 focus:bg-white focus:ring-1 focus:ring-[#121212]">
                                
                                <button type="button" 
                                        @click="applyPromo()"
                                        x-show="appliedPromo === ''"
                                        :disabled="isCheckingPromo || !promoCode.trim()"
                                        class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-[#121212] text-white hover:bg-black transition disabled:opacity-50">
                                    <span x-show="!isCheckingPromo">Terapkan</span>
                                    <span x-show="isCheckingPromo" style="display: none;">...</span>
                                </button>

                                <button type="button" 
                                        @click="removePromo()"
                                        x-show="appliedPromo !== ''"
                                        style="display: none;"
                                        class="px-3 py-2 rounded-xl text-xs font-semibold bg-[#F5F2EA] text-rose-800 border border-[#DAD6CD] hover:bg-rose-50 transition">
                                    Hapus
                                </button>
                            </div>

                            <!-- Promo Feedback Messages -->
                            <p x-show="promoMessage" class="text-xs text-emerald-800 mt-2 font-medium" x-text="promoMessage" style="display: none;"></p>
                            <p x-show="promoError" class="text-xs text-rose-700 mt-2 font-medium" x-text="promoError" style="display: none;"></p>

                            <div class="mt-3 pt-2 text-xs text-[#8B887F]">
                                <a href="{{ route('promotions') }}" target="_blank" class="text-[#121212] underline font-medium">
                                    Lihat daftar kode promo aktif &rarr;
                                </a>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" 
                                    class="w-full py-3.5 px-4 rounded-xl text-sm font-semibold bg-[#121212] text-white hover:bg-black transition shadow-xs">
                                Lanjutkan ke Pembayaran &rarr;
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection
