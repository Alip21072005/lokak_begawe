<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { 
    ArrowLeft, CheckCircle, 
    Globe, Mail, MapPin, Info
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{ mitra: any }>();
defineOptions({ layout: AppLayout });

const verifyMitra = (status: 'verified') => {
    Swal.fire({
        title: 'Verifikasi Perusahaan?',
        text: 'Dengan menyetujui, perusahaan ini dapat mulai menerbitkan lowongan pekerjaan.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        confirmButtonText: 'Ya, Verifikasi!',
        customClass: { popup: 'rounded-[3rem]' }
    }).then((result) => {
        if (result.isConfirmed) {
            router.patch(`/dashboard/admin/mitra/${props.mitra.id}/status`, { status });
        }
    });
};
</script>

<template>
    <Head title="Detail Verifikasi Mitra" />
    <div class="space-y-8 p-6 lg:p-10">
        <Link href="/dashboard/admin/kelolamitra" class="group inline-flex items-center gap-2 text-xs font-black uppercase italic text-slate-400 hover:text-sky-700">
            <ArrowLeft class="h-4 w-4 transition-transform group-hover:-translate-x-1" /> Kembali
        </Link>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="lg:col-span-8 space-y-8">
                <div class="rounded-[3rem] border border-slate-200 bg-white p-10 shadow-sm">
                    <div class="flex flex-col gap-8 md:flex-row md:items-center">
                        <div class="flex h-32 w-32 shrink-0 items-center justify-center rounded-4xl border-4 border-slate-50 bg-white text-4xl font-black text-sky-700 shadow-xl italic">
                            <img v-if="mitra.logo_mitra" :src="`/storage/${mitra.logo_mitra}`" class="h-full w-full object-cover rounded-4xl" />
                            <span v-else>{{ mitra.nama_mitra.charAt(0) }}</span>
                        </div>
                        <div class="space-y-2">
                            <h2 class="text-4xl font-black italic uppercase tracking-tighter text-slate-900">{{ mitra.nama_mitra }}</h2>
                            <p class="text-xs font-bold text-sky-700 uppercase italic tracking-widest">{{ mitra.kategori?.nama_kategori }}</p>
                            <div v-if="mitra.status_mitra === 'verified'" class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-1 text-[9px] font-black text-emerald-600 uppercase italic border border-emerald-100">
                                <CheckCircle class="h-3 w-3" /> Akun Terverifikasi
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 space-y-4 border-t border-slate-50 pt-10">
                        <h4 class="text-[10px] font-black uppercase italic text-slate-400 flex items-center gap-2">
                            <Info class="h-3 w-3" /> Tentang Perusahaan
                        </h4>
                        <p class="text-xs font-medium leading-relaxed text-slate-600 italic bg-slate-50 p-8 rounded-[2.5rem]">
                            "{{ mitra.deskripsi_mitra || 'Tidak ada deskripsi.' }}"
                        </p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6">
                <div class="rounded-[3rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <h4 class="mb-6 text-[10px] font-black uppercase italic text-slate-400 tracking-widest">Informasi Kontak</h4>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-400"><Globe class="h-4 w-4" /></div>
                            <div class="text-[11px] font-bold text-slate-600 underline uppercase italic">{{ mitra.website_mitra || '-' }}</div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-400"><Mail class="h-4 w-4" /></div>
                            <div class="text-[11px] font-bold text-slate-600 uppercase italic">{{ mitra.email_mitra }}</div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-400"><MapPin class="h-4 w-4" /></div>
                            <div class="text-[11px] font-bold text-slate-600 uppercase italic">{{ mitra.alamat_mitra }}</div>
                        </div>
                    </div>
                </div>

                <div v-if="mitra.status_mitra === 'pending'" class="rounded-[2.5rem] bg-slate-900 p-8 shadow-2xl">
                    <h4 class="mb-6 text-center text-[10px] font-black uppercase italic text-slate-400 tracking-widest">Moderasi Admin</h4>
                    <button 
                        @click="verifyMitra('verified')"
                        class="w-full rounded-2xl bg-emerald-500 py-5 text-xs font-black text-white uppercase italic shadow-lg shadow-emerald-900/40 transition-all hover:bg-emerald-600 active:scale-95"
                    >
                        <CheckCircle class="mr-2 inline h-4 w-4" /> Verifikasi Sekarang
                    </button>
                    <p class="mt-4 text-center text-[9px] font-bold text-slate-500 uppercase italic leading-tight">
                        Pastikan perusahaan ini valid dan bukan merupakan akun spam.
                    </p>
                </div>

                <div v-else class="rounded-[2.5rem] bg-emerald-500 p-8 text-center text-white shadow-xl">
                    <CheckCircle class="mx-auto h-12 w-12 mb-4" />
                    <h4 class="text-xs font-black uppercase italic">Mitra Terverifikasi</h4>
                </div>
            </div>
        </div>
    </div>
</template>