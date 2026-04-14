<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

// Form untuk fitur pencarian (Search Bar)
const searchForm = useForm({
    keyword: '',
    category: '',
    location: '',
});

const handleSearch = () => {
    // Logic pencarian (akan dikirim ke controller nanti)
    console.log('Searching for:', searchForm.data());
};
</script>

<template>
    <Head title="Cari Lowongan - Lokak Begawe" />

    <div
        class="min-h-screen overflow-x-hidden bg-lokak-bg font-sans text-lokak-text"
    >
        <Navbar />

        <main class="mx-auto max-w-7xl px-4 py-12 md:px-11">
            <div class="mb-10">
                <h2
                    class="text-3xl font-black tracking-tighter text-lokak-text uppercase italic md:text-5xl"
                >
                    CARI <span class="text-lokak-brand">LOWONGAN</span>
                </h2>
                <div class="mt-4 mb-2 h-1 w-24 rounded-full bg-slate-200"></div>
                <p
                    class="text-xs font-bold tracking-widest text-lokak-text-muted uppercase"
                >
                    Temukan masa depanmu di tanah Bengkulu
                </p>
            </div>

            <div
                class="mb-16 flex flex-col gap-4 rounded-4xl border border-slate-200 bg-white p-4 shadow-sm md:flex-row"
            >
                <div class="flex-1">
                    <input
                        v-model="searchForm.keyword"
                        type="text"
                        placeholder="Masukan Kata Kunci (Contoh: Admin, Driver...)"
                        class="h-14 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 text-xs font-bold shadow-sm transition-all outline-none placeholder:text-slate-400 focus:border-lokak-brand focus:bg-white focus:ring-1 focus:ring-lokak-brand"
                    />
                </div>
                <div class="flex flex-col gap-4 sm:flex-row">
                    <select
                        v-model="searchForm.category"
                        class="h-14 min-w-40 rounded-xl border border-slate-200 bg-slate-50 px-5 text-xs font-bold text-lokak-text shadow-sm transition-all outline-none focus:border-lokak-brand focus:bg-white focus:ring-1 focus:ring-lokak-brand"
                    >
                        <option value="">Semua Kategori</option>
                        <option value="it">IT & Software</option>
                        <option value="design">Design</option>
                        <option value="marketing">Marketing</option>
                    </select>
                    <select
                        v-model="searchForm.location"
                        class="h-14 min-w-40 rounded-xl border border-slate-200 bg-slate-50 px-5 text-xs font-bold text-lokak-text shadow-sm transition-all outline-none focus:border-lokak-brand focus:bg-white focus:ring-1 focus:ring-lokak-brand"
                    >
                        <option value="">Pilih Lokasi</option>
                        <option value="bengkulu">Kota Bengkulu</option>
                        <option value="mukomuko">Mukomuko</option>
                        <option value="curup">Rejang Lebong</option>
                    </select>
                    <button
                        @click="handleSearch"
                        class="flex h-14 items-center justify-center gap-2 rounded-xl bg-lokak-brand px-10 text-xs font-black text-white uppercase shadow-lg shadow-lokak-brand/20 transition hover:bg-lokak-brand-dark active:scale-[0.98]"
                    >
                        🔍 CARI SEKARANG
                    </button>
                </div>
            </div>

            <div
                class="job-grid mb-16 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4"
            >
                <div
                    v-for="n in 8"
                    :key="n"
                    class="group relative flex flex-col rounded-3xl border border-slate-200 bg-white p-7 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-sky-200 hover:shadow-xl hover:shadow-sky-900/5"
                >
                    <span
                        class="absolute top-6 right-6 rounded-full bg-sky-50 px-3 py-1 text-[9px] font-black tracking-wider text-lokak-brand uppercase italic"
                    >
                        Terbaru
                    </span>

                    <div
                        class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-100 bg-slate-50 text-2xl shadow-inner transition duration-300 group-hover:scale-110"
                    >
                        <span v-if="n % 2 == 0">🎓</span>
                        <span v-else>💎</span>
                    </div>

                    <h3
                        class="mb-1 text-sm leading-tight font-black text-lokak-text uppercase transition group-hover:text-lokak-brand"
                    >
                        Frontend Developer
                    </h3>
                    <p
                        class="mb-5 text-[10px] font-bold text-lokak-text-muted uppercase"
                    >
                        Universitas Dehasen
                    </p>

                    <div class="mb-8 flex flex-wrap gap-2">
                        <span
                            class="rounded-lg bg-slate-100 px-3 py-1 text-[9px] font-bold text-slate-500 uppercase"
                        >
                            Full time
                        </span>
                        <span
                            class="rounded-lg bg-slate-100 px-3 py-1 text-[9px] font-bold text-slate-500 uppercase"
                        >
                            Remote
                        </span>
                    </div>

                    <div
                        class="mt-auto flex items-center justify-between border-t border-slate-100 pt-5 text-xs"
                    >
                        <span class="font-black text-lokak-brand"
                            >Rp 8-12 Juta</span
                        >
                        <span class="font-bold text-slate-400 italic"
                            >📍 Kota Bengkulu</span
                        >
                    </div>

                    <button
                        class="mt-6 w-full rounded-xl bg-slate-100 py-3 text-[10px] font-black text-lokak-text uppercase transition hover:bg-slate-200 hover:text-lokak-brand"
                    >
                        Lihat Detail
                    </button>
                </div>
            </div>

            <div class="mt-10 flex items-center justify-center gap-4">
                <button
                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white shadow-sm transition hover:bg-slate-50 hover:text-lokak-brand"
                >
                    ←
                </button>
                <div class="flex items-center gap-2 text-xs font-black">
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
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-lokak-brand text-white shadow-lg shadow-lokak-brand/20"
                    >
                        3
                    </button>
                    <button
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 transition hover:bg-slate-50 hover:text-lokak-brand"
                    >
                        4
                    </button>
                    <span class="px-2 text-slate-400">...</span>
                </div>
                <button
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-lokak-brand text-white shadow-lg shadow-lokak-brand/20 transition hover:bg-lokak-brand-dark"
                >
                    →
                </button>
            </div>
        </main>

        <Footer />
    </div>
</template>

<style scoped>
/* Menghilangkan ring fokus default browser */
input:focus,
select:focus,
button:focus {
    outline: none !important;
}

/* Memastikan placeholder memiliki warna yang konsisten */
input::placeholder {
    font-weight: 500;
    opacity: 0.6;
}

/* Animasi Fade In dengan Staggering */
.job-grid > div {
    animation: fadeIn 0.6s ease backwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Stagger delay untuk tiap card agar munculnya bergantian */
.job-grid > div:nth-child(1) {
    animation-delay: 0.05s;
}
.job-grid > div:nth-child(2) {
    animation-delay: 0.1s;
}
.job-grid > div:nth-child(3) {
    animation-delay: 0.15s;
}
.job-grid > div:nth-child(4) {
    animation-delay: 0.2s;
}
.job-grid > div:nth-child(5) {
    animation-delay: 0.25s;
}
.job-grid > div:nth-child(6) {
    animation-delay: 0.3s;
}
.job-grid > div:nth-child(7) {
    animation-delay: 0.35s;
}
.job-grid > div:nth-child(8) {
    animation-delay: 0.4s;
}
</style>
