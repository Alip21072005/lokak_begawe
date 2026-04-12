<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';

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

// Data Dummy untuk Mitra (Sesuai dengan Grid di Gambar)
const mitraList = [
    { id: 1, name: 'UNIVERSITAS DEHASEN', location: 'KOTA BENGKULU', jobs: '15 PEKERJAAN', rating: '4.3 (154)', logo: '🎓' },
    { id: 2, name: 'CODE 21', location: 'MUKOMUKO', jobs: '15 PEKERJAAN', rating: '4.3 (154)', logo: '💎' },
    { id: 3, name: 'DE CODE', location: 'KOTA BENGKULU', jobs: '15 PEKERJAAN', rating: '4.3 (154)', logo: '💻' },
    { id: 4, name: 'PHINCON ACADEMY', location: 'MANNA', jobs: '15 PEKERJAAN', rating: '4.3 (154)', logo: '🛡️' },
    { id: 5, name: 'UNIVERSITAS DEHASEN', location: 'KOTA BENGKULU', jobs: '15 PEKERJAAN', rating: '4.3 (154)', logo: '🎓' },
    { id: 6, name: 'CODE 21', location: 'MUKOMUKO', jobs: '15 PEKERJAAN', rating: '4.3 (154)', logo: '💎' },
    { id: 7, name: 'DE CODE', location: 'KOTA BENGKULU', jobs: '15 PEKERJAAN', rating: '4.3 (154)', logo: '💻' },
    { id: 8, name: 'PHINCON ACADEMY', location: 'MANNA', jobs: '15 PEKERJAAN', rating: '4.3 (154)', logo: '🛡️' },
];
</script>

