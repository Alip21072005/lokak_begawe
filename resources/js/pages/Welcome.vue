<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// Definisikan Props untuk data dari Laravel
defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
}>();

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

// Data Dummy untuk Lowongan (Sesuai Foto)
const lowonganTerbaru = [
    { id: 1, title: 'FRONTEND DEVELOPER (REACT)', company: 'UNIVERSITAS DEHASEN', salary: 'Rp 8-12 Juta', location: 'KOTA BENGKULU', icon: '🎓' },
    { id: 2, title: 'FRONTEND DEVELOPER (REACT)', company: 'CODE 21', salary: 'Rp 8-12 Juta', location: 'MUKOMUKO', icon: '💎' },
    { id: 3, title: 'CYBER SECURITY', company: 'DECODE', salary: 'Rp 8-9 Juta', location: 'KOTA BENGKULU', icon: '💻' },
    { id: 4, title: 'MENTOR BOOTCAMP (AI)', company: 'PHINCON ACADEMY', salary: 'Rp 15-20 Juta', location: 'MANNA', icon: '🤖' },
];

// Data Dummy untuk Mitra (Sesuai Foto)
const mitraTeratas = [
    { id: 1, name: 'UNIVERSITAS DEHASEN', location: 'Kota Bengkulu', jobs: '15 PEKERJAAN', rating: '4.3 (154)' },
    { id: 2, name: 'CODE 21', location: 'MUKOMUKO', jobs: '10 PEKERJAAN', rating: '4.3 (154)' },
    { id: 3, name: 'DE CODE', location: 'KOTA BENGKULU', jobs: '10 PEKERJAAN', rating: '4.3 (154)' },
    { id: 4, name: 'PHINCON ACADEMY', location: 'MANNA', jobs: '10 PEKERJAAN', rating: '4.3 (154)' },
];
</script>

