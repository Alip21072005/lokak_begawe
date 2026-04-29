<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import {
    Search,
    User as UserIcon,
    CheckCircle2,
    Mail,
    Phone,
    Eye,
    Trash2,
    Users,
    ArrowRight,
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    auth: any;
    users: any[];
    filters: { search: string };
    stats: { total: number; active: number };
}>();

defineOptions({ layout: AppLayout });

// --- PENCARIAN REAL-TIME DENGAN DEBOUNCE SEDERHANA ---
const search = ref(props.filters.search);
let searchTimeout: ReturnType<typeof setTimeout>;

watch(search, (value) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            route('admin.kelolapelamar'),
            { search: value },
            { preserveState: true, replace: true },
        );
    }, 300);
});

// --- AKSI HAPUS ---
const confirmDelete = (id: string, name: string) => {
    Swal.fire({
        title: 'Hapus Permanen?',
        text: `Seluruh data pelamar ${name} akan dihapus selamanya!`,
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-[2.5rem]' },
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('admin.user.delete', id), {
                preserveScroll: true,
                onSuccess: () =>
                    Swal.fire({
                        title: 'Terhapus!',
                        text: 'Data pelamar berhasil dihapus.',
                        icon: 'success',
                        customClass: { popup: 'rounded-[2.5rem]' },
                    }),
            });
        }
    });
};
</script>

<template>
    <Head title="Kelola Pelamar - Admin" />

    <div class="min-h-screen space-y-8 bg-slate-50/50 p-6 lg:p-10">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl">
                    KELOLA <span class="text-sky-700">PELAMAR</span>
                </h1>
                <p class="mt-1 flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic">
                    <Users class="h-3 w-3" /> Manajemen data talenta Bengkulu
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative w-full sm:w-80">
                    <Search class="absolute top-1/2 left-4 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari nama, email, atau universitas..."
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-white pr-4 pl-12 text-xs font-bold outline-none transition-all focus:border-sky-700 focus:ring-4 focus:ring-sky-700/5"
                    />
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 text-left text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase italic">
                            <th class="px-8 py-6">Identitas Pelamar</th>
                            <th class="px-6 py-6">Kontak</th>
                            <th class="px-6 py-6 text-center">Lamaran</th>
                            <th class="px-6 py-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-if="users.length === 0">
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-300">
                                    <Users class="mb-4 h-12 w-12 opacity-20" />
                                    <p class="text-xs font-black uppercase italic tracking-widest">Data pelamar tidak ditemukan</p>
                                </div>
                            </td>
                        </tr>
                        <tr
                            v-for="user in users"
                            :key="user.id"
                            class="group transition-colors hover:bg-slate-50/50"
                        >
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-[12px] font-black text-sky-700 shadow-inner group-hover:bg-white group-hover:shadow-md transition-all">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div class="flex flex-col truncate">
                                        <span class="text-sm font-black text-slate-900 uppercase italic leading-tight">{{ user.name }}</span>
                                        <span class="mt-0.5 text-[9px] font-bold text-slate-400 uppercase tracking-wider truncate max-w-37.5">
                                            {{ user.university || 'Belum mengisi instansi' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-6">
                                <div class="flex flex-col gap-1.5">
                                    <span class="flex items-center gap-2 text-[10px] font-bold text-slate-600 uppercase italic">
                                        <Mail class="h-3.5 w-3.5 text-sky-700" />
                                        {{ user.email }}
                                    </span>
                                    <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic">
                                        <Phone class="h-3.5 w-3.5" />
                                        {{ user.phone || '-' }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-6 text-center">
                                <span class="inline-flex items-center gap-1.5 rounded-xl bg-slate-100 px-4 py-2 text-[10px] font-black text-slate-700 uppercase italic">
                                    {{ user.applicationsCount || 0 }} <span class="text-[8px] text-slate-400">Apply</span>
                                </span>
                            </td>

                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-3">
                                    <Link
                                        :href="route('detail.pelamar', user.id)"
                                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-700 transition-all hover:bg-sky-700 hover:text-white hover:shadow-lg hover:shadow-sky-700/20 active:scale-90"
                                        title="Lihat Detail Pelamar"
                                    >
                                        <Eye class="h-4 w-4" />
                                    </Link>
                                    
                                    <button
                                        @click="confirmDelete(user.id, user.name)"
                                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition-all hover:bg-rose-600 hover:text-white hover:shadow-lg hover:shadow-rose-600/20 active:scale-90"
                                        title="Hapus Pelamar"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="flex items-center gap-6 rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                <div class="flex h-16 w-16 items-center justify-center rounded-[1.25rem] bg-sky-50 text-sky-700 shadow-inner">
                    <UserIcon class="h-8 w-8" />
                </div>
                <div>
                    <h4 class="text-[10px] font-black tracking-widest text-slate-400 uppercase italic">
                        Pelamar Terdaftar
                    </h4>
                    <p class="mt-1 text-3xl font-black text-slate-900">
                        {{ stats.total }}
                        <span class="ml-1 text-xs font-bold text-slate-300 uppercase italic">Talenta</span>
                    </p>
                </div>
            </div>

            <div class="group relative flex items-center gap-6 overflow-hidden rounded-[2.5rem] bg-slate-900 p-8 text-white shadow-xl shadow-slate-900/10 transition-all hover:-translate-y-1">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white/5 transition-transform group-hover:scale-150"></div>
                <div class="flex h-16 w-16 items-center justify-center rounded-[1.25rem] bg-white/10 text-emerald-400">
                    <CheckCircle2 class="h-8 w-8" />
                </div>
                <div class="relative z-10">
                    <h4 class="text-[10px] font-black tracking-widest uppercase italic opacity-60">
                        Pelamar Baru Bulan Ini
                    </h4>
                    <p class="mt-1 text-3xl font-black">
                        {{ stats.active }}
                        <span class="ml-1 text-xs font-bold uppercase italic opacity-40">Baru</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Transisi halus */
.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom Scrollbar untuk tabel mobile */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}
.overflow-x-auto::-webkit-scrollbar-track {
    background: transparent;
}

</style>