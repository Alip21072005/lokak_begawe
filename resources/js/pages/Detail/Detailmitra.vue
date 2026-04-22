<!-- eslint-disable @typescript-eslint/no-unused-vars -->
<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { 
    MapPin, Briefcase, Globe, Users, Star, Mail, 
    Building2, Calendar, ArrowUpRight, CheckCircle2 
} from 'lucide-vue-next';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

// MENERIMA DATA DINAMIS
const props = defineProps<{
    partnerDetail: any;
}>();
</script>

<template>
    <Head :title="`${partnerDetail.name} - Profil Mitra`" />

    <div class="min-h-screen bg-lokak-bg font-sans text-slate-900">
        <Navbar />

        <main class="w-full px-6 pt-32 pb-20 md:px-16 lg:px-24 xl:px-32">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                
                <div class="lg:col-span-8 space-y-8">
                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="flex items-center gap-6">
                                <div class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl bg-slate-100 flex items-center justify-center border border-slate-100">
                                    <img v-if="partnerDetail.logo" :src="partnerDetail.logo" class="h-full w-full object-cover" />
                                    <Building2 v-else class="h-10 w-10 text-slate-400" />
                                </div>
                                <div>
                                    <h1 class="text-3xl font-black italic tracking-tighter text-slate-800 uppercase">{{ partnerDetail.name }}</h1>
                                    <p class="text-sm font-bold text-lokak-brand italic uppercase">{{ partnerDetail.industry }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 grid grid-cols-2 gap-6 sm:grid-cols-4 border-t border-slate-50 pt-8">
                            <div class="flex flex-col gap-1">
                                <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic"><Globe class="h-3 w-3" /> Website</span>
                                <a :href="'https://' + partnerDetail.website" target="_blank" class="text-xs font-black italic text-blue-600 uppercase underline">{{ partnerDetail.website }}</a>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic"><Users class="h-3 w-3" /> Ukuran</span>
                                <span class="text-xs font-black italic text-slate-800 uppercase">{{ partnerDetail.size }}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic"><Calendar class="h-3 w-3" /> Didirikan</span>
                                <span class="text-xs font-black italic text-slate-800 uppercase">{{ partnerDetail.founded }}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic"><Star class="h-3 w-3" /> Rating</span>
                                <span class="text-xs font-black italic text-slate-800 uppercase">{{ partnerDetail.rating }} / 5.0</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-10 shadow-sm">
                        <h3 class="mb-6 text-xl font-black italic text-slate-800 uppercase flex items-center gap-2">
                            <Building2 class="h-5 w-5 text-lokak-brand" /> Tentang Kami
                        </h3>
                        <p class="text-sm font-medium leading-relaxed text-slate-600 mb-8 whitespace-pre-line">{{ partnerDetail.description }}</p>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-xl font-black italic text-slate-800 uppercase tracking-tight px-2">Lowongan Aktif ({{ partnerDetail.activeJobs.length }})</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div v-for="job in partnerDetail.activeJobs" :key="job.id" class="group rounded-2xl border border-slate-200 bg-white p-6 transition-all hover:border-lokak-brand hover:shadow-md">
                                <div class="flex items-center justify-between">
                                    <div class="space-y-1">
                                        <h4 class="font-black italic text-slate-800 uppercase group-hover:text-lokak-brand transition-colors">{{ job.title }}</h4>
                                        <div class="flex items-center gap-4 text-[10px] font-bold text-slate-400 uppercase italic">
                                            <span class="flex items-center gap-1"><Briefcase class="h-3 w-3" /> {{ job.type }}</span>
                                            <span class="flex items-center gap-1"><MapPin class="h-3 w-3" /> {{ partnerDetail.location }}</span>
                                        </div>
                                    </div>
                                    <Link :href="'/detail/lowongan/' + job.id" class="flex items-center gap-2 rounded-lg bg-slate-50 p-3 text-slate-400 transition-all group-hover:bg-lokak-brand group-hover:text-white">
                                        <span class="text-[10px] font-black italic">LIHAT</span>
                                        <ArrowUpRight class="h-4 w-4" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="sticky top-32 space-y-6">
                        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                            <h3 class="text-lg font-black italic text-slate-800 uppercase mb-6 border-b border-slate-50 pb-4">Kontak</h3>
                            <div class="space-y-6">
                                <div class="flex items-start gap-4">
                                    <div class="rounded-xl bg-sky-50 p-3 text-lokak-brand"><MapPin class="h-5 w-5" /></div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase italic">Alamat</p>
                                        <p class="text-xs font-black italic text-slate-700 uppercase leading-relaxed">{{ partnerDetail.address }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="rounded-xl bg-blue-50 p-3 text-blue-500"><Mail class="h-5 w-5" /></div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase italic">Email</p>
                                        <p class="text-xs font-black italic text-slate-700">{{ partnerDetail.email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-8 rounded-2xl bg-slate-900 p-6 text-center">
                                <p class="text-[10px] font-bold text-slate-400 uppercase italic mb-1">Status</p>
                                <p class="text-2xl font-black italic text-white uppercase tracking-tighter">{{ partnerDetail.jobCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <Footer />
    </div>
</template>