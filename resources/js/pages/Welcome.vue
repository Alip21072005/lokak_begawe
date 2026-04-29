<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { 
    Search, MapPin, ArrowUpRight, Building2, Star, 
    Layers, X, Sparkles, Briefcase, Flame,
    ChevronRight
} from 'lucide-vue-next';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';
import CustomSelect from '@/components/CustomSelect.vue';
import { ref, computed } from 'vue';

const props = defineProps<{
    lowonganTerbaru: Array<any>;
    mitraTeratas: Array<any>;
    lokasis?: Array<{id: string; nama_lokasi: string}>;
    kategoris?: Array<{id: string; nama_kategori: string}>;
}>();

// --- HERO SEARCH STATE ---
const heroSearch = ref('');
const heroCategory = ref('');
const heroLocation = ref('');
const showAdvancedSearch = ref(false);

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

// Hero search suggestions
const heroSuggestions = computed(() => {
    if (!heroSearch.value || heroSearch.value.length < 2) return [];
    const q = heroSearch.value.toLowerCase();
    const suggestions = new Set<string>();
    
    props.lowonganTerbaru?.forEach(job => {
        if (job.judul_lowongan?.toLowerCase().includes(q)) {
            suggestions.add(job.judul_lowongan);
        }
        if (job.mitra?.nama_mitra?.toLowerCase().includes(q)) {
            suggestions.add(job.mitra.nama_mitra);
        }
    });
    
    return Array.from(suggestions).slice(0, 5);
});

const selectHeroSuggestion = (suggestion: string) => {
    heroSearch.value = suggestion;
    performHeroSearch();
};

const performHeroSearch = () => {
    router.get(
        route('lowongan.index'),
        {
            keyword: heroSearch.value || undefined,
            category: heroCategory.value || undefined,
            location: heroLocation.value || undefined,
        },
        { preserveState: true }
    );
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

            <!-- Professional Search Section -->
            <div class="relative z-20 mx-auto -mt-16 mb-24 max-w-5xl px-4 sm:px-6 md:-mt-20">
                <div class="relative w-full rounded-3xl border border-slate-200 bg-white p-3 shadow-2xl shadow-slate-300/50 md:rounded-full md:p-2">
                    <!-- Main Search Row -->
                    <div class="flex w-full flex-col gap-2 md:flex-row md:items-center md:gap-0">
                        <!-- Search Input -->
                        <div class="relative flex h-14 min-w-0 flex-1 items-center rounded-2xl bg-slate-50 px-4 md:rounded-full md:px-6">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-600">
                                <Search class="h-5 w-5" />
                            </div>
                            <input 
                                v-model="heroSearch" 
                                type="text" 
                                placeholder="Cari posisi, perusahaan, keahlian..." 
                                class="h-full min-w-0 flex-1 border-none bg-transparent pl-3 pr-2 text-sm font-bold text-slate-700 focus:outline-none focus:ring-0 placeholder:text-slate-400 truncate"
                                @keyup.enter="performHeroSearch"
                            />
                            <button 
                                v-if="heroSearch" 
                                @click="heroSearch = ''"
                                class="flex h-8 w-8 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-200 hover:text-slate-600"
                            >
                                <X class="h-4 w-4" />
                            </button>
                        </div>

                        <!-- Category Select -->
                        <div class="md:w-56 md:border-l md:border-slate-100 md:pl-6">
                            <CustomSelect
                                v-model="heroCategory"
                                :options="props.kategoris?.map(k => ({ value: k.id, label: k.nama_kategori })) || []"
                                placeholder="Semua Kategori"
                                label="Kategori"
                                :icon="Layers"
                            />
                        </div>

                        <!-- Location Select -->
                        <div class="md:w-56 md:border-l md:border-slate-100 md:pl-6">
                            <CustomSelect
                                v-model="heroLocation"
                                :options="props.lokasis?.map(l => ({ value: l.id, label: l.nama_lokasi })) || []"
                                placeholder="Semua Lokasi"
                                label="Lokasi"
                                :icon="MapPin"
                            />
                        </div>

                        <!-- Search Button -->
                        <button 
                            @click="performHeroSearch"
                            class="flex h-12 shrink-0 items-center justify-center gap-2 rounded-full bg-lokak-brand px-8 text-sm font-black uppercase tracking-wider text-white shadow-lg shadow-sky-500/30 transition-all hover:bg-sky-600 hover:shadow-xl hover:-translate-y-0.5 active:scale-95"
                        >
                            <Search class="h-4 w-4" />
                            <span class="italic">Cari Loker</span>
                        </button>
                    </div>

                    <!-- Search Suggestions -->
                    <div 
                        v-if="heroSuggestions.length > 0" 
                        class="absolute left-0 right-0 top-full z-50 mt-2 max-h-60 overflow-y-auto rounded-2xl border border-slate-100 bg-white p-2 shadow-xl"
                    >
                        <div class="px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Saran Pencarian</div>
                        <button
                            v-for="suggestion in heroSuggestions"
                            :key="suggestion"
                            @click="selectHeroSuggestion(suggestion)"
                            class="flex w-full items-center gap-2 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-slate-700 transition hover:bg-sky-50 hover:text-sky-700"
                        >
                            <Sparkles class="h-4 w-4 text-sky-500" />
                            {{ suggestion }}
                        </button>
                    </div>
                </div>
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
                        <Link v-for="job in lowonganTerbaru" :key="job.id" :href="route('detail.lowongan', job.id)" class="group relative flex flex-col justify-between rounded-3xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-lokak-brand/30 hover:shadow-xl">
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

<style scoped>
/* No custom select styles needed - using CustomSelect component */
</style>