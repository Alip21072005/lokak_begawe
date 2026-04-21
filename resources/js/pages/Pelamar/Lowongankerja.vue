<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Search,
    MapPin,
    DollarSign,
    Briefcase,
    Clock,
    Bookmark,
    ChevronRight,
    SlidersHorizontal,
    Building2,
} from 'lucide-vue-next';
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

// 1. Tangkap props baru dari Controller (lokasis dan kategoris)
const props = defineProps<{
    lowonganList?: any[];
    lokasis?: any[];
    kategoris?: any[];
}>();

// 2. State untuk Filter
const searchQuery = ref('');
const selectedLokasi = ref('');
const selectedKategori = ref('');

// Helper Format Rupiah
const formatRupiah = (min: number | null, max: number | null) => {
    if (!min && !max) {
return 'Dirahasiakan';
}

    const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });

    if (min !== null && max !== null) {
        return `${formatter.format(min)} - ${formatter.format(max)}`;
    }

    if (min !== null) {
        return `Mulai ${formatter.format(min)}`;
    }

    return `Hingga ${formatter.format(max as number)}`;
};

// Helper Waktu Relatif
const timeAgo = (dateString: string) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now.getTime() - date.getTime()) / 1000);

    if (diffInSeconds < 60) {
return 'Baru saja';
}

    const diffInMinutes = Math.floor(diffInSeconds / 60);

    if (diffInMinutes < 60) {
return `${diffInMinutes} menit lalu`;
}

    const diffInHours = Math.floor(diffInMinutes / 60);

    if (diffInHours < 24) {
return `${diffInHours} jam lalu`;
}

    const diffInDays = Math.floor(diffInHours / 24);

    if (diffInDays < 30) {
return `${diffInDays} hari lalu`;
}

    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

// Helper Pembuat Inisial Logo
const getInitials = (name: string) => {
    if (!name) {
return 'PT';
}

    const words = name.split(' ');

    if (words.length >= 2) {
return (words[0][0] + words[1][0]).toUpperCase();
}

    return name.substring(0, 2).toUpperCase();
};

// 3. Data Computed (Kombinasi Filter Search, Lokasi, dan Kategori)
const filteredJobs = computed(() => {
    let list = props.lowonganList || [];

    // Filter 1: Pencarian Teks
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        list = list.filter((job: any) => {
            const title = job.judul_lowongan?.toLowerCase() || '';
            const company = job.mitra?.nama_mitra?.toLowerCase() || '';

            return title.includes(query) || company.includes(query);
        });
    }

    // Filter 2: Lokasi
    if (selectedLokasi.value) {
        list = list.filter((job: any) => {
            // Cek lokasi_id dari tabel lowongan, jika kosong cek lokasi_id dari mitranya
            const jobLokasiId = job.lokasi_id || job.mitra?.lokasi_id;

            return jobLokasiId === selectedLokasi.value;
        });
    }

    // Filter 3: Kategori
    if (selectedKategori.value) {
        list = list.filter((job: any) => {
            // Karena kategori biasanya menempel di mitra perusahaan
            return job.mitra?.kategori_id === selectedKategori.value;
        });
    }

    return list.map((job: any) => {
        const isHot = (new Date().getTime() - new Date(job.created_at).getTime()) / (1000 * 3600 * 24) <= 3;
        const location = job.lokasi?.nama_lokasi || job.mitra?.lokasi?.nama_lokasi || 'Bengkulu';

        return {
            id: job.id,
            title: job.judul_lowongan,
            company: job.mitra?.nama_mitra || 'Perusahaan Rahasia',
            location: location,
            salary: formatRupiah(job.gaji_min, job.gaji_max),
            type: job.tipe_pekerjaan || 'Full-time',
            postedAt: timeAgo(job.created_at),
            isHot: isHot,
            logo: getInitials(job.mitra?.nama_mitra),
        };
    });
});

// Fungsi untuk mereset semua filter
const resetFilter = () => {
    searchQuery.value = '';
    selectedLokasi.value = '';
    selectedKategori.value = '';
};
</script>

