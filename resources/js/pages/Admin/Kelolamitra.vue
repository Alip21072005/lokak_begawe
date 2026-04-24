<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import {
    Search,
    Eye,
    AlertCircle,
    UserPlus,
    Building2} from 'lucide-vue-next';
import { ref, computed, watch } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps<{
    auth: any;
    mitraPending: any[];
    mitraActive: any[];
    stats: any;
    filters: any;
}>();

const activeTab = ref('pending');

// --- PENCARIAN ---
const search = ref(props.filters.search);
watch(search, (val) => {
    router.get(
        '/dashboard/admin/kelolamitra',
        { search: val },
        { preserveState: true, replace: true },
    );
});

// Pilih data mana yang tampil berdasarkan tab
const displayedMitra = computed(() => {
    return activeTab.value === 'pending'
        ? props.mitraPending
        : props.mitraActive;
});
</script>

<template>
    <Head title="Kelola Mitra - Admin" />

    <div class="space-y-8 p-6 lg:p-10 text-slate-900">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tighter uppercase italic md:text-4xl">
                    KONTROL <span class="text-sky-700">MITRA</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
            </div>

            <Link
                :href="route('admin.mitra.create')"
                class="flex items-center justify-center gap-2 rounded-2xl bg-sky-700 px-8 py-4 text-xs font-black text-white uppercase italic shadow-lg shadow-sky-900/20 transition-all hover:bg-sky-800 active:scale-95"
            >
                <UserPlus class="h-4 w-4" /> TAMBAH MITRA MANUAL
            </Link>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-sm flex items-center gap-6">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-400">
                    <Building2 class="h-7 w-7" />
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 italic">Total Mitra Terdaftar</p>
                    <p class="text-3xl font-black italic">{{ stats.total }} <span class="text-xs">Perusahaan</span></p>
                </div>
            </div>
            <div class="rounded-3xl border border-amber-100 bg-amber-50/50 p-8 shadow-sm flex items-center gap-6">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-lg shadow-amber-900/20">
                    <AlertCircle class="h-7 w-7" />
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-amber-600 italic">Menunggu Verifikasi</p>
                    <p class="text-3xl font-black italic text-amber-700">{{ stats.pending }} <span class="text-xs">Antrean</span></p>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between pt-4">
            <div class="flex w-fit gap-2 rounded-2xl bg-slate-100 p-1.5 border border-slate-200">
                <button
                    @click="activeTab = 'pending'"
                    :class="activeTab === 'pending' ? 'bg-white text-sky-700 shadow-sm border border-slate-200' : 'text-slate-500 hover:text-slate-900'"
                    class="rounded-xl px-6 py-3 text-[10px] font-black uppercase italic transition-all"
                >
                    PENDING ({{ mitraPending.length }})
                </button>
                <button
                    @click="activeTab = 'active'"
                    :class="activeTab === 'active' ? 'bg-white text-sky-700 shadow-sm border border-slate-200' : 'text-slate-500 hover:text-slate-900'"
                    class="rounded-xl px-6 py-3 text-[10px] font-black uppercase italic transition-all"
                >
                    AKTIF ({{ mitraActive.length }})
                </button>
            </div>

            <div class="relative w-full lg:w-80">
                <Search class="absolute top-1/2 left-4 h-4 w-4 -translate-y-1/2 text-slate-400" />
                <input
                    v-model="search"
                    type="text"
                    placeholder="Cari nama perusahaan..."
                    class="h-14 w-full rounded-2xl border border-slate-200 bg-white pr-4 pl-12 text-xs font-bold outline-none focus:ring-4 focus:ring-sky-700/5 transition-all"
                />
            </div>
        </div>

        <div class="rounded-[2.5rem] border border-slate-200 bg-white p-4 shadow-sm lg:p-8">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase italic border-b border-slate-50">
                            <th class="px-6 py-6">Informasi Perusahaan</th>
                            <th class="px-6 py-6">Bidang Industri</th>
                            <th class="px-6 py-6">Tgl Registrasi</th>
                            <th class="px-6 py-6 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-if="displayedMitra.length === 0">
                            <td colspan="4" class="py-24 text-center text-[10px] font-black tracking-widest text-slate-300 uppercase italic">
                                Belum ada data mitra untuk ditampilkan
                            </td>
                        </tr>
                        <tr
                            v-for="mitra in displayedMitra"
                            :key="mitra.id"
                            class="group transition-colors hover:bg-slate-50/50"
                        >
                            <td class="px-6 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-slate-100 bg-white font-black text-sky-700 uppercase shadow-sm transition-transform group-hover:scale-110 italic">
                                        {{ mitra.nama_mitra.charAt(0) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black uppercase tracking-tight text-slate-900 group-hover:text-sky-700 transition-colors italic">{{ mitra.nama_mitra }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 italic lowercase">{{ mitra.email_mitra }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <span class="rounded-lg bg-slate-100 px-3 py-1.5 text-[9px] font-black text-slate-500 uppercase italic">
                                    {{ mitra.kategori?.nama_kategori || 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-6 text-[10px] font-bold text-slate-400 italic">
                                {{ new Date(mitra.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                            </td>
                            <td class="px-6 py-6 text-right">
                                <Link
                                    :href="`/dashboard/admin/kelolamitra/${mitra.id}`"
                                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-6 py-3 text-[10px] font-black text-white uppercase italic transition-all hover:bg-sky-700"
                                >
                                    <Eye class="h-4 w-4" /> PERIKSA PROFIL
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>