<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'; // Pastikan Link diimport
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

const isModalOpen = ref(false);
const selectedJob = ref<Job | null>(null);

const openJobDetail = (job: Job) => {
    selectedJob.value = job;
    isModalOpen.value = true;
    document.body.style.overflow = 'hidden';
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedJob.value = null;
    document.body.style.overflow = 'auto';
};

onUnmounted(() => {
    document.body.style.overflow = 'auto';
});

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
        class="mt-12 min-h-screen overflow-x-hidden bg-lokak-bg font-sans text-lokak-text selection:bg-lokak-brand selection:text-white"
    >
        <Navbar />

        <main class="w-full px-6 py-12 md:px-16 lg:px-24 xl:px-32">
            <section
                class="relative mb-28 flex min-h-[500px] flex-col items-stretch overflow-hidden rounded-[3.5rem] bg-lokak-brand text-white shadow-2xl shadow-sky-900/20 lg:flex-row"
            >
                <div
                    class="relative z-20 flex w-full flex-col justify-center p-12 md:p-20 lg:w-3/5"
                >
                    <h1
                        class="mb-10 text-6xl leading-[0.8] font-black tracking-tighter uppercase italic md:text-8xl xl:text-7xl"
                    >
                        CARI GAWE<br />
                        DAK BETELE
                    </h1>
                    <p
                        class="mb-14 max-w-2xl text-base leading-relaxed font-medium text-sky-50 md:text-xl"
                    >
                        Ribuan lowongan dari perusahaan terverifikasi di
                        Bengkulu menunggumu. Ado loker, pela begawe sekarang!
                    </p>
                    <div class="flex flex-wrap gap-6">
                        <button
                            class="rounded-3xl bg-white px-12 py-6 text-sm font-black text-lokak-brand uppercase italic shadow-2xl transition-all hover:-translate-y-1 hover:bg-sky-50 active:scale-95"
                        >
                            Daftar Sekarang
                        </button>
                        <button
                            class="rounded-3xl border-2 border-white/30 px-12 py-6 text-sm font-black uppercase italic backdrop-blur-md transition-all hover:bg-white/10 active:scale-95"
                        >
                            Pelajari Lebih Lanjut
                        </button>
                    </div>
                </div>

                <div
                    class="relative min-h-[300px] w-full lg:min-h-full lg:w-2/5"
                >
                    <div
                        class="absolute inset-0 z-10 hidden bg-gradient-to-r from-lokak-brand via-transparent to-transparent lg:block"
                    ></div>
                    <div
                        class="absolute inset-0 z-10 bg-gradient-to-t from-lokak-brand via-transparent to-transparent lg:hidden"
                    ></div>

                    <img
                        src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&q=80"
                        alt="Loker Bengkulu"
                        class="absolute inset-0 h-full w-full object-cover brightness-75 grayscale-[20%] lg:brightness-100"
                    />
                </div>

                <div
                    class="absolute -top-20 -right-20 z-0 h-96 w-96 rounded-full bg-white/10 blur-[120px]"
                ></div>
                <div
                    class="absolute -bottom-20 left-20 z-0 h-96 w-96 rounded-full bg-sky-900/40 blur-[120px]"
                ></div>
            </section>

            <section class="mb-32">
                <div class="mb-14 flex items-end justify-between">
                    <div class="flex flex-col">
                        <h2
                            class="text-3xl font-black tracking-tight text-lokak-text uppercase italic"
                        >
                            LOWONGAN
                            <span class="text-lokak-brand">TERBARU</span>
                        </h2>
                        <div
                            class="mt-4 h-2 w-32 rounded-full bg-lokak-brand"
                        ></div>
                    </div>
                    <Link
                        href="/lowongan"
                        class="hidden text-xs font-black tracking-widest text-slate-400 uppercase italic hover:text-lokak-brand md:block"
                    >
                        Lihat Semua Loker →
                    </Link>
                </div>

                <div
                    class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                >
                    <div
                        v-for="job in lowonganTerbaru"
                        :key="job.id"
                        @click="openJobDetail(job)"
                        class="group relative cursor-pointer rounded-[3rem] border border-slate-100 bg-white p-10 shadow-sm transition-all duration-500 hover:-translate-y-3 hover:border-sky-200 hover:shadow-2xl hover:shadow-sky-900/10"
                    >
                        <span
                            class="absolute top-10 right-10 rounded-full bg-sky-50 px-4 py-1.5 text-[10px] font-black tracking-wider text-lokak-brand uppercase italic"
                        >
                            Baru
                        </span>
                        <div
                            class="mb-10 flex h-20 w-20 items-center justify-center rounded-3xl bg-slate-50 text-4xl shadow-inner transition-transform group-hover:scale-110 group-hover:rotate-6"
                        >
                            {{ job.icon }}
                        </div>
                        <h3
                            class="mb-3 text-lg leading-tight font-black text-lokak-text uppercase italic group-hover:text-lokak-brand"
                        >
                            {{ job.title }}
                        </h3>
                        <p
                            class="mb-8 text-xs font-bold text-slate-400 uppercase italic"
                        >
                            {{ job.company }}
                        </p>
                        <div class="mb-12 flex gap-3">
                            <span
                                class="rounded-xl bg-slate-100 px-4 py-2 text-[10px] font-bold text-slate-500 uppercase italic"
                                >Full Time</span
                            >
                        </div>
                        <div
                            class="flex items-center justify-between border-t border-slate-50 pt-8"
                        >
                            <span
                                class="text-base font-black text-lokak-brand italic"
                                >{{ job.salary }}</span
                            >
                            <span
                                class="text-[10px] font-bold text-slate-400 uppercase italic"
                                >📍 {{ job.location }}</span
                            >
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-40">
                <div class="mb-14 flex flex-col items-start">
                    <h2
                        class="text-3xl font-black tracking-tight text-lokak-text uppercase italic"
                    >
                        MITRA <span class="text-lokak-brand">TERATAS</span>
                    </h2>
                    <div
                        class="mt-4 h-2 w-32 rounded-full bg-lokak-brand"
                    ></div>
                </div>

                <div
                    class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4"
                >
                    <div
                        v-for="mitra in mitraTeratas"
                        :key="mitra.id"
                        class="group rounded-[3rem] border border-slate-100 bg-white p-12 text-center shadow-sm transition-all duration-500 hover:-translate-y-3 hover:border-sky-200 hover:shadow-2xl"
                    >
                        <div
                            class="mx-auto mb-10 flex h-28 w-28 items-center justify-center rounded-full border border-slate-50 bg-slate-50 text-5xl shadow-inner transition-transform duration-500 group-hover:scale-110"
                        >
                            🏢
                        </div>
                        <h3
                            class="mb-2 text-lg font-black text-lokak-text uppercase italic transition-colors group-hover:text-lokak-brand"
                        >
                            {{ mitra.name }}
                        </h3>
                        <p
                            class="mb-8 text-xs font-bold tracking-wide text-slate-400 uppercase italic"
                        >
                            {{ mitra.location }}
                        </p>
                        <div
                            class="mb-10 flex items-center justify-center gap-5"
                        >
                            <span
                                class="text-xs font-black tracking-tighter text-amber-500 italic"
                                >⭐ {{ mitra.rating }}</span
                            >
                            <span
                                class="rounded-2xl bg-lokak-brand px-5 py-2 text-[10px] font-black text-white uppercase italic shadow-lg shadow-sky-900/20"
                            >
                                {{ mitra.jobs }}
                            </span>
                        </div>
                        <button
                            class="text-[11px] font-black tracking-[0.2em] text-slate-400 uppercase italic transition-colors hover:text-lokak-brand"
                        >
                            Lihat Profil →
                        </button>
                    </div>
                </div>
            </section>
        </main>

        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-100 flex items-center justify-center p-6"
        >
            <div
                class="absolute inset-0 bg-lokak-text/60 backdrop-blur-xl transition-opacity"
                @click="closeModal"
            ></div>
            <div
                class="relative z-10 w-full max-w-2xl animate-in rounded-[4rem] bg-white p-12 shadow-2xl duration-300 zoom-in-95 md:p-20"
            >
                <div class="mb-12 flex items-start justify-between">
                    <div
                        class="flex h-24 w-24 items-center justify-center rounded-4xl border border-slate-100 bg-slate-50 text-5xl shadow-inner"
                    >
                        {{ selectedJob?.icon }}
                    </div>
                    <button
                        @click="closeModal"
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition-all hover:bg-rose-50 hover:text-rose-500"
                    >
                        <span class="text-2xl font-bold">✕</span>
                    </button>
                </div>
                <h2
                    class="mb-4 text-4xl leading-tight font-black text-lokak-text uppercase italic"
                >
                    {{ selectedJob?.title }}
                </h2>
                <p
                    class="mb-12 text-base font-black tracking-widest text-lokak-brand uppercase italic"
                >
                    {{ selectedJob?.company }}
                </p>
                <div
                    class="mb-14 space-y-6 rounded-[2.5rem] border border-slate-50 bg-slate-50 p-10"
                >
                    <div
                        class="flex items-center justify-between text-xs font-black uppercase italic"
                    >
                        <span class="tracking-widest text-slate-400"
                            >Gaji Bulanan</span
                        >
                        <span class="text-lokak-text">{{
                            selectedJob?.salary
                        }}</span>
                    </div>
                    <div class="h-px w-full bg-slate-200"></div>
                    <div
                        class="flex items-center justify-between text-xs font-black uppercase italic"
                    >
                        <span class="tracking-widest text-slate-400"
                            >Lokasi Kerja</span
                        >
                        <span class="text-lokak-text"
                            >📍 {{ selectedJob?.location }}</span
                        >
                    </div>
                </div>
                <button
                    class="w-full rounded-4xl bg-lokak-brand py-8 text-base font-black text-white uppercase italic shadow-2xl shadow-sky-900/30 transition-all hover:-translate-y-1 hover:brightness-110 active:scale-95"
                >
                    Lamar Pekerjaan Sekarang
                </button>
            </div>
        </div>

        <Footer />
    </div>
</template>

<style scoped>
*:focus {
    outline: none !important;
}

.transition-all {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-in {
    animation: zoomIn 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

@keyframes zoomIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>
