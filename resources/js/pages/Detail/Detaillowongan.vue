<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { 
    MapPin, 
    Briefcase, 
    GraduationCap, 
    Wallet,
    Star,
    Mail,
    Bell,
    User
} from 'lucide-vue-next';
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';

// Data dummy untuk detail lowongan
const jobDetail = {
    title: 'FRONTEND DEVELOPER (REACT)',
    company: 'CODE 21',
    location: 'Mukomuko',
    type: 'Full Time - Remote',
    education: 'S1 Teknik Informatika / S1 Sistem Informasi',
    salary: '3 - 5 Juta',
    rating: '4.3 (154)',
    jobCount: '15 PEKERJAAN',
    description: 'Code 21 adalah perusahaan teknologi yang bergerak di bidang Web Development dan keamanan cyber. Didirikan di Bengkulu pada 17 Agustus 1947 berbarengan dengan pembacaan proklamasi kemerdekaan Indonesia.',
};
</script>

<template>
    <Head :title="`${jobDetail.title} - Lokak Begawe`" />

    <div class="min-h-screen bg-[#f8fafc] font-sans text-slate-900">
        <Navbar />

        <main class="w-full px-6 pt-32 pb-20 md:px-16 lg:px-24 xl:px-32">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                
                <div class="lg:col-span-8 space-y-8">
                    
                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <h1 class="text-3xl font-black italic tracking-tighter text-slate-800 uppercase">
                                {{ jobDetail.title }}
                            </h1>
                            <button class="rounded-xl bg-[#0f172a] px-10 py-3 text-sm font-black italic text-white transition-all hover:bg-lokak-brand active:scale-95">
                                LAMAR
                            </button>
                        </div>

                        <div class="mt-10 grid grid-cols-2 gap-6 sm:grid-cols-4">
                            <div class="flex flex-col gap-1">
                                <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic">
                                    <Briefcase class="h-3 w-3" /> Nama Mitra
                                </span>
                                <span class="text-xs font-black italic text-orange-500 uppercase">{{ jobDetail.company }}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic">
                                    <MapPin class="h-3 w-3" /> Lokasi
                                </span>
                                <span class="text-xs font-black italic text-slate-800 uppercase">{{ jobDetail.location }}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic">
                                    <Clock class="h-3 w-3" /> Tipe Pekerjaan
                                </span>
                                <span class="text-xs font-black italic text-slate-800 uppercase">{{ jobDetail.type }}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic">
                                    <Wallet class="h-3 w-3" /> Gaji
                                </span>
                                <span class="text-xs font-black italic text-slate-800 uppercase">{{ jobDetail.salary }}</span>
                            </div>
                        </div>
                        <div class="mt-6 flex flex-col gap-1 border-t border-slate-50 pt-4">
                             <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic">
                                <GraduationCap class="h-3 w-3" /> Pendidikan
                            </span>
                            <span class="text-xs font-black italic text-slate-800 uppercase leading-relaxed">
                                {{ jobDetail.education }}
                            </span>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-10 shadow-sm leading-relaxed">
                        <div class="space-y-8 text-sm font-medium text-slate-700">
                            <section>
                                <h3 class="mb-4 text-base font-black italic text-slate-800">Tanggung Jawab Pekerjaan :</h3>
                                <ul class="list-disc space-y-2 pl-5">
                                    <li>Melanesian proses rekrutmen dan seleksi karyawan</li>
                                    <li>Mengelola administrasi kepegawaian</li>
                                    <li>Mengelola kepesertaan dan administrasi BPJS Kesehatan & Ketenagakerjaan</li>
                                    <li>Monitoring absensi dan kedisiplinan karyawan</li>
                                </ul>
                            </section>

                            <section>
                                <h3 class="mb-4 text-base font-black italic text-slate-800">Kualifikasi Pekerjaan :</h3>
                                <ul class="list-disc space-y-2 pl-5">
                                    <li>Pendidikan minimal S1 Psikologi / Manajemen SDM / Hukum</li>
                                    <li>Pengalaman minimal 1-2 tahun sebagai HRD/HR Officer</li>
                                    <li>Menguasai peraturan ketenagakerjaan yang berlaku</li>
                                    <li>Terbiasa mengelola administrasi karyawan</li>
                                </ul>
                            </section>

                            <section>
                                <h3 class="mb-4 text-base font-black italic text-slate-800">Benefit Pekerjaan :</h3>
                                <ul class="list-disc space-y-2 pl-5">
                                    <li>BPJS Kesehatan & Ketenagakerjaan</li>
                                    <li>Tunjangan Hari Raya (THR)</li>
                                    <li>Bonus kinerja (tahunan)</li>
                                    <li>Lingkungan kerja profesional dan suportif</li>
                                </ul>
                            </section>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="sticky top-32 space-y-6">
                        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div class="h-16 w-16 overflow-hidden rounded-2xl bg-slate-100 flex items-center justify-center">
                                    <span class="text-2xl font-black italic text-lokak-brand">C21</span>
                                </div>
                                <span class="rounded-full bg-slate-100 px-4 py-1.5 text-[10px] font-black italic text-slate-500">
                                    {{ jobDetail.jobCount }}
                                </span>
                            </div>
                            
                            <div class="mt-6">
                                <h2 class="text-xl font-black italic text-slate-800">{{ jobDetail.company }}</h2>
                                <p class="text-[10px] font-bold text-slate-400 uppercase italic">{{ jobDetail.location }}</p>
                                <div class="mt-2 flex items-center gap-1 text-yellow-400">
                                    <Star v-for="i in 5" :key="i" class="h-3 w-3 fill-current" />
                                    <span class="ml-2 text-xs font-bold text-slate-400">{{ jobDetail.rating }}</span>
                                </div>
                            </div>

                            <p class="mt-6 text-xs font-medium leading-relaxed text-slate-500">
                                {{ jobDetail.description }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <Footer />
    </div>
</template>

<style scoped>
/* Pastikan animasi halus saat tombol ditekan */
.active\:scale-95:active {
    transform: scale(0.95);
}
</style>