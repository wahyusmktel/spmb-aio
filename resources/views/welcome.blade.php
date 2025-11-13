<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased bg-white bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px]">

    <div class="bg-transparent" x-data="{ mobileMenuOpen: false }">
        <header class="absolute inset-x-0 top-0 z-50">
            <nav aria-label="Global" class="flex items-center justify-between p-6 lg:px-8">
                <div class="flex lg:flex-1">
                    <a href="#" class="-m-1.5 p-1.5">
                        <span class="sr-only">Your Company</span>
                        <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=red&shade=600"
                            alt="" class="h-8 w-auto" />
                    </a>
                </div>
                <div class="flex lg:hidden">
                    <button type="button" @click="mobileMenuOpen = true"
                        class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                        <span class="sr-only">Open main menu</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            data-slot="icon" aria-hidden="true" class="size-6">
                            <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
                <div class="hidden lg:flex lg:gap-x-12">
                    <a href="#" class="text-sm/6 font-semibold text-gray-900">Tentang Kami</a>
                    <a href="#" class="text-sm/6 font-semibold text-gray-900">Program</a>
                    <a href="#alur-seleksi" class="text-sm/6 font-semibold text-gray-900">Alur Seleksi</a>
                </div>
                <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="text-sm/6 font-semibold text-gray-900">Login Admin <span
                                aria-hidden="true">&rarr;</span></a>
                    @endif
                    @if (Route::has('peserta.login'))
                        <a href="{{ route('peserta.login') }}" class="ml-6 text-sm/6 font-semibold text-gray-900">Login
                            Peserta <span aria-hidden="true">&rarr;</span></a>
                    @endif
                </div>
            </nav>

            <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="lg:hidden" role="dialog" aria-modal="true">

                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div class="fixed inset-0 z-50 overflow-y-auto">
                    <div class="flex min-h-full items-stretch justify-end text-center">
                        <div @click.away="mobileMenuOpen = false" x-show="mobileMenuOpen"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                            class="relative w-full max-w-sm overflow-y-auto bg-white p-6 shadow-xl">

                            <div class="flex items-center justify-between">
                                <a href="#" class="-m-1.5 p-1.5">
                                    <span class="sr-only">Your Company</span>
                                    <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=red&shade=600"
                                        alt="" class="h-8 w-auto" />
                                </a>
                                <button type="button" @click="mobileMenuOpen = false"
                                    class="-m-2.5 rounded-md p-2.5 text-gray-700">
                                    <span class="sr-only">Close menu</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        data-slot="icon" aria-hidden="true" class="size-6">
                                        <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-6 flow-root">
                                <div class="-my-6 divide-y divide-gray-500/10">
                                    <div class="space-y-2 py-6">
                                        <a href="#"
                                            class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Tentang
                                            Kami</a>
                                        <a href="#"
                                            class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Program</a>
                                        <a href="#alur-seleksi"
                                            class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Alur
                                            Seleksi</a>
                                    </div>
                                    <div class="py-6">
                                        <a href="{{ route('login') }}"
                                            class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Login
                                            Admin</a>
                                        <a href="{{ route('peserta.login') }}"
                                            class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Login
                                            Peserta</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="relative isolate px-6 pt-14 lg:px-8">
            <div aria-hidden="true"
                class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
                <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"
                    class="relative left-[calc(50%-11rem)] aspect-1155/678 w-144.5 -translate-x-1/2 rotate-30 bg-gradient-to-tr from-red-500 to-orange-400 opacity-20 sm:left-[calc(50%-30rem)] sm:w-288.75">
                </div>
            </div>

            <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                <div class="hidden sm:mb-8 sm:flex sm:justify-center">
                    <div
                        class="relative rounded-full px-3 py-1 text-sm/6 text-gray-600 ring-1 ring-gray-900/10 hover:ring-gray-900/20">
                        Sistem Informasi Seleksi Penerimaan Murid Baru. <a href="#"
                            class="font-semibold text-red-600"><span aria-hidden="true"
                                class="absolute inset-0"></span>Read more <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>
                <div class="text-center">
                    <h1 class="text-5xl font-semibold tracking-tight text-balance text-gray-900 sm:text-7xl">Selamat
                        Datang di Portal SPMB</h1>
                    <p class="mt-8 text-lg font-medium text-pretty text-gray-600 sm:text-xl/8">Silakan login sesuai
                        dengan hak akses Anda. Peserta seleksi dapat login melalui tombol "Login Peserta" untuk memulai
                        tes.</p>
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <a href="{{ route('peserta.login') }}"
                            class="rounded-md bg-red-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-red-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Login
                            Peserta</a>
                        <a href="{{ route('login') }}" class="text-sm/6 font-semibold text-gray-900">Login
                            Admin/Petugas <span aria-hidden="true">→</span></a>
                    </div>
                </div>
            </div>

            <div aria-hidden="true"
                class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]">
                <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"
                    class="relative left-[calc(50%+3rem)] aspect-1155/678 w-144.5 -translate-x-1/2 bg-gradient-to-tr from-red-500 to-orange-400 opacity-20 sm:left-[calc(50%+36rem)] sm:w-288.75">
                </div>
            </div>
        </div>

        <div id="alur-seleksi" class="py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:text-center">
                    <h2 class="text-base font-semibold leading-7 text-red-600">Alur Seleksi</h2>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        Langkah-Langkah Mengikuti Seleksi
                    </p>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        Proses seleksi kami terdiri dari 5 tahapan utama yang wajib dilalui oleh setiap calon peserta
                        untuk menyelesaikan pendaftaran.
                    </p>
                </div>

                <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
                    <ol class="relative border-l border-gray-200">

                        <li class="mb-10 ms-6">
                            <span
                                class="absolute flex items-center justify-center w-8 h-8 bg-red-100 rounded-full -start-4 ring-8 ring-white">
                                <svg class="w-4 h-4 text-red-600" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM10 15a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 15zM10 8.5a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 8.5zM6.03 5.03a.75.75 0 010 1.06l-1.06 1.06a.75.75 0 11-1.06-1.06l1.06-1.06a.75.75 0 011.06 0zM16.09 14.97a.75.75 0 010 1.06l-1.06 1.06a.75.75 0 01-1.06-1.06l1.06-1.06a.75.75 0 011.06 0zM14.97 6.09a.75.75 0 011.06 0l1.06 1.06a.75.75 0 11-1.06 1.06l-1.06-1.06a.75.75 0 010-1.06zM5.03 13.91a.75.75 0 011.06 0l1.06 1.06a.75.75 0 11-1.06 1.06l-1.06-1.06a.75.75 0 010-1.06zM2 10a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5A.75.75 0 012 10zM15 10a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5A.75.75 0 0115 10z" />
                                </svg>
                            </span>
                            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">Tes Potensi Akademik
                                (TPA) <span
                                    class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded ms-3">Online</span>
                            </h3>
                            <p class="text-base font-normal text-gray-500">Peserta mengerjakan soal TPA (Logika,
                                Verbal, Matematika) secara online melalui portal.</p>
                        </li>

                        <li class="mb-10 ms-6">
                            <span
                                class="absolute flex items-center justify-center w-8 h-8 bg-red-100 rounded-full -start-4 ring-8 ring-white">
                                <svg class="w-4 h-4 text-red-600" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"></path>
                                    <path fill-rule="evenodd"
                                        d="M.458 10C3.732 4.943 9.522 3 10 3s6.268 1.943 9.542 7c-3.274 5.057-9.062 7-9.542 7S3.732 15.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">Tes Buta Warna <span
                                    class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded ms-3">Online</span>
                            </h3>
                            <p class="text-base font-normal text-gray-500">Peserta mengerjakan tes Ishihara (menebak
                                angka) secara online.</p>
                        </li>

                        <li class="mb-10 ms-6">
                            <span
                                class="absolute flex items-center justify-center w-8 h-8 bg-red-100 rounded-full -start-4 ring-8 ring-white">
                                <svg class="w-4 h-4 text-red-600" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">Tes Wawancara
                                Peserta <span
                                    class="bg-gray-100 text-gray-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded ms-3">Manual/Offline</span>
                            </h3>
                            <p class="text-base font-normal text-gray-500">Wawancara tatap muka antara panitia dan
                                calon peserta untuk menilai kesiapan dan motivasi.</p>
                        </li>

                        <li class="mb-10 ms-6">
                            <span
                                class="absolute flex items-center justify-center w-8 h-8 bg-red-100 rounded-full -start-4 ring-8 ring-white">
                                <svg class="w-4 h-4 text-red-600" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 2a.75.75 0 01.75.75v.51a4.5 4.5 0 014.237 5.14 4.493 4.493 0 01-1.03 2.507A5.54 5.54 0 0110.75 14v.266a.75.75 0 01-1.5 0v-.266a5.54 5.54 0 01-3.207-2.836 4.493 4.493 0 01-1.03-2.507A4.5 4.5 0 019.25 3.26V2.75A.75.75 0 0110 2zM8.5 6a.75.75 0 00-1.5 0v1.5a.75.75 0 001.5 0V6zm3 0a.75.75 0 00-1.5 0v1.5a.75.75 0 001.5 0V6zM5 10a.75.75 0 01.75-.75h8.5a.75.75 0 010 1.5h-8.5A.75.75 0 015 10zM5.04 15.17A.75.75 0 016 14.75h8a.75.75 0 01.96.42 6.51 6.51 0 00-9.92 0z">
                                    </path>
                                </svg>
                            </span>
                            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">Tes Membaca
                                Al-Qur'an <span
                                    class="bg-gray-100 text-gray-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded ms-3">Manual/Offline</span>
                            </h3>
                            <p class="text-base font-normal text-gray-500">Tes kemampuan membaca Al-Qur'an (bagi yang
                                muslim) oleh penguji.</p>
                        </li>

                        <li class="ms-6">
                            <span
                                class="absolute flex items-center justify-center w-8 h-8 bg-red-100 rounded-full -start-4 ring-8 ring-white">
                                <svg class="w-4 h-4 text-red-600" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.126-2.095a1.23 1.23 0 00.41-1.412A9.982 9.982 0 0010 12.75a9.982 9.982 0 00-6.535 1.743z">
                                    </path>
                                </svg>
                            </span>
                            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">Wawancara Orang Tua
                                <span
                                    class="bg-gray-100 text-gray-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded ms-3">Manual/Offline</span>
                            </h3>
                            <p class="text-base font-normal text-gray-500">Wawancara tatap muka antara panitia dan
                                orang tua/wali calon peserta.</p>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
