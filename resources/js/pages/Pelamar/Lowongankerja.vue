<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Search, MapPin, DollarSign, Briefcase, 
    Clock, Bookmark, ChevronRight, SlidersHorizontal, 
    Building2, Flame
} from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps<{
    lowonganList?: any[];
    lokasis?: any[];
    kategoris?: any[];
}>();

// --- STATE FILTER ---
const searchQuery = ref('');
const selectedLokasi = ref('');
const selectedKategori = ref('');

// --- HELPERS ---
const formatRupiah = (min: number | null, max: number | null) => {
    if (!min && !max) {
return 'Gaji Dirahasiakan';
}

    const formatter = new Intl.NumberFormat('id-ID', { 
        style: 'currency', 
        currency: 'IDR', 
        minimumFractionDigits: 0 
    });

    if (min && max) {
return `${formatter.format(min)} - ${formatter.format(max)}`;
}

    return min ? `Mulai ${formatter.format(min)}` : `Hingga ${formatter.format(max!)}`;
};

const timeAgo = (dateString: string) => {
    if (!dateString) {
return 'Baru saja';
}

    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now.getTime() - date.getTime()) / 1000);

    if (isNaN(diffInSeconds)) {
return 'Baru saja';
} // Fix "Invalid Date"

    if (diffInSeconds < 60) {
return 'Baru saja';
}
    
    const diffInMinutes = Math.floor(diffInSeconds / 60);

    if (diffInMinutes < 60) {
return `${diffInMinutes}m lalu`;
}
    
    const diffInHours = Math.floor(diffInMinutes / 60);

    if (diffInHours < 24) {
return `${diffInHours}j lalu`;
}
    
    const diffInDays = Math.floor(diffInHours / 24);

    if (diffInDays < 30) {
return `${diffInDays} hari lalu`;
}
    
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
};

const getInitials = (name: string) => {
    if (!name) {
return '??';
}

    const parts = name.trim().split(' ');

    return parts.length >= 2 
        ? (parts[0][0] + parts[1][0]).toUpperCase() 
        : name.substring(0, 2).toUpperCase();
};

// --- COMPUTED FILTERED JOBS ---
const filteredJobs = computed(() => {
    let list = props.lowonganList || [];

    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        list = list.filter(j => 
            j.judul_lowongan?.toLowerCase().includes(q) || 
            j.mitra?.nama_mitra?.toLowerCase().includes(q)
        );
    }

    if (selectedLokasi.value) {
        list = list.filter(j => (j.lokasi_id || j.mitra?.lokasi_id) === selectedLokasi.value);
    }

    if (selectedKategori.value) {
        list = list.filter(j => j.mitra?.kategori_id === selectedKategori.value);
    }

    return list;
});

const resetFilter = () => {
    searchQuery.value = '';
    selectedLokasi.value = '';
    selectedKategori.value = '';
};
</script>