<template>
    <Head title="Cari Lowongan - Lokak Begawe" />

    <div class="space-y-8 p-6 lg:p-10">
        <div class="flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tighter text-lokak-text uppercase italic md:text-4xl">
                    REKOMENDASI <span class="text-lokak-brand">LOWONGAN</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
                <p class="mt-4 text-[11px] font-bold tracking-widest text-lokak-text-muted uppercase italic">
                    Temukan karir impianmu dari ratusan mitra kami di Bengkulu.
                </p>
            </div>

            <div class="flex w-full items-center gap-3 md:w-96">
                <div class="relative w-full">
                    <Search class="absolute top-1/2 left-4 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari posisi atau perusahaan..."
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-white pr-4 pl-11 text-xs font-bold transition-all outline-none focus:border-lokak-brand focus:ring-2 focus:ring-lokak-brand/20"
                    />
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="space-y-6 lg:col-span-3">
                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-4 flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-2">
                            <SlidersHorizontal class="h-4 w-4 text-lokak-brand" />
                            <h3 class="text-xs font-black tracking-widest text-lokak-text uppercase italic">
                                Filter Pencarian
                            </h3>
                        </div>
                        <button @click="resetFilter" v-if="searchQuery || selectedLokasi || selectedKategori" class="text-[9px] font-bold text-rose-500 hover:underline">
                            Reset
                        </button>
                    </div>

                    <div class="space-y-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black tracking-widest text-slate-400 uppercase italic">Lokasi</label>
                            <select v-model="selectedLokasi" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 outline-none focus:border-lokak-brand focus:ring-1 focus:ring-lokak-brand">
                                <option value="">Semua Lokasi</option>
                                <option v-for="lok in props.lokasis" :key="lok.id" :value="lok.id">
                                    {{ lok.nama_lokasi }}
                                </option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black tracking-widest text-slate-400 uppercase italic">Kategori Industri</label>
                            <select v-model="selectedKategori" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-600 outline-none focus:border-lokak-brand focus:ring-1 focus:ring-lokak-brand">
                                <option value="">Semua Kategori</option>
                                <option v-for="kat in props.kategoris" :key="kat.id" :value="kat.id">
                                    {{ kat.nama_kategori }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                
            </div>

            <div class="space-y-4 lg:col-span-9">
                <div
                    v-for="job in filteredJobs"
                    :key="job.id"
                    class="group relative flex flex-col justify-between gap-6 overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white p-6 shadow-sm transition-all hover:border-sky-200 hover:shadow-xl sm:flex-row sm:items-center"
                >
                    <div class="flex items-start gap-5">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl border border-slate-100 bg-slate-50 text-xl font-black text-lokak-brand shadow-inner transition-colors group-hover:bg-white">
                            {{ job.logo }}
                        </div>
                        <div class="flex flex-col">
                            <div class="mb-1 flex items-center gap-2">
                                <h2 class="text-base font-black text-lokak-text uppercase transition-colors group-hover:text-lokak-brand">
                                    {{ job.title }}
                                </h2>
                                <span v-if="job.isHot" class="rounded-lg bg-rose-100 px-2 py-0.5 text-[8px] font-black text-rose-600 uppercase italic">
                                    HOT
                                </span>
                            </div>

                            <p class="mb-3 flex items-center gap-1.5 text-[10px] font-bold text-slate-500 uppercase italic">
                                <Building2 class="h-3 w-3" /> {{ job.company }}
                            </p>

                            <div class="flex flex-wrap items-center gap-3 text-[10px] font-bold text-slate-400 uppercase italic">
                                <span class="flex items-center gap-1">
                                    <MapPin class="h-3 w-3 text-lokak-brand" /> {{ job.location }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <Briefcase class="h-3 w-3 text-emerald-500" /> {{ job.type }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <DollarSign class="h-3 w-3 text-amber-500" /> {{ job.salary }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 sm:flex-col sm:items-end">
                        <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-400 transition-all hover:border-lokak-brand hover:text-lokak-brand">
                            <Bookmark class="h-4 w-4" />
                        </button>
                        <Link :href="`/lowongan/${job.id}`" class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-slate-900 px-6 py-3 text-[10px] font-black text-white uppercase italic shadow-lg transition-all hover:bg-lokak-brand active:scale-95 sm:flex-none">
                            LIHAT DETAIL <ChevronRight class="h-3 w-3" />
                        </Link>
                        <span class="absolute top-6 right-6 text-[9px] font-bold text-slate-300 uppercase italic sm:static">
                            <Clock class="mr-1 mb-0.5 inline h-3 w-3" />{{ job.postedAt }}
                        </span>
                    </div>
                </div>

                <div v-if="filteredJobs.length === 0" class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="mb-4 text-6xl opacity-20">🔍</div>
                    <h3 class="text-sm font-black text-slate-400 uppercase italic">Lowongan Tidak Ditemukan</h3>
                    <p class="mt-1 text-[10px] font-bold text-slate-300 uppercase italic">Coba ganti filter lokasi, kategori, atau kata kunci lain.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.group {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>