<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import {
    Search,
    CheckCircle2,
    Eye,
    Trash2,
    ShieldCheck,
    AlertCircle,
    UserPlus,
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { ref, computed, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps<{
    auth: any;
    mitraPending: any[];
    mitraActive: any[];
    stats: any;
    filters: any;
}>();

const activeTab = ref('pending'); // 'pending' atau 'active'

// --- PENCARIAN ---
const search = ref(props.filters.search);
watch(search, (val) => {
    router.get(
        '/dashboard/admin/kelolamitra',
        { search: val },
        { preserveState: true, replace: true },
    );
});

// Pilih data mana yang tampil berdasarkan tab
const displayedMitra = computed(() => {
    return activeTab.value === 'pending'
        ? props.mitraPending
        : props.mitraActive;
});

// --- AKSI DENGAN POP-UP ---

const confirmVerify = (id: string, name: string) => {
    Swal.fire({
        title: 'Verifikasi Mitra?',
        text: `Setujui ${name} untuk mulai posting lowongan?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        confirmButtonText: 'Ya, Verifikasi!',
        customClass: { popup: 'rounded-[2.5rem]' },
    }).then((result) => {
        if (result.isConfirmed) {
            router.patch(
                `/dashboard/admin/mitra/${id}/status`,
                { status: 'verified' },
                {
                    onSuccess: () =>
                        Swal.fire({
                            title: 'Berhasil!',
                            icon: 'success',
                            customClass: { popup: 'rounded-[2.5rem]' },
                        }),
                },
            );
        }
    });
};

const confirmDelete = (id: string, name: string) => {
    Swal.fire({
        title: 'Hapus Mitra?',
        text: `Data ${name} dan seluruh lowongannya akan hilang!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        confirmButtonText: 'Ya, Hapus',
        customClass: { popup: 'rounded-[2.5rem]' },
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/dashboard/admin/hapus-akun-user/${id}`, {
                // Menggunakan route hapus user yang kita buat sebelumnya
                onSuccess: () =>
                    Swal.fire({
                        title: 'Terhapus!',
                        icon: 'success',
                        customClass: { popup: 'rounded-[2.5rem]' },
                    }),
            });
        }
    });
};
</script>

<template>
    <Head title="Kelola Mitra - Admin" />

    <div class="space-y-8 p-6 lg:p-10">
        <div
            class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <h1
                    class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl"
                >
                    KONTROL <span class="text-sky-700">MITRA</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
                <p
                    class="mt-4 text-[11px] font-bold tracking-widest text-slate-400 uppercase italic"
                >
                    Manajemen pendaftaran perusahaan dan verifikasi legalitas
                    mitra.
                </p>
            </div>

            <button
                class="flex items-center justify-center gap-2 rounded-2xl bg-sky-700 px-8 py-4 text-xs font-black text-white uppercase italic shadow-lg shadow-sky-900/20 transition-all hover:bg-sky-800 active:scale-95"
            >
                <UserPlus class="h-4 w-4" /> TAMBAH MITRA MANUAL
            </button>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
            <div
                class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm"
            >
                <p
                    class="text-[9px] font-black tracking-widest text-slate-400 uppercase italic"
                >
                    Total Mitra
                </p>
                <p class="text-2xl font-black text-slate-900 italic">
                    {{ stats.total }}
                    <span class="text-[10px] text-slate-300">Perusahaan</span>
                </p>
            </div>
            <div
                class="rounded-3xl border border-amber-100 bg-amber-50/50 p-6 shadow-sm"
            >
                <p
                    class="text-[9px] font-black tracking-widest text-amber-600 uppercase italic"
                >
                    Butuh Verifikasi
                </p>
                <p class="text-2xl font-black text-amber-700 italic">
                    {{ stats.pending }}
                    <span class="text-[10px]">Menunggu</span>
                </p>
            </div>
            
        </div>

        <div
            class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between"
        >
            <div class="flex w-fit gap-2 rounded-2xl bg-slate-100 p-1.5">
                <button
                    @click="activeTab = 'pending'"
                    :class="
                        activeTab === 'pending'
                            ? 'bg-white text-sky-700 shadow-sm'
                            : 'text-slate-500 hover:text-slate-900'
                    "
                    class="rounded-xl px-6 py-2.5 text-[10px] font-black uppercase italic transition-all"
                >
                    Menunggu Verifikasi ({{ mitraPending.length }})
                </button>
                <button
                    @click="activeTab = 'active'"
                    :class="
                        activeTab === 'active'
                            ? 'bg-white text-sky-700 shadow-sm'
                            : 'text-slate-500 hover:text-slate-900'
                    "
                    class="rounded-xl px-6 py-2.5 text-[10px] font-black uppercase italic transition-all"
                >
                    Daftar Mitra Aktif ({{ mitraActive.length }})
                </button>
            </div>

            <div class="relative w-full lg:w-80">
                <Search
                    class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400"
                />
                <input
                    v-model="search"
                    type="text"
                    placeholder="Cari nama perusahaan..."
                    class="h-12 w-full rounded-2xl border border-slate-200 bg-white pr-4 pl-10 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20"
                />
            </div>
        </div>

        <div
            class="rounded-[2.5rem] border border-slate-200 bg-white p-2 shadow-sm lg:p-6"
        >
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr
                            class="text-left text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase italic"
                        >
                            <th class="px-6 py-4">Informasi Mitra</th>
                            <th class="px-6 py-4">Bidang</th>
                            <th class="px-6 py-4">Tgl Daftar</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Kendali</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-if="displayedMitra.length === 0">
                            <td
                                colspan="5"
                                class="py-20 text-center text-[10px] font-black tracking-widest text-slate-300 uppercase italic"
                            >
                                Data tidak ditemukan
                            </td>
                        </tr>
                        <tr
                            v-for="mitra in displayedMitra"
                            :key="mitra.id"
                            class="group transition-colors hover:bg-slate-50/50"
                        >
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-100 bg-white font-black text-sky-700 uppercase shadow-sm transition-transform group-hover:scale-110"
                                    >
                                        {{ mitra.nama_mitra.charAt(0) }}
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm leading-tight font-black text-slate-900 uppercase"
                                        >
                                            {{ mitra.nama_mitra }}
                                        </p>
                                        <p
                                            class="text-[10px] font-bold text-slate-400 lowercase italic"
                                        >
                                            {{ mitra.email_mitra }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span
                                    class="rounded-lg bg-slate-100 px-3 py-1 text-[10px] font-black text-slate-500 uppercase italic"
                                >
                                    {{ mitra.kategori?.nama_kategori }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-5 text-[11px] font-bold text-slate-400 italic"
                            >
                                {{
                                    new Date(
                                        mitra.created_at,
                                    ).toLocaleDateString('id-ID', {
                                        day: 'numeric',
                                        month: 'short',
                                        year: 'numeric',
                                    })
                                }}
                            </td>
                            <td class="px-6 py-5">
                                <div
                                    v-if="mitra.status_mitra === 'pending'"
                                    class="flex items-center gap-2 text-amber-500"
                                >
                                    <AlertCircle class="h-4 w-4" />
                                    <span
                                        class="text-[10px] font-black uppercase italic"
                                        >Pending</span
                                    >
                                </div>
                                <div
                                    v-else
                                    class="flex items-center gap-2 text-emerald-500"
                                >
                                    <ShieldCheck class="h-4 w-4" />
                                    <span
                                        class="text-[10px] font-black uppercase italic"
                                        >Verified</span
                                    >
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div
                                    class="flex justify-end gap-2 opacity-0 transition-opacity group-hover:opacity-100"
                                >
                                    <button
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-400 shadow-sm transition-all hover:border-sky-700 hover:text-sky-700"
                                    >
                                        <Eye class="h-4 w-4" />
                                    </button>
                                    <button
                                        v-if="mitra.status_mitra === 'pending'"
                                        @click="
                                            confirmVerify(
                                                mitra.id,
                                                mitra.nama_mitra,
                                            )
                                        "
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-lg shadow-emerald-900/20 transition-all hover:bg-emerald-600"
                                    >
                                        <CheckCircle2 class="h-4 w-4" />
                                    </button>
                                    <button
                                        @click="
                                            confirmDelete(
                                                mitra.user_id,
                                                mitra.nama_mitra,
                                            )
                                        "
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-rose-100 bg-white text-rose-400 shadow-sm transition-all hover:bg-rose-500 hover:text-white"
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
    </div>
</template>
