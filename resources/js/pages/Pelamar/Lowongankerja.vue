<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Search, MapPin, DollarSign, Briefcase,
    Clock, Bookmark, ChevronRight, SlidersHorizontal,
    Building2, Flame, X, Sparkles, MapPinned, Layers,
    ArrowUpDown, TrendingUp, TrendingDown, Calendar,
    Wallet, BadgeCheck, Filter, RotateCcw, Sparkle
} from 'lucide-vue-next';
import { ref, computed, watch, nextTick, onMounted, onUnmounted } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';

defineOptions({ layout: AppLayout });

const props = defineProps<{
    lowonganList?: Array<{
        id: string;
        judul_lowongan: string;
        tipe_pekerjaan: string;
        gaji_min: number | null;
        gaji_max: number | null;
        created_at: string;
        deskripsi_lowongan?: string;
        lokasi_id?: string;
        mitra?: {
            id: string;
            nama_mitra: string;
            logo_mitra: string | null;
            lokasi_id?: string;
            kategori_id?: string;
            lokasi?: { nama_lokasi: string };
        };
        lokasi?: { nama_lokasi: string };
        skills?: Array<{ id: string; nama_skill: string }>;
    }>;
    lokasis?: Array<{ id: string; nama_lokasi: string }>;
    kategoris?: Array<{ id: string; nama_kategori: string }>;
    filters?: {
        search?: string;
        lokasi?: string;
        kategori?: string;
        gaji_min?: number | null;
        gaji_max?: number | null;
        sort?: string;
    };
}>();

// --- STATE FILTER ---
const searchQuery = ref(props.filters?.search || '');
const selectedLokasi = ref(props.filters?.lokasi || '');
const selectedKategori = ref(props.filters?.kategori || '');
const gajiMin = ref<number | null>(props.filters?.gaji_min || null);
const gajiMax = ref<number | null>(props.filters?.gaji_max || null);
const sortBy = ref(props.filters?.sort || 'newest');
const showFilters = ref(false);
const searchInput = ref<HTMLInputElement | null>(null);
const suggestionTop = ref(0);
const suggestionLeft = ref(0);
const suggestionWidth = ref(0);
const searchContainer = ref<HTMLElement | null>(null);
const suggestionsRef = ref<HTMLElement | null>(null);
const showSuggestions = ref(false);

// Debounce search
let searchTimeout: ReturnType<typeof setTimeout>;

const updateSuggestionPosition = () => {
    if (searchContainer.value) {
        const rect = searchContainer.value.getBoundingClientRect();
        suggestionTop.value = rect.bottom + window.scrollY + 4;
        suggestionLeft.value = rect.left + window.scrollX;
        suggestionWidth.value = rect.width;
    }
};

// Close suggestions when clicking outside
const handleClickOutside = (event: MouseEvent) => {
    const target = event.target as HTMLElement;
    const isInsideSearch = searchContainer.value?.contains(target);
    const isInsideSuggestions = suggestionsRef.value?.contains(target);
    
    if (!isInsideSearch && !isInsideSuggestions) {
        showSuggestions.value = false;
    }
};

watch(searchQuery, (val) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 300);
    // Update position and show suggestions
    if (val && val.length >= 2) {
        showSuggestions.value = true;
        nextTick(updateSuggestionPosition);
    } else {
        showSuggestions.value = false;
    }
});

// Add click-outside listener
onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    window.addEventListener('resize', updateSuggestionPosition);
    window.addEventListener('scroll', updateSuggestionPosition, true);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
    window.removeEventListener('resize', updateSuggestionPosition);
    window.removeEventListener('scroll', updateSuggestionPosition, true);
});

// --- HELPERS ---
const formatRupiah = (value: number | null) => {
    if (!value) return '';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(value);
};

const formatRupiahRange = (min: number | null, max: number | null) => {
    if (!min && !max) return 'Gaji Dirahasiakan';
    if (min && max) return `${formatRupiah(min)} - ${formatRupiah(max)}`;
    if (min) return `Mulai ${formatRupiah(min)}`;
    return `Hingga ${formatRupiah(max)}`;
};

