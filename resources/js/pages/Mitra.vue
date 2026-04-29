<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Search, MapPin, Layers, Star, Building2 } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';
import { route } from 'ziggy-js';

const props = defineProps<{
    mitras: any[];
    lokasis: any[];
    kategoris: any[];
    filters: any;
}>();

const search = ref(props.filters?.search || '');
const kategori = ref(props.filters?.kategori || '');
const lokasi = ref(props.filters?.lokasi || '');

let searchTimeout: ReturnType<typeof setTimeout>;

// Fitur Live Search dengan Debounce
watch([search, kategori, lokasi], () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            route('mitra.index'),
            { search: search.value, kategori: kategori.value, lokasi: lokasi.value },
            { preserveState: true, replace: true, preserveScroll: true }
        );
    }, 300);
});

const getInitials = (name: string) => {
    if (!name) return '??';
    const words = name.trim().split(' ');
    return words.length >= 2 ? (words[0][0] + words[1][0]).toUpperCase() : name.substring(0, 2).toUpperCase();
};
</script>

<template>
    <Head title="Cari Mitra - Lokak Begawe" />
    
    <div class="mt-12 min-h-screen overflow-x-hidden bg-lokak-bg font-sans text-lokak-text">
        <Navbar />

        <main class="w-full px-6 py-12 md:px-16 lg:px-24 xl:px-32">
            
            <header class="mb-12 flex flex-col items-center text-center">
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight text-slate-900 md:text-5xl lg:text-6xl uppercase italic">
                    Cari <span class="text-lokak-brand">Mitra</span>
                </h1>
                <p class="max-w-2xl text-base font-medium leading-relaxed text-slate-500 md:text-lg">
                    Temukan dan pelajari perusahaan-perusahaan terbaik di Provinsi Bengkulu. Ado loker, pela begawe!
                </p>
            </header>

            <section class="mb-20">
                <div class="mx-auto max-w-5xl rounded-3xl border border-slate-200 bg-white p-3 shadow-lg md:rounded-full md:p-3">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <div class="relative flex h-14 flex-1 items-center border-b border-slate-100 md:border-b-0 md:border-r md:h-12">
                            <Search class="absolute left-5 h-5 w-5 text-slate-400" />
                            <input v-model="search" type="text" placeholder="Cari PT atau Instansi..." class="h-full w-full border-none bg-transparent pl-12 pr-4 text-sm font-bold text-slate-700 outline-none placeholder:text-slate-400 focus:ring-0" />
                        </div>

                        <div class="relative flex h-14 flex-1 items-center border-b border-slate-100 md:border-b-0 md:border-r md:h-12">
                            <Layers class="absolute left-5 h-5 w-5 text-slate-400" />
                            <select v-model="kategori" class="h-full w-full cursor-pointer appearance-none border-none bg-transparent pl-12 pr-10 text-sm font-bold text-slate-700 outline-none focus:ring-0">
                                <option value="">Semua Industri</option>
                                <option v-for="kat in kategoris" :key="kat.id" :value="kat.id">{{ kat.nama_kategori }}</option>
                            </select>
                        </div>

                        <div class="relative flex h-14 flex-1 items-center md:h-12">
                            <MapPin class="absolute left-5 h-5 w-5 text-slate-400" />
                            <select v-model="lokasi" class="h-full w-full cursor-pointer appearance-none border-none bg-transparent pl-12 pr-10 text-sm font-bold text-slate-700 outline-none focus:ring-0">
                                <option value="">Semua Lokasi</option>
                                <option v-for="lok in lokasis" :key="lok.id" :value="lok.id">{{ lok.nama_lokasi }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <div v-if="mitras && mitras.length > 0" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 lg:gap-8">
                <Link v-for="mitra in mitras" :key="mitra.id" :href="route('mitra.show', mitra.id)" class="group flex flex-col justify-between rounded-3xl border border-slate-100 bg-white p-6 text-center shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-lokak-brand/30 hover:shadow-xl">
                    
                    <div class="flex flex-col items-center">
                        <div class="mb-5 flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-full border border-slate-100 bg-slate-50 shadow-inner">
                            <img v-if="mitra.logo" :src="mitra.logo" class="h-full w-full object-cover" :alt="mitra.name" />
                            <span v-else class="text-3xl font-bold text-lokak-brand">{{ getInitials(mitra.name) }}</span>
                        </div>

                        <h3 class="mb-2 line-clamp-2 text-lg font-black leading-snug text-slate-900 transition-colors group-hover:text-lokak-brand uppercase italic" :title="mitra.name">
                            {{ mitra.name }}
                        </h3>

                        <div class="mb-4 flex items-center justify-center gap-2">
                            <div class="flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1">
                                <Star class="h-3.5 w-3.5 text-amber-500 fill-amber-500" />
                                <span class="text-xs font-bold text-amber-700">{{ mitra.rating ?? '0' }}</span>
                            </div>
                            <span class="text-xs font-bold text-slate-400">({{ mitra.review_count ?? '0' }} Ulasan)</span>
                        </div>
                        
                        <div class="mb-6 flex items-center gap-1.5 text-sm font-bold text-slate-500">
                            <MapPin class="h-4 w-4 shrink-0 text-slate-400" /> 
                            <span class="truncate">{{ mitra.location }}</span>
                        </div>
                    </div>

                    <div class="mt-auto pt-4 border-t border-slate-100 w-full">
                        <span class="inline-flex w-full items-center justify-center rounded-full bg-slate-50 py-3 text-[10px] font-black uppercase tracking-wider text-slate-600 transition-colors group-hover:bg-lokak-brand group-hover:text-white italic">
                            Profil Perusahaan &rarr;
                        </span>
                    </div>
                </Link>
            </div>

            <div v-else class="flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 bg-white py-24 text-center shadow-sm">
                <Building2 class="mb-4 h-16 w-16 text-slate-300" />
                <h3 class="text-xl font-bold text-slate-800">Mitra Tidak Ditemukan</h3>
                <p class="mt-2 text-sm font-medium text-slate-500">Coba gunakan kata kunci pencarian atau filter yang berbeda.</p>
            </div>
            
        </main>
        
        <Footer />
    </div>
</template>

<style scoped>
select { -webkit-appearance: none; -moz-appearance: none; appearance: none; }
</style>