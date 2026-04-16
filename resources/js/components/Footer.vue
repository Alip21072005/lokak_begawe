<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const suggestionForm = useForm({
    email: '',
    message: '',
});

const submitSuggestion = () => {
    suggestionForm.reset();
    alert('Saran berhasil dikirim!');
};
</script>
<template>
    <footer
        class="bg-lokak-footer px-6 py-20 text-white md:px-16 lg:px-24 xl:px-32"
    >
        <div class="grid w-full grid-cols-1 gap-16 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <div class="mb-8 flex items-center gap-4">
                    <img
                        src="/Logo.png"
                        alt="Logo"
                        class="h-14 w-14 rounded-2xl opacity-80"
                    />
                    <div class="flex flex-col">
                        <span
                            class="text-2xl font-black tracking-tighter uppercase italic"
                            >LOKAK<span class="text-sky-400">BEGAWE</span></span
                        >
                        <span
                            class="text-[10px] font-bold text-slate-500 uppercase italic"
                            >Ado Lokak, Pela Begawe</span
                        >
                    </div>
                </div>
                <p
                    class="max-w-xs text-xs leading-relaxed font-medium text-slate-400 italic"
                >
                    Portal lowongan kerja nomor satu di Provinsi Bengkulu.
                    Menghubungkan talenta lokal dengan peluang global.
                </p>
            </div>

            <div
                class="grid grid-cols-2 gap-8 text-xs font-bold text-slate-400 uppercase italic lg:col-span-4"
            >
                <div class="flex flex-col gap-5">
                    <span class="text-[10px] font-black text-white opacity-30"
                        >NAVIGASI</span
                    >
                    <a href="#" class="transition-colors hover:text-sky-400"
                        >Cari Pekerjaan</a
                    >
                    <a href="#" class="transition-colors hover:text-sky-400"
                        >Daftar Mitra</a
                    >
                </div>
                <div class="flex flex-col gap-5">
                    <span class="text-[10px] font-black text-white opacity-30"
                        >DUKUNGAN</span
                    >
                    <a href="#" class="transition-colors hover:text-sky-400"
                        >Instagram</a
                    >
                    <a href="#" class="transition-colors hover:text-sky-400"
                        >Pusat Bantuan</a
                    >
                </div>
            </div>

            <div
                class="rounded-[2.5rem] border border-white/5 bg-white/3 p-8 lg:col-span-4"
            >
                <h4
                    class="mb-6 text-xs font-black tracking-widest text-white uppercase italic"
                >
                    SARAN <span class="text-sky-400">& MASUKAN</span>
                </h4>
                <form @submit.prevent="submitSuggestion" class="space-y-4">
                    <input
                        v-model="suggestionForm.email"
                        type="email"
                        placeholder="Email aktif"
                        class="w-full rounded-2xl border border-white/5 bg-white/5 p-4 text-xs font-bold outline-none focus:border-sky-500/50"
                    />
                    <button
                        type="submit"
                        class="w-full rounded-2xl bg-white py-4 text-xs font-black text-lokak-footer uppercase italic transition-all hover:bg-sky-400 hover:text-white"
                    >
                        Kirim Sekarang
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-24 border-t border-white/5 pt-10 text-center">
            <p
                class="text-[9px] font-black tracking-[0.4em] text-slate-500 uppercase"
            >
                © 2026 LOKAK BEGAWE - BENGKULU PRIDE
            </p>
        </div>
    </footer>
</template>
