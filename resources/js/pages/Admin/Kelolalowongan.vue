<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import {
    Search,
    Eye,
    Building2,
    Briefcase,
    Wallet,
    ShieldCheck,
    Clock3,
    Filter,
    Sparkles,
} from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface JobItem {
    id: string | number;
    judul_lowongan: string;
    mitra_nama: string;
    lokasi_nama?: string;
    gaji: string;
    status: string;
    pembayaran_status: string;
    created_at: string;
}

const props = defineProps<{
    jobs: JobItem[];
    filters: { search: string };
}>();

defineOptions({ layout: AppLayout });

const search = ref(props.filters?.search ?? '');
let searchDebounce: ReturnType<typeof setTimeout> | null = null;

watch(search, (value) => {
    if (searchDebounce) clearTimeout(searchDebounce);
    searchDebounce = setTimeout(() => {
        router.get(
            route('admin.kelolalowongan'),
            { search: value },
            { preserveState: true, replace: true, preserveScroll: true },
        );
    }, 350);
});

const formatDate = (dateString?: string) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return '-';
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const getStatusStyle = (status?: string) => {
    const map: Record<string, string> = {
        verified: 'bg-emerald-50 text-emerald-700 border-emerald-200',
        rejected: 'bg-rose-50 text-rose-700 border-rose-200',
        pending: 'bg-amber-50 text-amber-700 border-amber-200',
    };
    return map[(status || 'pending').toLowerCase()] || map.pending;
};

const getPaymentStyle = (status?: string) => {
    const s = (status || 'unpaid').toLowerCase();
    if (s === 'verified' || s === 'paid') return 'bg-emerald-50 text-emerald-700 border-emerald-200';
    if (s === 'rejected') return 'bg-rose-50 text-rose-700 border-rose-200';
    return 'bg-amber-50 text-amber-700 border-amber-200';
};

const paymentLabel = (status?: string) => {
    const s = (status || 'unpaid').toLowerCase();
    if (s === 'verified' || s === 'paid') return 'Paid';
    if (s === 'rejected') return 'Rejected';
    return 'Unpaid';
};

const stats = computed(() => {
    const jobs = props.jobs || [];
    return {
        total: jobs.length,
        verified: jobs.filter((j) => (j.status || '').toLowerCase() === 'verified').length,
        pending: jobs.filter((j) => (j.status || '').toLowerCase() === 'pending').length,
        paid: jobs.filter((j) => ['verified', 'paid'].includes((j.pembayaran_status || '').toLowerCase())).length,
    };
});
</script>

