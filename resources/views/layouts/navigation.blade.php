<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <!-- Link Dashboard (Semua Role) -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- === KATEGORI 1: PORTAL TUGAS (Workflow Roles) === -->
                    @role('Staff TU|Kepala Sekolah|Guru')
                        <div class="hidden sm:flex sm:items-center sm:ms-10">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Portal Tugas</div>
                                        <div class="ms-1"><svg class="fill-current h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg></div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    @role('Staff TU')
                                        <x-dropdown-link :href="route('pengajuan.index')" :active="request()->routeIs('pengajuan.index')">
                                            {{ __('Pengajuan Surat') }}
                                        </x-dropdown-link>
                                    @endrole
                                    @role('Kepala Sekolah')
                                        <x-dropdown-link :href="route('persetujuan.index')" :active="request()->routeIs('persetujuan.index')">
                                            {{ __('Persetujuan SPT') }}
                                        </x-dropdown-link>
                                    @endrole
                                    @role('Guru')
                                        <x-dropdown-link :href="route('absensi-mandiri.index')" :active="request()->routeIs('absensi-mandiri.index')">
                                            {{ __('Absensi Tugas Saya') }}
                                        </x-dropdown-link>
                                    @endrole
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endrole

                    <!-- === KATEGORI 2: MANAJEMEN SELEKSI (Admin) === -->
                    @role('Admin')
                        <div class="hidden sm:flex sm:items-center sm:ms-10">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Manajemen Seleksi</div>
                                        <div class="ms-1"><svg class="fill-current h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg></div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('jadwal-seleksi.index')" :active="request()->routeIs('jadwal-seleksi.*')">
                                        {{ __('Jadwal Seleksi') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('tahun-pelajaran.index')" :active="request()->routeIs('tahun-pelajaran.*')">
                                        {{ __('Tahun Pelajaran') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endrole

                    <!-- === KATEGORI 3: DATA REFERENSI (Admin) === -->
                    @role('Admin')
                        <div class="hidden sm:flex sm:items-center sm:ms-10">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Data Referensi</div>
                                        <div class="ms-1"><svg class="fill-current h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg></div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('guru.index')" :active="request()->routeIs('guru.*')">
                                        {{ __('Data Guru') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('referensi-tugas.index')" :active="request()->routeIs('referensi-tugas.*')">
                                        {{ __('Referensi Tugas') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('referensi-akun-cbt.index')" :active="request()->routeIs('referensi-akun-cbt.*')">
                                        {{ __('Akun CBT') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endrole

                    <!-- === KATEGORI 4: BANK SOAL (Admin) === -->
                    @role('Admin')
                        <div class="hidden sm:flex sm:items-center sm:ms-10">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Bank Soal</div>
                                        <div class="ms-1"><svg class="fill-current h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg></div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('soal-buta-warna.index')" :active="request()->routeIs('soal-buta-warna.*')">
                                        Soal Buta Warna
                                    </x-dropdown-link>
                                    <div class="border-t border-gray-200"></div>
                                    <x-dropdown-link :href="route('tpa-grup-soal.index')" :active="request()->routeIs('tpa-grup-soal.*')">
                                        Grup Soal TPA
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('tpa-soal.index')" :active="request()->routeIs('tpa-soal.*')">
                                        Soal TPA
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endrole

                    <!-- === KATEGORI 5: LAPORAN (Admin & Kepsek) === -->
                    @role('Admin|Kepala Sekolah')
                        <div class="hidden sm:flex sm:items-center sm:ms-10">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Laporan</div>
                                        <div class="ms-1"><svg class="fill-current h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg></div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('laporan.peserta.index')" :active="request()->routeIs('laporan.peserta.index')">
                                        {{ __('Laporan Peserta') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('laporan.petugas.index')" :active="request()->routeIs('laporan.petugas.index')">
                                        {{ __('Laporan Petugas') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endrole

                    <!-- === KATEGORI 6: ADMINISTRASI (Admin) === -->
                    @role('Admin')
                        <div class="hidden sm:flex sm:items-center sm:ms-10">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Administrasi</div>
                                        <div class="ms-1"><svg class="fill-current h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg></div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                                        Manajemen User
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('roles.index')" :active="request()->routeIs('roles.*')">
                                        Manajemen Role
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endrole
                </div>
            </div>

            <!-- Settings Dropdown (User Profile) - (Sudah dihapus dark: class-nya) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (MOBILE VIEW - SUDAH DIRAPIKAN) -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Portal Tugas -->
            @role('Staff TU')
                <x-responsive-nav-link :href="route('pengajuan.index')" :active="request()->routeIs('pengajuan.index')">
                    {{ __('Pengajuan Surat') }}
                </x-responsive-nav-link>
            @endrole
            @role('Kepala Sekolah')
                <x-responsive-nav-link :href="route('persetujuan.index')" :active="request()->routeIs('persetujuan.index')">
                    {{ __('Persetujuan SPT') }}
                </x-responsive-nav-link>
            @endrole
            @role('Guru')
                <x-responsive-nav-link :href="route('absensi-mandiri.index')" :active="request()->routeIs('absensi-mandiri.index')">
                    {{ __('Absensi Tugas Saya') }}
                </x-responsive-nav-link>
            @endrole

            <!-- Laporan -->
            @role('Admin|Kepala Sekolah')
                <div class="pt-2 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">Laporan</div>
                    </div>
                    <x-responsive-nav-link :href="route('laporan.peserta.index')" :active="request()->routeIs('laporan.peserta.index')">
                        {{ __('Laporan Peserta') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('laporan.petugas.index')" :active="request()->routeIs('laporan.petugas.index')">
                        {{ __('Laporan Petugas') }}
                    </x-responsive-nav-link>
                </div>
            @endrole

            <!-- Menu Admin Lengkap -->
            @role('Admin')
                <div class="pt-2 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">Manajemen Seleksi</div>
                    </div>
                    <x-responsive-nav-link :href="route('jadwal-seleksi.index')" :active="request()->routeIs('jadwal-seleksi.*')">
                        {{ __('Jadwal Seleksi') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('tahun-pelajaran.index')" :active="request()->routeIs('tahun-pelajaran.*')">
                        {{ __('Tahun Pelajaran') }}
                    </x-responsive-nav-link>
                </div>

                <div class="pt-2 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">Data Referensi</div>
                    </div>
                    <x-responsive-nav-link :href="route('guru.index')" :active="request()->routeIs('guru.*')">
                        {{ __('Data Guru') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('referensi-tugas.index')" :active="request()->routeIs('referensi-tugas.*')">
                        {{ __('Referensi Tugas') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('referensi-akun-cbt.index')" :active="request()->routeIs('referensi-akun-cbt.*')">
                        {{ __('Akun CBT') }}
                    </x-responsive-nav-link>
                </div>

                <div class="pt-2 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">Bank Soal</div>
                    </div>
                    <x-responsive-nav-link :href="route('soal-buta-warna.index')" :active="request()->routeIs('soal-buta-warna.*')">
                        Soal Buta Warna
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('tpa-grup-soal.index')" :active="request()->routeIs('tpa-grup-soal.*')">
                        Grup Soal TPA
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('tpa-soal.index')" :active="request()->routeIs('tpa-soal.*')">
                        Soal TPA
                    </x-responsive-nav-link>
                </div>

                <div class="pt-2 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">Administrasi</div>
                    </div>
                    <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                        Manajemen User
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.*')">
                        Manajemen Role
                    </x-responsive-nav-link>
                </div>
            @endrole
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