<template>
    <Head title="Lokak Begawe - Bengkulu Job Portal" />

    <div class="min-h-screen bg-[#DDEEF3] font-sans text-[#1A313C] overflow-x-hidden">
        
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
                    <Link href="/" class="text-white border-b-2 border-white pb-1">Beranda</Link>
                    <Link href="/lowongan" class="hover:text-white transition">Lowongan</Link>
                    <Link href="/mitra" class="hover:text-white transition">Mitra</Link>
                </div>

                <div class="flex items-center gap-2">
                    <button class="text-slate-300 hover:text-white p-1">🔔</button>
                    <Link href="/login" class="px-4 py-1.5 rounded-lg text-[9px] font-bold text-slate-300 border border-slate-700 hover:border-white transition uppercase">LOGIN USER</Link>
                    <Link href="/login-mitra" class="px-4 py-1.5 rounded-lg bg-[#1A313C] text-[9px] font-bold text-white hover:bg-slate-800 transition uppercase italic">LOGIN MITRA</Link>
                </div>
            </nav>
        </header>

        <main class="py-8 px-4 md:px-11 max-w-7xl mx-auto">
            <section class="mb-16 grid grid-cols-1 lg:grid-cols-12 items-center bg-[#598392] p-10 md:p-16 rounded-[40px] shadow-2xl text-white relative overflow-hidden">
                <div class="lg:col-span-8 z-10">
                    <h1 class="text-5xl md:text-7xl font-black uppercase italic leading-[0.9] tracking-tighter mb-6">
                        CARI GAWE<br>DAK BETELE
                    </h1>
                    <p class="text-[11px] md:text-xs opacity-70 mb-10 max-w-md leading-relaxed">
                        Ribuan lowongan dari perusahaan terverifikasi menunggumu. Gabung sekarang dan mulai karir impianmu.
                    </p>
                    <div class="flex gap-4">
                        <button class="px-8 py-3 bg-white text-[#598392] rounded-xl font-black text-[10px] uppercase italic shadow-lg">Daftar Sekarang</button>
                        <button class="px-8 py-3 border-2 border-white/30 rounded-xl font-black text-[10px] uppercase italic">Pelajari Lebih</button>
                    </div>
                </div>
                <div class="hidden lg:flex lg:col-span-4 justify-center items-center opacity-20">
                    <svg width="200" height="200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
            </section>

            <section class="mb-20">
                <h2 class="text-xl font-black text-[#1A313C] uppercase mb-10 italic">LOWONGAN <span class="text-[#598392]">TERBARU</span></h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="job in lowonganTerbaru" :key="job.id" class="bg-white p-7 rounded-[35px] shadow-sm hover:shadow-xl transition-all border border-slate-50 relative group">
                        <span class="absolute top-6 right-6 px-3 py-1 bg-teal-100 text-teal-800 text-[8px] font-black rounded-full uppercase italic">Baru Saja</span>
                        <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-2xl mb-6">{{ job.icon }}</div>
                        <h3 class="font-black text-sm mb-1 uppercase leading-tight">{{ job.title }}</h3>
                        <p class="text-[9px] text-slate-400 font-bold mb-5 uppercase">{{ job.company }}</p>
                        <div class="flex gap-2 mb-8">
                            <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[8px] font-black rounded-full uppercase">Full Time</span>
                            <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[8px] font-black rounded-full uppercase">Magang</span>
                        </div>
                        <div class="flex items-center justify-between text-[10px] border-t border-slate-50 pt-5">
                            <span class="font-black text-[#598392]">{{ job.salary }}</span>
                            <span class="text-slate-400 font-bold italic">📍 {{ job.location }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-20">
                <h2 class="text-xl font-black text-[#1A313C] uppercase mb-10 italic">MITRA <span class="text-[#598392]">TERATAS</span></h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="mitra in mitraTeratas" :key="mitra.id" class="bg-white p-8 rounded-[35px] shadow-sm text-center border border-slate-50">
                        <div class="w-20 h-20 bg-slate-100 rounded-full mx-auto mb-6 flex items-center justify-center text-3xl font-bold text-slate-300">🏢</div>
                        <h3 class="font-black text-xs mb-1 uppercase tracking-tighter">{{ mitra.name }}</h3>
                        <p class="text-[9px] text-slate-400 font-bold mb-4 uppercase italic">{{ mitra.location }}</p>
                        <div class="flex items-center justify-center gap-2 mb-6 text-[10px]">
                            <span class="text-amber-400">⭐ {{ mitra.rating }}</span>
                            <span class="px-3 py-1 bg-[#598392] text-white text-[8px] font-black rounded-full uppercase">{{ mitra.jobs }}</span>
                        </div>
                        <button class="text-[9px] font-black uppercase italic text-slate-400 hover:text-[#1A313C] transition">Lihat Detail</button>
                    </div>
                </div>
            </section>

            <section class="mb-20">
                <h2 class="text-xl font-black text-[#1A313C] uppercase mb-10 italic">KATA <span class="text-[#598392]">MEREKA</span></h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="n in 4" :key="n" class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-50 relative text-center">
                        <span class="text-4xl text-slate-200 font-serif leading-none">“</span>
                        <p class="text-[10px] font-black uppercase italic leading-relaxed text-slate-700 mb-8 mt-4">
                            Platform ini sangat memudahkan saya mencari pekerjaan yang sesuai dengan passion saya di bidang IT. Interface-nya keren banget!
                        </p>
                        <div class="flex flex-col items-center gap-2 pt-6 border-t border-slate-100">
                            <div class="w-10 h-10 bg-slate-200 rounded-full"></div>
                            <span class="font-black text-[9px] uppercase tracking-widest">Sahrul Refoam Simalungun</span>
                            <span class="text-[8px] text-[#598392] font-bold uppercase italic">Web Developer</span>
                        </div>
                    </div>
                </div>
            </section>
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
                    <a href="#" class="hover:underline">Loker</a>
                </div>

                <div class="lg:col-span-3 flex flex-col gap-4 text-[11px] font-bold uppercase italic">
                    <span class="text-white opacity-40 text-[9px] font-black">HUBUNGI KAMI</span>
                    <a href="#" class="hover:underline">Instagram</a>
                    <a href="#" class="hover:underline">WhatsApp</a>
                    <a href="#" class="hover:underline">Email</a>
                </div>

                <div class="lg:col-span-3 bg-white p-8 rounded-[35px] shadow-2xl">
                    <h4 class="font-black uppercase text-[10px] mb-4 text-[#1A313C] italic">BERIKAN SARAN</h4>
                    <form @submit.prevent="submitSuggestion" class="space-y-3">
                        <input v-model="suggestionForm.email" type="email" placeholder="Email kamu..." class="w-full p-4 bg-slate-50 border-none rounded-2xl text-[10px] text-[#1A313C] shadow-inner outline-none">
                        <textarea v-model="suggestionForm.message" placeholder="Pesan Kamu..." rows="3" class="w-full p-4 bg-slate-50 border-none rounded-2xl text-[10px] text-[#1A313C] shadow-inner outline-none resize-none"></textarea>
                        <button type="submit" class="w-full py-4 bg-[#1A313C] text-white rounded-2xl text-[10px] font-black uppercase italic shadow-lg">Kirim Saran</button>
                    </form>
                </div>
            </div>
            <div class="max-w-7xl mx-auto mt-20 pt-8 border-t border-white/10 text-center text-[8px] font-black opacity-40 uppercase tracking-[0.3em]">
                © 2026 LOKAK BEGAWE - HAK CIPTA DILINDUNGI UNDANG-UNDANG
            </div>
        </footer>
    </div>
</template>