const formatRupiahShort = (value: number | null) => {
    if (!value) return '';
    if (value >= 1000000) {
        return `${(value / 1000000).toFixed(1)}jt`;
    }
    if (value >= 1000) {
        return `${(value / 1000).toFixed(0)}rb`;
    }
    return value.toString();
};

const timeAgo = (dateString: string) => {
    if (!dateString) return 'Baru saja';
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now.getTime() - date.getTime()) / 1000);
    if (isNaN(diffInSeconds)) return 'Baru saja';
    if (diffInSeconds < 60) return 'Baru saja';
    const diffInMinutes = Math.floor(diffInSeconds / 60);
    if (diffInMinutes < 60) return `${diffInMinutes}m lalu`;
    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) return `${diffInHours}j lalu`;
    const diffInDays = Math.floor(diffInHours / 24);
    if (diffInDays < 30) return `${diffInDays} hari lalu`;
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
};

const getInitials = (name: string) => {
    if (!name) return '??';
    const parts = name.trim().split(' ');
    return parts.length >= 2
        ? (parts[0][0] + parts[1][0]).toUpperCase()
        : name.substring(0, 2).toUpperCase();
};

const isHotJob = (dateString: string) => {
    if (!dateString) return false;
    const date = new Date(dateString);
    const now = new Date();
    const diffInHours = (now.getTime() - date.getTime()) / (1000 * 60 * 60);
    return diffInHours < 24; // HOT untuk lowongan < 24 jam
};

