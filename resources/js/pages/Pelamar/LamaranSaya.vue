<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Search,
    Building2,
    MapPin,
    Clock,
    CheckCircle2,
    XCircle,
    Loader2,
    MessageCircle,
    Calendar,
    Info,
    Briefcase,
    Filter,
    Sparkles,
    ListChecks,
} from 'lucide-vue-next';
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const props = defineProps<{
    auth: any;
    lamaranList?: any[];
}>();

const searchQuery = ref('');
const statusFilter = ref('');

const getStatusConfig = (status: string) => {
    const s = status?.toLowerCase() || 'pending';

    switch (s) {
        case 'accepted':
            return {
                key: 'accepted',
                text: 'Diterima',
                style: 'bg-emerald-100 text-emerald-700 border-emerald-200',
                icon: CheckCircle2,
            };
        case 'rejected':
            return {
                key: 'rejected',
                text: 'Ditolak',
                style: 'bg-rose-100 text-rose-700 border-rose-200',
                icon: XCircle,
            };
        case 'interview':
            return {
                key: 'interview',
                text: 'Wawancara',
                style: 'bg-purple-100 text-purple-700 border-purple-200',
                icon: Calendar,
            };
        case 'reviewed':
            return {
                key: 'reviewed',
                text: 'Ditinjau',
                style: 'bg-blue-100 text-blue-700 border-blue-200',
                icon: Info,
            };
        default:
            return {
                key: 'pending',
                text: 'Menunggu',
                style: 'bg-amber-100 text-amber-700 border-amber-200',
                icon: Loader2,
            };
    }
};

const normalizedList = computed(() => props.lamaranList || []);

const stats = computed(() => {
    const list = normalizedList.value;
    const countBy = (k: string) => list.filter((x: any) => (x.status || '').toLowerCase() === k).length;

    return {
        total: list.length,
        pending: countBy('pending'),
        reviewed: countBy('reviewed'),
        interview: countBy('interview'),
        accepted: countBy('accepted'),
        rejected: countBy('rejected'),
    };
});

const filteredApplications = computed(() => {
    let list = normalizedList.value;

    if (searchQuery.value.trim()) {
        const query = searchQuery.value.toLowerCase().trim();
        list = list.filter((app: any) => {
            const position = app.posisi?.toLowerCase() || '';
            const company = app.perusahaan?.toLowerCase() || '';
            const location = app.lokasi?.toLowerCase() || '';
            return position.includes(query) || company.includes(query) || location.includes(query);
        });
    }

    if (statusFilter.value) {
        list = list.filter((app: any) => (app.status || 'pending').toLowerCase() === statusFilter.value);
    }

    return list;
});
</script>

