<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Search, Building2, MapPin, Eye, Sparkles, SlidersHorizontal, X,
    MapPinned, Layers, RotateCcw, ChevronRight, BadgeCheck, Sparkle
} from 'lucide-vue-next';
import { computed, ref, watch, nextTick, onMounted, onUnmounted } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    auth: any;
    mitraList: Array<{
        id: string;
        nama_mitra: string;
        lokasi: string;
        kategori: string;
        lokasi_id?: string;
        kategori_id?: string;
        deskripsi: string | null;
        logo_mitra: string | null;
    }>;
    lokasis?: Array<{ id: string; nama_lokasi: string }>;
    kategoris?: Array<{ id: string; nama_kategori: string }>;
    filters?: {
        search?: string;
        lokasi?: string;
        kategori?: string;
    };
}>();

defineOptions({ layout: AppLayout });

// --- STATE ---
const searchQuery = ref(props.filters?.search || '');
const selectedLokasi = ref(props.filters?.lokasi || '');
const selectedKategori = ref(props.filters?.kategori || '');
const showFilters = ref(false);
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

// Watch for search query changes
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

// --- COMPUTED ---
const filteredMitra = computed(() => {
    let result = props.mitraList || [];
    const q = searchQuery.value.toLowerCase().trim();
    
    if (q) {
        result = result.filter((m) => {
            const nama = (m.nama_mitra || '').toLowerCase();
            const lokasi = (m.lokasi || '').toLowerCase();
            const kategori = (m.kategori || '').toLowerCase();
            const deskripsi = (m.deskripsi || '').toLowerCase();
            return nama.includes(q) || lokasi.includes(q) || kategori.includes(q) || deskripsi.includes(q);
        });
    }

    if (selectedLokasi.value) {
        result = result.filter((m) => m.lokasi_id === selectedLokasi.value || m.lokasi === props.lokasis?.find(l => l.id === selectedLokasi.value)?.nama_lokasi);
    }

    if (selectedKategori.value) {
        result = result.filter((m) => m.kategori_id === selectedKategori.value || m.kategori === props.kategoris?.find(k => k.id === selectedKategori.value)?.nama_kategori);
    }

    return result;
});

const searchSuggestions = computed(() => {
    if (!searchQuery.value || searchQuery.value.length < 2) return [];
    const q = searchQuery.value.toLowerCase();
    const suggestions = new Set<string>();
    
    props.mitraList?.forEach(m => {
        if (m.nama_mitra?.toLowerCase().includes(q)) {
            suggestions.add(m.nama_mitra);
        }
        if (m.kategori?.toLowerCase().includes(q)) {
            suggestions.add(m.kategori);
        }
    });
    
    return Array.from(suggestions).slice(0, 5);
});

const hasActiveFilters = computed(() => {
    return selectedLokasi.value || selectedKategori.value || searchQuery.value;
});

const activeFilterCount = computed(() => {
    let count = 0;
    if (searchQuery.value) count++;
    if (selectedLokasi.value) count++;
    if (selectedKategori.value) count++;
    return count;
});

