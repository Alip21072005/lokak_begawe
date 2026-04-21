<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    Search,
    Filter,
    MoreVertical,
    Building2,
    MapPin,
    Clock,
    CheckCircle2,
    XCircle,
    Loader2,
} from 'lucide-vue-next';
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

// 1. Definisikan Props dari Controller
const props = defineProps<{
    auth: any;
    lamaranList?: any[];
}>();

// 2. State untuk Fitur Pencarian Real-time
const searchQuery = ref('');

// 3. Helper untuk mengubah status Database (Inggris) menjadi UI (Indonesia + Warna + Icon)
const getStatusConfig = (status: string) => {
    const s = status?.toLowerCase() || 'pending';
    
    if (s === 'accepted') {
        return { text: 'Panggilan Interview', style: 'bg-emerald-100 text-emerald-700 border-emerald-200', icon: CheckCircle2 };
    }

    if (s === 'rejected') {
        return { text: 'Ditolak', style: 'bg-rose-100 text-rose-700 border-rose-200', icon: XCircle };
    }

    // Default: Pending
    return { text: 'Sedang Ditinjau', style: 'bg-amber-100 text-amber-700 border-amber-200', icon: Loader2 };
};

// 4. Data Computed (Mengolah raw data dari backend menjadi rapi & bisa di-search)
const filteredApplications = computed(() => {
    let list = props.lamaranList || [];

    // Jika ada teks pencarian, saring datanya
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        list = list.filter((app: any) => {
            const position = app.lowongan?.judul_lowongan?.toLowerCase() || '';
            const company = app.lowongan?.mitra?.nama_mitra?.toLowerCase() || '';

            return position.includes(query) || company.includes(query);
        });
    }

    // Mapping ulang struktur data agar mudah dibaca oleh template HTML
    return list.map((app: any) => {
        const config = getStatusConfig(app.status);

        return {
            id: app.id,
            position: app.lowongan?.judul_lowongan || 'Posisi Dihapus',
            company: app.lowongan?.mitra?.nama_mitra || 'Perusahaan Tidak Diketahui',
            location: app.lowongan?.mitra?.lokasi?.nama_lokasi || 'Bengkulu',
            // Format tanggal dari "2026-04-12T00..." menjadi "12 April 2026"
            date: new Date(app.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }),
            status: config.text,
            statusStyle: config.style,
            icon: config.icon
        };
    });
});
</script>

<template>
    <Head title="Lamaran Saya" />

    <div class="space-y-8 p-6 lg:p-10">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tighter text-lokak-text uppercase italic md:text-4xl">
                    LAMARAN <span class="text-lokak-brand">SAYA</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
                <p class="mt-4 text-[11px] font-bold tracking-widest text-lokak-text-muted uppercase italic">
                    Pantau status dan perkembangan karirmu di sini.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative hidden sm:block">
                    <Search class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari posisi atau pt..."
                        class="h-11 rounded-xl border border-slate-200 bg-white pr-4 pl-10 text-xs font-bold outline-none focus:ring-2 focus:ring-lokak-brand/20"
                    />
                </div>
                <button class="flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-lokak-brand">
                    <Filter class="h-5 w-5" />
                </button>
            </div>
        </div>

        <div class="rounded-[2.5rem] border border-slate-200 bg-white p-2 shadow-sm lg:p-6">
            <div class="overflow-x-auto">
                <table class="w-full border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-left text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase italic">
                            <th class="px-6 pb-4">Pekerjaan & Perusahaan</th>
                            <th class="px-6 pb-4">Tanggal Lamar</th>
                            <th class="px-6 pb-4">Status</th>
                            <th class="px-6 pb-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="app in filteredApplications"
                            :key="app.id"
                            class="group transition-all hover:translate-x-1"
                        >
                            <td class="rounded-l-4xl border-y border-l border-slate-100 bg-slate-50 px-6 py-5 group-hover:border-slate-200 group-hover:bg-white group-hover:shadow-sm">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-100 bg-white text-xl shadow-sm">
                                        💼
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-lokak-text uppercase transition-colors group-hover:text-lokak-brand">
                                            {{ app.position }}
                                        </span>
                                        <div class="mt-1 flex items-center gap-3 text-[10px] font-bold text-slate-400 uppercase italic">
                                            <span class="flex items-center gap-1"><Building2 class="h-3 w-3" /> {{ app.company }}</span>
                                            <span class="flex items-center gap-1"><MapPin class="h-3 w-3" /> {{ app.location }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="border-y border-slate-100 bg-slate-50 px-6 py-5 group-hover:border-slate-200 group-hover:bg-white group-hover:shadow-sm">
                                <div class="flex items-center gap-2 text-[11px] font-black text-slate-500 uppercase italic">
                                    <Clock class="h-3.5 w-3.5" /> {{ app.date }}
                                </div>
                            </td>

                            <td class="border-y border-slate-100 bg-slate-50 px-6 py-5 group-hover:border-slate-200 group-hover:bg-white group-hover:shadow-sm">
                                <span :class="[app.statusStyle, 'inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-[9px] font-black tracking-wider uppercase italic']">
                                    <component :is="app.icon" class="h-3 w-3" />
                                    {{ app.status }}
                                </span>
                            </td>

                            <td class="rounded-r-4xl border-y border-r border-slate-100 bg-slate-50 px-6 py-5 text-right group-hover:border-slate-200 group-hover:bg-white group-hover:shadow-sm">
                                <button class="rounded-xl p-2 text-slate-400 transition-all hover:bg-slate-100 hover:text-lokak-brand">
                                    <MoreVertical class="h-5 w-5" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="filteredApplications.length === 0" class="flex flex-col items-center justify-center py-20 text-center">
                <div class="mb-4 text-6xl opacity-20">📂</div>
                <h3 class="text-sm font-black text-slate-400 uppercase italic">
                    {{ searchQuery ? 'Lamaran Tidak Ditemukan' : 'Belum Ada Lamaran' }}
                </h3>
                <p class="mt-1 text-[10px] font-bold text-slate-300 uppercase italic">
                    {{ searchQuery ? 'Coba gunakan kata kunci lain.' : 'Silakan cari lowongan dan mulai melamar!' }}
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
tr {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>