<template>
    <Head title="Status Lamaran Saya" />

    <div class="min-h-screen bg-slate-50/50 p-4 md:p-6 lg:p-8">
        <!-- Header -->
        <section class="rounded-[2.5rem] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl font-black tracking-tighter text-lokak-text uppercase italic md:text-4xl">
                        STATUS <span class="text-lokak-brand">LAMARAN</span>
                    </h1>
                    <p class="mt-2 inline-flex items-center gap-2 text-[10px] font-black uppercase italic tracking-[0.16em] text-slate-400">
                        <Sparkles class="h-3.5 w-3.5 text-lokak-brand" />
                        Pantau progres karirmu secara real-time
                    </p>
                </div>

                <div class="grid w-full grid-cols-2 gap-2 sm:grid-cols-3 lg:w-auto">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-center">
                        <p class="text-[9px] font-black uppercase text-slate-400">Total</p>
                        <p class="text-lg font-black text-slate-900">{{ stats.total }}</p>
                    </div>
                    <div class="rounded-xl border border-amber-100 bg-amber-50/60 px-3 py-2 text-center">
                        <p class="text-[9px] font-black uppercase text-amber-700">Menunggu</p>
                        <p class="text-lg font-black text-amber-800">{{ stats.pending }}</p>
                    </div>
                    <div class="rounded-xl border border-emerald-100 bg-emerald-50/60 px-3 py-2 text-center col-span-2 sm:col-span-1">
                        <p class="text-[9px] font-black uppercase text-emerald-700">Diterima</p>
                        <p class="text-lg font-black text-emerald-800">{{ stats.accepted }}</p>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="mt-5 grid grid-cols-1 gap-3 md:grid-cols-2">
                <div class="relative">
                    <Search class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari posisi / perusahaan / lokasi..."
                        class="h-11 w-full rounded-xl border border-slate-200 bg-white pr-4 pl-10 text-xs font-bold outline-none transition focus:ring-4 focus:ring-lokak-brand/10"
                    />
                </div>

                <div class="relative">
                    <Filter class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <select
                        v-model="statusFilter"
                        class="h-11 w-full rounded-xl border border-slate-200 bg-white pr-4 pl-10 text-xs font-bold outline-none transition focus:ring-4 focus:ring-lokak-brand/10"
                    >
                        <option value="">Semua status</option>
                        <option value="pending">Menunggu</option>
                        <option value="reviewed">Ditinjau</option>
                        <option value="interview">Wawancara</option>
                        <option value="accepted">Diterima</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- List -->
        <section v-if="filteredApplications.length > 0" class="space-y-4">
            <article
                v-for="app in filteredApplications"
                :key="app.id"
                class="group relative overflow-hidden rounded-[2.2rem] border border-slate-200 bg-white p-5 shadow-sm transition-all hover:border-sky-100 hover:shadow-md md:p-6"
            >
                <div class="absolute -top-10 -right-10 h-28 w-28 rounded-full bg-slate-50 transition-transform group-hover:scale-125"></div>

                <div class="relative grid grid-cols-1 gap-4 lg:grid-cols-12">
                    <div class="space-y-3 lg:col-span-7">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-lg font-black uppercase italic tracking-tight text-slate-900 md:text-xl">
                                {{ app.posisi }}
                            </h2>
                            <span
                                :class="[getStatusConfig(app.status).style, 'inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-[9px] font-black uppercase italic tracking-wider']"
                            >
                                <component
                                    :is="getStatusConfig(app.status).icon"
                                    class="h-3 w-3"
                                    :class="(app.status || '').toLowerCase() === 'pending' ? 'animate-spin-slow' : ''"
                                />
                                {{ getStatusConfig(app.status).text }}
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-4 text-[10px] font-bold uppercase italic tracking-wide text-slate-400">
                            <span class="inline-flex items-center gap-1.5">
                                <Building2 class="h-3.5 w-3.5 text-lokak-brand" />
                                {{ app.perusahaan }}
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <MapPin class="h-3.5 w-3.5 text-lokak-brand" />
                                {{ app.lokasi }}
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <Clock class="h-3.5 w-3.5 text-lokak-brand" />
                                Dilamar: {{ app.tanggal_kirim }}
                            </span>
                        </div>
                    </div>

                    <div v-if="app.catatan_mitra" class="lg:col-span-5">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <p class="mb-1 inline-flex items-center gap-2 text-[10px] font-black uppercase italic text-lokak-brand">
                                <MessageCircle class="h-3.5 w-3.5" />
                                Pesan Mitra
                            </p>
                            <p class="text-xs font-bold italic leading-relaxed text-slate-600">
                                "{{ app.catatan_mitra }}"
                            </p>
                        </div>
                    </div>
                </div>
            </article>
        </section>

        <!-- Empty state -->
        <section v-else class="rounded-[2.2rem] border border-dashed border-slate-300 bg-white py-16 text-center">
            <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 text-slate-300">
                <ListChecks class="h-10 w-10" />
            </div>

            <h3 class="text-sm font-black uppercase italic text-slate-500">
                {{ searchQuery || statusFilter ? 'Pencarian tidak ditemukan' : 'Belum Ada Jejak Lamaran' }}
            </h3>

            <p class="mt-2 text-[10px] font-bold uppercase italic text-slate-400">
                {{
                    searchQuery || statusFilter
                        ? 'Coba ubah kata kunci atau filter status.'
                        : 'Ayo mulai petualangan karirmu dengan melamar lowongan pertama!'
                }}
            </p>

            <Link
                href="/lowongan"
                class="mt-6 inline-flex rounded-xl bg-lokak-brand px-4 py-2 text-xs font-black uppercase italic text-white transition hover:opacity-90"
            >
                Telusuri Lowongan
            </Link>
        </section>
    </div>
</template>

<style scoped>
.animate-spin-slow {
    animation: spin 2.5s linear infinite;
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>