<script setup lang="ts">
import Navbar from '@/components/navbar.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

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
        class="min-h-screen overflow-x-hidden bg-[#DDEEF3] font-sans text-[#1A313C]"
    >
        <Navbar />

        <main class="mx-auto max-w-7xl px-4 py-12 md:px-11">
            <div class="mb-10">
                <h2
                    class="text-3xl font-black tracking-tighter text-[#1A313C] uppercase italic md:text-5xl"
                >
                    CARI LOWONGAN
                </h2>
                <p
                    class="mt-2 text-xs font-bold tracking-widest uppercase opacity-60 md:text-sm"
                >
                    Temukan masa depanmu di tanah Bengkulu
                </p>
            </div>

            <div
                class="mb-16 flex flex-col gap-4 rounded-4xl border border-white/20 bg-white/40 p-4 shadow-sm backdrop-blur-sm md:flex-row"
            >
                <div class="flex-1">
                    <input
                        v-model="searchForm.keyword"
                        type="text"
                        placeholder="Masukan Kata Kunci (Contoh: Admin, Driver...)"
                        class="w-full rounded-2xl border-none bg-white p-4 text-xs font-bold shadow-sm outline-none focus:ring-2 focus:ring-[#598392]/20"
                    />
                </div>
                <div class="flex flex-col gap-4 sm:flex-row">
                    <select
                        v-model="searchForm.category"
                        class="min-w-40 rounded-2xl border-none bg-white p-4 text-xs font-bold text-[#1A313C] shadow-sm outline-none"
                    >
                        <option value="">Semua Kategori</option>
                        <option value="it">IT & Software</option>
                        <option value="design">Design</option>
                        <option value="marketing">Marketing</option>
                    </select>
                    <select
                        v-model="searchForm.location"
                        class="min-w-40 rounded-2xl border-none bg-white p-4 text-xs font-bold text-[#1A313C] shadow-sm outline-none"
                    >
                        <option value="">Pilih Lokasi</option>
                        <option value="bengkulu">Kota Bengkulu</option>
                        <option value="mukomuko">Mukomuko</option>
                        <option value="curup">Rejang Lebong</option>
                    </select>
                    <button
                        @click="handleSearch"
                        class="flex items-center justify-center gap-2 rounded-2xl bg-[#1A313C] px-10 py-4 text-xs font-black text-white uppercase shadow-lg transition hover:bg-[#598392]"
                    >
                        🔍 CARI SEKARANG
                    </button>
                </div>
            </div>

            <div
                class="mb-16 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4"
            >
                <div
                    v-for="n in 8"
                    :key="n"
                    class="group relative rounded-[35px] border border-slate-50 bg-white p-7 shadow-sm transition-all duration-300 hover:shadow-2xl"
                >
                    <span
                        class="absolute top-6 right-6 rounded-full bg-teal-100 px-3 py-1 text-[9px] font-black tracking-tighter text-teal-800 uppercase italic"
                        >Terbaru</span
                    >

                    <div
                        class="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-3xl transition duration-300 group-hover:scale-110"
                    >
                        <span v-if="n % 2 == 0">🎓</span>
                        <span v-else>💎</span>
                    </div>

                    <h3
                        class="mb-1 text-lg leading-tight font-black tracking-tight uppercase transition group-hover:text-[#598392]"
                    >
                        Frontend Developer
                    </h3>
                    <p
                        class="mb-5 text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                    >
                        Universitas Dehasen
                    </p>

                    <div class="mb-8 flex gap-2">
                        <span
                            class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-1.5 text-[9px] font-black text-slate-500 uppercase italic"
                            >Full time</span
                        >
                        <span
                            class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-1.5 text-[9px] font-black text-slate-500 uppercase italic"
                            >Remote</span
                        >
                    </div>

                    <div
                        class="mt-2 flex items-center justify-between border-t border-slate-50 pt-6 text-[11px]"
                    >
                        <span class="font-black text-[#598392]"
                            >Rp 8-12 Juta</span
                        >
                        <span
                            class="flex items-center gap-1 font-black tracking-tighter text-slate-400 uppercase italic"
                            >📍 Kota Bengkulu</span
                        >
                    </div>

                    <button
                        class="mt-7 w-full rounded-2xl bg-[#F8FAFB] py-4 text-[10px] font-black tracking-widest text-[#1A313C] uppercase italic shadow-sm transition hover:bg-[#1A313C] hover:text-white"
                    >
                        Lihat Detail
                    </button>
                </div>
            </div>

            <div class="mt-10 flex items-center justify-center gap-4">
                <button
                    class="group flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-100 bg-white shadow-sm transition hover:bg-[#1A313C] hover:text-white"
                >
                    <span class="transition group-hover:scale-125">←</span>
                </button>
                <div class="flex items-center gap-2 text-xs font-black">
                    <button
                        class="h-10 w-10 rounded-xl text-[#598392] transition hover:bg-white"
                    >
                        01
                    </button>
                    <button
                        class="h-10 w-10 rounded-xl transition hover:bg-white"
                    >
                        02
                    </button>
                    <button
                        class="h-10 w-10 rounded-xl bg-[#1A313C] text-white shadow-lg"
                    >
                        03
                    </button>
                    <button
                        class="h-10 w-10 rounded-xl transition hover:bg-white"
                    >
                        04
                    </button>
                    <span class="px-2 opacity-30">...</span>
                </div>
                <button
                    class="group flex h-12 w-12 items-center justify-center rounded-2xl bg-[#598392] text-white shadow-lg transition hover:bg-[#1A313C]"
                >
                    <span class="transition group-hover:scale-125">→</span>
                </button>
            </div>
        </main>

        <footer
            class="mt-20 border-t border-white/10 bg-[#598392] px-11 py-12 text-[#DDEEF3]"
        >
            <div
                class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-8 md:flex-row"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-700 font-black text-white italic shadow-xl"
                    >
                        L
                    </div>
                    <div class="flex flex-col">
                        <span
                            class="text-xl font-black tracking-tighter text-white uppercase"
                            >LOKAKBEGAWE</span
                        >
                        <span class="text-[9px] text-slate-300 italic"
                            >Bengkulu Pride Project</span
                        >
                    </div>
                </div>
                <div
                    class="text-center text-[9px] leading-loose font-black tracking-[0.3em] uppercase opacity-50 md:text-right"
                >
                    © 2026 LOKAK BEGAWE - Ado Lokak Pela Begawe<br />
                    Handcrafted for Bengkulu
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* Animasi Fade In */
.grid > div {
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

/* Stagger delay untuk tiap card */
.grid > div:nth-child(1) {
    animation-delay: 0.1s;
}
.grid > div:nth-child(2) {
    animation-delay: 0.15s;
}
.grid > div:nth-child(3) {
    animation-delay: 0.2s;
}
.grid > div:nth-child(4) {
    animation-delay: 0.25s;
}
.grid > div:nth-child(5) {
    animation-delay: 0.3s;
}
.grid > div:nth-child(6) {
    animation-delay: 0.35s;
}
.grid > div:nth-child(7) {
    animation-delay: 0.4s;
}
.grid > div:nth-child(8) {
    animation-delay: 0.45s;
}
</style>
