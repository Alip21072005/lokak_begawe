<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { Search, MapPin, Clock, ArrowUpRight, Building2, Star } from 'lucide-vue-next';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

defineProps<{
    lowonganTerbaru: Array<any>;
    mitraTeratas: Array<any>;
}>();

const formatRupiah = (value: any) => {
    if (!value || value == 0) {
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
            
            <section class="relative mb-8 min-h-125 overflow-hidden rounded-3xl bg-lokak-brand text-white shadow-2xl">
                <div class="absolute inset-0 z-0">
                    <img 
                        src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&q=80" 
                        alt="Orang bekerja di kantor"
                        class="h-full w-full object-cover object-center transition-transform duration-700 hover:scale-105"
                    />
                    <div class="absolute inset-0 bg-linear-to-t from-lokak-brand via-lokak-brand/80 to-transparent lg:bg-linear-to-r lg:from-lokak-brand lg:via-lokak-brand/90 lg:to-transparent"></div>
                </div>

                <div class="relative z-10 flex h-full min-h-125 flex-col justify-center p-10 md:p-16 lg:w-3/5 lg:p-20">
                    <h1 class="mb-6 text-5xl font-extrabold leading-[1.1] tracking-tight md:text-6xl lg:text-7xl uppercase italic">
                        Cari Gawe<br />
                        <span class="text-sky-300">Dak Betele</span>
                    </h1>
                    <p class="mb-10 max-w-lg text-base font-medium leading-relaxed text-white/90 md:text-xl">
                        Ribuan lowongan dari perusahaan terverifikasi di Bengkulu menunggumu. Ado loker, pela begawe sekarang!
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <Link :href="route('register.pelamar')" class="inline-flex items-center justify-center rounded-full bg-white px-10 py-4 text-xs font-black uppercase tracking-widest text-lokak-brand shadow-xl transition-all duration-300 hover:-translate-y-1 hover:bg-sky-50 active:scale-95 italic">
                            Daftar Sekarang
                        </Link>
                    </div>
                </div>
            </section>

            <div class="relative z-20 mx-auto -mt-16 mb-24 max-w-5xl px-4 md:-mt-20">
                <form :action="route('lowongan.index')" method="GET" class="flex w-full flex-col items-center gap-4 rounded-3xl bg-white p-4 shadow-2xl md:flex-row md:rounded-full md:p-3">
                    <div class="flex w-full flex-1 items-center rounded-full bg-slate-50 px-6 py-3">
                        <Search class="mr-3 h-5 w-5 text-slate-400" />
                        <input type="text" name="keyword" placeholder="Cari posisi, perusahaan, atau keahlian..." class="w-full border-none bg-transparent text-sm font-bold text-slate-700 focus:outline-none focus:ring-0 placeholder:text-slate-400" />
                    </div>
                    <button type="submit" class="w-full whitespace-nowrap rounded-full bg-lokak-brand px-10 py-4 text-xs font-black uppercase tracking-widest text-white transition-all hover:bg-blue-700 md:w-auto md:py-3.5 italic shadow-md active:scale-95">
                        Cari Loker
                    </button>
                </form>
            </div>

            <section class="mb-28">
                <div class="mb-10 flex items-end justify-between">
                    <div>
                        <h2 class="text-3xl font-extrabold text-lokak-text md:text-4xl uppercase italic">Lowongan <span class="text-lokak-brand">Terbaru</span></h2>
                    </div>
                    <Link :href="route('lowongan.index')" class="hidden font-black text-xs uppercase tracking-widest text-slate-500 transition-colors hover:text-lokak-brand md:block italic">
                        Lihat Semua &rarr;
                    </Link>
                </div>

                <template v-if="lowonganTerbaru && lowonganTerbaru.length > 0">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">
                        <Link v-for="job in lowonganTerbaru" :key="job.id" :href="route('detail.lowongan', job.id)" class="group flex flex-col justify-between rounded-3xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-lokak-brand/30 hover:shadow-xl">
                            <div>
                                <div class="mb-5 flex items-start justify-between">
                                    <div class="rounded-full bg-sky-50 px-3 py-1.5 text-[10px] font-black uppercase text-lokak-brand tracking-wider">
                                        {{ job.mitra?.kategori?.nama_kategori || 'Umum' }}
                                    </div>
                                    <ArrowUpRight class="h-5 w-5 text-slate-300 transition-colors group-hover:text-lokak-brand" />
                                </div>

                                <div class="mb-6">
                                    <h3 class="mb-1.5 line-clamp-2 text-lg font-black leading-snug text-slate-900 transition-colors group-hover:text-lokak-brand uppercase italic" :title="job.judul_lowongan">
                                        {{ job.judul_lowongan }}
                                    </h3>
                                    <div class="flex items-center gap-1.5 text-sm font-bold text-slate-500">
                                        <Building2 class="h-4 w-4 shrink-0 text-slate-400" /> 
                                        <span class="truncate">{{ job.mitra?.nama_mitra }}</span>
                                    </div>
                                </div>

                                <div class="mb-8 flex flex-wrap gap-2">
                                    <div class="flex items-center gap-1.5 rounded-full bg-slate-50 px-3 py-1 text-[10px] font-bold text-slate-600 uppercase tracking-tight">
                                        <Clock class="h-3.5 w-3.5 text-slate-400" /> {{ job.tipe_pekerjaan }}
                                    </div>
                                    <div class="flex items-center gap-1.5 rounded-full bg-slate-50 px-3 py-1 text-[10px] font-bold text-slate-600 uppercase tracking-tight">
                                        <MapPin class="h-3.5 w-3.5 text-slate-400" /> <span class="truncate max-w-25">{{ job.lokasi?.nama_lokasi }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-5">
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Gaji Mulai</span>
                                    <span class="text-base font-black text-lokak-brand italic">{{ formatRupiah(job.gaji_min) }}</span>
                                </div>
                                <span class="inline-flex items-center justify-center rounded-full bg-slate-900 px-5 py-2 text-[10px] font-black uppercase tracking-wider text-white transition-all group-hover:bg-lokak-brand italic shadow-md active:scale-95"> 
                                    Detail &rarr;
                                </span>
                            </div>
                        </Link>
                    </div>
                </template>
                <template v-else>
                    <div class="flex w-full flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50 py-16 text-center shadow-sm">
                        <Search class="mb-4 h-16 w-16 text-slate-300" />
                        <h3 class="text-xl font-bold text-slate-800">Belum Ada Lowongan Baru</h3>
                        <p class="mt-2 text-sm font-medium text-slate-500">Silakan cek kembali beberapa saat lagi untuk pembaruan loker.</p>
                    </div>
                </template>

                <div class="mt-8 text-center md:hidden">
                    <Link :href="route('lowongan.index')" class="inline-block rounded-full border border-slate-200 bg-white px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-600 shadow-sm transition-all hover:bg-slate-50 italic">
                        Lihat Semua Loker &rarr;
                    </Link>
                </div>
            </section>

            <section class="mb-32">
                <div class="mb-10 text-center md:text-left">
                    <h2 class="text-3xl font-extrabold text-lokak-text md:text-4xl uppercase italic">Mitra <span class="text-lokak-brand">Teratas</span></h2>
                    <p class="mt-3 text-sm font-bold text-slate-400">Ayo menjadi bagian dari perusahaan berkualitas di Bengkulu</p>
                    <div class="mt-4 hidden h-1.5 w-24 rounded-full bg-lokak-brand md:block"></div>
                </div>

                <template v-if="mitraTeratas && mitraTeratas.length > 0">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">
                        <Link v-for="mitra in mitraTeratas" :key="mitra.id" :href="route('mitra.show', mitra.id)" class="group flex flex-col items-center rounded-3xl border border-slate-100 bg-white p-8 text-center shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-lokak-brand/30 hover:shadow-xl">
                            
                            <div class="mb-6 flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-full border border-slate-100 bg-slate-50 shadow-inner">
                                <img v-if="mitra.logo" :src="mitra.logo" :alt="mitra.nama_mitra" class="h-full w-full object-cover" />
                                <span v-else class="text-3xl font-bold text-lokak-brand">{{ getInitials(mitra.nama_mitra) }}</span>
                            </div>
                            
                            <h3 class="mb-4 line-clamp-1 text-lg font-black leading-snug text-slate-900 transition-colors group-hover:text-lokak-brand uppercase italic" :title="mitra.nama_mitra">
                                {{ mitra.nama_mitra }}
                            </h3>
                            
                            <div class="mt-auto flex w-full flex-col items-center gap-4">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1">
                                        <Star class="h-3.5 w-3.5 text-amber-500 fill-amber-500" />
                                        <span class="text-xs font-bold text-amber-700">{{ mitra.rating_avg ?? '0' }}</span>
                                    </div>
                                    <span class="text-xs font-bold text-slate-400">({{ mitra.review_count ?? '0' }} Ulasan)</span>
                                </div>
                                
                                <span class="inline-block rounded-full bg-sky-50 px-4 py-1.5 text-[10px] font-black uppercase tracking-wider text-lokak-brand">
                                    {{ mitra.lowongan_count ?? '0' }} Loker Tersedia
                                </span>
                            </div>
                        </Link>
                    </div>
                </template>
                <template v-else>
                    <div class="flex w-full flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50 py-16 text-center shadow-sm">
                        <Building2 class="mb-4 h-16 w-16 text-slate-300" />
                        <h3 class="text-xl font-bold text-slate-800">Mitra Belum Tersedia</h3>
                        <p class="mt-2 text-sm font-medium text-slate-500">Saat ini belum ada data perusahaan yang dapat ditampilkan.</p>
                    </div>
                </template>
            </section>

            <section class="mb-20 overflow-hidden rounded-[2.5rem] bg-slate-900 px-8 py-16 text-center shadow-2xl md:px-16 md:py-20 lg:px-24">
                <div class="mx-auto max-w-3xl">
                    <h2 class="mb-6 text-3xl font-extrabold leading-tight text-white md:text-4xl uppercase italic">
                        Butuh Talenta Terbaik di Bengkulu?
                    </h2>
                    <p class="mb-10 text-base font-medium text-slate-300 md:text-lg">
                        Bergabunglah dengan puluhan perusahaan lainnya. Pasang lowongan pekerjaan Anda di Lokak Begawe dan temukan kandidat berkualitas dengan cepat dan tepat.
                    </p>
                    <Link :href="route('register')" class="inline-block rounded-xl bg-lokak-brand px-10 py-4 text-xs font-black tracking-widest text-white shadow-lg uppercase italic transition-all duration-300 hover:-translate-y-1 hover:bg-blue-600 hover:shadow-xl active:scale-95">
                        Daftar Sebagai Perusahaan
                    </Link>
                </div>
            </section>

        </main>

        <Footer />
    </div>
</template>