<template>
    <Head title="Kelola Lowongan - Admin" />

    <div class="min-h-screen space-y-6 bg-slate-50 p-4 md:p-6 lg:p-8">
        <!-- Header -->
        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm md:p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-2xl font-black uppercase italic tracking-tight text-slate-900 md:text-3xl">
                        KELOLA <span class="text-sky-700">LOWONGAN</span>
                    </h1>
                    <p class="mt-1 inline-flex items-center gap-2 text-[11px] font-bold uppercase tracking-wide text-slate-500">
                        <Sparkles class="h-3.5 w-3.5" />
                        Kelola dan verifikasi lowongan mitra dengan cepat
                    </p>
                </div>

                <div class="relative w-full lg:w-80">
                    <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari judul lowongan / nama mitra..."
                        class="h-11 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-sky-300 focus:ring-4 focus:ring-sky-100"
                    />
                </div>
            </div>
        </section>

        <!-- Mini Stats -->
        <section class="grid grid-cols-2 gap-3 md:grid-cols-4">
            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="mb-2 inline-flex rounded-xl bg-sky-50 p-2 text-sky-700"><Briefcase class="h-4 w-4" /></div>
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Total</p>
                <p class="text-2xl font-black text-slate-900">{{ stats.total }}</p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="mb-2 inline-flex rounded-xl bg-emerald-50 p-2 text-emerald-700"><ShieldCheck class="h-4 w-4" /></div>
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Verified</p>
                <p class="text-2xl font-black text-slate-900">{{ stats.verified }}</p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="mb-2 inline-flex rounded-xl bg-amber-50 p-2 text-amber-700"><Clock3 class="h-4 w-4" /></div>
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Pending</p>
                <p class="text-2xl font-black text-slate-900">{{ stats.pending }}</p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="mb-2 inline-flex rounded-xl bg-indigo-50 p-2 text-indigo-700"><Wallet class="h-4 w-4" /></div>
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Paid</p>
                <p class="text-2xl font-black text-slate-900">{{ stats.paid }}</p>
            </article>
        </section>

        <!-- Desktop Table -->
        <section class="hidden rounded-3xl border border-slate-200 bg-white p-4 shadow-sm lg:block lg:p-6">
            <div class="mb-4 flex items-center gap-2 text-slate-500">
                <Filter class="h-4 w-4" />
                <p class="text-xs font-bold uppercase tracking-wider">Daftar Lowongan</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-left text-[10px] font-black uppercase tracking-[0.14em] text-slate-400">
                            <th class="px-3 pb-4 text-center">Tanggal</th>
                            <th class="px-3 pb-4">Detail Lowongan</th>
                            <th class="px-3 pb-4">Gaji</th>
                            <th class="px-3 pb-4 text-center">Pembayaran</th>
                            <th class="px-3 pb-4">Status</th>
                            <th class="px-3 pb-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        <tr
                            v-for="job in jobs"
                            :key="job.id"
                            class="transition-colors hover:bg-slate-50/70"
                        >
                            <td class="px-3 py-4 text-center">
                                <span class="text-[11px] font-semibold text-slate-500">
                                    {{ formatDate(job.created_at) }}
                                </span>
                            </td>

                            <td class="px-3 py-4">
                                <div class="space-y-1">
                                    <p class="line-clamp-1 text-sm font-black uppercase italic text-slate-900">
                                        {{ job.judul_lowongan }}
                                    </p>
                                    <p class="inline-flex items-center gap-1 text-[11px] font-semibold text-sky-700">
                                        <Building2 class="h-3.5 w-3.5" />
                                        {{ job.mitra_nama }}
                                    </p>
                                </div>
                            </td>

                            <td class="px-3 py-4">
                                <p class="text-[11px] font-bold text-slate-600">{{ job.gaji }}</p>
                            </td>

                            <td class="px-3 py-4 text-center">
                                <span
                                    :class="[getPaymentStyle(job.pembayaran_status), 'inline-flex rounded-full border px-3 py-1 text-[10px] font-black uppercase tracking-wide']"
                                >
                                    {{ paymentLabel(job.pembayaran_status) }}
                                </span>
                            </td>

                            <td class="px-3 py-4">
                                <span
                                    :class="[getStatusStyle(job.status), 'inline-flex rounded-full border px-3 py-1 text-[10px] font-black uppercase tracking-wide']"
                                >
                                    {{ job.status || 'pending' }}
                                </span>
                            </td>

                            <td class="px-3 py-4 text-right">
                                <!-- FIX: wajib ke detailvertifikasilowongan -->
                                <Link
                                    :href="route('admin.detaillowongan', job.id)"
                                    class="inline-flex h-9 items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 text-[11px] font-black uppercase tracking-wide text-white transition hover:bg-sky-700 active:scale-95"
                                >
                                    <Eye class="h-4 w-4" />
                                    Detail
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div v-if="jobs.length === 0" class="py-16 text-center">
                    <p class="text-sm font-bold text-slate-500">Tidak ada lowongan ditemukan.</p>
                    <p class="mt-1 text-xs text-slate-400">Coba ubah kata kunci pencarian.</p>
                </div>
            </div>
        </section>

        <!-- Mobile / Tablet Cards -->
        <section class="space-y-3 lg:hidden">
            <article
                v-for="job in jobs"
                :key="job.id"
                class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
            >
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="line-clamp-2 text-sm font-black uppercase italic text-slate-900">
                            {{ job.judul_lowongan }}
                        </p>
                        <p class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-sky-700">
                            <Building2 class="h-3.5 w-3.5" />
                            {{ job.mitra_nama }}
                        </p>
                    </div>
                    <span class="shrink-0 text-[11px] font-semibold text-slate-400">
                        {{ formatDate(job.created_at) }}
                    </span>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-2">
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-2">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Gaji</p>
                        <p class="mt-1 text-[11px] font-semibold text-slate-700">{{ job.gaji }}</p>
                    </div>
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-2">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Pembayaran</p>
                        <span
                            :class="[getPaymentStyle(job.pembayaran_status), 'mt-1 inline-flex rounded-full border px-2 py-0.5 text-[10px] font-black uppercase']"
                        >
                            {{ paymentLabel(job.pembayaran_status) }}
                        </span>
                    </div>
                </div>

                <div class="mt-3 flex items-center justify-between">
                    <span
                        :class="[getStatusStyle(job.status), 'inline-flex rounded-full border px-3 py-1 text-[10px] font-black uppercase']"
                    >
                        {{ job.status || 'pending' }}
                    </span>

                    <!-- FIX: wajib ke detailvertifikasilowongan -->
                    <Link
                        :href="route('admin.detaillowongan', job.id)"
                        class="inline-flex h-9 items-center justify-center gap-1 rounded-lg bg-slate-900 px-3 text-[10px] font-black uppercase tracking-wide text-white transition hover:bg-sky-700 active:scale-95"
                    >
                        <Eye class="h-4 w-4" />
                        Detail
                    </Link>
                </div>
            </article>

            <div v-if="jobs.length === 0" class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
                <p class="text-sm font-bold text-slate-500">Tidak ada lowongan ditemukan.</p>
            </div>
        </section>
    </div>
</template>