<script setup lang="ts">
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();

// Data Dummy untuk Mitra (Aura Kota Bengkulu/Provinsi Bengkulu)
const mitraList = [
    {
        id: 1,
        name: 'UNIVERSITAS DEHASEN',
        location: 'KOTA BENGKULU',
        jobs: '15 PEKERJAAN',
        rating: '4.3 (154)',
        logo: '🎓',
    },
    {
        id: 2,
        name: 'CODE 21 BENGKULU',
        location: 'MUKOMUKO',
        jobs: '15 PEKERJAAN',
        rating: '4.3 (154)',
        logo: '💎',
    },
    {
        id: 3,
        name: 'DE CODE BENGKULU',
        location: 'KOTA BENGKULU',
        jobs: '15 PEKERJAAN',
        rating: '4.3 (154)',
        logo: '💻',
    },
    {
        id: 4,
        name: 'PHINCON ACADEMY BKL',
        location: 'MANNA, BENGKULU SELATAN',
        jobs: '15 PEKERJAAN',
        rating: '4.3 (154)',
        logo: '🛡️',
    },
    {
        id: 5,
        name: 'UNIVERSITAS DEHASEN',
        location: 'KOTA BENGKULU',
        jobs: '15 PEKERJAAN',
        rating: '4.3 (154)',
        logo: '🎓',
    },
    {
        id: 6,
        name: 'CODE 21 BENGKULU',
        location: 'MUKOMUKO',
        jobs: '15 PEKERJAAN',
        rating: '4.3 (154)',
        logo: '💎',
    },
    {
        id: 7,
        name: 'DE CODE BENGKULU',
        location: 'KOTA BENGKULU',
        jobs: '15 PEKERJAAN',
        rating: '4.3 (154)',
        logo: '💻',
    },
    {
        id: 8,
        name: 'PHINCON ACADEMY BKL',
        location: 'MANNA, BENGKULU SELATAN',
        jobs: '15 PEKERJAAN',
        rating: '4.3 (154)',
        logo: '🛡️',
    },
];
</script>

