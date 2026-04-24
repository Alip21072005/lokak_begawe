<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
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
    Info
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

// Helper untuk Badge Status & Warna
const getStatusConfig = (status: string) => {
    const s = status?.toLowerCase() || 'pending';
    
    switch (s) {
        case 'accepted':
            return { text: 'Diterima', style: 'bg-emerald-100 text-emerald-700 border-emerald-200', icon: CheckCircle2 };
        case 'rejected':
            return { text: 'Ditolak', style: 'bg-rose-100 text-rose-700 border-rose-200', icon: XCircle };
        case 'interview':
            return { text: 'Wawancara', style: 'bg-purple-100 text-purple-700 border-purple-200', icon: Calendar };
        case 'reviewed':
            return { text: 'Ditinjau', style: 'bg-blue-100 text-blue-700 border-blue-200', icon: Info };
        default:
            return { text: 'Menunggu', style: 'bg-amber-100 text-amber-700 border-amber-200', icon: Loader2 };
    }
};

const filteredApplications = computed(() => {
    let list = props.lamaranList || [];

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        list = list.filter((app: any) => {
            const position = app.posisi?.toLowerCase() || '';
            const company = app.perusahaan?.toLowerCase() || '';

            return position.includes(query) || company.includes(query);
        });
    }

    return list;
});
</script>

<template>
    <Head title="Status Lamaran Saya" />

    <div class="space-y-8 p-6 lg:p-10">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tighter text-lokak-text uppercase italic md:text-4xl">
                    STATUS <span class="text-lokak-brand">LAMARAN</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
                <p class="mt-4 text-[11px] font-bold tracking-widest text-lokak-text-muted uppercase italic">
                    Pantau progres karirmu secara real-time.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative w-full md:w-64">
                    <Search class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari posisi..."
                        class="h-11 w-full rounded-xl border border-slate-200 bg-white pr-4 pl-10 text-xs font-bold outline-none focus:ring-2 focus:ring-lokak-brand/20"
                    />
                </div>
            </div>
        </div>

        <div v-if="filteredApplications.length > 0" class="grid grid-cols-1 gap-6">
            <div 
                v-for="app in filteredApplications" 
                :key="app.id" 
                class="group relative overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm transition-all hover:shadow-xl hover:border-sky-100"
            >
                <div class="absolute -top-12 -right-12 h-32 w-32 rounded-full bg-slate-50 transition-transform group-hover:scale-150"></div>

                <div class="relative flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                    <div class="space-y-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <h2 class="text-xl font-black italic tracking-tighter text-slate-900 uppercase">
                                {{ app.posisi }}
                            </h2>
                            <div :class="[getStatusConfig(app.status).style, 'flex items-center gap-1.5 rounded-full border px-3 py-1 text-[9px] font-black uppercase italic tracking-wider']">
                                <component :is="getStatusConfig(app.status).icon" class="h-3 w-3" :class="app.status === 'pending' ? 'animate-spin' : ''" />
                                {{ getStatusConfig(app.status).text }}
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-5 text-[10px] font-bold uppercase italic text-slate-400 tracking-wide">
                            <span class="flex items-center gap-1.5"><Building2 class="h-3.5 w-3.5 text-lokak-brand" /> {{ app.perusahaan }}</span>
                            <span class="flex items-center gap-1.5"><MapPin class="h-3.5 w-3.5 text-lokak-brand" /> {{ app.lokasi }}</span>
                            <span class="flex items-center gap-1.5"><Clock class="h-3.5 w-3.5 text-lokak-brand" /> Dilamar: {{ app.tanggal_kirim }}</span>
                        </div>
                    </div>

                    <div v-if="app.catatan_mitra" class="relative max-w-md flex-1 rounded-3xl bg-slate-50 p-5 border border-slate-100 lg:mx-8">
                        <div class="mb-2 flex items-center gap-2 text-[10px] font-black text-lokak-brand uppercase italic">
                            <MessageCircle class="h-3.5 w-3.5" /> Pesan dari Mitra:
                        </div>
                        <p class="text-xs font-bold text-slate-600 leading-relaxed italic">
                            "{{ app.catatan_mitra }}"
                        </p>
                    </div>

                    

                    
                </div>
            </div>
        </div>

        <div v-else class="flex flex-col items-center justify-center py-20 text-center">
            <div class="mb-6 h-24 w-24 rounded-full bg-slate-100 flex items-center justify-center text-5xl grayscale opacity-30 shadow-inner">
                📁
            </div>
            <h3 class="text-sm font-black text-slate-400 uppercase italic">
                {{ searchQuery ? 'Pencarian tidak ditemukan' : 'Belum Ada Jejak Lamaran' }}
            </h3>
            <p class="mt-2 text-[10px] font-bold text-slate-300 uppercase italic">
                {{ searchQuery ? 'Coba cari dengan kata kunci posisi yang berbeda.' : 'Ayo mulai petualangan karirmu dengan melamar lowongan pertama!' }}
            </p>
            <Link href="/lowongan" class="mt-8 text-xs font-black text-lokak-brand underline uppercase italic">
                Telusuri Lowongan Sekarang
            </Link>
        </div>
    </div>
</template>

<style scoped>
.animate-spin {
    animation: spin 3s linear infinite;
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>