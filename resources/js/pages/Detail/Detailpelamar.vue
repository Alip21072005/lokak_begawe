<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { 
    MapPin, Mail, Phone, Globe, GraduationCap, Wrench, 
    Calendar, Download, ArrowLeft, Briefcase, Info, ExternalLink
} from 'lucide-vue-next';
import { route } from 'ziggy-js';


const props = defineProps<{
    pelamar: any;
}>();



const formatDate = (dateString: string) => {
    if (!dateString) return 'Sekarang';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
};
</script>

<template>
    <Head :title="`${pelamar?.nama_pelamar} - Profil Profesional`" />

    <div class="min-h-screen bg-[#F8FAFC] font-sans text-slate-900">
   

        <main class="mx-auto mt-20 max-w-6xl px-6 py-12 md:px-12 lg:px-10">
            
            <div class="mb-8">
                <Link :href="route('welcome')" class="inline-flex items-center gap-2 text-[10px] font-black uppercase italic text-slate-400 transition-all hover:text-lokak-brand">
                    <ArrowLeft class="h-4 w-4" /> Kembali ke Jelajah
                </Link>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                
                <div class="space-y-6 lg:col-span-4">
                    <div class="overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white shadow-sm transition-all hover:shadow-md">
                        <div class="h-24 w-full bg-lokak-brand relative">
                            <div class="absolute inset-0 bg-linear-to-br from-white/10 to-transparent"></div>
                        </div>
                        
                        <div class="px-8 pb-8">
                            <div class="relative -mt-12 mb-6 inline-block">
                                <div class="h-32 w-32 overflow-hidden rounded-4xl border-4 border-white bg-slate-100 shadow-lg">
                                    <img v-if="pelamar?.foto_pelamar" :src="`/storage/${pelamar.foto_pelamar}`" class="h-full w-full object-cover" />
                                    <div v-else class="flex h-full w-full items-center justify-center bg-sky-50 text-sky-700">
                                        <span class="text-4xl font-black italic">{{ pelamar?.nama_pelamar?.charAt(0) }}</span>
                                    </div>
                                </div>
                                <div class="absolute bottom-1 right-1 h-6 w-6 rounded-full border-4 border-white bg-emerald-500"></div>
                            </div>

                            <h1 class="text-2xl font-black uppercase italic tracking-tighter text-slate-900">
                                {{ pelamar?.nama_pelamar }}
                            </h1>
                            <p class="mt-1 flex items-center gap-1 text-[10px] font-black uppercase italic tracking-widest text-lokak-brand">
                                <MapPin class="h-3 w-3" /> {{ pelamar?.lokasi?.nama_lokasi || 'Provinsi Bengkulu' }}
                            </p>

                            <div class="mt-8 space-y-4 border-t border-slate-50 pt-8">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-50 text-slate-400"><Mail class="h-4 w-4" /></div>
                                    <div class="flex flex-col">
                                        <span class="text-[9px] font-black uppercase text-slate-400">Email</span>
                                        <span class="text-xs font-bold text-slate-700 italic truncate">{{ pelamar?.email_pelamar || pelamar?.user?.email }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-50 text-slate-400"><Phone class="h-4 w-4" /></div>
                                    <div class="flex flex-col">
                                        <span class="text-[9px] font-black uppercase text-slate-400">Telepon</span>
                                        <span class="text-xs font-bold text-slate-700 italic">{{ pelamar?.nohp_pelamar || '-' }}</span>
                                    </div>
                                </div>
                                <div v-if="pelamar?.website_portfolio" class="flex items-center gap-4">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-50 text-slate-400"><Globe class="h-4 w-4" /></div>
                                    <div class="flex flex-col">
                                        <span class="text-[9px] font-black uppercase text-slate-400">Portfolio</span>
                                        <a :href="pelamar.website_portfolio" target="_blank" class="flex items-center gap-1 text-xs font-bold text-lokak-brand underline italic">
                                            Kunjungi Situs <ExternalLink class="h-3 w-3" />
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <a v-if="pelamar?.cv_pelamar" :href="`/storage/${pelamar.cv_pelamar}`" target="_blank" class="mt-8 flex w-full items-center justify-center gap-3 rounded-2xl bg-slate-900 py-4 text-xs font-black uppercase italic text-white shadow-xl transition-all hover:bg-lokak-brand active:scale-95">
                                <Download class="h-4 w-4" /> Unduh Berkas CV
                            </a>
                        </div>
                    </div>

                    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                        <h3 class="mb-6 flex items-center gap-2 text-[10px] font-black uppercase italic tracking-[0.2em] text-slate-400">
                            <Wrench class="h-4 w-4 text-lokak-brand" /> Keahlian Utama
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <template v-if="pelamar?.skills?.length">
                                <span v-for="skill in pelamar.skills" :key="skill.id" class="rounded-lg border border-sky-100 bg-sky-50 px-3 py-1.5 text-[10px] font-black uppercase italic text-lokak-brand">
                                    {{ skill.master_skill?.nama_skill || skill.nama_skill }}
                                </span>
                            </template>
                            <p v-else class="text-[10px] font-bold italic text-slate-300">Belum ada skill terdaftar.</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-8 lg:col-span-8">
                    
                    <div class="relative overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white p-10 shadow-sm">
                        <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-slate-50"></div>
                        <h3 class="relative z-10 mb-6 text-[10px] font-black uppercase italic tracking-[0.3em] text-slate-400">Ringkasan Profil</h3>
                        <p class="relative z-10 text-sm font-medium leading-relaxed italic text-slate-600 whitespace-pre-line">
                            "{{ pelamar?.bio || 'Pelamar belum menuliskan ringkasan profesional untuk menarik perhatian mitra.' }}"
                        </p>
                    </div>

                    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-10 shadow-sm">
                        <div class="mb-10 flex items-center justify-between">
                            <h3 class="text-[10px] font-black uppercase italic tracking-[0.3em] text-slate-400">Pengalaman Kerja</h3>
                            <Briefcase class="h-5 w-5 text-slate-200" />
                        </div>
                        
                        <div class="space-y-12">
                            <template v-if="pelamar?.pengalamans?.length">
                                <div v-for="exp in pelamar.pengalamans" :key="exp.id" class="group relative pl-10 before:absolute before:left-0 before:top-2 before:h-full before:w-0.5 before:bg-slate-100 last:before:hidden">
                                    <div class="absolute -left-2 top-1.5 h-4 w-4 rounded-full border-4 border-white bg-slate-200 transition-colors group-hover:bg-lokak-brand shadow-sm"></div>
                                    
                                    <div class="mb-2 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                        <h4 class="text-lg font-black uppercase italic text-slate-900 leading-none group-hover:text-lokak-brand transition-colors">{{ exp.posisi }}</h4>
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-[9px] font-black uppercase italic text-slate-500">
                                            <Calendar class="h-3 w-3" /> {{ formatDate(exp.tgl_mulai) }} — {{ exp.is_current ? 'SEKARANG' : formatDate(exp.tgl_selesai) }}
                                        </span>
                                    </div>
                                    <p class="mb-4 text-xs font-black uppercase italic text-lokak-brand">
                                        {{ exp.master_perusahaan?.nama_perusahaan || exp.nama_perusahaan }}
                                    </p>
                                    <p class="text-xs font-medium leading-relaxed text-slate-500 italic">
                                        {{ exp.deskripsi }}
                                    </p>
                                </div>
                            </template>
                            <div v-else class="flex flex-col items-center justify-center py-12 text-slate-300">
                                <Info class="mb-2 h-8 w-8 opacity-20" />
                                <p class="text-[10px] font-black uppercase italic tracking-widest">Belum ada riwayat pengalaman kerja</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-10 shadow-sm">
                        <div class="mb-10 flex items-center justify-between">
                            <h3 class="text-[10px] font-black uppercase italic tracking-[0.3em] text-slate-400">Riwayat Pendidikan</h3>
                            <GraduationCap class="h-5 w-5 text-slate-200" />
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <template v-if="pelamar?.pendidikans?.length">
                                <div v-for="edu in pelamar.pendidikans" :key="edu.id" class="rounded-4xl border border-slate-100 bg-slate-50 p-6 transition-all hover:bg-white hover:shadow-xl hover:shadow-slate-200/50">
                                    <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-white text-lokak-brand shadow-sm">
                                        <GraduationCap class="h-5 w-5" />
                                    </div>
                                    <h4 class="text-sm font-black uppercase italic text-slate-900">
                                        {{ edu.master_instansi?.nama_instansi || edu.nama_instansi }}
                                    </h4>
                                    <p class="mt-1 text-[10px] font-black uppercase italic text-lokak-brand">{{ edu.gelar }}</p>
                                    <div class="mt-4 flex items-center gap-2 text-[9px] font-black uppercase italic text-slate-400">
                                        <Calendar class="h-3 w-3" /> Lulus: {{ formatDate(edu.tgl_lulus) }}
                                    </div>
                                </div>
                            </template>
                            <div v-else class="col-span-2 flex flex-col items-center justify-center py-12 text-slate-300">
                                <Info class="mb-2 h-8 w-8 opacity-20" />
                                <p class="text-[10px] font-black uppercase italic tracking-widest">Belum ada riwayat pendidikan</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>

      
    </div>
</template>

<style scoped>
/* Transisi halus untuk hover */
.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>