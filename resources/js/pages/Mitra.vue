<script setup lang="ts">
import Navbar from '@/components/navbar.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// Definisikan Props
defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();

// Form untuk fitur saran di footer
const suggestionForm = useForm({
    email: '',
    message: '',
});

const submitSuggestion = () => {
    suggestionForm.reset();
    alert('Saran berhasil dikirim!');
};

// Data Dummy untuk Mitra
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
        name: 'CODE 21',
        location: 'MUKOMUKO',
        jobs: '15 PEKERJAAN',
        rating: '4.3 (154)',
        logo: '💎',
    },
    {
        id: 3,
        name: 'DE CODE',
        location: 'KOTA BENGKULU',
        jobs: '15 PEKERJAAN',
        rating: '4.3 (154)',
        logo: '💻',
    },
    {
        id: 4,
        name: 'PHINCON ACADEMY',
        location: 'MANNA',
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
        name: 'CODE 21',
        location: 'MUKOMUKO',
        jobs: '15 PEKERJAAN',
        rating: '4.3 (154)',
        logo: '💎',
    },
    {
        id: 7,
        name: 'DE CODE',
        location: 'KOTA BENGKULU',
        jobs: '15 PEKERJAAN',
        rating: '4.3 (154)',
        logo: '💻',
    },
    {
        id: 8,
        name: 'PHINCON ACADEMY',
        location: 'MANNA',
        jobs: '15 PEKERJAAN',
        rating: '4.3 (154)',
        logo: '🛡️',
    },
];
</script>

<template>
    <Head title="Cari Mitra - Lokak Begawe" />

    <div
        class="min-h-screen overflow-x-hidden bg-[#F1EDE4] font-sans text-[#1A313C]"
    >
        <Navbar />

        <main class="mx-auto max-w-7xl px-4 py-10 md:px-11">
            <div class="mb-8">
                <h1 class="text-2xl font-black text-[#598392] uppercase italic">
                    CARI <span class="text-[#1A313C]">MITRA</span>
                </h1>
            </div>

            <section class="mb-12">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                    <input
                        type="text"
                        placeholder="Masukan Kata Kunci"
                        class="rounded-md border-none bg-[#F8F5F0] px-4 py-3 text-xs shadow-sm focus:ring-1 focus:ring-[#598392]"
                    />
                    <select
                        class="rounded-md border-none bg-[#F8F5F0] px-4 py-3 text-xs text-gray-400 shadow-sm"
                    >
                        <option>Kategori</option>
                    </select>
                    <input
                        type="text"
                        placeholder="Lokasi"
                        class="rounded-md border-none bg-[#F8F5F0] px-4 py-3 text-xs shadow-sm focus:ring-1 focus:ring-[#598392]"
                    />
                    <button
                        class="flex items-center justify-center gap-2 rounded-md bg-[#1A313C] text-xs font-bold text-white transition hover:bg-slate-800"
                    >
                        <span>🔍</span> CARI
                    </button>
                </div>
            </section>

            <section class="mb-10">
                <h2
                    class="mb-8 text-lg font-black text-[#1A313C] uppercase italic"
                >
                    MITRA <span class="text-[#598392]">TERATAS</span>
                </h2>

                <div
                    class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4"
                >
                    <div
                        v-for="mitra in mitraList"
                        :key="mitra.id"
                        class="relative flex flex-col items-center rounded-[2.5rem] border border-gray-100 bg-white p-6 text-center shadow-sm transition-shadow hover:shadow-md"
                    >
                        <h3
                            class="mb-0.5 text-[11px] font-black tracking-tight text-[#598392] uppercase"
                        >
                            {{ mitra.name }}
                        </h3>
                        <p
                            class="mb-4 text-[9px] font-bold text-gray-400 uppercase"
                        >
                            {{ mitra.location }}
                        </p>

                        <div
                            class="mb-4 flex h-20 w-20 items-center justify-center rounded-full border border-gray-50 bg-gray-50 text-3xl"
                        >
                            {{ mitra.logo }}
                        </div>

                        <div
                            class="mt-2 mb-4 flex w-full items-center justify-between"
                        >
                            <span class="text-[10px] font-bold text-yellow-500"
                                >⭐ {{ mitra.rating }}</span
                            >
                            <span
                                class="rounded-md bg-[#598392] px-3 py-1 text-[8px] font-bold text-white uppercase"
                                >{{ mitra.jobs }}</span
                            >
                        </div>

                        <button
                            class="w-full rounded-xl bg-[#F8F5F0] py-2.5 text-[10px] font-black text-[#1A313C] uppercase italic transition hover:bg-gray-200"
                        >
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </section>

            <div
                class="mt-12 mb-20 flex items-center justify-center gap-2 text-[10px] font-bold text-gray-400"
            >
                <button
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-sm"
                >
                    ←
                </button>
                <button class="px-3">1</button>
                <button class="px-3">2</button>
                <button class="px-3">3</button>
                <button
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#1A313C] text-white shadow-md"
                >
                    4
                </button>
                <button class="px-3">5</button>
                <span>...578</span>
                <button
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#598392] text-white shadow-sm"
                >
                    →
                </button>
            </div>
        </main>

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
                    <a href="#" class="hover:underline">Logo</a>
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
                    class="rounded-4xl bg-[#6C94A1] p-6 shadow-inner lg:col-span-3"
                >
                    <h4
                        class="mb-4 text-[10px] font-black text-white uppercase italic"
                    >
                        BERIKAN SARAN
                    </h4>
                    <form @submit.prevent="submitSuggestion" class="space-y-2">
                        <input
                            v-model="suggestionForm.email"
                            type="email"
                            placeholder="Email kamu..."
                            class="w-full rounded-xl border-none bg-white p-3 text-[10px] text-[#1A313C] outline-none"
                        />
                        <textarea
                            v-model="suggestionForm.message"
                            placeholder="Pesan Kamu..."
                            rows="2"
                            class="w-full resize-none rounded-xl border-none bg-white p-3 text-[10px] text-[#1A313C] outline-none"
                        ></textarea>
                        <button
                            type="submit"
                            class="w-full rounded-xl bg-white py-3 text-[10px] font-black text-[#598392] uppercase italic shadow-md"
                        >
                            Kirim Saran
                        </button>
                    </form>
                </div>
            </div>
            <div
                class="mx-auto mt-16 max-w-7xl border-t border-white/10 pt-8 text-center text-[8px] font-black tracking-[0.3em] uppercase opacity-40"
            >
                © 2026 LOKAK BEGAWE - HAK CIPTA DILINDUNGI UNDANG-UNDANG
            </div>
        </footer>
    </div>
</template>
