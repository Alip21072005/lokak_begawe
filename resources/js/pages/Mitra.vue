<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Search, MapPin, ChevronRight, SlidersHorizontal, Star } from 'lucide-vue-next';
import { ref } from 'vue';
import { route } from 'ziggy-js';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

const props = defineProps<{
    mitras: any[];
    lokasis: any[];
    kategoris: any[];
    filters: any;
}>();

const search = ref(props.filters.search || '');
const lokasi = ref(props.filters.lokasi || '');
const kategori = ref(props.filters.kategori || '');

const handleSearch = () => {
    router.get(
        route('mitra.index'),
        { search: search.value, lokasi: lokasi.value, kategori: kategori.value },
        { preserveState: true, replace: true }
    );
};

const getInitials = (name: string) => {
    if (!name) {
return '??';
}

    const words = name.trim().split(' ');

    if (words.length >= 2) {
        return (words[0][0] + words[1][0]).toUpperCase();
    }

    return name.substring(0, 2).toUpperCase();
};
</script>

<template>
    <Head title="Cari Mitra - Lokak Begawe" />
    <div class="min-h-screen bg-lokak-bg font-sans text-lokak-text">
        <Navbar />

        <main class="mt-12 px-6 py-12 md:px-16 lg:px-32">
            <section class="mb-20">
                <div class="mb-10 space-y-4">
                    <h1 class="text-4xl font-black tracking-tighter uppercase italic md:text-6xl">
                        CARI <span class="text-lokak-brand">MITRA</span>
                    </h1>
                    <div class="h-2 w-24 rounded-full bg-lokak-brand"></div>
                </div>

                <div class="relative rounded-[3rem] border border-slate-100 bg-white p-6 shadow-2xl shadow-slate-200/50 md:p-8">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
                        <div class="lg:col-span-4">
                            <label class="mb-2 ml-4 block text-[10px] font-black text-slate-400 uppercase">Nama Perusahaan</label>
                            <div class="relative">
                                <Search class="absolute top-1/2 left-5 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                <input v-model="search" type="text" placeholder="Cari PT atau Instansi..." 
                                    class="h-16 w-full rounded-4xl border border-slate-100 bg-slate-50 pl-14 text-xs font-bold focus:border-lokak-brand focus:bg-white outline-none transition-all" />
                            </div>
                        </div>

                        <div class="lg:col-span-3">
                            <label class="mb-2 ml-4 block text-[10px] font-black text-slate-400 uppercase">Sektor Industri</label>
                            <div class="relative">
                                <SlidersHorizontal class="absolute top-1/2 left-5 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                <select v-model="kategori" class="h-16 w-full appearance-none rounded-4xl border border-slate-100 bg-slate-50 pl-14 pr-10 text-xs font-bold outline-none focus:border-lokak-brand">
                                    <option value="">Semua Industri</option>
                                    <option v-for="kat in kategoris" :key="kat.id" :value="kat.id">{{ kat.nama_kategori }}</option>
                                </select>
                                <ChevronRight class="absolute top-1/2 right-5 h-4 w-4 -translate-y-1/2 rotate-90 text-slate-300 pointer-events-none" />
                            </div>
                        </div>

                        <div class="lg:col-span-3">
                            <label class="mb-2 ml-4 block text-[10px] font-black text-slate-400 uppercase">Wilayah</label>
                            <div class="relative">
                                <MapPin class="absolute top-1/2 left-5 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                <select v-model="lokasi" class="h-16 w-full appearance-none rounded-4xl border border-slate-100 bg-slate-50 pl-14 pr-10 text-xs font-bold outline-none focus:border-lokak-brand">
                                    <option value="">Semua Lokasi</option>
                                    <option v-for="lok in lokasis" :key="lok.id" :value="lok.id">{{ lok.nama_lokasi }}</option>
                                </select>
                                <ChevronRight class="absolute top-1/2 right-5 h-4 w-4 -translate-y-1/2 rotate-90 text-slate-300 pointer-events-none" />
                            </div>
                        </div>

                        <div class="flex items-end lg:col-span-2">
                            <button @click="handleSearch" class="h-16 w-full rounded-4xl bg-lokak-brand font-black text-white italic transition-all hover:-translate-y-1 hover:bg-sky-700 shadow-xl shadow-sky-900/20">
                                CARI
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="mitra in mitras" :key="mitra.id" class="group flex flex-col justify-between rounded-[2.5rem] border border-slate-100 bg-white p-8 text-center transition-all hover:-translate-y-2 hover:shadow-2xl">
                    
                    <div>
                        <div class="relative mx-auto mb-6 flex h-24 w-24 items-center justify-center overflow-hidden rounded-full border bg-slate-50 shadow-inner">
                            <img 
                                v-if="mitra.logo" 
                                :src="mitra.logo" 
                                class="h-full w-full object-cover" 
                                :alt="mitra.name" 
                            />
                            <span v-else class="text-2xl font-black text-lokak-brand uppercase italic">
                                {{ getInitials(mitra.name) }}
                            </span>
                        </div>

                        <div class="mb-4 flex items-center justify-center gap-2">
                            <div class="flex items-center gap-1 rounded-full bg-amber-50 px-3 py-1">
                                <Star class="h-3 w-3 text-amber-500 fill-amber-500" />
                                <span class="text-[10px] font-black text-amber-700 italic">{{ mitra.rating }}</span>
                            </div>
                            <span class="text-[9px] font-bold text-slate-300 uppercase italic">
                                {{ mitra.review_count }} Ulasan
                            </span>
                        </div>

                        <h3 class="mb-2 text-sm font-black uppercase italic group-hover:text-lokak-brand transition-colors">
                            {{ mitra.name }}
                        </h3>

                        <p class="mb-6 flex items-center justify-center gap-1 text-[10px] font-bold text-slate-400 italic uppercase">
                            <MapPin class="h-3 w-3" /> 
                            {{ mitra.location }}
                        </p>
                    </div>

                    <Link :href="route('mitra.show', mitra.id)" class="block w-full rounded-2xl bg-slate-900 py-4 text-[10px] font-black text-white uppercase italic hover:bg-lokak-brand transition-colors shadow-lg">
                        LIHAT PROFIL
                    </Link>
                </div>
            </div>

            <div v-if="mitras.length === 0" class="flex flex-col items-center justify-center py-32 opacity-30">
                <div class="mb-4 text-6xl">🏢</div>
                <p class="text-xl font-black italic uppercase">Mitra tidak ditemukan</p>
            </div>
        </main>
        
        <Footer />
    </div>
</template>