<template>
    <Head title="Cari Lowongan Kerja - Lokak Begawe" />

    <div class="space-y-10 p-6 lg:p-10 bg-slate-50/50 min-h-screen">
        <div class="flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
            <div class="space-y-4">
                <h1 class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic md:text-5xl">
                    CARI <span class="text-sky-700">KERJA</span>
                </h1>
                <div class="h-1.5 w-20 rounded-full bg-sky-700"></div>
                <p class="text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase italic">
                    Temukan peluang karir terbaik di wilayah Bengkulu.
                </p>
            </div>

            <div class="relative w-full md:w-96 group">
                <Search class="absolute top-1/2 left-4 h-4 w-4 -translate-y-1/2 text-slate-300 group-focus-within:text-sky-700 transition-colors" />
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Posisi, Skill, atau Perusahaan..."
                    class="h-14 w-full rounded-3xl border-none bg-white pr-4 pl-12 text-xs font-bold shadow-sm ring-1 ring-slate-100 focus:ring-4 focus:ring-sky-700/10 outline-none transition-all italic"
                />
            </div>
        </div>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
            <div class="space-y-6 lg:col-span-3">
                <div class="rounded-[2.5rem] border border-white bg-white/70 backdrop-blur-md p-8 shadow-xl shadow-slate-200/50">
                    <div class="mb-8 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <SlidersHorizontal class="h-4 w-4 text-sky-700" />
                            <h3 class="text-[10px] font-black tracking-widest text-slate-900 uppercase italic">Filter</h3>
                        </div>
                        <button @click="resetFilter" v-if="searchQuery || selectedLokasi || selectedKategori" class="text-[9px] font-black text-rose-500 uppercase italic hover:underline">
                            Reset
                        </button>
                    </div>

                    <div class="space-y-8">
                        <div class="space-y-3">
                            <label class="text-[9px] font-black tracking-[0.2em] text-slate-400 uppercase italic ml-2">Lokasi Penempatan</label>
                            <select v-model="selectedLokasi" class="w-full rounded-2xl border-none bg-slate-100/50 px-4 py-3.5 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-sky-700/20 italic">
                                <option value="">Seluruh Bengkulu</option>
                                <option v-for="lok in props.lokasis" :key="lok.id" :value="lok.id">{{ lok.nama_lokasi }}</option>
                            </select>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[9px] font-black tracking-[0.2em] text-slate-400 uppercase italic ml-2">Sektor Industri</label>
                            <select v-model="selectedKategori" class="w-full rounded-2xl border-none bg-slate-100/50 px-4 py-3.5 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-sky-700/20 italic">
                                <option value="">Semua Kategori</option>
                                <option v-for="kat in props.kategoris" :key="kat.id" :value="kat.id">{{ kat.nama_kategori }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6 lg:col-span-9">
                <div
                    v-for="job in filteredJobs"
                    :key="job.id"
                    class="group relative flex flex-col justify-between gap-6 overflow-hidden rounded-[3rem] border border-white bg-white p-8 shadow-sm transition-all hover:-translate-y-1 hover:shadow-2xl hover:shadow-sky-900/10 md:flex-row md:items-center"
                >
                    <div class="flex items-start gap-6">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-3xl bg-sky-50 text-xl font-black text-sky-700 shadow-inner group-hover:bg-sky-700 group-hover:text-white transition-all duration-500">
                            {{ getInitials(job.mitra?.nama_mitra) }}
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <h2 class="text-lg font-black uppercase italic tracking-tighter text-slate-900 group-hover:text-sky-700 transition-colors">
                                    {{ job.judul_lowongan }}
                                </h2>
                                <span v-if="new Date(job.created_at) > new Date(Date.now() - 3*24*60*60*1000)" class="rounded-lg bg-amber-100 px-2 py-0.5 text-[8px] font-black text-amber-600 uppercase italic flex items-center gap-1">
                                    <Flame class="h-2.5 w-2.5" /> HOT
                                </span>
                            </div>

                            <p class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400 uppercase italic">
                                <Building2 class="h-3 w-3 text-sky-700" /> {{ job.mitra?.nama_mitra || 'Perusahaan Mitra' }}
                            </p>

                            <div class="flex flex-wrap items-center gap-4 pt-2">
                                <span class="flex items-center gap-1.5 text-[9px] font-bold text-slate-500 uppercase italic">
                                    <MapPin class="h-3 w-3 text-sky-700" /> {{ job.lokasi?.nama_lokasi || job.mitra?.lokasi?.nama_lokasi || 'Bengkulu' }}
                                </span>
                                <span class="flex items-center gap-1.5 text-[9px] font-bold text-slate-500 uppercase italic">
                                    <Briefcase class="h-3 w-3 text-sky-700" /> {{ job.tipe_pekerjaan }}
                                </span>
                                <span class="flex items-center gap-1.5 text-[9px] font-bold text-emerald-600 uppercase italic bg-emerald-50 px-2 py-1 rounded-lg">
                                    <DollarSign class="h-3 w-3" /> {{ formatRupiah(job.gaji_min, job.gaji_max) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-row items-center justify-between gap-4 border-t border-slate-50 pt-6 md:flex-col md:items-end md:border-none md:pt-0">
                        <div class="flex items-center gap-3">
                            <button class="flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-100 bg-white text-slate-300 transition-all hover:border-sky-200 hover:text-sky-700 active:scale-90">
                                <Bookmark class="h-4 w-4" />
                            </button>
                            <Link 
                                v-if="job.id"
                                :href="route('detail.lowongan', { id: job.id })" 
                                class="flex items-center justify-center gap-2 rounded-2xl bg-slate-900 px-8 py-4 text-[10px] font-black text-white uppercase italic shadow-xl shadow-slate-900/20 transition-all hover:bg-sky-700 hover:-translate-y-0.5 active:scale-95"
                            >
                                DETAIL <ChevronRight class="h-3.5 w-3.5" />
                            </Link>
                        </div>
                        <span class="text-[9px] font-black text-slate-300 uppercase italic flex items-center gap-1">
                            <Clock class="h-3 w-3" /> {{ timeAgo(job.created_at) }}
                        </span>
                    </div>
                </div>

                <div v-if="filteredJobs.length === 0" class="flex flex-col items-center justify-center py-32 text-center space-y-4">
                    <div class="h-24 w-24 rounded-[3rem] bg-slate-100 flex items-center justify-center text-slate-300">
                        <Search class="h-10 w-10" />
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-sm font-black text-slate-400 uppercase italic">Pencarian Nihil</h3>
                        <p class="text-[10px] font-bold text-slate-300 uppercase italic">Coba ubah filter atau kata kunci pencarian Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.scrollbar-hide::-webkit-scrollbar { display: none; }
</style>