// --- ACTIONS ---
const applyFilters = () => {
    router.get(
        route('pelamar.carimitra'),
        {
            search: searchQuery.value || undefined,
            lokasi: selectedLokasi.value || undefined,
            kategori: selectedKategori.value || undefined,
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
    router.get(
        route('pelamar.carimitra'),
        {},
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const selectSuggestion = (suggestion: string) => {
    searchQuery.value = suggestion;
    showSuggestions.value = false;
    applyFilters();
};

const getInitials = (name: string) => {
    if (!name) return '??';
    const parts = name.trim().split(' ');
    return parts.length >= 2
        ? (parts[0][0] + parts[1][0]).toUpperCase()
        : name.substring(0, 2).toUpperCase();
};
</script>

<template>
    <Head title="Cari Mitra" />

    <div class="min-h-screen bg-slate-50/50 p-4 md:p-6 lg:p-8">
        <!-- Hero Search Section -->
        <section class="relative overflow-hidden rounded-4xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-sky-100/30 blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 h-48 w-48 rounded-full bg-sky-50/50 blur-2xl"></div>

            <div class="relative">
                <div class="mb-6 text-center">
                    <h1 class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl lg:text-5xl">
                        CARI <span class="text-lokak-brand">MITRA</span>
                    </h1>
                    <p class="mx-auto mt-2 max-w-md text-xs font-medium text-slate-500">
                        Temukan perusahaan terverifikasi yang mencari talenta seperti Anda. Cari berdasarkan nama, lokasi, atau industri.
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
                                v-model="searchQuery"
                                type="text"
                                placeholder="Cari perusahaan, industri..."
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
                            Filter
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
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
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
                </div>
            </section>
        </Transition>

        <!-- Results Header -->
        <div class="mt-6 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <BadgeCheck class="h-5 w-5 text-lokak-brand" />
                <span class="text-sm font-bold text-slate-700">
                    {{ filteredMitra.length }} Mitra Terverifikasi
                </span>
            </div>
        </div>

        <!-- Mitra Grid -->
        <section v-if="filteredMitra.length > 0" class="relative mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            <article
                v-for="mitra in filteredMitra"
                :key="mitra.id"
                class="group flex flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-sky-200 hover:shadow-lg"
            >
                <!-- Header -->
                <div class="mb-4 flex items-start gap-3">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-slate-100 bg-slate-50 shadow-sm">
                        <img
                            v-if="mitra.logo_mitra"
                            :src="`/storage/${mitra.logo_mitra}`"
                            class="h-full w-full object-cover"
                        />
                        <span v-else class="text-lg font-black text-lokak-brand">{{ getInitials(mitra.nama_mitra) }}</span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <h3 class="truncate text-sm font-black uppercase leading-tight text-slate-900 group-hover:text-lokak-brand transition-colors">
                            {{ mitra.nama_mitra }}
                        </h3>
                        <p class="mt-1 inline-flex items-center gap-1 text-[10px] font-bold uppercase text-slate-500">
                            <MapPin class="h-3 w-3 text-slate-400" />
                            {{ mitra.lokasi }}
                        </p>
                        <p class="mt-1 text-[10px] font-black uppercase italic text-sky-600">
                            {{ mitra.kategori }}
                        </p>
                    </div>
                </div>

                <!-- Description -->
                <p class="mb-4 line-clamp-3 min-h-12 text-xs font-medium leading-relaxed text-slate-500">
                    {{ mitra.deskripsi || 'Perusahaan terverifikasi dengan peluang karier aktif.' }}
                </p>

                <!-- Footer -->
                <div class="mt-auto border-t border-slate-100 pt-4">
                    <Link
                        :href="route('pelamar.mitra.profil', mitra.id)"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-lokak-brand px-4 py-2.5 text-xs font-black uppercase italic text-white shadow-md shadow-sky-500/20 transition hover:bg-sky-600 hover:shadow-lg"
                    >
                        <Eye class="h-4 w-4" />
                        Lihat Profil
                        <ChevronRight class="h-3.5 w-3.5" />
                    </Link>
                </div>
            </article>
        </section>

        <!-- Empty State -->
        <section v-else class="mt-4 flex flex-col items-center justify-center rounded-4xl border border-dashed border-slate-300 bg-white py-16 text-center">
            <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-2xl bg-slate-100">
                <Building2 class="h-10 w-10 text-slate-300" />
            </div>
            <h3 class="text-base font-black uppercase italic text-slate-600">Mitra tidak ditemukan</h3>
            <p class="mt-2 max-w-sm text-sm font-medium text-slate-400">
                Coba ubah filter atau kata kunci pencarian Anda. Anda bisa mencari berdasarkan nama perusahaan, lokasi, atau industri.
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