<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

defineProps<{
    lowonganTerbaru: Array<any>;
    mitraTeratas: Array<any>;
}>();

const formatRupiah = (value: any) => {
    if (!value) {
return 'Bersaing';
}

    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
};

const getInitials = (name: string) => {
    if (!name) {
return '??';
}

    const words = name.trim().split(' ');

    return words.length >= 2 ? (words[0][0] + words[1][0]).toUpperCase() : name.substring(0, 2).toUpperCase();
};
</script>

<template>
    <Head title="Lokak Begawe - Portal Lowongan Kerja Bengkulu" />

    <div class="mt-12 min-h-screen overflow-x-hidden bg-lokak-bg font-sans text-lokak-text">
        <Navbar />

        <main class="w-full px-6 py-12 md:px-16 lg:px-24 xl:px-32">
            <section class="relative mb-28 flex min-h-125 flex-col items-stretch overflow-hidden rounded-[3.5rem] bg-lokak-brand text-white shadow-2xl lg:flex-row">
                <div class="relative z-20 flex w-full flex-col justify-center p-12 md:p-20 lg:w-3/5">
                    <h1 class="mb-10 text-6xl font-black uppercase italic md:text-8xl">CARI GAWE<br />DAK BETELE</h1>
                    <p class="mb-14 max-w-2xl text-base font-medium text-sky-50 md:text-xl">
                        Ribuan lowongan dari perusahaan terverifikasi di Bengkulu menunggumu. Ado loker, pela begawe sekarang!
                    </p>
                    <div class="flex flex-wrap gap-6">
                        <Link :href="route('register.pelamar')" class="rounded-3xl bg-white px-12 py-6 text-sm font-black text-lokak-brand uppercase italic shadow-2xl transition-all hover:-translate-y-1 active:scale-95">
                            Daftar Sekarang
                        </Link>
                    </div>
                </div>
                <div class="relative min-h-75 w-full lg:w-2/5">
                    <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&q=80" class="absolute inset-0 h-full w-full object-cover brightness-75" />
                </div>
            </section>

            <section class="mb-32">
                <div class="mb-14 flex items-end justify-between">
                    <h2 class="text-3xl font-black text-lokak-text uppercase italic">LOWONGAN <span class="text-lokak-brand">TERBARU</span></h2>
                    <Link :href="route('lowongan.index')" class="hidden font-black text-slate-400 uppercase italic hover:text-lokak-brand md:block">Lihat Semua →</Link>
                </div>

                <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4">
                    <Link v-for="job in lowonganTerbaru" :key="job.id" :href="route('detail.lowongan', job.id)" class="group rounded-[3rem] border border-slate-100 bg-white p-10 shadow-sm transition-all hover:-translate-y-3 hover:shadow-2xl">
                        <div class="mb-10 flex h-20 w-20 items-center justify-center overflow-hidden rounded-3xl bg-slate-50 text-2xl">
                            <img v-if="job.mitra?.logo" :src="job.mitra.logo" class="h-full w-full object-cover" />
                            <span v-else class="font-black text-lokak-brand uppercase italic">{{ getInitials(job.mitra?.nama_mitra) }}</span>
                        </div>
                        <h3 class="mb-3 text-lg font-black text-lokak-text uppercase italic group-hover:text-lokak-brand">{{ job.judul_lowongan }}</h3>
                        <p class="mb-8 text-xs font-bold text-slate-400 uppercase italic">{{ job.mitra?.nama_mitra }}</p>
                        <div class="flex items-center justify-between border-t border-slate-50 pt-8">
                            <span class="text-sm font-black text-lokak-brand italic">{{ formatRupiah(job.gaji_min) }}</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase italic">📍 {{ job.lokasi?.nama_lokasi }}</span>
                        </div>
                    </Link>
                </div>
            </section>

            <section class="mb-40">
                <div class="mb-14">
                    <h2 class="text-3xl font-black text-lokak-text uppercase italic">MITRA <span class="text-lokak-brand">TERATAS</span></h2>
                    <p class="mt-2 text-xs font-bold text-slate-400 uppercase italic">Ayo menjadi bagian dari perusahan berkualitas di Bengkulu</p>
                    <div class="mt-4 h-2 w-32 rounded-full bg-lokak-brand"></div>
                </div>

                <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4">
                    <Link v-for="mitra in mitraTeratas" :key="mitra.id" :href="route('mitra.show', mitra.id)" class="group rounded-[3rem] border border-slate-100 bg-white p-12 text-center transition-all hover:-translate-y-3 shadow-sm hover:shadow-2xl">
                        <div class="mx-auto mb-10 flex h-28 w-28 items-center justify-center overflow-hidden rounded-full bg-slate-50 text-4xl shadow-inner">
                            <img v-if="mitra.logo" :src="mitra.logo" class="h-full w-full object-cover" />
                            <span v-else class="font-black text-lokak-brand uppercase italic">{{ getInitials(mitra.nama_mitra) }}</span>
                        </div>
                        
                        <h3 class="mb-2 text-lg font-black text-lokak-text uppercase italic group-hover:text-lokak-brand">{{ mitra.nama_mitra }}</h3>
                        
                        <div class="mb-10 flex flex-col items-center gap-2">
                            <div class="flex items-center gap-1">
                                <span class="text-xs font-black text-amber-500 italic">⭐ {{ mitra.rating_avg }}</span>
                                <span class="text-[9px] font-bold text-slate-300 uppercase italic">({{ mitra.review_count }} Ulasan)</span>
                            </div>
                            <span class="inline-block rounded-2xl bg-lokak-brand px-4 py-2 text-[10px] font-black text-white uppercase italic">
                                {{ mitra.lowongan_count }} LOKER
                            </span>
                        </div>
                    </Link>
                </div>
            </section>
        </main>

        <Footer />
    </div>
</template>