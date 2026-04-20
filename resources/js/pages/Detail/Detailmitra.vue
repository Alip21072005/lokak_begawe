<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { 
    MapPin, 
    Briefcase, 
    Globe, 
    Users,
    Star,
    Mail,
    Building2,
    Calendar,
    ArrowUpRight,
    CheckCircle2
} from 'lucide-vue-next';
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';

// Data Mitra Riil (Contoh: Profil Perusahaan)
const partnerDetail = {
    name: 'CODE 21 INDONESIA',
    industry: 'Teknologi Informasi & Software',
    location: 'Mukomuko, Bengkulu',
    website: 'www.code21.co.id',
    founded: '2015',
    size: '50 - 100 Karyawan',
    rating: '4.8',
    reviewCount: '154',
    jobCount: '6 LOWONGAN AKTIF',
    description: 'Code 21 adalah perusahaan teknologi yang berfokus pada solusi Web Development, Mobile Apps, dan Cyber Security. Kami berkomitmen untuk membangun ekosistem digital yang inklusif dari daerah untuk Indonesia. Dengan budaya kerja yang fleksibel dan inovatif, kami mencari talenta terbaik untuk tumbuh bersama.',
    achievements: [
        'Best Startup Bengkulu 2023',
        'ISO 27001 Certified for Information Security',
        'Official Google Cloud Partner'
    ],
    // Lowongan yang sedang dibuka oleh mitra ini
    activeJobs: [
        { id: 1, title: 'Frontend Developer (React)', type: 'Full Time', salary: '3 - 5 Juta' },
        { id: 2, title: 'UI/UX Designer', type: 'Contract', salary: '4 - 6 Juta' },
        { id: 3, title: 'Backend Engineer (Go)', type: 'Full Time', salary: '6 - 9 Juta' },
    ]
};
</script>

