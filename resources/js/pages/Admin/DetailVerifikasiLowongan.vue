<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import {
    ArrowLeft,
    CheckCircle,
    XCircle,
    MapPin,
    Building2,
    Banknote,
    FileText,
    Clock,
    ShieldCheck,
    AlertCircle,
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import AppLayout from '@/layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    lowongan: any;
}>();

const job = props.lowongan;
defineOptions({ layout: AppLayout });

const updateStatus = (status: 'verified' | 'rejected') => {
    if (!job) return;

    Swal.fire({
        title: status === 'verified' ? 'Setujui Lowongan?' : 'Tolak Lowongan?',
        text:
            status === 'verified'
                ? `Lowongan "${job.judul_lowongan}" akan segera ditayangkan di platform.`
                : `Lowongan "${job.judul_lowongan}" akan ditolak dan tidak akan tampil.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: status === 'verified' ? '#10b981' : '#f43f5e',
        cancelButtonColor: '#cbd5e1',
        confirmButtonText: status === 'verified' ? 'Ya, Setujui' : 'Ya, Tolak',
        customClass: { popup: 'rounded-[2.5rem]' },
    }).then((result) => {
        if (result.isConfirmed) {
            router.patch(`/dashboard/admin/kelolalowongan/${job.id}/status`, { status }, {
                onBefore: () => {
                    Swal.fire({
                        title: 'Memproses...',
                        didOpen: () => Swal.showLoading(),
                        allowOutsideClick: false,
                        customClass: { popup: 'rounded-[2.5rem]' },
                    });
                },
                onSuccess: () => {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: `Status lowongan berhasil diubah menjadi ${status}.`,
                        icon: 'success',
                        customClass: { popup: 'rounded-[2.5rem]' },
                    });
                },
            });
        }
    });
};

const formatRupiah = (value: any) => {
    if (value === null || value === undefined || value === '') return 'Bersaing';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(Number(value));
};
</script>

<template>
    <Head title="Verifikasi Detail Lowongan" />

    <div v-if="!job" class="flex h-screen items-center justify-center italic text-slate-400">
        Memuat data lowongan...
    </div>

    <div v-else class="space-y-8 p-6 lg:p-10">
        <div class="flex items-center justify-between">
            <Link :href="route('admin.kelolalowongan')" class="group inline-flex items-center gap-2 text-xs font-black uppercase italic text-slate-400 hover:text-sky-700 transition-colors">
                <ArrowLeft class="h-4 w-4 transition-transform group-hover:-translate-x-1" />
                Kembali ke Daftar
            </Link>

            <div
                v-if="job.status_lowongan !== 'pending'"
                :class="job.status_lowongan === 'verified' ? 'text-emerald-500 bg-emerald-50' : 'text-rose-500 bg-rose-50'"
                class="px-6 py-2 rounded-2xl border border-current/10 flex items-center gap-2"
            >
                <component :is="job.status_lowongan === 'verified' ? ShieldCheck : AlertCircle" class="h-4 w-4" />
                <span class="text-[10px] font-black uppercase italic tracking-widest">
                    Status: {{ job.status_lowongan === 'verified' ? 'Terverifikasi' : 'Ditolak' }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="space-y-8 lg:col-span-7">
                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-10 shadow-sm">
                    <h2 class="mb-2 text-3xl font-black italic uppercase tracking-tighter text-slate-900 leading-none">
                        {{ job.judul_lowongan }}
                    </h2>

                    <div class="mb-10 flex flex-wrap gap-6">
                        <span class="flex items-center gap-2 text-[10px] font-black uppercase text-slate-400 italic">
                            <Building2 class="h-4 w-4 text-sky-600" /> {{ job.mitra?.nama_mitra || '-' }}
                        </span>
                        <span class="flex items-center gap-2 text-[10px] font-black uppercase text-slate-400 italic">
                            <MapPin class="h-4 w-4 text-sky-600" /> {{ job.lokasi?.nama_lokasi || '-' }}
                        </span>
                        <span class="flex items-center gap-2 text-[10px] font-black uppercase text-emerald-600 italic">
                            <Banknote class="h-4 w-4" /> {{ formatRupiah(job.gaji_min) }} - {{ formatRupiah(job.gaji_max) }}
                        </span>
                    </div>

                    <div class="space-y-4 border-t border-slate-50 pt-8">
                        <h4 class="flex items-center gap-2 text-[10px] font-black uppercase italic text-slate-400">
                            <FileText class="h-3 w-3" /> Deskripsi Pekerjaan
                        </h4>
                        <div class="text-xs font-medium leading-relaxed text-slate-600 whitespace-pre-line italic bg-slate-50/50 p-8 rounded-4xl border border-slate-100">
                            "{{ job.deskripsi_lowongan || '-' }}"
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-8 lg:col-span-5">
                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <div class="mb-6 flex items-center justify-between">
                        <h4 class="text-[10px] font-black uppercase italic text-slate-400">Bukti Pembayaran</h4>
                        <span class="rounded-lg bg-sky-100 px-3 py-1 text-[9px] font-black text-sky-700 uppercase italic">
                            {{ job.paket_pembayaran || 'Tanpa Paket' }}
                        </span>
                    </div>

                    <div v-if="job.bukti_pembayaran" class="relative group overflow-hidden rounded-3xl border border-slate-100 shadow-xl bg-slate-50">
                        <img :src="job.bukti_pembayaran" class="w-full transition-transform duration-700 group-hover:scale-110" alt="Bukti Pembayaran" />
                        <div class="absolute inset-0 bg-linear-to-t from-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                            <a :href="job.bukti_pembayaran" target="_blank" class="text-[10px] font-black text-white uppercase italic underline">
                                Lihat Full Image
                            </a>
                        </div>
                    </div>
                    <div v-else class="flex h-64 flex-col items-center justify-center rounded-3xl bg-slate-50 border-2 border-dashed border-slate-200 opacity-40 italic">
                        <Banknote class="h-16 w-16 mb-4 text-slate-300" />
                        <p class="text-[10px] font-black uppercase">Bukti belum diunggah</p>
                    </div>

                    <div class="mt-10">
                        <div v-if="job.status_lowongan === 'pending'" class="flex gap-4">
                            <button
                                @click="updateStatus('verified')"
                                class="flex-1 rounded-2xl bg-emerald-500 py-5 text-xs font-black text-white uppercase italic shadow-lg shadow-emerald-900/20 transition-all hover:bg-emerald-600 active:scale-95"
                            >
                                <CheckCircle class="mr-2 inline h-4 w-4" /> Setujui
                            </button>
                            <button
                                @click="updateStatus('rejected')"
                                class="flex-1 rounded-2xl border border-rose-100 bg-white py-5 text-xs font-black text-rose-500 uppercase italic shadow-sm hover:bg-rose-50 active:scale-95"
                            >
                                <XCircle class="mr-2 inline h-4 w-4" /> Tolak
                            </button>
                        </div>

                        <div v-else-if="job.status_lowongan === 'verified'" class="w-full rounded-2xl bg-emerald-50 py-6 border-2 border-emerald-100 flex flex-col items-center justify-center gap-2">
                            <div class="h-12 w-12 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-900/20">
                                <CheckCircle class="h-6 w-6" />
                            </div>
                            <span class="text-xs font-black text-emerald-700 uppercase italic">Lowongan Telah Ditayangkan</span>
                        </div>

                        <div v-else-if="job.status_lowongan === 'rejected'" class="w-full rounded-2xl bg-rose-50 py-6 border-2 border-rose-100 flex flex-col items-center justify-center gap-2">
                            <div class="h-12 w-12 rounded-full bg-rose-500 text-white flex items-center justify-center shadow-lg shadow-rose-900/20">
                                <XCircle class="h-6 w-6" />
                            </div>
                            <span class="text-xs font-black text-rose-700 uppercase italic">Lowongan Telah Ditolak</span>
                        </div>
                    </div>
                </div>

                <div class="px-8 flex items-center justify-between text-[9px] font-bold text-slate-300 uppercase italic tracking-widest">
                    <span>ID: {{ job.id }}</span>
                    <span class="flex items-center gap-1">
                        <Clock class="h-3 w-3" />
                        {{ job.created_at ? new Date(job.created_at).toLocaleDateString() : '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>