<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import { Search, Eye, Building2 } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    jobs: any[];
    filters: { search: string };
}>();

defineOptions({ layout: AppLayout });

const search = ref(props.filters.search);

// Debounce search agar tidak terlalu berat
watch(search, (value) => {
    router.get('/dashboard/admin/kelolalowongan', { search: value }, { 
        preserveState: true, 
        replace: true 
    });
});

/**
 * Fungsi untuk mencegah "Invalid Date"
 */
const formatDate = (dateString: string) => {
    if (!dateString) {
return '---';
}

    const date = new Date(dateString);

    if (isNaN(date.getTime())) {
return 'Format Salah';
}
    
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
};

const getStatusStyle = (status: string) => {
    const styles: Record<string, string> = {
        verified: 'bg-emerald-100 text-emerald-700 border-emerald-200',
        rejected: 'bg-rose-100 text-rose-700 border-rose-200',
        pending: 'bg-amber-100 text-amber-700 border-amber-200'
    };

    return styles[status] || styles['pending'];
};
</script>

<template>
    <Head title="Kelola Lowongan - Admin" />
    <div class="space-y-8 p-6 lg:p-10">
        <header class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl">
                    KELOLA <span class="text-sky-700">LOWONGAN</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
            </div>
            <div class="relative w-full sm:w-64">
                <Search class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400" />
                <input v-model="search" type="text" placeholder="Cari loker atau mitra..." class="h-11 w-full rounded-xl border border-slate-200 bg-white pr-4 pl-10 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20 shadow-sm" />
            </div>
        </header>

        <div class="rounded-[2.5rem] border border-slate-200 bg-white p-4 shadow-sm lg:p-8">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 text-left text-[10px] font-black tracking-widest text-slate-400 uppercase italic">
                            <th class="px-4 pb-6 text-center">Tgl Input</th>
                            <th class="px-4 pb-6">Detail Lowongan</th>
                            <th class="px-4 pb-6">Gaji</th>
                            <th class="px-4 pb-6">Pembayaran</th>
                            <th class="px-4 pb-6">Status Loker</th>
                            <th class="px-4 pb-6 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="job in jobs" :key="job.id" class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-6 text-center">
                                <span class="text-[10px] font-black text-slate-400 uppercase italic">
                                    {{ formatDate(job.created_at) }}
                                </span>
                            </td>
                            <td class="px-4 py-6">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-900 uppercase italic">{{ job.judul_lowongan }}</span>
                                    <div class="flex items-center gap-1 text-[10px] font-bold text-sky-700 uppercase italic">
                                        <Building2 class="h-3 w-3" /> {{ job.mitra_nama }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-6">
                                <span class="text-[10px] font-black text-slate-600 uppercase italic">{{ job.gaji }}</span>
                            </td>
                            <td class="px-4 py-6 text-center">
                                <span :class="job.pembayaran_status === 'verified' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100'" class="rounded-lg border px-2 py-1 text-[9px] font-black uppercase italic">
                                    {{ job.pembayaran_status === 'verified' ? 'Paid' : 'Unpaid' }}
                                </span>
                            </td>
                            <td class="px-4 py-6">
                                <span :class="[getStatusStyle(job.status), 'inline-flex rounded-full border px-3 py-1 text-[8px] font-black uppercase italic']">
                                    {{ job.status }}
                                </span>
                            </td>
                            <td class="px-4 py-6 text-right">
                                <Link :href="`/dashboard/admin/kelolalowongan/${job.id}`" class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-slate-900 px-6 text-[10px] font-black text-white uppercase italic transition-all hover:bg-sky-700 active:scale-95">
                                    <Eye class="h-4 w-4" /> Detail
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="jobs.length === 0" class="py-20 text-center">
                    <p class="text-xs font-black uppercase italic text-slate-300 tracking-widest">Tidak ada lowongan ditemukan</p>
                </div>
            </div>
        </div>
    </div>
</template>