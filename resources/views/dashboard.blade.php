<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium">Filter Data</h3>
                    <form action="{{ route('dashboard') }}" method="GET">
                        <div class="flex items-end space-x-4">
                            <div>
                                <x-input-label for="filter_bulan" value="Pilih Bulan & Tahun" />
                                <select id="filter_bulan" name="filter_bulan"
                                    onchange="
                                            document.getElementById('input_bulan').value = this.value.split('-')[0];
                                            document.getElementById('input_tahun').value = this.value.split('-')[1];
                                        "
                                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full">

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

                            <x-primary-button type="submit">
                                Filter
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h3 class="text-lg font-medium">Rekap Pendaftaran Peserta (Per Minggu)</h3>

                    <div x-data="{
                        chart: null,
                        chartData: {{ json_encode($chartData) }},
                    
                        // Fungsi untuk inisialisasi
                        initChart() {
                            // HAPUS SEMUA LOGIC DARK MODE
                    
                            const options = {
                                chart: {
                                    type: 'bar',
                                    height: 350,
                                    toolbar: { show: true },
                                    zoom: { enabled: false }
                                },
                                series: [{
                                    name: 'Jumlah Peserta',
                                    data: this.chartData.data // Data dari controller
                                }],
                                xaxis: {
                                    categories: this.chartData.labels, // Label dari controller
                                    labels: {
                                        style: {
                                            colors: '#6B7280' // Paksa warna light
                                        }
                                    }
                                },
                                yaxis: {
                                    labels: {
                                        style: {
                                            colors: '#6B7280' // Paksa warna light
                                        }
                                    }
                                },
                                grid: {
                                    borderColor: '#E5E7EB', // Paksa warna light
                                },
                                plotOptions: {
                                    bar: {
                                        horizontal: false,
                                        borderRadius: 4,
                                        columnWidth: '50%',
                                    }
                                },
                                // PERMINTAAN #1: Paksa tema jadi LIGHT
                                theme: {
                                    mode: 'light'
                                }
                            };
                    
                            this.chart = new ApexCharts(this.$refs.chart, options);
                            this.chart.render();
                    
                            // HAPUS EVENT LISTENER DARK MODE
                        }
                    }" x-init="initChart()" class="mt-4">
                        <div x-ref="chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
