<x-app-layout>
    <x-slot name="header">
        <!-- Hapus dark:text-gray-200 -->
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- === STAT CARDS (BARU) === -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Card 1: Total Peserta -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white"
                                    xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-1.657-.672-3.157-1.757-4.243M12 15v5m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Peserta Seleksi</dt>
                                <dd class="text-3xl font-bold text-gray-900">{{ $stats['total_peserta'] }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card 2: Total Jadwal -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white"
                                    xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 truncate">Jadwal Seleksi Terbit</dt>
                                <dd class="text-3xl font-bold text-gray-900">{{ $stats['total_jadwal_terbit'] }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card 3: Total Petugas/Guru -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white"
                                    xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Guru/Petugas (Aktif)</dt>
                                <dd class="text-3xl font-bold text-gray-900">{{ $stats['total_guru_aktif'] }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- === END STAT CARDS === -->

            <!-- === LAYOUT BARU (Filter + Grafik) === -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- KOLOM 1: FILTER -->
                <div class="lg:col-span-1">
                    <!-- Hapus dark:bg-gray-800 dan dark:text-gray-100 -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium">Filter Grafik</h3>
                            <form action="{{ route('dashboard') }}" method="GET" class="mt-4">
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="filter_bulan" value="Pilih Bulan & Tahun" />
                                        <!-- Hapus dark: class dari select -->
                                        <select id="filter_bulan" name="filter_bulan"
                                            onchange="
                                                document.getElementById('input_bulan').value = this.value.split('-')[0];
                                                document.getElementById('input_tahun').value = this.value.split('-')[1];
                                            "
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">

                                            @foreach ($filterBulan as $filter)
                                                <option value="{{ $filter['bulan'] }}-{{ $filter['tahun'] }}"
                                                    {{ $filter['bulan'] == $selectedMonth && $filter['tahun'] == $selectedYear ? 'selected' : '' }}>
                                                    {{ $filter['nama'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <input type="hidden" id="input_bulan" name="bulan" value="{{ $selectedMonth }}">
                                    <input type="hidden" id="input_tahun" name="tahun" value="{{ $selectedYear }}">

                                    <x-primary-button type="submit" class="w-full justify-center">
                                        Filter Grafik
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- KOLOM 2: GRAFIK -->
                <div class="lg:col-span-2">
                    <!-- Hapus dark:bg-gray-800 dan dark:text-gray-100 -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium">Rekap Pendaftaran Peserta (Per Minggu)</h3>

                            <!-- Logic Chart (sudah diset 'light' jadi tidak perlu diubah) -->
                            <div x-data="{
                                chart: null,
                                chartData: {{ json_encode($chartData) }},
                                initChart() {
                                    const options = {
                                        chart: {
                                            type: 'bar',
                                            height: 350,
                                            toolbar: { show: true },
                                            zoom: { enabled: false }
                                        },
                                        series: [{
                                            name: 'Jumlah Peserta',
                                            data: this.chartData.data
                                        }],
                                        xaxis: {
                                            categories: this.chartData.labels,
                                            labels: {
                                                style: { colors: '#6B7280' }
                                            }
                                        },
                                        yaxis: {
                                            labels: {
                                                style: { colors: '#6B7280' }
                                            }
                                        },
                                        grid: {
                                            borderColor: '#E5E7EB',
                                        },
                                        plotOptions: {
                                            bar: {
                                                horizontal: false,
                                                borderRadius: 4,
                                                columnWidth: '50%',
                                            }
                                        },
                                        theme: {
                                            mode: 'light'
                                        }
                                    };
                                    this.chart = new ApexCharts(this.$refs.chart, options);
                                    this.chart.render();
                                }
                            }" x-init="initChart()" class="mt-4">
                                <div x-ref="chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
