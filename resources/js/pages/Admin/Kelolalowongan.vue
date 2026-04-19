<!-- eslint-disable @typescript-eslint/no-unused-vars -->
<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import {
    Search,
    Clock,
    Building2,
    Eye,
    ThumbsUp,
    ThumbsDown,
    AlertCircle,
    Trash2,
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    auth: any;
    jobs: any[];
    filters: { search: string };
}>();

defineOptions({ layout: AppLayout });

const search = ref(props.filters.search);

watch(search, (value) => {
    router.get(
        '/dashboard/admin/kelolalowongan',
        { search: value },
        {
            preserveState: true,
            replace: true,
        },
    );
});

// FUNGSI MODERASI DENGAN POP-UP
const confirmAction = (id: string, title: string, status: string) => {
    const isApprove = status === 'verified';

    Swal.fire({
        title: isApprove ? 'Setujui Lowongan?' : 'Tolak Lowongan?',
        text: `Lowongan "${title}" akan ditandai sebagai ${status === 'verified' ? 'Tayang' : 'Ditolak'}.`,
        icon: isApprove ? 'question' : 'warning',
        showCancelButton: true,
        confirmButtonColor: isApprove ? '#10b981' : '#f43f5e',
        confirmButtonText: isApprove ? 'Ya, Setujui' : 'Ya, Tolak',
        customClass: { popup: 'rounded-[2.5rem]' },
    }).then((result) => {
        if (result.isConfirmed) {
            router.patch(
                `/dashboard/admin/kelolalowongan/${id}/status`,
                { status },
                {
                    preserveScroll: true,
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

const confirmDelete = (id: string, title: string) => {
    Swal.fire({
        title: 'Hapus Lowongan?',
        text: `Data lowongan "${title}" akan dihapus permanen.`,
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        confirmButtonText: 'Ya, Hapus',
        customClass: { popup: 'rounded-[2.5rem]' },
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/dashboard/admin/kelolalowongan/${id}`, {
                preserveScroll: true,
            });
        }
    });
};

const getStatusStyle = (status: string) => {
    if (status === 'verified') {
        return 'bg-emerald-100 text-emerald-700 border-emerald-200';
    }

    if (status === 'rejected') {
        return 'bg-rose-100 text-rose-700 border-rose-200';
    }

    return 'bg-amber-100 text-amber-700 border-amber-200';
};
</script>

<template>
    <Head title="Verifikasi Lowongan - Admin" />

    <div class="space-y-8 p-6 lg:p-10">
        <div
            class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <h1
                    class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl"
                >
                    VERIFIKASI <span class="text-sky-700">LOWONGAN</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative w-full sm:w-64">
                    <Search
                        class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400"
                    />
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari loker atau mitra..."
                        class="h-11 w-full rounded-xl border border-slate-200 bg-white pr-4 pl-10 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20"
                    />
                </div>
            </div>
        </div>

        <div
            class="flex items-center gap-4 rounded-4xl border border-amber-100 bg-amber-50/50 p-6"
        >
            <div
                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-lg shadow-amber-900/20"
            >
                <AlertCircle class="h-6 w-6" />
            </div>
            <div>
                <h4
                    class="text-xs font-black tracking-tight text-amber-800 uppercase italic"
                >
                    Perhatian Admin
                </h4>
                <p
                    class="mt-1 text-[10px] leading-tight font-bold text-amber-700/80 uppercase italic"
                >
                    Harap periksa kelengkapan deskripsi dan rentang gaji sebelum
                    memberikan persetujuan tayang.
                </p>
            </div>
        </div>

        <div
            class="rounded-[2.5rem] border border-slate-200 bg-white p-4 shadow-sm lg:p-8"
        >
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr
                            class="border-b border-slate-100 text-left text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase italic"
                        >
                            <th class="px-4 pb-6">Lowongan & Mitra</th>
                            <th class="px-4 pb-6">Gaji Ditawarkan</th>
                            <th class="px-4 pb-6">Tgl Pengajuan</th>
                            <th class="px-4 pb-6">Status</th>
                            <th class="px-4 pb-6 text-right">Aksi Moderasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-if="jobs.length === 0">
                            <td
                                colspan="5"
                                class="py-20 text-center text-[10px] font-black text-slate-300 uppercase italic"
                            >
                                Tidak ada pengajuan lowongan
                            </td>
                        </tr>
                        <tr
                            v-for="job in jobs"
                            :key="job.id"
                            class="group transition-colors hover:bg-slate-50/50"
                        >
                            <td class="px-4 py-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-sky-700 uppercase shadow-inner group-hover:bg-white"
                                    >
                                        {{
                                            job.mitra?.nama_mitra?.charAt(0) ||
                                            'L'
                                        }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-black text-slate-900 uppercase transition-colors group-hover:text-sky-700"
                                            >{{ job.judul_lowongan }}</span
                                        >
                                        <span
                                            class="text-[10px] font-bold text-slate-400 uppercase italic"
                                            >{{ job.mitra?.nama_mitra }}</span
                                        >
                                    </div>
                                </div>
                            </td>

                            <td
                                class="px-4 py-6 text-[11px] font-black text-slate-700 uppercase"
                            >
                                {{ job.gaji_lowongan }}
                            </td>

                            <td class="px-4 py-6">
                                <div
                                    class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic"
                                >
                                    <Clock class="h-4 w-4" />
                                    {{
                                        new Date(
                                            job.created_at,
                                        ).toLocaleDateString('id-ID', {
                                            day: 'numeric',
                                            month: 'long',
                                            year: 'numeric',
                                        })
                                    }}
                                </div>
                            </td>

                            <td class="px-4 py-6">
                                <span
                                    :class="[
                                        getStatusStyle(job.status_lowongan),
                                        'inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-[9px] font-black tracking-widest uppercase italic',
                                    ]"
                                >
                                    {{
                                        job.status_lowongan === 'verified'
                                            ? 'Tayang'
                                            : job.status_lowongan === 'rejected'
                                              ? 'Ditolak'
                                              : 'Pending'
                                    }}
                                </span>
                            </td>

                            <td class="px-4 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <button
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-400 shadow-sm hover:border-sky-700 hover:text-sky-700"
                                    >
                                        <Eye class="h-4 w-4" />
                                    </button>
                                    <button
                                        @click="
                                            confirmAction(
                                                job.id,
                                                job.judul_lowongan,
                                                'verified',
                                            )
                                        "
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-lg shadow-emerald-900/20 transition-all hover:bg-emerald-600"
                                    >
                                        <ThumbsUp class="h-4 w-4" />
                                    </button>
                                    <button
                                        @click="
                                            confirmAction(
                                                job.id,
                                                job.judul_lowongan,
                                                'rejected',
                                            )
                                        "
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-rose-100 bg-white text-rose-500 shadow-sm transition-all hover:bg-rose-50"
                                    >
                                        <ThumbsDown class="h-4 w-4" />
                                    </button>
                                    <button
                                        @click="
                                            confirmDelete(
                                                job.id,
                                                job.judul_lowongan,
                                            )
                                        "
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-300 transition-all hover:text-rose-600"
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