<template>
    <Head :title="`${partnerDetail.name} - Profil Mitra`" />

    <div class="min-h-screen bg-[#f8fafc] font-sans text-slate-900">
        <Navbar />

        <main class="w-full px-6 pt-32 pb-20 md:px-16 lg:px-24 xl:px-32">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                
                <div class="lg:col-span-8 space-y-8">
                    
                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="flex items-center gap-6">
                                <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-2xl bg-slate-900 flex items-center justify-center">
                                    <span class="text-3xl font-black italic text-white">C21</span>
                                </div>
                                <div>
                                    <h1 class="text-3xl font-black italic tracking-tighter text-slate-800 uppercase">
                                        {{ partnerDetail.name }}
                                    </h1>
                                    <p class="text-sm font-bold text-orange-500 italic uppercase">{{ partnerDetail.industry }}</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="rounded-xl border-2 border-slate-200 px-6 py-3 text-sm font-black italic text-slate-600 transition-all hover:bg-slate-50">
                                    HUBUNGI
                                </button>
                                <button class="rounded-xl bg-[#0f172a] px-6 py-3 text-sm font-black italic text-white transition-all hover:bg-orange-500 active:scale-95">
                                    IKUTI
                                </button>
                            </div>
                        </div>

                        <div class="mt-10 grid grid-cols-2 gap-6 sm:grid-cols-4 border-t border-slate-50 pt-8">
                            <div class="flex flex-col gap-1">
                                <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic">
                                    <Globe class="h-3 w-3" /> Website
                                </span>
                                <a href="#" class="text-xs font-black italic text-blue-600 uppercase underline">{{ partnerDetail.website }}</a>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic">
                                    <Users class="h-3 w-3" /> Ukuran Perusahaan
                                </span>
                                <span class="text-xs font-black italic text-slate-800 uppercase">{{ partnerDetail.size }}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic">
                                    <Calendar class="h-3 w-3" /> Didirikan
                                </span>
                                <span class="text-xs font-black italic text-slate-800 uppercase">{{ partnerDetail.founded }}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic">
                                    <Star class="h-3 w-3" /> Rating Mitra
                                </span>
                                <span class="text-xs font-black italic text-slate-800 uppercase">{{ partnerDetail.rating }} / 5.0</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-10 shadow-sm">
                        <h3 class="mb-6 text-xl font-black italic text-slate-800 uppercase tracking-tight flex items-center gap-2">
                            <Building2 class="h-5 w-5 text-orange-500" /> Tentang Kami
                        </h3>
                        <p class="text-sm font-medium leading-relaxed text-slate-600 mb-8">
                            {{ partnerDetail.description }}
                        </p>
                        
                        <div class="space-y-4">
                            <h4 class="text-xs font-black italic text-slate-400 uppercase">Pencapaian & Sertifikasi :</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div v-for="ach in partnerDetail.achievements" :key="ach" class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <CheckCircle2 class="h-4 w-4 text-green-500" />
                                    <span class="text-xs font-bold text-slate-700 italic">{{ ach }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-xl font-black italic text-slate-800 uppercase tracking-tight px-2">Lowongan Aktif ({{ partnerDetail.activeJobs.length }})</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div v-for="job in partnerDetail.activeJobs" :key="job.id" class="group rounded-2xl border border-slate-200 bg-white p-6 transition-all hover:border-orange-500 hover:shadow-md">
                                <div class="flex items-center justify-between">
                                    <div class="space-y-1">
                                        <h4 class="font-black italic text-slate-800 uppercase group-hover:text-orange-500 transition-colors">{{ job.title }}</h4>
                                        <div class="flex items-center gap-4 text-[10px] font-bold text-slate-400 uppercase italic">
                                            <span class="flex items-center gap-1"><Briefcase class="h-3 w-3" /> {{ job.type }}</span>
                                            <span class="flex items-center gap-1"><MapPin class="h-3 w-3" /> {{ partnerDetail.location }}</span>
                                        </div>
                                    </div>
                                    <Link :href="`/jobs/${job.id}`" class="flex items-center gap-2 rounded-lg bg-slate-50 p-3 text-slate-400 transition-all group-hover:bg-orange-500 group-hover:text-white">
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
                            <h3 class="text-lg font-black italic text-slate-800 uppercase mb-6 border-b border-slate-50 pb-4">Informasi Kontak</h3>
                            
                            <div class="space-y-6">
                                <div class="flex items-start gap-4">
                                    <div class="rounded-xl bg-orange-50 p-3 text-orange-500">
                                        <MapPin class="h-5 w-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase italic">Alamat Kantor</p>
                                        <p class="text-xs font-black italic text-slate-700 uppercase leading-relaxed">
                                            Jl. Lintas Barat Sumatera, Kota Mukomuko, Bengkulu 38711
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div class="rounded-xl bg-blue-50 p-3 text-blue-500">
                                        <Mail class="h-5 w-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase italic">Email Rekrutmen</p>
                                        <p class="text-xs font-black italic text-slate-700">hrd@code21.co.id</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 rounded-2xl bg-slate-900 p-6 text-center">
                                <p class="text-[10px] font-bold text-slate-400 uppercase italic mb-1">Total Lowongan Terpasang</p>
                                <p class="text-3xl font-black italic text-white uppercase tracking-tighter">{{ partnerDetail.jobCount }}</p>
                            </div>
                        </div>

                        <div class="rounded-3xl border-2 border-dashed border-slate-200 p-6 flex items-center gap-4 bg-slate-50/50">
                            <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                <CheckCircle2 class="h-6 w-6" />
                            </div>
                            <div>
                                <p class="text-xs font-black italic text-slate-800 uppercase">Mitra Terverifikasi</p>
                                <p class="text-[10px] font-bold text-slate-400 italic">Telah melewati verifikasi dokumen identitas & legalitas.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <Footer />
    </div>
</template>

<style scoped>
.active\:scale-95:active {
    transform: scale(0.95);
}
</style>