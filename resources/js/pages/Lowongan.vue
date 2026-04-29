<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Search, MapPin, Layers, Clock, ArrowUpRight, Building2 } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

const props = defineProps<{
    lowongans?: { data: Array<any>; links: Array<any>; };
    lokasis: Array<any>;
    kategoris: Array<any>;
    filters: any;
}>();

const keyword = ref(props.filters?.keyword || '');
const category = ref(props.filters?.category || '');
const location = ref(props.filters?.location || '');

let searchTimeout: ReturnType<typeof setTimeout>;

// Fitur Live Search dengan Debounce
watch([keyword, category, location], () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            '/lowongan',
            { keyword: keyword.value, category: category.value, location: location.value },
            { preserveState: true, replace: true, preserveScroll: true }
        );
    }, 300);
});

const formatRupiah = (v: any) => {
    if (!v || v == 0) return 'Bersaing';
    return new Intl.NumberFormat('id-ID', { 
        style: 'currency', currency: 'IDR', minimumFractionDigits: 0 
    }).format(v);
};
</script>

<template>
    <Head title="Cari Lowongan - Lokak Begawe" />
    
    <div class="mt-12 min-h-screen overflow-x-hidden bg-lokak-bg font-sans text-lokak-text">
        <Navbar />
        
        <main class="w-full px-6 py-12 md:px-16 lg:px-24 xl:px-32">
            
            <header class="mb-12 flex flex-col items-center text-center">
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight text-slate-900 md:text-5xl lg:text-6xl uppercase italic">
                    Cari <span class="text-lokak-brand">Gawe</span>
                </h1>
                <p class="max-w-2xl text-base font-medium leading-relaxed text-slate-500 md:text-lg">
                    Jelajahi ribuan peluang karir profesional di seluruh wilayah Provinsi Bengkulu. Ado loker, pela begawe!
                </p>
            </header>

            <section class="mb-20">
                <div class="mx-auto max-w-5xl rounded-3xl border border-slate-200 bg-white p-3 shadow-lg md:rounded-full md:p-3">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <div class="relative flex h-14 flex-1 items-center border-b border-slate-100 md:border-b-0 md:border-r md:h-12">
                            <Search class="absolute left-5 h-5 w-5 text-slate-400" />
                            <input v-model="keyword" type="text" placeholder="Posisi atau Keahlian..." class="h-full w-full border-none bg-transparent pl-12 pr-4 text-sm font-bold text-slate-700 outline-none placeholder:text-slate-400 focus:ring-0" />
                        </div>

                        <div class="relative flex h-14 flex-1 items-center border-b border-slate-100 md:border-b-0 md:border-r md:h-12">
                            <Layers class="absolute left-5 h-5 w-5 text-slate-400" />
                            <select v-model="category" class="h-full w-full cursor-pointer appearance-none border-none bg-transparent pl-12 pr-10 text-sm font-bold text-slate-700 outline-none focus:ring-0">
                                <option value="">Semua Kategori</option>
                                <option v-for="kat in kategoris" :key="kat.id" :value="kat.id">{{ kat.nama_kategori }}</option>
                            </select>
                        </div>

                        <div class="relative flex h-14 flex-1 items-center md:h-12">
                            <MapPin class="absolute left-5 h-5 w-5 text-slate-400" />
                            <select v-model="location" class="h-full w-full cursor-pointer appearance-none border-none bg-transparent pl-12 pr-10 text-sm font-bold text-slate-700 outline-none focus:ring-0">
                                <option value="">Semua Lokasi</option>
                                <option v-for="lok in lokasis" :key="lok.id" :value="lok.id">{{ lok.nama_lokasi }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <div v-if="props.lowongans?.data && props.lowongans.data.length > 0" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 lg:gap-8">
                <Link v-for="job in props.lowongans.data" :key="job.id" :href="'/detail/lowongan/' + job.id" class="group flex flex-col justify-between rounded-3xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-lokak-brand/30 hover:shadow-xl">
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

            <div v-else class="flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 bg-white py-24 text-center shadow-sm">
                <Search class="mb-4 h-16 w-16 text-slate-300" />
                <h3 class="text-xl font-bold text-slate-800">Lowongan tidak ditemukan</h3>
                <p class="mt-2 text-sm font-medium text-slate-500">Coba gunakan kata kunci atau filter kategori/lokasi yang lain.</p>
            </div>

            <div v-if="props.lowongans?.links && props.lowongans.links.length > 3" class="mt-16 flex items-center justify-center gap-1.5">
                <template v-for="(link, k) in props.lowongans.links" :key="k">
                    <div v-if="!link.url" class="flex h-10 min-w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-3 text-[10px] font-black text-slate-400 opacity-50 uppercase italic">
                        <span v-html="link.label"></span>
                    </div>
                    <Link v-else :href="link.url" :class="['flex h-10 min-w-10 items-center justify-center rounded-xl px-3 text-[10px] font-black transition-all duration-200 uppercase italic shadow-sm hover:-translate-y-1', link.active ? 'bg-lokak-brand text-white shadow-md' : 'border border-slate-200 bg-white text-slate-600 hover:border-lokak-brand hover:text-lokak-brand']">
                        <span v-html="link.label"></span>
                    </Link>
                </template>
            </div>
        </main>
        
        <Footer />
    </div>
</template>

<style scoped>
select { -webkit-appearance: none; -moz-appearance: none; appearance: none; }
</style>