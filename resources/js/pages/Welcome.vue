<script setup lang="ts">
import { ref, onUnmounted } from 'vue';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

interface Job {
    id: number;
    title: string;
    company: string;
    salary: string;
    location: string;
    icon: string;
}

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

    <div
        class="min-h-screen overflow-x-hidden bg-lokak-bg font-sans text-lokak-text"
    >
        <Navbar />

        <main class="mx-auto max-w-7xl px-4 py-8 md:px-11">
            <section
                class="relative mb-20 grid grid-cols-1 items-center overflow-hidden rounded-[2.5rem] bg-lokak-brand p-10 text-white shadow-2xl shadow-lokak-brand/20 md:p-16 lg:grid-cols-12"
            >
                <div class="z-10 lg:col-span-8">
                    <h1
                        class="mb-6 text-5xl leading-[0.9] font-black tracking-tighter uppercase italic md:text-7xl"
                    >
                        CARI GAWE<br />DAK BETELE
                    </h1>
                    <p
                        class="mb-10 max-w-md text-xs leading-relaxed font-medium opacity-80 md:text-sm"
                    >
                        Ribuan lowongan dari perusahaan terverifikasi di
                        Bengkulu menunggumu. Gabung sekarang dan mulai karir
                        impianmu.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <button
                            class="rounded-xl bg-white px-8 py-3.5 text-xs font-black text-lokak-brand uppercase italic shadow-lg transition-transform hover:-translate-y-1 active:scale-95"
                        >
                            Daftar Sekarang
                        </button>
                        <button
                            class="rounded-xl border border-white/30 px-8 py-3.5 text-xs font-black uppercase italic transition-colors hover:bg-white/10 active:scale-95"
                        >
                            Pelajari Lebih Lanjut
                        </button>
                    </div>
                </div>

                <div
                    class="hidden items-center justify-center opacity-20 lg:col-span-4 lg:flex"
                >
                    <svg
                        width="240"
                        height="240"
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

                <div
                    class="absolute -top-20 -right-20 h-64 w-64 rounded-full bg-white/5 blur-3xl"
                ></div>
                <div
                    class="absolute -bottom-20 left-20 h-64 w-64 rounded-full bg-lokak-brand-dark/50 blur-3xl"
                ></div>
            </section>

            <section class="mb-20">
                <div class="mb-10 flex flex-col items-start">
                    <h2
                        class="text-xl font-black tracking-tight text-lokak-text uppercase italic"
                    >
                        LOWONGAN <span class="text-lokak-brand">TERBARU</span>
                    </h2>
                    <div class="mt-2 h-1 w-24 rounded-full bg-slate-200"></div>
                </div>

                <div
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4"
                >
                    <div
                        v-for="job in lowonganTerbaru"
                        :key="job.id"
                        @click="openJobDetail(job)"
                        class="group relative cursor-pointer rounded-3xl border border-slate-200 bg-white p-7 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-sky-200 hover:shadow-xl hover:shadow-sky-900/5"
                    >
                        <span
                            class="absolute top-6 right-6 rounded-full bg-sky-50 px-3 py-1 text-[9px] font-black tracking-wider text-lokak-brand uppercase"
                        >
                            Baru Saja
                        </span>

                        <div
                            class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-100 bg-slate-50 text-2xl shadow-inner transition-transform group-hover:scale-110"
                        >
                            {{ job.icon }}
                        </div>

                        <h3
                            class="mb-2 text-sm leading-tight font-black text-lokak-text uppercase transition-colors group-hover:text-lokak-brand"
                        >
                            {{ job.title }}
                        </h3>
                        <p
                            class="mb-5 text-[10px] font-bold text-lokak-text-muted uppercase"
                        >
                            {{ job.company }}
                        </p>

                        <div class="mb-8 flex flex-wrap gap-2">
                            <span
                                class="rounded-lg bg-slate-100 px-3 py-1 text-[9px] font-bold text-slate-500 uppercase"
                            >
                                Full Time
                            </span>
                            <span
                                class="rounded-lg bg-slate-100 px-3 py-1 text-[9px] font-bold text-slate-500 uppercase"
                            >
                                Magang
                            </span>
                        </div>

                        <div
                            class="flex items-center justify-between border-t border-slate-100 pt-5 text-xs"
                        >
                            <span class="font-black text-lokak-brand">{{
                                job.salary
                            }}</span>
                            <span class="font-bold text-slate-400 italic"
                                >📍 {{ job.location }}</span
                            >
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-24">
                <div class="mb-10 flex flex-col items-start">
                    <h2
                        class="text-xl font-black tracking-tight text-lokak-text uppercase italic"
                    >
                        MITRA <span class="text-lokak-brand">TERATAS</span>
                    </h2>
                    <div class="mt-2 h-1 w-24 rounded-full bg-slate-200"></div>
                </div>

                <div
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4"
                >
                    <div
                        v-for="mitra in mitraTeratas"
                        :key="mitra.id"
                        class="group rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm transition-all hover:border-sky-200 hover:shadow-lg"
                    >
                        <div
                            class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full border border-slate-100 bg-slate-50 text-3xl shadow-inner transition-transform group-hover:scale-110"
                        >
                            🏢
                        </div>
                        <h3
                            class="mb-1 text-sm font-black tracking-tight text-lokak-text uppercase transition-colors group-hover:text-lokak-brand"
                        >
                            {{ mitra.name }}
                        </h3>
                        <p
                            class="mb-5 text-[10px] font-bold text-lokak-text-muted uppercase"
                        >
                            {{ mitra.location }}
                        </p>

                        <div
                            class="mb-6 flex items-center justify-center gap-3 text-[10px]"
                        >
                            <span class="font-bold text-yellow-500"
                                >⭐ {{ mitra.rating }}</span
                            >
                            <span
                                class="rounded-md bg-lokak-brand px-3 py-1 font-bold text-white uppercase shadow-sm"
                            >
                                {{ mitra.jobs }}
                            </span>
                        </div>

                        <button
                            class="text-[10px] font-black text-slate-400 uppercase transition hover:text-lokak-brand"
                        >
                            Lihat Detail →
                        </button>
                    </div>
                </div>
            </section>
        </main>

        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
        >
            <div
                class="absolute inset-0 bg-lokak-text/40 backdrop-blur-sm transition-opacity"
                @click="closeModal"
            ></div>

            <div
                class="relative z-10 w-full max-w-lg animate-in overflow-hidden rounded-[2.5rem] bg-white shadow-2xl duration-200 zoom-in-95"
            >
                <div class="p-8 md:p-12">
                    <div class="mb-8 flex items-start justify-between">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-2xl border border-slate-100 bg-slate-50 text-3xl shadow-inner"
                        >
                            {{ selectedJob?.icon }}
                        </div>
                        <button
                            @click="closeModal"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition-colors hover:bg-slate-100 hover:text-lokak-text"
                        >
                            <span class="text-lg font-bold">✕</span>
                        </button>
                    </div>

                    <h2
                        class="mb-2 text-2xl font-black tracking-tight text-lokak-text uppercase"
                    >
                        {{ selectedJob?.title }}
                    </h2>
                    <p
                        class="mb-8 text-xs font-bold tracking-wider text-lokak-brand uppercase"
                    >
                        {{ selectedJob?.company }}
                    </p>

                    <div
                        class="mb-10 space-y-4 rounded-2xl border border-slate-100 bg-slate-50 p-6"
                    >
                        <div class="flex items-center gap-3 text-xs font-bold">
                            <span class="w-20 text-lokak-text-muted uppercase"
                                >Gaji</span
                            >
                            <span class="text-lokak-text">{{
                                selectedJob?.salary
                            }}</span>
                        </div>
                        <div class="h-px w-full bg-slate-200"></div>
                        <div class="flex items-center gap-3 text-xs font-bold">
                            <span class="w-20 text-lokak-text-muted uppercase"
                                >Lokasi</span
                            >
                            <span class="text-lokak-text"
                                >📍 {{ selectedJob?.location }}</span
                            >
                        </div>
                    </div>

                    <button
                        class="w-full rounded-2xl bg-lokak-brand py-4 text-xs font-black tracking-widest text-white uppercase shadow-lg shadow-lokak-brand/20 transition-all hover:bg-lokak-brand-dark active:scale-[0.98]"
                    >
                        Lamar Pekerjaan Ini
                    </button>
                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>

<style scoped>
/* Menghilangkan ring fokus default browser agar border kustom bekerja baik */
input:focus,
select:focus,
textarea:focus,
button:focus {
    outline: none !important;
}

/* Memastikan modal tidak mematahkan layout jika tampilannya panjang */
.animate-in {
    animation-duration: 0.2s;
}
</style>
