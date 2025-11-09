<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg ...">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg ...">{{ session('error') }}</div>
            @endif

            <div class="mb-4 flex justify-end">
                <a href="{{ route('users.create') }}">
                    <x-primary-button>Tambah User Baru</x-primary-button>
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 ...">Nama</th>
                                    <th class="px-6 py-3 ...">Email</th>
                                    <th class="px-6 py-3 ...">Role</th>
                                    <th class="px-6 py-3 ...">Terhubung ke Guru</th>
                                    <th class="px-6 py-3 ...">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white ...">
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 ...">{{ $user->name }}</td>
                                        <td class="px-6 py-4 ...">{{ $user->email }}</td>
                                        <td class="px-6 py-4 ...">
                                            @foreach ($user->roles as $role)
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 ...">
                                            {{ $user->guru->nama_guru ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 ... text-right flex space-x-2">
                                            <a href="{{ route('users.edit', $user->id) }}">
                                                <x-secondary-button>Edit</x-secondary-button>
                                            </a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus user ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button type="submit">Hapus</x-danger-button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $users->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