<template>
    <Head title="Cari Mitra - Lokak Begawe" />

    <div
        class="min-h-screen overflow-x-hidden bg-lokak-bg font-sans text-lokak-text"
    >
        <Navbar />

        <main class="mx-auto max-w-7xl px-4 py-10 md:px-11">
            <div
                class="mb-12 w-full rounded-[2.5rem] bg-white p-8 shadow-[0_20px_50px_rgba(0,0,0,0.05)] sm:p-12"
            >
                <div class="mb-10 flex flex-col items-center text-center">
                    <h2
                        class="mb-2 text-sm font-bold tracking-[0.2em] text-slate-400 uppercase"
                    >
                        Portal Kemitraan
                    </h2>
                    <h1
                        class="text-3xl leading-tight font-black tracking-tight text-lokak-text uppercase italic"
                    >
                        Cari <span class="text-lokak-brand">Mitra</span>
                    </h1>
                    <p
                        class="mt-2 text-xs font-medium tracking-wide text-lokak-text-muted uppercase"
                    >
                        Temukan Perusahaan Penyedia Lowongan Kerja Terbaik di
                        Bengkulu
                    </p>
                </div>

                <section class="mb-12">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                        <input
                            type="text"
                            placeholder="Masukan Kata Kunci (contoh: 'Teknologi')"
                            class="h-12 rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-medium shadow-sm transition-all outline-none placeholder:text-slate-400 focus:border-lokak-brand focus:bg-white focus:ring-1 focus:ring-lokak-brand"
                        />
                        <select
                            class="h-12 rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-medium text-slate-400 shadow-sm transition-all outline-none focus:border-lokak-brand focus:bg-white focus:ring-1 focus:ring-lokak-brand"
                        >
                            <option>Kategori Industri</option>
                            <option>Teknologi</option>
                            <option>Pendidikan</option>
                            <option>Kesehatan</option>
                        </select>
                        <input
                            type="text"
                            placeholder="Lokasi (contoh: 'Kota Bengkulu')"
                            class="h-12 rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm font-medium shadow-sm transition-all outline-none placeholder:text-slate-400 focus:border-lokak-brand focus:bg-white focus:ring-1 focus:ring-lokak-brand"
                        />
                        <button
                            class="flex h-12 items-center justify-center gap-2 rounded-xl bg-lokak-brand text-sm font-black text-white uppercase shadow-lg shadow-lokak-brand/20 transition-all hover:bg-lokak-brand-dark hover:shadow-lokak-brand/30 active:scale-[0.98]"
                        >
                            <span>🔍</span> CARI MITRA
                        </button>
                    </div>
                </section>

                <section class="mb-10">
                    <div class="mb-8 flex flex-col items-start">
                        <h2
                            class="text-lg font-black tracking-tight text-lokak-text uppercase italic"
                        >
                            Mitra <span class="text-lokak-brand">Teratas</span>
                        </h2>
                        <div
                            class="mt-1 h-1 w-20 rounded-full bg-slate-200"
                        ></div>
                    </div>

                    <div
                        class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4"
                    >
                        <div
                            v-for="mitra in mitraList"
                            :key="mitra.id"
                            class="group relative flex flex-col items-center rounded-xl border border-slate-200 bg-white p-6 text-center shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-sky-200 hover:shadow-xl hover:shadow-sky-900/5"
                        >
                            <h3
                                class="mb-0.5 text-xs font-black tracking-tight text-lokak-brand uppercase italic transition-colors group-hover:text-lokak-brand-dark"
                            >
                                {{ mitra.name }}
                            </h3>
                            <p
                                class="mb-4 text-xs font-bold text-lokak-text-muted uppercase"
                            >
                                {{ mitra.location }}
                            </p>

                            <div
                                class="mb-4 flex h-20 w-20 items-center justify-center rounded-full border border-slate-100 bg-slate-50 text-3xl shadow-inner transition-transform group-hover:scale-110"
                            >
                                {{ mitra.logo }}
                            </div>

                            <div
                                class="mt-2 mb-4 flex w-full items-center justify-between"
                            >
                                <span class="text-xs font-bold text-yellow-500"
                                    >⭐ {{ mitra.rating }}</span
                                >
                                <span
                                    class="rounded-md bg-lokak-brand px-3 py-1 text-xs font-bold text-white uppercase shadow-sm"
                                    >{{ mitra.jobs }}</span
                                >
                            </div>

                            <button
                                class="w-full rounded-xl bg-slate-100 py-2.5 text-xs font-black text-lokak-text uppercase transition hover:bg-slate-200 hover:text-lokak-brand"
                            >
                                Lihat Detail
                            </button>
                        </div>
                    </div>
                </section>

                <div
                    class="mt-12 flex items-center justify-center gap-2 text-xs font-bold text-slate-500"
                >
                    <button
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white shadow-sm transition hover:bg-slate-50 hover:text-lokak-brand"
                    >
                        ←
                    </button>
                    <button
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 transition hover:bg-slate-50 hover:text-lokak-brand"
                    >
                        1
                    </button>
                    <button
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 transition hover:bg-slate-50 hover:text-lokak-brand"
                    >
                        2
                    </button>
                    <button
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 transition hover:bg-slate-50 hover:text-lokak-brand"
                    >
                        3
                    </button>
                    <button
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-lokak-brand text-white shadow-lg shadow-lokak-brand/20"
                    >
                        4
                    </button>
                    <button
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 transition hover:bg-slate-50 hover:text-lokak-brand"
                    >
                        5
                    </button>
                    <span class="px-2 text-slate-400">...578</span>
                    <button
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-lokak-brand text-white shadow-lg shadow-lokak-brand/20 transition hover:bg-lokak-brand-dark"
                    >
                        →
                    </button>
                </div>
            </div>
        </main>
        <Footer />
    </div>
</template>

<style scoped>
/* Menghilangkan ring fokus default browser */
input:focus,
select:focus,
textarea:focus,
button:focus {
    outline: none !important;
}

/* Memastikan placeholder memiliki warna yang konsisten */
input::placeholder,
select::placeholder,
textarea::placeholder {
    font-weight: 500;
    opacity: 0.6;
}
</style>
