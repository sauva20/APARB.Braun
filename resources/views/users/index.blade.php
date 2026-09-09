@extends('layouts.app')

@section('title', 'Manajemen User - APAR Monitoring System')

@section('content')
<div class="space-y-6" x-data="{ 
    showModalUser: {{ old('form_type') == 'tambah_user' && $errors->any() ? 'true' : 'false' }},
    showEditUser: {{ old('form_type') == 'edit_user' && $errors->any() ? 'true' : 'false' }},
    editUser: { 
        id:'{{ old('form_type') == 'edit_user' ? old('id') : '' }}', 
        name:'{{ old('form_type') == 'edit_user' ? old('name') : '' }}',
        email:'{{ old('form_type') == 'edit_user' ? old('email') : '' }}',
        role:'{{ old('form_type') == 'edit_user' ? old('role') : '' }}'
    }
}">

    <!-- Header Area -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Left: Page Context -->
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white border border-slate-200/60 shadow-sm flex items-center justify-center text-[#009B77]">
                <i class="ph-bold ph-users text-xl"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800 leading-tight">Manajemen User</h2>
                <p class="text-xs font-semibold text-slate-500 mt-0.5">Kelola hak akses dan data pengguna</p>
            </div>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-3">
            <button @click="showModalUser = true" class="btn-smooth-ring bg-[#009B77] hover:bg-[#008264] text-white font-bold py-2.5 px-5 rounded-xl shadow-[0_4px_12px_rgba(0,155,119,0.25)] transition-all flex items-center gap-2 text-sm hover:-translate-y-0.5">
                <i class="ph-bold ph-plus text-lg"></i>
                <span>Tambah User</span>
            </button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200/60">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-16">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/60">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-semibold text-slate-600">
                            {{ $users->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-slate-800">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-teal-50 text-[#009B77] border border-teal-200/60">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button @click="showEditUser = true; editUser = { id: '{{ $user->id }}', name: '{{ addslashes($user->name) }}', email: '{{ addslashes($user->email) }}', role: '{{ addslashes($user->role) }}' }" class="p-2 text-slate-400 hover:text-[#009B77] hover:bg-teal-50 rounded-lg transition-colors">
                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                            </button>
                            <form action="/users/{{ $user->id }}" method="POST" class="inline-block" onsubmit="confirmDelete(event, 'User ini akan dihapus secara permanen!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                    <i class="ph-bold ph-trash text-lg"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 mb-4 bg-slate-50 rounded-full flex items-center justify-center text-slate-400">
                                    <i class="ph-fill ph-users text-3xl"></i>
                                </div>
                                <h3 class="text-sm font-bold text-slate-700">Belum ada data User</h3>
                                <p class="text-xs text-slate-500 mt-1">Silakan tambah data user baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-200/60 bg-slate-50/50">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Tambah User -->
    <div x-show="showModalUser" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showModalUser" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/40 backdrop-blur-sm" @click="showModalUser = false"></div>

            <div x-show="showModalUser" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Tambah User Baru</h3>
                        <p class="text-xs font-semibold text-slate-500 mt-0.5">Masukkan data pengguna baru</p>
                    </div>
                    <button @click="showModalUser = false" class="text-slate-400 hover:text-slate-600 transition-colors p-2 hover:bg-slate-100 rounded-lg">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                </div>

                <form action="/users" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="form_type" value="tambah_user">
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('form_type') == 'tambah_user' ? old('name') : '' }}" required class="w-full bg-slate-50 border border-slate-200/60 text-slate-800 text-sm rounded-xl focus:ring-[#009B77] focus:border-[#009B77] block p-2.5 transition-colors font-medium">
                        @if(old('form_type') == 'tambah_user') @error('name') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror @endif
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('form_type') == 'tambah_user' ? old('email') : '' }}" required class="w-full bg-slate-50 border border-slate-200/60 text-slate-800 text-sm rounded-xl focus:ring-[#009B77] focus:border-[#009B77] block p-2.5 transition-colors font-medium">
                        @if(old('form_type') == 'tambah_user') @error('email') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror @endif
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Role / Jabatan <span class="text-red-500">*</span></label>
                        <input type="text" name="role" value="{{ old('form_type') == 'tambah_user' ? old('role') : '' }}" required placeholder="Contoh: EHSS Staff" class="w-full bg-slate-50 border border-slate-200/60 text-slate-800 text-sm rounded-xl focus:ring-[#009B77] focus:border-[#009B77] block p-2.5 transition-colors font-medium">
                        @if(old('form_type') == 'tambah_user') @error('role') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror @endif
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">PIN Inspeksi <span class="text-red-500">*</span></label>
                        <input type="text" name="pin" value="{{ old('form_type') == 'tambah_user' ? old('pin') : '' }}" required minlength="4" maxlength="6" pattern="[0-9]*" inputmode="numeric" placeholder="4-6 digit angka" class="w-full bg-slate-50 border border-slate-200/60 text-slate-800 text-sm rounded-xl focus:ring-[#009B77] focus:border-[#009B77] block p-2.5 transition-colors font-medium">
                        <p class="text-xs text-slate-500 mt-1">Digunakan sebagai autentikasi saat melakukan inspeksi APAR.</p>
                        @if(old('form_type') == 'tambah_user') @error('pin') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror @endif
                    </div>

                    <div class="mt-6 flex gap-3 justify-end pt-4 border-t border-slate-100">
                        <button type="button" @click="showModalUser = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-200/60 hover:bg-slate-50 hover:text-slate-900 rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-[#009B77] hover:bg-[#008264] rounded-xl shadow-[0_4px_12px_rgba(0,155,119,0.25)] transition-all hover:-translate-y-0.5">
                            Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div x-show="showEditUser" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showEditUser" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/40 backdrop-blur-sm" @click="showEditUser = false"></div>

            <div x-show="showEditUser" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Edit User</h3>
                        <p class="text-xs font-semibold text-slate-500 mt-0.5">Ubah data pengguna</p>
                    </div>
                    <button @click="showEditUser = false" class="text-slate-400 hover:text-slate-600 transition-colors p-2 hover:bg-slate-100 rounded-lg">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                </div>

                <form :action="`/users/${editUser.id}`" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="form_type" value="edit_user">
                    <input type="hidden" name="id" x-model="editUser.id">
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" x-model="editUser.name" required class="w-full bg-slate-50 border border-slate-200/60 text-slate-800 text-sm rounded-xl focus:ring-[#009B77] focus:border-[#009B77] block p-2.5 transition-colors font-medium">
                        @if(old('form_type') == 'edit_user') @error('name') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror @endif
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" x-model="editUser.email" required class="w-full bg-slate-50 border border-slate-200/60 text-slate-800 text-sm rounded-xl focus:ring-[#009B77] focus:border-[#009B77] block p-2.5 transition-colors font-medium">
                        @if(old('form_type') == 'edit_user') @error('email') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror @endif
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Role / Jabatan <span class="text-red-500">*</span></label>
                        <input type="text" name="role" x-model="editUser.role" required class="w-full bg-slate-50 border border-slate-200/60 text-slate-800 text-sm rounded-xl focus:ring-[#009B77] focus:border-[#009B77] block p-2.5 transition-colors font-medium">
                        @if(old('form_type') == 'edit_user') @error('role') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror @endif
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">PIN Inspeksi Baru</label>
                        <input type="text" name="pin" minlength="4" maxlength="6" pattern="[0-9]*" inputmode="numeric" placeholder="Biarkan kosong jika tidak ingin mengubah PIN" class="w-full bg-slate-50 border border-slate-200/60 text-slate-800 text-sm rounded-xl focus:ring-[#009B77] focus:border-[#009B77] block p-2.5 transition-colors font-medium">
                        @if(old('form_type') == 'edit_user') @error('pin') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror @endif
                    </div>

                    <div class="mt-6 flex gap-3 justify-end pt-4 border-t border-slate-100">
                        <button type="button" @click="showEditUser = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-200/60 hover:bg-slate-50 hover:text-slate-900 rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-[#009B77] hover:bg-[#008264] rounded-xl shadow-[0_4px_12px_rgba(0,155,119,0.25)] transition-all hover:-translate-y-0.5">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
