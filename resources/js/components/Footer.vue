<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { Mail, Instagram, MessageSquare, Send, MapPin, Globe } from 'lucide-vue-next';

const suggestionForm = useForm({
    email: '',
    message: '',
});

const submitSuggestion = () => {
    suggestionForm.reset();
    alert('Terima kasih! Saran Anda berhasil dikirim.');
};
</script>

<template>
    <footer class="bg-slate-900 py-20 text-white">
        <div class="w-full px-6 md:px-16 lg:px-24 xl:px-32">
            <div class="grid w-full grid-cols-1 gap-12 lg:grid-cols-12 lg:gap-16">
                
                <div class="lg:col-span-4">
                    <div class="mb-6 flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-xl bg-lokak-brand shadow-lg">
                            <img src="/Logo.png" alt="Logo" class="h-full w-full object-cover" />
                        </div>
                        <div class="flex flex-col leading-tight">
                            <span class="text-xl font-black tracking-tighter uppercase italic">
                                LOKAK<span class="text-lokak-brand">BEGAWE</span>
                            </span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase italic tracking-wider">
                                Ado Lokak, Pela Begawe!
                            </span>
                        </div>
                    </div>
                    <p class="mb-8 max-w-xs text-sm font-medium leading-relaxed text-slate-400">
                        Portal lowongan kerja nomor satu di Provinsi Bengkulu. Menghubungkan talenta lokal dengan peluang terbaik.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="social-icon-btn"><Instagram class="h-5 w-5" /></a>
                        <a href="#" class="social-icon-btn"><Mail class="h-5 w-5" /></a>
                        <a href="#" class="social-icon-btn"><Globe class="h-5 w-5" /></a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8 text-sm lg:col-span-4">
                    <div class="flex flex-col gap-4">
                        <span class="footer-label">Navigasi</span>
                        <Link href="/lowongan" class="footer-link">Cari Lowongan</Link>
                        <Link href="/mitra" class="footer-link">Daftar Mitra</Link>
                        <Link href="/register" class="footer-link">Pasang Loker</Link>
                    </div>
                    <div class="flex flex-col gap-4">
                        <span class="footer-label">Dukungan</span>
                        <Link href="#" class="footer-link">Pusat Bantuan</Link>
                        <Link href="#" class="footer-link">Kebijakan Privasi</Link>
                        <Link href="#" class="footer-link">Syarat & Ketentuan</Link>
                    </div>
                </div>

                <div class="rounded-3xl border border-white/5 bg-white/3 p-8 lg:col-span-4">
                    <div class="mb-6">
                        <h4 class="flex items-center gap-2 text-sm font-black tracking-widest text-white uppercase italic">
                            <MessageSquare class="h-4 w-4 text-lokak-brand" /> Saran & Masukan
                        </h4>
                    </div>
                    <form @submit.prevent="submitSuggestion" class="space-y-3">
                        <input v-model="suggestionForm.email" type="email" required placeholder="Email Anda" class="footer-input" />
                        <textarea v-model="suggestionForm.message" required rows="3" placeholder="Tulis pesan..." class="footer-input"></textarea>
                        <button type="submit" class="w-full rounded-xl bg-lokak-brand py-3.5 text-xs font-black uppercase text-white hover:bg-blue-600 transition-all">
                            <Send class="mr-2 inline h-4 w-4" /> Kirim Saran
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-20 flex flex-col items-center justify-between border-t border-white/5 pt-10 md:flex-row">
                <p class="text-[10px] font-bold tracking-[0.2em] text-slate-500 uppercase italic">
                    © 2026 LOKAK BEGAWE — BENGKULU PRIDE
                </p>
                <div class="mt-4 flex items-center gap-6 text-[10px] font-bold text-slate-500 uppercase md:mt-0">
                    <span class="flex items-center gap-1.5"><MapPin class="h-3 w-3" /> Kota Bengkulu, Indonesia</span>
                </div>
            </div>
        </div>
    </footer>
</template>

