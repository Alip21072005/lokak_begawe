<script setup lang="ts">
import Navbar from '@/components/navbar.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onUnmounted, defineAsyncComponent } from 'vue';

interface Job {
    id: number;
    title: string;
    company: string;
    salary: string;
    location: string;
    icon: string;
}

// Form untuk fitur saran di footer
const suggestionForm = useForm({
    email: '',
    message: '',
});

const submitSuggestion = () => {
    console.log('Suggestion submitted:', suggestionForm.data());
    suggestionForm.reset();
    alert('Saran berhasil dikirim!');
};

// --- LOGIKA MODAL LOWONGAN ---
const isModalOpen = ref(false);
const selectedJob = ref<Job | null>(null);

const openJobDetail = (job: Job) => {
    selectedJob.value = job;
    isModalOpen.value = true;
    // Mencegah scroll pada latar belakang saat modal aktif
    document.body.style.overflow = 'hidden';
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedJob.value = null;
    document.body.style.overflow = 'auto';
};

// Pastikan overflow dikembalikan jika komponen di-unmount saat modal terbuka
onUnmounted(() => {
    document.body.style.overflow = 'auto';
});

// Data Dummy untuk Lowongan
const lowonganTerbaru: Job[] = [
    {
        id: 1,
        title: 'FRONTEND DEVELOPER (REACT)',
        company: 'UNIVERSITAS DEHASEN',
        salary: 'Rp 8-12 Juta',
        location: 'KOTA BENGKULU',
        icon: '🎓',
    },
    {
        id: 2,
        title: 'FRONTEND DEVELOPER (REACT)',
        company: 'CODE 21',
        salary: 'Rp 8-12 Juta',
        location: 'MUKOMUKO',
        icon: '💎',
    },
    {
        id: 3,
        title: 'CYBER SECURITY',
        company: 'DECODE',
        salary: 'Rp 8-9 Juta',
        location: 'KOTA BENGKULU',
        icon: '💻',
    },
    {
        id: 4,
        title: 'MENTOR BOOTCAMP (AI)',
        company: 'PHINCON ACADEMY',
        salary: 'Rp 15-20 Juta',
        location: 'MANNA',
        icon: '🤖',
    },
];

// Data Dummy untuk Mitra
const mitraTeratas = [
    {
        id: 1,
        name: 'UNIVERSITAS DEHASEN',
        location: 'Kota Bengkulu',
        jobs: '15 PEKERJAAN',
        rating: '4.3 (154)',
    },
    {
        id: 2,
        name: 'CODE 21',
        location: 'MUKOMUKO',
        jobs: '10 PEKERJAAN',
        rating: '4.3 (154)',
    },
    {
        id: 3,
        name: 'DE CODE',
        location: 'KOTA BENGKULU',
        jobs: '10 PEKERJAAN',
        rating: '4.3 (154)',
    },
    {
        id: 4,
        name: 'PHINCON ACADEMY',
        location: 'MANNA',
        jobs: '10 PEKERJAAN',
        rating: '4.3 (154)',
    },
];
</script>

