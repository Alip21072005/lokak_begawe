<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { 
    MapPin, 
    Mail, 
    Phone, 
    Globe, 
    GraduationCap, 
    Wrench, 
    Calendar,
    Download,
    ArrowLeft
} from 'lucide-vue-next';
import { route } from 'ziggy-js';
import AppLayout from '@/layouts/AppLayout.vue';

defineProps<{
    pelamar: any;
}>();

defineOptions({ layout: AppLayout });

/** * Fungsi format tanggal untuk pengalaman & pendidikan
 * Mengubah format YYYY-MM-DD menjadi Nama Bulan YYYY
 */
const formatDate = (date: string) => {
    if (!date) {
return 'Sekarang';
}

    return new Date(date).toLocaleDateString('id-ID', {
        month: 'long',
        year: 'numeric'
    });
};
</script>

<template>
    <Head :title="`${pelamar.nama_pelamar} - Profil Profesional`" />

    <div class="min-h-screen bg-[#F8FAFC] pb-20">
        <div class="relative h-64 w-full overflow-hidden bg-slate-900 lg:h-80">
            <div class="absolute -right-20 -top-20 h-96 w-96 rounded-full bg-sky-600/20 blur-3xl"></div>
            <div class="absolute -left-20 -bottom-20 h-64 w-64 rounded-full bg-indigo-600/10 blur-3xl"></div>
            
            <div class="mx-auto max-w-7xl px-6 pt-10 lg:px-10">
                <Link :href="route('welcome')" class="inline-flex items-center gap-2 text-xs font-black uppercase italic text-white/50 transition-colors hover:text-sky-400">
                    <ArrowLeft class="h-4 w-4" /> Kembali ke Jelajah
                </Link>
            </div>
        </div>

        <div class="mx-auto -mt-32 max-w-7xl px-6 lg:px-10">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                
                <div class="lg:col-span-4 space-y-6">
                    <div class="rounded-[3rem] border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/50">
                        <div class="flex flex-col items-center text-center">
                            <div class="relative -mt-20 mb-6 h-40 w-40 overflow-hidden rounded-[2.5rem] border-8 border-white bg-slate-100 shadow-2xl">
                                <img v-if="pelamar.foto_pelamar" :src="`/storage/${pelamar.foto_pelamar}`" class="h-full w-full object-cover" />
                                <div v-else class="flex h-full w-full items-center justify-center bg-sky-50 text-sky-700">
                                    <span class="text-5xl font-black italic">{{ pelamar.nama_pelamar.charAt(0) }}</span>
                                </div>
                            </div>
                            
                            <h1 class="text-3xl font-black uppercase italic tracking-tighter text-slate-900 leading-tight">
                                {{ pelamar.nama_pelamar }}
                            </h1>
                            <p class="mt-2 flex items-center gap-1 text-[10px] font-black uppercase tracking-widest text-sky-700 italic">
                                <MapPin class="h-3 w-3" /> {{ pelamar.lokasi?.nama_lokasi || 'Lokasi tidak diset' }}
                            </p>

                            <div class="mt-8 w-full space-y-4 border-t border-slate-50 pt-8 text-left">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-50 text-slate-400"><Mail class="h-4 w-4" /></div>
                                    <span class="text-xs font-bold text-slate-600 italic truncate">{{ pelamar.email_pelamar }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-50 text-slate-400"><Phone class="h-4 w-4" /></div>
                                    <span class="text-xs font-bold text-slate-600 italic">{{ pelamar.nohp_pelamar }}</span>
                                </div>
                                <div v-if="pelamar.website_portfolio" class="flex items-center gap-4">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-50 text-slate-400"><Globe class="h-4 w-4" /></div>
                                    <a :href="pelamar.website_portfolio" target="_blank" class="text-xs font-bold text-sky-700 underline italic truncate">Portofolio Luar</a>
                                </div>
                            </div>

                            <a v-if="pelamar.cv_pelamar" :href="`/storage/${pelamar.cv_pelamar}`" target="_blank" class="mt-10 flex w-full items-center justify-center gap-3 rounded-2xl bg-slate-900 py-5 text-xs font-black text-white uppercase italic shadow-xl transition-all hover:bg-sky-700 active:scale-95">
                                <Download class="h-4 w-4" /> Unduh CV Pelamar
                            </a>
                        </div>
                    </div>

                    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                        <h3 class="mb-6 flex items-center gap-2 text-[10px] font-black uppercase italic text-slate-400 tracking-widest">
                            <Wrench class="h-4 w-4 text-sky-700" /> Keahlian Utama
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <span v-for="skill in pelamar.skills" :key="skill.id" class="rounded-lg bg-sky-50 px-3 py-1.5 text-[10px] font-black text-sky-700 uppercase italic border border-sky-100">
                                {{ skill.master_skill?.nama_skill }}
                            </span>
                            <p v-if="!pelamar.skills?.length" class="text-[10px] font-bold italic text-slate-300">Belum menambahkan skill.</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-8 space-y-8">
                    <div class="rounded-[3rem] border border-slate-200 bg-white p-10 shadow-sm relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-slate-50 opacity-50"></div>
                        <h3 class="mb-6 text-[10px] font-black uppercase italic text-slate-400 tracking-[0.2em]">Tentang Saya</h3>
                        <p class="relative z-10 text-[13px] font-medium leading-relaxed italic text-slate-600 whitespace-pre-line">
                            "{{ pelamar.bio || 'Pelamar belum menuliskan ringkasan profesional.' }}"
                        </p>
                    </div>

                    <div class="rounded-[3rem] border border-slate-200 bg-white p-10 shadow-sm">
                        <h3 class="mb-10 text-[10px] font-black uppercase italic text-slate-400 tracking-[0.2em]">Pengalaman Profesional</h3>
                        <div class="space-y-10">
                            <div v-for="exp in pelamar.pengalamans" :key="exp.id" class="relative pl-8 before:absolute before:left-0 before:top-2 before:h-full before:w-0.5 before:bg-slate-100 last:before:hidden">
                                <div class="absolute -left-1.5 top-1.5 h-3 w-3 rounded-full bg-sky-600 shadow-[0_0_10px_rgba(3,105,161,0.5)]"></div>
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-2">
                                    <h4 class="text-lg font-black uppercase italic text-slate-900 leading-none">{{ exp.posisi }}</h4>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-[9px] font-black text-slate-500 uppercase italic">
                                        <Calendar class="h-3 w-3" /> {{ formatDate(exp.tgl_mulai) }} — {{ exp.is_current ? 'Sekarang' : formatDate(exp.tgl_selesai) }}
                                    </span>
                                </div>
                                <p class="text-xs font-black text-sky-700 uppercase italic mb-3">
                                    {{ exp.master_perusahaan?.nama_perusahaan }}
                                </p>
                                <p class="text-xs font-medium leading-relaxed text-slate-500 italic">{{ exp.deskripsi }}</p>
                            </div>
                            <p v-if="!pelamar.pengalamans?.length" class="py-10 text-center text-xs font-bold italic text-slate-300 uppercase">Belum ada riwayat pengalaman.</p>
                        </div>
                    </div>

                    <div class="rounded-[3rem] border border-slate-200 bg-white p-10 shadow-sm">
                        <h3 class="mb-10 text-[10px] font-black uppercase italic text-slate-400 tracking-[0.2em]">Riwayat Pendidikan</h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div v-for="edu in pelamar.pendidikans" :key="edu.id" class="rounded-[2.5rem] bg-slate-50 p-6 border border-slate-100 transition-all hover:bg-white hover:shadow-lg">
                                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-white shadow-sm text-sky-700">
                                    <GraduationCap class="h-5 w-5" />
                                </div>
                                <h4 class="text-sm font-black uppercase italic text-slate-900">
                                    {{ edu.master_instansi?.nama_instansi }}
                                </h4>
                                <p class="text-[10px] font-bold text-sky-700 uppercase italic mb-2">{{ edu.gelar }}</p>
                                <p class="text-[9px] font-black text-slate-400 uppercase italic">Lulus Tahun {{ formatDate(edu.tgl_lulus) }}</p>
                            </div>
                        </div>
                        <p v-if="!pelamar.pendidikans?.length" class="py-10 text-center text-xs font-bold italic text-slate-300 uppercase">Belum ada riwayat pendidikan.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<style scoped>
/* Custom radius jika utility belum ada */
.rounded-4xl {
    border-radius: 2.5rem;
}
</style>