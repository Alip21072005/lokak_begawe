<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
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

    <div class="min-h-screen bg-[#DDEEF3] font-sans text-[#1A313C] overflow-x-hidden">
        
        <Navbar />

        <main class="py-12 px-4 md:px-11 max-w-7xl mx-auto">
            <div class="mb-10">
                <h2 class="text-3xl md:text-5xl font-black italic uppercase tracking-tighter text-[#1A313C]">
                    CARI LOWONGAN
                </h2>
                <p class="text-xs md:text-sm font-bold opacity-60 uppercase tracking-widest mt-2">Temukan masa depanmu di tanah Bengkulu</p>
            </div>
            
            <div class="flex flex-col md:flex-row gap-4 mb-16 bg-white/40 p-4 rounded-[32px] shadow-sm backdrop-blur-sm border border-white/20">
                <div class="flex-1">
                    <input 
                        v-model="searchForm.keyword"
                        type="text" 
                        placeholder="Masukan Kata Kunci (Contoh: Admin, Driver...)" 
                        class="w-full p-4 bg-white rounded-2xl outline-none text-xs font-bold border-none shadow-sm focus:ring-2 focus:ring-[#598392]/20"
                    >
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <select v-model="searchForm.category" class="p-4 bg-white rounded-2xl text-xs font-bold outline-none shadow-sm min-w-[160px] border-none text-[#1A313C]">
                        <option value="">Semua Kategori</option>
                        <option value="it">IT & Software</option>
                        <option value="design">Design</option>
                        <option value="marketing">Marketing</option>
                    </select>
                    <select v-model="searchForm.location" class="p-4 bg-white rounded-2xl text-xs font-bold outline-none shadow-sm min-w-[160px] border-none text-[#1A313C]">
                        <option value="">Pilih Lokasi</option>
                        <option value="bengkulu">Kota Bengkulu</option>
                        <option value="mukomuko">Mukomuko</option>
                        <option value="curup">Rejang Lebong</option>
                    </select>
                    <button @click="handleSearch" class="bg-[#1A313C] text-white px-10 py-4 rounded-2xl font-black text-xs uppercase hover:bg-[#598392] transition shadow-lg flex items-center justify-center gap-2">
                        🔍 CARI SEKARANG
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
                <div v-for="n in 8" :key="n" class="bg-white p-7 rounded-[35px] shadow-sm hover:shadow-2xl transition-all duration-300 border border-slate-50 relative group">
                    <span class="absolute top-6 right-6 px-3 py-1 bg-teal-100 text-teal-800 text-[9px] font-black rounded-full uppercase italic tracking-tighter">Terbaru</span>
                    
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition duration-300">
                        <span v-if="n%2==0">🎓</span>
                        <span v-else>💎</span>
                    </div>

                    <h3 class="font-black text-lg mb-1 uppercase tracking-tight leading-tight group-hover:text-[#598392] transition">Frontend Developer</h3>
                    <p class="text-[10px] text-slate-400 font-black mb-5 uppercase italic tracking-widest">Universitas Dehasen</p>
                    
                    <div class="flex gap-2 mb-8">
                        <span class="px-3 py-1.5 bg-slate-50 border border-slate-100 text-slate-500 text-[9px] font-black rounded-xl uppercase italic">Full time</span>
                        <span class="px-3 py-1.5 bg-slate-50 border border-slate-100 text-slate-500 text-[9px] font-black rounded-xl uppercase italic">Remote</span>
                    </div>

                    <div class="flex items-center justify-between text-[11px] border-t border-slate-50 pt-6 mt-2">
                        <span class="font-black text-[#598392]">Rp 8-12 Juta</span>
                        <span class="text-slate-400 font-black flex items-center gap-1 uppercase italic tracking-tighter">📍 Kota Bengkulu</span>
                    </div>

                    <button class="w-full mt-7 py-4 bg-[#F8FAFB] text-[#1A313C] rounded-2xl text-[10px] font-black uppercase italic hover:bg-[#1A313C] hover:text-white transition tracking-widest shadow-sm">
                        Lihat Detail
                    </button>
                </div>
            </div>

            <div class="flex justify-center items-center gap-4 mt-10">
                <button class="w-12 h-12 rounded-2xl bg-white border border-slate-100 flex items-center justify-center shadow-sm hover:bg-[#1A313C] hover:text-white transition group">
                    <span class="group-hover:scale-125 transition">←</span>
                </button>
                <div class="flex items-center gap-2 font-black text-xs">
                    <button class="w-10 h-10 rounded-xl hover:bg-white transition text-[#598392]">01</button>
                    <button class="w-10 h-10 rounded-xl hover:bg-white transition">02</button>
                    <button class="w-10 h-10 rounded-xl bg-[#1A313C] text-white shadow-lg">03</button>
                    <button class="w-10 h-10 rounded-xl hover:bg-white transition">04</button>
                    <span class="px-2 opacity-30">...</span>
                </div>
                <button class="w-12 h-12 rounded-2xl bg-[#598392] text-white flex items-center justify-center shadow-lg hover:bg-[#1A313C] transition group">
                    <span class="group-hover:scale-125 transition">→</span>
                </button>
            </div>
        </main>

        <footer class="bg-[#598392] text-[#DDEEF3] py-12 px-11 mt-20 border-t border-white/10">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-slate-700 rounded-xl flex items-center justify-center font-black text-white italic shadow-xl">L</div>
                    <div class="flex flex-col">
                        <span class="font-black text-xl uppercase tracking-tighter text-white">LOKAKBEGAWE</span>
                        <span class="text-[9px] text-slate-300 italic">Bengkulu Pride Project</span>
                    </div>
                </div>
                <div class="text-[9px] opacity-50 font-black tracking-[0.3em] uppercase text-center md:text-right leading-loose">
                    © 2026 LOKAK BEGAWE - Ado Lokak Pela Begawe<br>
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
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Stagger delay untuk tiap card */
.grid > div:nth-child(1) { animation-delay: 0.1s; }
.grid > div:nth-child(2) { animation-delay: 0.15s; }
.grid > div:nth-child(3) { animation-delay: 0.2s; }
.grid > div:nth-child(4) { animation-delay: 0.25s; }
.grid > div:nth-child(5) { animation-delay: 0.3s; }
.grid > div:nth-child(6) { animation-delay: 0.35s; }
.grid > div:nth-child(7) { animation-delay: 0.4s; }
.grid > div:nth-child(8) { animation-delay: 0.45s; }
</style>