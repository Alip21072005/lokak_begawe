<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { 
    Search, MapPin, Layers, ArrowUpRight, Building2, 
    X, Sparkles, Briefcase, Flame, ChevronRight,
    RotateCcw
} from 'lucide-vue-next';
import { ref, watch, computed } from 'vue';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';
import CustomSelect from '@/components/CustomSelect.vue';

const props = defineProps<{
    lowongans?: { data: Array<any>; links: Array<any>; };
    lokasis: Array<any>;
    kategoris: Array<any>;
    filters: any;
}>();

const keyword = ref(props.filters?.keyword || '');
const category = ref(props.filters?.category || '');
const location = ref(props.filters?.location || '');
const showFilters = ref(false);

let searchTimeout: ReturnType<typeof setTimeout>;

// Fitur Live Search dengan Debounce
watch([keyword, category, location], () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 300);
});

const applyFilters = () => {
    router.get(
        '/lowongan',
        { 
            keyword: keyword.value || undefined, 
            category: category.value || undefined, 
            location: location.value || undefined 
        },
        { preserveState: true, replace: true, preserveScroll: true }
    );
};

const resetFilters = () => {
    keyword.value = '';
    category.value = '';
    location.value = '';
    router.get('/lowongan', {}, { preserveState: true, preserveScroll: true });
};

const hasActiveFilters = computed(() => keyword.value || category.value || location.value);

const formatRupiah = (v: any) => {
    if (!v || v == 0) return 'Bersaing';
    return new Intl.NumberFormat('id-ID', { 
        style: 'currency', currency: 'IDR', minimumFractionDigits: 0 
    }).format(v);
};

const isHotJob = (dateString: string) => {
    if (!dateString) return false;
    const date = new Date(dateString);
    const now = new Date();
    const diffInHours = (now.getTime() - date.getTime()) / (1000 * 60 * 60);
    return diffInHours < 24; // HOT untuk lowongan < 24 jam
};

const timeAgo = (dateString: string) => {
    if (!dateString) return 'Baru saja';
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now.getTime() - date.getTime()) / 1000);
    if (diffInSeconds < 60) return 'Baru saja';
    const diffInMinutes = Math.floor(diffInSeconds / 60);
    if (diffInMinutes < 60) return `${diffInMinutes}m lalu`;
    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) return `${diffInHours}j lalu`;
    const diffInDays = Math.floor(diffInHours / 24);
    if (diffInDays < 30) return `${diffInDays} hari lalu`;
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
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

            <!-- Professional Search Section -->
            <section class="mb-10 px-4 sm:px-6">
                <div class="mx-auto max-w-5xl rounded-3xl border border-slate-200 bg-white p-3 shadow-xl shadow-slate-200/50 md:rounded-full md:p-2">
                    <div class="flex w-full flex-col gap-2 md:flex-row md:items-center md:gap-0">
                        <!-- Search Input -->
                        <div class="relative flex h-14 min-w-0 flex-1 items-center rounded-2xl bg-slate-50 px-4 md:rounded-full md:px-6">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-600">
                                <Search class="h-5 w-5" />
                            </div>
                            <input 
                                v-model="keyword" 
                                type="text" 
                                placeholder="Posisi, perusahaan, keahlian..." 
                                class="h-full min-w-0 flex-1 border-none bg-transparent pl-3 pr-2 text-sm font-bold text-slate-700 outline-none placeholder:text-slate-400 focus:ring-0 truncate"
                                @keyup.enter="applyFilters"
                            />
                            <button 
                                v-if="keyword" 
                                @click="keyword = ''; applyFilters()"
                                class="flex h-8 w-8 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-200 hover:text-slate-600"
                            >
                                <X class="h-4 w-4" />
                            </button>
                        </div>

                        <!-- Category Select -->
                        <div class="md:w-56 md:border-l md:border-slate-100 md:pl-6">
                            <CustomSelect
                                v-model="category"
                                :options="kategoris.map(k => ({ value: k.id, label: k.nama_kategori }))"
                                placeholder="Semua Kategori"
                                label="Kategori"
                                :icon="Layers"
                            />
                        </div>

                        <!-- Location Select -->
                        <div class="md:w-56 md:border-l md:border-slate-100 md:pl-6">
                            <CustomSelect
                                v-model="location"
                                :options="lokasis.map(l => ({ value: l.id, label: l.nama_lokasi }))"
                                placeholder="Semua Lokasi"
                                label="Lokasi"
                                :icon="MapPin"
                            />
                        </div>

                        <!-- Search Button -->
                        <button 
                            @click="applyFilters"
                            class="flex h-12 shrink-0 items-center justify-center gap-2 rounded-full bg-lokak-brand px-8 text-sm font-black uppercase tracking-wider text-white shadow-lg shadow-sky-500/30 transition-all hover:bg-sky-600 hover:shadow-xl hover:-translate-y-0.5 active:scale-95"
                        >
                            <Search class="h-4 w-4" />
                            <span class="italic">Cari</span>
                        </button>
                    </div>
                </div>

                <!-- Reset Filter -->
                <div v-if="hasActiveFilters" class="mt-3 flex justify-center">
                    <button 
                        @click="resetFilters"
                        class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-500 transition hover:border-rose-200 hover:text-rose-600 hover:bg-rose-50"
                    >
                        <RotateCcw class="h-3 w-3" />
                        Reset Filter
                    </button>
                </div>
            </section>

            <div v-if="props.lowongans?.data && props.lowongans.data.length > 0" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 lg:gap-8">
                <Link v-for="job in props.lowongans.data" :key="job.id" :href="'/detail/lowongan/' + job.id" class="group relative flex flex-col justify-between rounded-3xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-lokak-brand/30 hover:shadow-xl">
                    <!-- HOT Badge -->
                    <div v-if="isHotJob(job.created_at)" class="absolute -right-1 -top-1">
                        <div class="flex items-center gap-1 rounded-bl-xl rounded-tr-xl bg-linear-to-br from-amber-500 to-orange-500 px-2.5 py-1 text-[9px] font-black text-white uppercase italic shadow-lg shadow-amber-500/30">
                            <Flame class="h-3 w-3" />
                            HOT
                        </div>
                    </div>

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
                                <Briefcase class="h-3.5 w-3.5 text-slate-400" /> {{ job.tipe_pekerjaan || 'Full Time' }}
                            </div>
                            <div class="flex items-center gap-1.5 rounded-full bg-slate-50 px-3 py-1 text-[10px] font-bold text-slate-600 uppercase tracking-tight">
                                <MapPin class="h-3.5 w-3.5 text-slate-400" /> <span class="truncate max-w-25">{{ job.lokasi?.nama_lokasi }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-5">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Gaji</span>
                            <span class="text-base font-black italic" :class="job.gaji_min ? 'text-lokak-brand' : 'text-slate-500'">{{ formatRupiah(job.gaji_min) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold uppercase italic text-slate-400">{{ timeAgo(job.created_at) }}</span>
                            <span class="inline-flex items-center justify-center rounded-full bg-slate-900 px-4 py-2 text-[10px] font-black uppercase tracking-wider text-white transition-all group-hover:bg-lokak-brand italic shadow-md active:scale-95"> 
                                Detail
                            </span>
                        </div>
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
/* No custom select styles needed - using CustomSelect component */
</style>