// --- FILTER ACTIONS ---
const applyFilters = () => {
    router.get(
        route('pelamar.lowongankerja'),
        {
            search: searchQuery.value || undefined,
            lokasi: selectedLokasi.value || undefined,
            kategori: selectedKategori.value || undefined,
            gaji_min: gajiMin.value || undefined,
            gaji_max: gajiMax.value || undefined,
            sort: sortBy.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

const resetFilters = () => {
    searchQuery.value = '';
    selectedLokasi.value = '';
    selectedKategori.value = '';
    gajiMin.value = null;
    gajiMax.value = null;
    sortBy.value = 'newest';
    router.get(
        route('pelamar.lowongankerja'),
        {},
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const hasActiveFilters = computed(() => {
    return searchQuery.value || selectedLokasi.value || selectedKategori.value ||
           gajiMin.value || gajiMax.value || sortBy.value !== 'newest';
});

const activeFilterCount = computed(() => {
    let count = 0;
    if (searchQuery.value) count++;
    if (selectedLokasi.value) count++;
    if (selectedKategori.value) count++;
    if (gajiMin.value || gajiMax.value) count++;
    if (sortBy.value !== 'newest') count++;
    return count;
});

// --- COMPUTED FILTERED JOBS ---
const filteredJobs = computed(() => {
    return props.lowonganList || [];
});

const searchSuggestions = computed(() => {
    if (!searchQuery.value || searchQuery.value.length < 2) return [];
    const q = searchQuery.value.toLowerCase();
    const suggestions = new Set<string>();
    
    props.lowonganList?.forEach(job => {
        if (job.judul_lowongan?.toLowerCase().includes(q)) {
            suggestions.add(job.judul_lowongan);
        }
        if (job.mitra?.nama_mitra?.toLowerCase().includes(q)) {
            suggestions.add(job.mitra.nama_mitra);
        }
        job.skills?.forEach(skill => {
            if (skill.nama_skill?.toLowerCase().includes(q)) {
                suggestions.add(skill.nama_skill);
            }
        });
    });
    
    return Array.from(suggestions).slice(0, 5);
});

const selectSuggestion = (suggestion: string) => {
    searchQuery.value = suggestion;
    showSuggestions.value = false;
    applyFilters();
};
</script>

<template>
    <Head title="Cari Lowongan" />

    <div class="min-h-screen bg-slate-50/50 p-4 md:p-6 lg:p-8">
        <!-- Hero Search Section -->
        <section class="relative isolate rounded-4xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-sky-100/30 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-20 -left-20 h-48 w-48 rounded-full bg-sky-50/50 blur-2xl"></div>

            <div class="relative">
                <div class="mb-6 text-center">
                    <h1 class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl lg:text-5xl">
                        CARI <span class="text-lokak-brand">LOWONGAN</span>
                    </h1>
                    <p class="mx-auto mt-2 max-w-md text-xs font-medium text-slate-500">
                        Temukan pekerjaan impianmu di Bengkulu. Cari berdasarkan posisi, perusahaan, atau keahlian.
                    </p>
                </div>

                <!-- Main Search Bar -->
                <div ref="searchContainer" class="mx-auto max-w-4xl px-4 sm:px-6">
                    <div class="relative w-full">
                        <div class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white p-2 shadow-lg shadow-slate-200/50 transition-all focus-within:border-lokak-brand focus-within:ring-4 focus-within:ring-lokak-brand/10">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                                <Search class="h-5 w-5" />
                            </div>
                            <input
                                ref="searchInput"
                                v-model="searchQuery"
                                type="text"
                                placeholder="Cari posisi, perusahaan, keahlian..."
                                class="h-12 min-w-0 flex-1 border-0 bg-transparent pl-2 pr-4 text-sm font-bold text-slate-800 placeholder:text-slate-400 focus:outline-none truncate"
                            />
                            <button
                                v-if="searchQuery"
                                @click="searchQuery = ''; applyFilters()"
                                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600"
                            >
                                <X class="h-4 w-4" />
                            </button>
                            <button
                                @click="applyFilters"
                                class="flex h-12 shrink-0 items-center justify-center gap-2 rounded-full bg-lokak-brand px-8 text-sm font-black uppercase italic text-white shadow-lg shadow-sky-500/30 transition-all hover:bg-sky-600 hover:shadow-xl hover:-translate-y-0.5 active:scale-95"
                            >
                                <Search class="h-4 w-4" />
                                Cari
                            </button>
                        </div>

                    </div>

                    <!-- Search Suggestions - Outside container to avoid overflow issues -->
                    <Teleport to="body">
                        <div
                            v-if="showSuggestions && searchSuggestions.length > 0"
                            ref="suggestionsRef"
                            class="fixed z-100 overflow-y-auto rounded-xl border border-slate-200/80 bg-white/95 p-1.5 shadow-2xl shadow-slate-300/50 backdrop-blur-sm"
                            :style="{ maxHeight: '280px', top: suggestionTop + 'px', left: suggestionLeft + 'px', width: suggestionWidth + 'px' }"
                        >
                            <div class="mb-1 px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-slate-400">Saran Pencarian</div>
                            <button
                                v-for="(suggestion, index) in searchSuggestions"
                                :key="suggestion"
                                @click="selectSuggestion(suggestion)"
                                class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm font-semibold text-slate-700 transition-all duration-200 hover:bg-sky-50 hover:text-lokak-brand"
                                :class="{ 'bg-sky-50/50': index === 0 }"
                            >
                                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-sky-100 text-sky-600">
                                    <Sparkle class="h-3.5 w-3.5" />
                                </div>
                                <span class="truncate">{{ suggestion }}</span>
                            </button>
                        </div>
                    </Teleport>

                    <!-- Filter Toggle & Active Filters -->
                    <div class="mt-4 flex flex-wrap items-center justify-center gap-2 px-2">
                        <button
                            @click="showFilters = !showFilters"
                            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 transition hover:border-lokak-brand hover:text-lokak-brand"
                            :class="{ 'border-lokak-brand text-lokak-brand': showFilters }"
                        >
                            <SlidersHorizontal class="h-3.5 w-3.5" />
                            Filter Lanjutan
                            <span
                                v-if="activeFilterCount > 0"
                                class="ml-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-lokak-brand px-1.5 text-[10px] text-white"
                            >
                                {{ activeFilterCount }}
                            </span>
                        </button>

                        <!-- Active Filter Pills -->
                        <template v-if="selectedLokasi">
                            <div class="inline-flex items-center gap-1.5 rounded-full bg-sky-50 px-3 py-1.5 text-[10px] font-bold text-sky-700">
                                <MapPin class="h-3 w-3" />
                                {{ props.lokasis?.find(l => l.id === selectedLokasi)?.nama_lokasi }}
                                <button @click="selectedLokasi = ''; applyFilters()" class="ml-1 hover:text-sky-900">
                                    <X class="h-3 w-3" />
                                </button>
                            </div>
                        </template>
                        <template v-if="selectedKategori">
                            <div class="inline-flex items-center gap-1.5 rounded-full bg-sky-50 px-3 py-1.5 text-[10px] font-bold text-sky-700">
                                <Layers class="h-3 w-3" />
                                {{ props.kategoris?.find(k => k.id === selectedKategori)?.nama_kategori }}
                                <button @click="selectedKategori = ''; applyFilters()" class="ml-1 hover:text-sky-900">
                                    <X class="h-3 w-3" />
                                </button>
                            </div>
                        </template>
                        <template v-if="gajiMin || gajiMax">
                            <div class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-[10px] font-bold text-emerald-700">
                                <Wallet class="h-3 w-3" />
                                {{ formatRupiahShort(gajiMin) || '0' }} - {{ formatRupiahShort(gajiMax) || '∞' }}
                                <button @click="gajiMin = null; gajiMax = null; applyFilters()" class="ml-1 hover:text-emerald-900">
                                    <X class="h-3 w-3" />
                                </button>
                            </div>
                        </template>

                        <button
                            v-if="hasActiveFilters"
                            @click="resetFilters"
                            class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[10px] font-bold text-slate-500 transition hover:border-rose-200 hover:text-rose-600"
                        >
                            <RotateCcw class="h-3 w-3" />
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Advanced Filters Panel -->
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 -translate-y-4"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-4"
        >
            <section v-if="showFilters" class="mt-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Filter Lokasi -->
                    <div>
                        <label class="mb-1.5 flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                            <MapPinned class="h-3.5 w-3.5" />
                            Lokasi
                        </label>
                        <div class="relative">
                            <select
                                v-model="selectedLokasi"
                                @change="applyFilters"
                                class="h-11 w-full cursor-pointer appearance-none rounded-xl border border-slate-200 bg-slate-50 pl-3 pr-8 text-xs font-bold text-slate-700 outline-none transition focus:border-lokak-brand focus:bg-white focus:ring-2 focus:ring-lokak-brand/20"
                            >
                                <option value="">Semua Lokasi</option>
                                <option v-for="lokasi in props.lokasis" :key="lokasi.id" :value="lokasi.id">
                                    {{ lokasi.nama_lokasi }}
                                </option>
                            </select>
                            <ChevronRight class="absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 rotate-90 text-slate-400" />
                        </div>
                    </div>

                    <!-- Filter Kategori -->
                    <div>
                        <label class="mb-1.5 flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                            <Layers class="h-3.5 w-3.5" />
                            Kategori Industri
                        </label>
                        <div class="relative">
                            <select
                                v-model="selectedKategori"
                                @change="applyFilters"
                                class="h-11 w-full cursor-pointer appearance-none rounded-xl border border-slate-200 bg-slate-50 pl-3 pr-8 text-xs font-bold text-slate-700 outline-none transition focus:border-lokak-brand focus:bg-white focus:ring-2 focus:ring-lokak-brand/20"
                            >
                                <option value="">Semua Kategori</option>
                                <option v-for="kategori in props.kategoris" :key="kategori.id" :value="kategori.id">
                                    {{ kategori.nama_kategori }}
                                </option>
                            </select>
                            <ChevronRight class="absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 rotate-90 text-slate-400" />
                        </div>
                    </div>

                    <!-- Filter Gaji Min -->
                    <div>
                        <label class="mb-1.5 flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                            <TrendingUp class="h-3.5 w-3.5" />
                            Gaji Minimum
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                            <input
                                v-model.number="gajiMin"
                                type="number"
                                placeholder="Contoh: 3000000"
                                @change="applyFilters"
                                class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 pl-8 pr-3 text-xs font-bold text-slate-700 outline-none transition focus:border-lokak-brand focus:bg-white focus:ring-2 focus:ring-lokak-brand/20"
                            />
                        </div>
                    </div>

                    <!-- Filter Gaji Max -->
                    <div>
                        <label class="mb-1.5 flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                            <TrendingDown class="h-3.5 w-3.5" />
                            Gaji Maksimum
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                            <input
                                v-model.number="gajiMax"
                                type="number"
                                placeholder="Contoh: 8000000"
                                @change="applyFilters"
                                class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 pl-8 pr-3 text-xs font-bold text-slate-700 outline-none transition focus:border-lokak-brand focus:bg-white focus:ring-2 focus:ring-lokak-brand/20"
                            />
                        </div>
                    </div>
                </div>

                <!-- Sort Options -->
                <div class="mt-4 border-t border-slate-100 pt-4">
                    <label class="mb-2 flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        <ArrowUpDown class="h-3.5 w-3.5" />
                        Urutkan Berdasarkan
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="option in [
                                { value: 'newest', label: 'Terbaru', icon: Calendar },
                                { value: 'oldest', label: 'Terlama', icon: Clock },
                                { value: 'gaji_desc', label: 'Gaji Tertinggi', icon: TrendingUp },
                                { value: 'gaji_asc', label: 'Gaji Terendah', icon: TrendingDown },
                            ]"
                            :key="option.value"
                            @click="sortBy = option.value; applyFilters()"
                            class="inline-flex items-center gap-1.5 rounded-xl border px-3 py-2 text-[10px] font-bold uppercase transition"
                            :class="sortBy === option.value
                                ? 'border-lokak-brand bg-lokak-brand text-white'
                                : 'border-slate-200 bg-white text-slate-600 hover:border-lokak-brand hover:text-lokak-brand'"
                        >
                            <component :is="option.icon" class="h-3.5 w-3.5" />
                            {{ option.label }}
                        </button>
                    </div>
                </div>
            </section>
        </Transition>

        <!-- Results Header -->
        <div class="mt-6 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <BadgeCheck class="h-5 w-5 text-lokak-brand" />
                <span class="text-sm font-bold text-slate-700">
                    {{ filteredJobs.length }} Lowongan Tersedia
                </span>
            </div>
            <div class="text-xs font-medium text-slate-400">
                {{ sortBy === 'newest' ? 'Diurutkan: Terbaru' : 
                   sortBy === 'oldest' ? 'Diurutkan: Terlama' :
                   sortBy === 'gaji_desc' ? 'Diurutkan: Gaji Tertinggi' :
                   sortBy === 'gaji_asc' ? 'Diurutkan: Gaji Terendah' : '' }}
            </div>
        </div>

        <!-- Jobs Grid -->
        <section v-if="filteredJobs.length > 0" class="relative mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            <article
                v-for="job in filteredJobs"
                :key="job.id"
                class="group relative flex flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-sky-200 hover:shadow-lg"
            >
                <!-- Hot Badge -->
                <div v-if="isHotJob(job.created_at)" class="absolute -right-2 -top-2 z-10">
                    <div class="flex items-center gap-1 rounded-lg bg-linear-to-br from-amber-500 to-orange-500 px-2 py-1 text-[9px] font-black text-white uppercase italic shadow-lg shadow-amber-500/30">
                        <Flame class="h-3 w-3" />
                        HOT
                    </div>
                </div>

                <!-- Header -->
                <div class="mb-4 flex items-start gap-3">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-slate-100 bg-slate-50 shadow-sm">
                        <img
                            v-if="job.mitra?.logo_mitra"
                            :src="`/storage/${job.mitra.logo_mitra}`"
                            class="h-full w-full object-cover"
                        />
                        <span v-else class="text-sm font-black text-lokak-brand">{{ getInitials(job.mitra?.nama_mitra || '') }}</span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <h3 class="truncate text-sm font-black uppercase italic leading-tight text-slate-900 group-hover:text-lokak-brand transition-colors">
                            {{ job.judul_lowongan }}
                        </h3>
                        <p class="mt-0.5 inline-flex items-center gap-1 text-[10px] font-bold uppercase text-slate-500">
                            <Building2 class="h-3 w-3 text-slate-400" />
                            {{ job.mitra?.nama_mitra || 'Perusahaan Mitra' }}
                        </p>
                    </div>
                </div>

                <!-- Skills Tags -->
                <div v-if="job.skills && job.skills.length > 0" class="mb-3 flex flex-wrap gap-1.5">
                    <span
                        v-for="skill in job.skills.slice(0, 3)"
                        :key="skill.id"
                        class="inline-flex items-center rounded-md bg-sky-50 px-2 py-1 text-[9px] font-bold text-sky-700"
                    >
                        {{ skill.nama_skill }}
                    </span>
                    <span v-if="job.skills.length > 3" class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-[9px] font-bold text-slate-500">
                        +{{ job.skills.length - 3 }}
                    </span>
                </div>

                <!-- Details -->
                <div class="mb-4 space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1 rounded-lg border border-slate-100 bg-slate-50 px-2 py-1 text-[9px] font-bold uppercase text-slate-600">
                            <MapPin class="h-3 w-3 text-lokak-brand" />
                            {{ job.lokasi?.nama_lokasi || job.mitra?.lokasi?.nama_lokasi || 'Bengkulu' }}
                        </span>
                        <span class="inline-flex items-center gap-1 rounded-lg border border-slate-100 bg-slate-50 px-2 py-1 text-[9px] font-bold uppercase text-slate-600">
                            <Briefcase class="h-3 w-3 text-lokak-brand" />
                            {{ job.tipe_pekerjaan || 'Full Time' }}
                        </span>
                    </div>
                    <p class="flex items-center gap-1 text-xs font-black uppercase italic" :class="job.gaji_min || job.gaji_max ? 'text-emerald-600' : 'text-slate-400'">
                        <DollarSign class="h-3.5 w-3.5" />
                        {{ formatRupiahRange(job.gaji_min, job.gaji_max) }}
                    </p>
                </div>

                <!-- Footer -->
                <div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-4">
                    <span class="text-[9px] font-bold uppercase italic text-slate-400">
                        <Clock class="mr-1 inline h-3 w-3" />
                        {{ timeAgo(job.created_at) }}
                    </span>
                    <div class="flex items-center gap-2">
                        <button class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition hover:border-lokak-brand hover:text-lokak-brand">
                            <Bookmark class="h-4 w-4" />
                        </button>
                        <Link
                            v-if="job.id"
                            :href="route('pelamar.lowongan.detail', job.id)"
                            class="inline-flex items-center gap-1 rounded-xl bg-lokak-brand px-4 py-2 text-[10px] font-black uppercase italic text-white shadow-md shadow-sky-500/20 transition hover:bg-sky-600 hover:shadow-lg"
                        >
                            Detail
                            <ChevronRight class="h-3.5 w-3.5" />
                        </Link>
                    </div>
                </div>
            </article>
        </section>

        <!-- Empty State -->
        <section v-else class="mt-4 flex flex-col items-center justify-center rounded-4xl border border-dashed border-slate-300 bg-white py-16 text-center">
            <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-2xl bg-slate-100">
                <Search class="h-10 w-10 text-slate-300" />
            </div>
            <h3 class="text-base font-black uppercase italic text-slate-600">Lowongan tidak ditemukan</h3>
            <p class="mt-2 max-w-sm text-sm font-medium text-slate-400">
                Coba ubah filter atau kata kunci pencarian Anda. Anda juga bisa mencari berdasarkan keahlian atau nama perusahaan.
            </p>
            <button
                v-if="hasActiveFilters"
                @click="resetFilters"
                class="mt-6 inline-flex items-center gap-2 rounded-xl bg-lokak-brand px-6 py-3 text-sm font-black uppercase italic text-white shadow-lg shadow-sky-500/25 transition hover:bg-sky-600"
            >
                <RotateCcw class="h-4 w-4" />
                Reset Semua Filter
            </button>
        </section>
    </div>
</template>