<template>
    <Head title="Cari Mitra - Lokak Begawe" />

    <div class="min-h-screen bg-[#F1EDE4] font-sans text-[#1A313C] overflow-x-hidden">
        
        <header class="py-3 px-4 md:px-11 w-full text-[#DDEEF3] sticky top-0 bg-[#598392] z-50 shadow-md">
            <nav class="flex items-center justify-between max-w-7xl mx-auto">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-slate-700 rounded-xl flex items-center justify-center font-black text-white italic">L</div>
                    <div class="flex flex-col leading-none">
                        <span class="font-black text-lg uppercase tracking-tighter text-white">LOKAKBEGAWE</span>
                        <span class="text-[8px] text-slate-300 italic uppercase">Ado Lokak, Pela Begawe</span>
                    </div>
                </div>

                <div class="hidden lg:flex items-center gap-7 uppercase font-extrabold text-slate-300 text-[10px]">
                    <Link href="/" class="hover:text-white transition">Beranda</Link>
                    <Link href="/lowongan" class="hover:text-white transition">Lowongan</Link>
                    <Link href="/mitra" class="text-white border-b-2 border-white pb-1">Mitra</Link>
                </div>

                <div class="flex items-center gap-2">
                    <button class="text-slate-300 hover:text-white p-1">🔔</button>
                    <Link href="/login" class="px-4 py-1.5 rounded-lg text-[9px] font-bold text-slate-300 border border-slate-700 hover:border-white transition uppercase">LOGIN USER</Link>
                    <Link href="/login-mitra" class="px-4 py-1.5 rounded-lg bg-[#1A313C] text-[9px] font-bold text-white hover:bg-slate-800 transition uppercase italic">LOGIN MITRA</Link>
                </div>
            </nav>
        </header>

        <main class="py-10 px-4 md:px-11 max-w-7xl mx-auto">
            <div class="mb-8">
                <h1 class="text-2xl font-black text-[#598392] italic uppercase italic">CARI <span class="text-[#1A313C]">MITRA</span></h1>
            </div>

            <section class="mb-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <input type="text" placeholder="Masukan Kata Kunci" class="bg-[#F8F5F0] border-none rounded-md px-4 py-3 text-xs shadow-sm focus:ring-1 focus:ring-[#598392]">
                    <select class="bg-[#F8F5F0] border-none rounded-md px-4 py-3 text-xs shadow-sm text-gray-400">
                        <option>Kategori</option>
                    </select>
                    <input type="text" placeholder="Lokasi" class="bg-[#F8F5F0] border-none rounded-md px-4 py-3 text-xs shadow-sm focus:ring-1 focus:ring-[#598392]">
                    <button class="bg-[#1A313C] text-white rounded-md font-bold text-xs flex items-center justify-center gap-2 hover:bg-slate-800 transition">
                        <span>🔍</span> CARI
                    </button>
                </div>
            </section>

            <section class="mb-10">
                <h2 class="text-lg font-black text-[#1A313C] uppercase mb-8 italic">MITRA <span class="text-[#598392]">TERATAS</span></h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div v-for="mitra in mitraList" :key="mitra.id" class="bg-white p-6 rounded-[2.5rem] shadow-sm hover:shadow-md transition-shadow border border-gray-100 flex flex-col items-center text-center relative">
                        <h3 class="font-black text-[11px] text-[#598392] mb-0.5 tracking-tight uppercase">{{ mitra.name }}</h3>
                        <p class="text-[9px] text-gray-400 font-bold mb-4 uppercase">{{ mitra.location }}</p>
                        
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-3xl mb-4 border border-gray-50">
                            {{ mitra.logo }}
                        </div>

                        <div class="flex items-center justify-between w-full mt-2 mb-4">
                            <span class="text-[10px] font-bold text-yellow-500">⭐ {{ mitra.rating }}</span>
                            <span class="px-3 py-1 bg-[#598392] text-white text-[8px] font-bold rounded-md uppercase">{{ mitra.jobs }}</span>
                        </div>

                        <button class="w-full py-2.5 bg-[#F8F5F0] text-[#1A313C] rounded-xl text-[10px] font-black uppercase italic hover:bg-gray-200 transition">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </section>

            <div class="flex justify-center items-center gap-2 mt-12 mb-20 text-[10px] font-bold text-gray-400">
                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-white shadow-sm">←</button>
                <button class="px-3">1</button>
                <button class="px-3">2</button>
                <button class="px-3">3</button>
                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#1A313C] text-white shadow-md">4</button>
                <button class="px-3">5</button>
                <span>...578</span>
                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#598392] text-white shadow-sm">→</button>
            </div>
        </main>

        <footer class="bg-[#598392] text-white py-16 px-11">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-10">
                <div class="lg:col-span-3">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-10 h-10 bg-slate-700 rounded-xl flex items-center justify-center font-black text-white italic">L</div>
                        <span class="font-black text-xl uppercase tracking-tighter">LOKAKBEGAWE</span>
                    </div>
                    <p class="text-[10px] opacity-60 font-bold uppercase italic">Ado Lokak, Pela Begawe</p>
                </div>
                
                <div class="lg:col-span-3 flex flex-col gap-4 text-[11px] font-bold uppercase italic">
                    <span class="text-white opacity-40 text-[9px] font-black">TENTANG KAMI</span>
                    <a href="#" class="hover:underline">Tentang Kami</a>
                    <a href="#" class="hover:underline">Logo</a>
                </div>

                <div class="lg:col-span-3 flex flex-col gap-4 text-[11px] font-bold uppercase italic">
                    <span class="text-white opacity-40 text-[9px] font-black">HUBUNGI KAMI</span>
                    <a href="#" class="hover:underline">Instagram</a>
                    <a href="#" class="hover:underline">WhatsApp</a>
                    <a href="#" class="hover:underline">Email</a>
                </div>

                <div class="lg:col-span-3 bg-[#6C94A1] p-6 rounded-[2rem] shadow-inner">
                    <h4 class="font-black uppercase text-[10px] mb-4 text-white italic">BERIKAN SARAN</h4>
                    <form @submit.prevent="submitSuggestion" class="space-y-2">
                        <input v-model="suggestionForm.email" type="email" placeholder="Email kamu..." class="w-full p-3 bg-white border-none rounded-xl text-[10px] text-[#1A313C] outline-none">
                        <textarea v-model="suggestionForm.message" placeholder="Pesan Kamu..." rows="2" class="w-full p-3 bg-white border-none rounded-xl text-[10px] text-[#1A313C] outline-none resize-none"></textarea>
                        <button type="submit" class="w-full py-3 bg-white text-[#598392] rounded-xl text-[10px] font-black uppercase italic shadow-md">Kirim Saran</button>
                    </form>
                </div>
            </div>
            <div class="max-w-7xl mx-auto mt-16 pt-8 border-t border-white/10 text-center text-[8px] font-black opacity-40 uppercase tracking-[0.3em]">
                © 2026 LOKAK BEGAWE - HAK CIPTA DILINDUNGI UNDANG-UNDANG
            </div>
        </footer>
    </div>
</template>