<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import { Search, Eye } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    jobs: any[];
    filters: { search: string };
}>();

defineOptions({ layout: AppLayout });
const search = ref(props.filters.search);

watch(search, (value) => {
    router.get('/dashboard/admin/kelolalowongan', { search: value }, { preserveState: true, replace: true });
});

const getStatusStyle = (status: string) => {
    if (status === 'verified') {
return 'bg-emerald-100 text-emerald-700 border-emerald-200';
}

    if (status === 'rejected') {
return 'bg-rose-100 text-rose-700 border-rose-200';
}

    return 'bg-amber-100 text-amber-700 border-amber-200';
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
                <input v-model="search" type="text" placeholder="Cari loker atau mitra..." class="h-11 w-full rounded-xl border border-slate-200 bg-white pr-4 pl-10 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20" />
            </div>
        </header>

        <div class="rounded-[2.5rem] border border-slate-200 bg-white p-4 shadow-sm lg:p-8">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 text-left text-[10px] font-black tracking-widest text-slate-400 uppercase italic">
                            <th class="px-4 pb-6">Lowongan</th>
                            <th class="px-4 pb-6">Gaji</th>
                            <th class="px-4 pb-6">Pembayaran</th>
                            <th class="px-4 pb-6">Status Loker</th>
                            <th class="px-4 pb-6 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="job in jobs" :key="job.id" class="group hover:bg-slate-50/50">
                            <td class="px-4 py-6">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-900 uppercase italic">{{ job.judul_lowongan }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase italic">{{ job.mitra_nama }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-6 text-[10px] font-black text-slate-600 uppercase">{{ job.gaji }}</td>
                            <td class="px-4 py-6">
                                <span :class="job.pembayaran_status === 'verified' ? 'text-emerald-600' : 'text-amber-500'" class="text-[10px] font-black uppercase italic">
                                    {{ job.pembayaran_status === 'verified' ? 'Sudah Bayar' : 'Menunggu Bukti' }}
                                </span>
                            </td>
                            <td class="px-4 py-6">
                                <span :class="[getStatusStyle(job.status), 'inline-flex rounded-full border px-3 py-1 text-[8px] font-black uppercase italic']">
                                    {{ job.status }}
                                </span>
                            </td>
                            <td class="px-4 py-6 text-right">
                                <Link :href="`/dashboard/admin/kelolalowongan/${job.id}`" class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-slate-900 px-6 text-[10px] font-black text-white uppercase italic transition-all hover:bg-sky-700">
                                    <Eye class="h-4 w-4" /> Detail Verifikasi
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>