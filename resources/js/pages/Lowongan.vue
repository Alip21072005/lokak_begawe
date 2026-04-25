<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Search, MapPin, Layers, Clock, ChevronRight, ArrowUpRight } from 'lucide-vue-next';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

const props = defineProps<{
    lowongans: { data: Array<any>; links: Array<any>; };
    lokasis: Array<any>;
    kategoris: Array<any>;
    filters: any;
}>();

const searchForm = useForm({
    keyword: props.filters?.keyword || '',
    category: props.filters?.category || '',
    location: props.filters?.location || '',
});

const handleSearch = () => {
    searchForm.get('/lowongan', { preserveState: true, replace: true });
};

const formatRupiah = (v: any) => {
    if (!v || v == 0) {
return 'Bersaing';
}

    return new Intl.NumberFormat('id-ID', { 
        style: 'currency', 
        currency: 'IDR', 
        minimumFractionDigits: 0 
    }).format(v);
};
</script>

<template>
    <Head title="Cari Lowongan - Lokak Begawe" />
    
    <div class="min-h-screen bg-[#F8FAFC] font-sans text-slate-900">
        <Navbar />
        
        <main class="mx-auto mt-7 max-w-360 px-6 py-16 md:px-16 lg:px-24">
            
            <header class="mb-16 flex flex-col items-center text-center">
                
                <h1 class="mb-6 text-5xl font-black tracking-tighter text-slate-900 uppercase italic md:text-7xl lg:text-8xl">
                    CARI <span class="text-lokak-brand">GAWE</span>
                </h1>
                <p class="max-w-xl text-sm font-medium leading-relaxed text-slate-500 md:text-base">
                    Jelajahi ribuan peluang karir profesional di seluruh wilayah Provinsi Bengkulu. 
                    Mulai langkah besarmu hari ini tanpa ribet.
                </p>
            </header>

            <section class="mb-24">
                <div class="relative mx-auto max-w-6xl rounded-[2.5rem] border border-slate-200 bg-white/80 p-3 shadow-2xl backdrop-blur-xl md:p-4">
                    <form @submit.prevent="handleSearch" class="grid grid-cols-1 gap-2 lg:grid-cols-12">
                        
                        <div class="relative lg:col-span-4">
                            <div class="group relative flex h-16 items-center">
                                <Search class="absolute left-6 h-5 w-5 text-slate-400 transition-colors group-focus-within:text-lokak-brand" />
                                <input
                                    v-model="searchForm.keyword"
                                    type="text"
                                    placeholder="Posisi atau Skill..."
                                    class="h-full w-full rounded-4xl border-none bg-transparent pl-16 text-sm font-bold placeholder-slate-400 outline-none ring-0 focus:ring-0"
                                />
                                <div class="absolute right-0 h-8 w-px bg-slate-200 hidden lg:block"></div>
                            </div>
                        </div>

                        <div class="relative lg:col-span-3">
                            <div class="group relative flex h-16 items-center">
                                <Layers class="absolute left-6 h-5 w-5 text-slate-400 transition-colors group-focus-within:text-lokak-brand" />
                                <select
                                    v-model="searchForm.category"
                                    class="h-full w-full appearance-none rounded-4xl border-none bg-transparent pl-16 pr-10 text-sm font-bold text-slate-700 outline-none ring-0 focus:ring-0"
                                >
                                    <option value="">Semua Kategori</option>
                                    <option v-for="kat in kategoris" :key="kat.id" :value="kat.id">{{ kat.nama_kategori }}</option>
                                </select>
                                <ChevronRight class="absolute right-6 h-4 w-4 rotate-90 text-slate-300 pointer-events-none" />
                                <div class="absolute right-0 h-8 w-px bg-slate-200 hidden lg:block"></div>
                            </div>
                        </div>

                        <div class="relative lg:col-span-3">
                            <div class="group relative flex h-16 items-center">
                                <MapPin class="absolute left-6 h-5 w-5 text-slate-400 transition-colors group-focus-within:text-lokak-brand" />
                                <select
                                    v-model="searchForm.location"
                                    class="h-full w-full appearance-none rounded-4xl border-none bg-transparent pl-16 pr-10 text-sm font-bold text-slate-700 outline-none ring-0 focus:ring-0"
                                >
                                    <option value="">Semua Lokasi</option>
                                    <option v-for="lok in lokasis" :key="lok.id" :value="lok.id">{{ lok.nama_lokasi }}</option>
                                </select>
                                <ChevronRight class="absolute right-6 h-4 w-4 rotate-90 text-slate-300 pointer-events-none" />
                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <button
                                type="submit"
                                class="flex h-16 w-full items-center justify-center gap-3 rounded-[1.8rem] bg-slate-900 px-8 text-xs font-black text-white uppercase italic transition-all duration-300 hover:bg-lokak-brand hover:shadow-xl hover:shadow-sky-900/20 active:scale-95 lg:h-full"
                            >
                                <Search class="h-4 w-4" />
                                <span>CARI</span>
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <div v-for="job in props.lowongans.data" :key="job.id" 
                    class="group relative flex flex-col justify-between rounded-[2.5rem] border border-slate-200 bg-white p-8 transition-all duration-500 hover:-translate-y-2 hover:border-lokak-brand/30 hover:shadow-[0_20px_50px_rgba(0,0,0,0.05)]"
                >
                    <div>
                        <div class="mb-6 flex items-start justify-between">
                            <div class="rounded-xl bg-slate-50 px-4 py-2 text-[10px] font-black uppercase italic text-slate-500">
                                {{ job.kategori?.nama_kategori || 'General' }}
                            </div>
                            <div class="text-slate-200 transition-colors group-hover:text-lokak-brand">
                                <ArrowUpRight class="h-6 w-6" />
                            </div>
                        </div>

                        <div class="mb-8">
                            <h3 class="mb-2 text-xl font-black leading-tight text-slate-900 uppercase italic transition-colors group-hover:text-lokak-brand">
                                {{ job.judul_lowongan }}
                            </h3>
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase italic">
                                <Building2 class="h-3 w-3" v-if="false" /> <span>{{ job.mitra?.nama_mitra || 'PT. Bengkulu Maju' }}</span>
                            </div>
                        </div>

                        <div class="mb-10 flex flex-wrap gap-2">
                            <div class="flex items-center gap-1.5 rounded-full border border-slate-100 bg-slate-50 px-4 py-1.5 text-[9px] font-black uppercase italic text-slate-600">
                                <Clock class="h-3 w-3 text-lokak-brand" />
                                {{ job.tipe_pekerjaan }}
                            </div>
                            <div class="flex items-center gap-1.5 rounded-full border border-slate-100 bg-slate-50 px-4 py-1.5 text-[9px] font-black uppercase italic text-slate-600">
                                <MapPin class="h-3 w-3 text-lokak-brand" />
                                {{ job.lokasi?.nama_lokasi || 'Bengkulu' }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between border-t border-slate-100 pt-8">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold uppercase italic text-slate-400">Estimasi Gaji</span>
                            <span class="text-lg font-black italic text-lokak-brand">{{ formatRupiah(job.gaji_min) }}</span>
                        </div>
                        <Link :href="'/detail/lowongan/' + job.id" 
                            class="flex h-12 items-center justify-center rounded-2xl bg-slate-900 px-6 text-[10px] font-black text-white uppercase italic transition-all hover:bg-lokak-brand active:scale-95"
                        >
                            DETAIL
                        </Link>
                    </div>
                </div>
            </div>

            <div v-if="props.lowongans.data.length === 0" class="flex flex-col items-center justify-center py-40">
                <div class="mb-6 rounded-full bg-slate-50 p-10">
                    <Search class="h-12 w-12 text-slate-200" />
                </div>
                <h3 class="text-xl font-black uppercase italic text-slate-400">Lowongan tidak ditemukan</h3>
                <p class="text-sm font-medium text-slate-300">Coba ubah kata kunci atau filter pencarianmu.</p>
            </div>

            <div v-if="props.lowongans.links.length > 3" class="mt-24 flex items-center justify-center gap-2">
                <template v-for="(link, k) in props.lowongans.links" :key="k">
                    <div v-if="!link.url" 
                        class="flex h-12 min-w-12 items-center justify-center rounded-2xl border border-slate-100 bg-white px-4 text-[10px] font-black text-slate-300 opacity-50 uppercase italic" 
                        v-html="link.label"
                    ></div>
                    <Link v-else :href="link.url" 
                        :class="['flex h-12 min-w-12 items-center justify-center rounded-2xl px-4 text-[10px] font-black uppercase italic transition-all duration-300', 
                        link.active ? 'bg-lokak-brand text-white shadow-xl shadow-sky-900/20' : 'border border-slate-100 bg-white text-slate-400 hover:border-lokak-brand hover:text-lokak-brand']"
                    >
                        <span v-html="link.label"></span>
                    </Link>
                </template>
            </div>
        </main>
        
        <Footer />
    </div>
</template>

<style scoped>
input::placeholder {
    color: #94a3b8;
    font-style: italic;
    font-weight: 700;
}
.transition-all {
    transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
}
/* Custom Scrollbar for better UX */
::-webkit-scrollbar {
    width: 8px;
}
::-webkit-scrollbar-track {
    background: #f1f1f1;
}
::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
    background: #0EA5E9;
}
</style>