<template>
    <Head title="Lokak Begawe - Bengkulu Job Portal" />
    <Navbar />
    <div
        class="min-h-screen overflow-x-hidden bg-[#DDEEF3] font-sans text-[#1A313C]"
    >
        <main class="mx-auto max-w-7xl px-4 py-8 md:px-11">
            <section
                class="relative mb-16 grid grid-cols-1 items-center overflow-hidden rounded-[40px] bg-[#598392] p-10 text-white shadow-2xl md:p-16 lg:grid-cols-12"
            >
                <div class="z-10 lg:col-span-8">
                    <h1
                        class="mb-6 text-5xl leading-[0.9] font-black tracking-tighter uppercase italic md:text-7xl"
                    >
                        CARI GAWE<br />DAK BETELE
                    </h1>
                    <p
                        class="mb-10 max-w-md text-[11px] leading-relaxed opacity-70 md:text-xs"
                    >
                        Ribuan lowongan dari perusahaan terverifikasi
                        menunggumu. Gabung sekarang dan mulai karir impianmu.
                    </p>
                    <div class="flex gap-4">
                        <button
                            class="rounded-xl bg-white px-8 py-3 text-[10px] font-black text-[#598392] uppercase italic shadow-lg"
                        >
                            Daftar Sekarang
                        </button>
                        <button
                            class="rounded-xl border-2 border-white/30 px-8 py-3 text-[10px] font-black uppercase italic"
                        >
                            Pelajari Lebih
                        </button>
                    </div>
                </div>
                <div
                    class="hidden items-center justify-center opacity-20 lg:col-span-4 lg:flex"
                >
                    <svg
                        width="200"
                        height="200"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"
                        />
                    </svg>
                </div>
            </section>

            <section class="mb-20">
                <h2
                    class="mb-10 text-xl font-black text-[#1A313C] uppercase italic"
                >
                    LOWONGAN <span class="text-[#598392]">TERBARU</span>
                </h2>
                <div
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4"
                >
                    <div
                        v-for="job in lowonganTerbaru"
                        :key="job.id"
                        @click="openJobDetail(job)"
                        class="group relative cursor-pointer rounded-[35px] border border-slate-50 bg-white p-7 shadow-sm transition-all hover:shadow-xl"
                    >
                        <span
                            class="absolute top-6 right-6 rounded-full bg-teal-100 px-3 py-1 text-[8px] font-black text-teal-800 uppercase italic"
                            >Baru Saja</span
                        >
                        <div
                            class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-2xl"
                        >
                            {{ job.icon }}
                        </div>
                        <h3
                            class="mb-1 text-sm leading-tight font-black uppercase"
                        >
                            {{ job.title }}
                        </h3>
                        <p
                            class="mb-5 text-[9px] font-bold text-slate-400 uppercase"
                        >
                            {{ job.company }}
                        </p>
                        <div class="mb-8 flex gap-2">
                            <span
                                class="rounded-full bg-slate-100 px-3 py-1 text-[8px] font-black text-slate-500 uppercase"
                                >Full Time</span
                            >
                            <span
                                class="rounded-full bg-slate-100 px-3 py-1 text-[8px] font-black text-slate-500 uppercase"
                                >Magang</span
                            >
                        </div>
                        <div
                            class="flex items-center justify-between border-t border-slate-50 pt-5 text-[10px]"
                        >
                            <span class="font-black text-[#598392]">{{
                                job.salary
                            }}</span>
                            <span class="font-bold text-slate-400 italic"
                                >📍 {{ job.location }}</span
                            >
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-20">
                <h2
                    class="mb-10 text-xl font-black text-[#1A313C] uppercase italic"
                >
                    MITRA <span class="text-[#598392]">TERATAS</span>
                </h2>
                <div
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4"
                >
                    <div
                        v-for="mitra in mitraTeratas"
                        :key="mitra.id"
                        class="rounded-[35px] border border-slate-50 bg-white p-8 text-center shadow-sm"
                    >
                        <div
                            class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 text-3xl font-bold text-slate-300"
                        >
                            🏢
                        </div>
                        <h3
                            class="mb-1 text-xs font-black tracking-tighter uppercase"
                        >
                            {{ mitra.name }}
                        </h3>
                        <p
                            class="mb-4 text-[9px] font-bold text-slate-400 uppercase italic"
                        >
                            {{ mitra.location }}
                        </p>
                        <div
                            class="mb-6 flex items-center justify-center gap-2 text-[10px]"
                        >
                            <span class="text-amber-400"
                                >⭐ {{ mitra.rating }}</span
                            >
                            <span
                                class="rounded-full bg-[#598392] px-3 py-1 text-[8px] font-black text-white uppercase"
                                >{{ mitra.jobs }}</span
                            >
                        </div>
                        <button
                            class="text-[9px] font-black text-slate-400 uppercase italic transition hover:text-[#1A313C]"
                        >
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </section>
        </main>

        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-60 flex items-center justify-center p-4"
        >
            <div
                class="absolute inset-0 mt-15 bg-slate-900/60 backdrop-blur-sm"
                @click="closeModal"
            ></div>

            <div
                class="relative z-70 w-full max-w-lg animate-in overflow-hidden rounded-[40px] bg-white shadow-2xl duration-300 fade-in zoom-in"
            >
                <div class="p-8 md:p-12">
                    <div class="mb-8 flex items-start justify-between">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-3xl"
                        >
                            {{ selectedJob?.icon }}
                        </div>
                        <button
                            @click="closeModal"
                            class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100"
                        >
                            ✕
                        </button>
                    </div>

                    <h2 class="mb-1 text-xl font-black uppercase italic">
                        {{ selectedJob?.title }}
                    </h2>
                    <p
                        class="mb-6 text-[10px] font-bold text-[#598392] uppercase"
                    >
                        {{ selectedJob?.company }}
                    </p>

                    <div class="mb-8 space-y-4">
                        <div
                            class="flex items-center gap-3 text-[11px] font-bold"
                        >
                            <span class="w-20 text-slate-400 uppercase"
                                >Gaji:</span
                            >
                            <span class="text-[#1A313C]">{{
                                selectedJob?.salary
                            }}</span>
                        </div>
                        <div
                            class="flex items-center gap-3 text-[11px] font-bold"
                        >
                            <span class="w-20 text-slate-400 uppercase"
                                >Lokasi:</span
                            >
                            <span class="text-[#1A313C]"
                                >📍 {{ selectedJob?.location }}</span
                            >
                        </div>
                    </div>

                    <button
                        class="w-full rounded-2xl bg-[#1A313C] py-4 text-[10px] font-black text-white uppercase italic shadow-lg transition-all hover:bg-[#598392]"
                    >
                        Lamar Sekarang
                    </button>
                </div>
            </div>
        </div>

        <footer class="bg-[#598392] px-11 py-16 text-white">
            <div
                class="mx-auto grid max-w-7xl grid-cols-1 gap-10 lg:grid-cols-12"
            >
                <div class="lg:col-span-3">
                    <div class="mb-6 flex items-center gap-2">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-700 font-black text-white italic"
                        >
                            L
                        </div>
                        <span
                            class="text-xl font-black tracking-tighter uppercase"
                            >LOKAKBEGAWE</span
                        >
                    </div>
                    <p
                        class="text-[10px] font-bold uppercase italic opacity-60"
                    >
                        Ado Lokak, Pela Begawe
                    </p>
                </div>

                <div
                    class="flex flex-col gap-4 text-[11px] font-bold uppercase italic lg:col-span-3"
                >
                    <span class="text-[9px] font-black text-white opacity-40"
                        >TENTANG KAMI</span
                    >
                    <a href="#" class="hover:underline">Tentang Kami</a>
                    <a href="#" class="hover:underline">Loker</a>
                </div>

                <div
                    class="flex flex-col gap-4 text-[11px] font-bold uppercase italic lg:col-span-3"
                >
                    <span class="text-[9px] font-black text-white opacity-40"
                        >HUBUNGI KAMI</span
                    >
                    <a href="#" class="hover:underline">Instagram</a>
                    <a href="#" class="hover:underline">WhatsApp</a>
                    <a href="#" class="hover:underline">Email</a>
                </div>

                <div
                    class="rounded-[35px] bg-white p-8 shadow-2xl lg:col-span-3"
                >
                    <h4
                        class="mb-4 text-[10px] font-black text-[#1A313C] uppercase italic"
                    >
                        BERIKAN SARAN
                    </h4>
                    <form @submit.prevent="submitSuggestion" class="space-y-3">
                        <input
                            v-model="suggestionForm.email"
                            type="email"
                            placeholder="Email kamu..."
                            class="w-full rounded-2xl border-none bg-slate-50 p-4 text-[10px] text-[#1A313C] shadow-inner outline-none"
                        />
                        <textarea
                            v-model="suggestionForm.message"
                            placeholder="Pesan Kamu..."
                            rows="3"
                            class="w-full resize-none rounded-2xl border-none bg-slate-50 p-4 text-[10px] text-[#1A313C] shadow-inner outline-none"
                        ></textarea>
                        <button
                            type="submit"
                            class="w-full rounded-2xl bg-[#1A313C] py-4 text-[10px] font-black text-white uppercase italic shadow-lg"
                        >
                            Kirim Saran
                        </button>
                    </form>
                </div>
            </div>
            <div
                class="mx-auto mt-20 max-w-7xl border-t border-white/10 pt-8 text-center text-[8px] font-black tracking-[0.3em] uppercase opacity-40"
            >
                © 2026 LOKAK BEGAWE - HAK CIPTA DILINDUNGI UNDANG-UNDANG
            </div>
        </footer>
    </div>
</template>
