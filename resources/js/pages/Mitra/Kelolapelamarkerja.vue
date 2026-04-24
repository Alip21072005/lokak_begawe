<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import {
    Search,
    FileText,
    CheckCircle2,
    XCircle,
    MessageCircle,
    X,
    Send,
    Calendar,
    Info
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    auth: any;
    applicants: any[];
    filters: any;
}>();

defineOptions({ layout: AppLayout });

// --- SEARCH LOGIC ---
const search = ref(props.filters.search || '');
watch(search, (value) => {
    router.get(
        (window as any).route('mitra.kelolapelamarkerja'),
        { search: value },
        { preserveState: true, replace: true },
    );
});

// --- STATE MODAL KEPUTUSAN ---
const isModalOpen = ref(false);
const selectedApplicant = ref<any>(null);

const form = useForm({
    status: '',
    catatan_mitra: '',
});

// --- HELPER STATUS STYLE ---
const getStatusStyle = (status: string) => {
    switch (status?.toLowerCase()) {
        case 'interview': return 'bg-purple-100 text-purple-700 border-purple-200';
        case 'rejected': return 'bg-rose-100 text-rose-700 border-rose-200';
        case 'accepted': return 'bg-emerald-100 text-emerald-700 border-emerald-200';
        case 'reviewed': return 'bg-blue-100 text-blue-700 border-blue-200';
        default: return 'bg-amber-100 text-amber-700 border-amber-200';
    }
};

// --- ACTION HANDLERS ---
const openDecisionModal = (applicant: any) => {
    selectedApplicant.value = applicant;
    form.status = applicant.status;
    form.catatan_mitra = applicant.catatan_mitra || '';
    isModalOpen.value = true;
};

const closeDecisionModal = () => {
    isModalOpen.value = false;
    selectedApplicant.value = null;
    form.reset();
};

const submitDecision = () => {
    form.patch((window as any).route('mitra.pelamar.status', selectedApplicant.value.id), {
        onSuccess: () => {
            closeDecisionModal();
            Swal.fire({
                title: 'Berhasil!',
                text: 'Status pelamar telah diperbarui.',
                icon: 'success',
                customClass: { popup: 'rounded-[2.5rem]' },
            });
        },
    });
};
</script>

<template>
    <Head title="Kelola Pelamar - Mitra" />

    <div class="space-y-8 p-6 lg:p-10">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl">
                    KELOLA <span class="text-sky-700">PELAMAR</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
                <p class="mt-4 text-[11px] font-bold tracking-widest text-slate-400 uppercase italic">
                    Seleksi kandidat terbaik untuk tim Anda.
                </p>
            </div>

            <div class="relative w-full sm:w-64">
                <Search class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400" />
                <input v-model="search" type="text" placeholder="Cari nama pelamar..." class="h-11 w-full rounded-xl border border-slate-200 bg-white pr-4 pl-10 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20" />
            </div>
        </div>

        <div class="rounded-[2.5rem] border border-slate-200 bg-white p-4 shadow-sm lg:p-8">
            <div class="overflow-x-auto">
                <table class="w-full border-separate border-spacing-y-2">
                    <thead>
                        <tr class="text-left text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase italic">
                            <th class="px-4 pb-6">Kandidat</th>
                            <th class="px-4 pb-6">Posisi</th>
                            <th class="px-4 pb-6">Status Saat Ini</th>
                            <th class="px-4 pb-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="applicant in applicants" :key="applicant.id" class="group bg-slate-50/50 transition-all hover:bg-white hover:shadow-md">
                            <td class="rounded-l-3xl px-4 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-700 font-black text-white uppercase shadow-lg shadow-sky-900/20">
                                        {{ applicant.avatar }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-900 uppercase">{{ applicant.name }}</span>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase italic">{{ applicant.appliedDate }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-6">
                                <span class="text-[11px] font-black text-slate-700 uppercase italic">{{ applicant.position }}</span>
                            </td>
                            <td class="px-4 py-6">
                                <span :class="[getStatusStyle(applicant.status), 'inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-[9px] font-black uppercase italic']">
                                    {{ applicant.status }}
                                </span>
                            </td>
                            <td class="rounded-r-3xl px-4 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <a :href="applicant.cv" target="_blank" v-if="applicant.cv" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-400 hover:text-sky-700 shadow-sm transition-all">
                                        <FileText class="h-4 w-4" />
                                    </a>
                                    <button @click="openDecisionModal(applicant)" class="flex h-9 px-4 items-center gap-2 rounded-xl bg-slate-900 text-[10px] font-black text-white uppercase italic hover:bg-sky-700 transition-all">
                                        Tentukan Status <MessageCircle class="h-3.5 w-3.5" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <Teleport to="body">
        <Transition name="fade">
            <div v-if="isModalOpen" class="fixed inset-0 z-100 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm">
                <div class="w-full max-w-lg rounded-[2.5rem] bg-white p-8 shadow-2xl relative animate-in zoom-in duration-300">
                    
                    <div class="mb-8 flex items-center justify-between">
                        <h2 class="text-xl font-black italic tracking-tighter text-slate-900 uppercase">
                            Keputusan <span class="text-sky-700">Seleksi</span>
                        </h2>
                        <button @click="closeDecisionModal" class="rounded-full p-2 hover:bg-slate-100 transition-colors">
                            <X class="h-5 w-5 text-slate-400" />
                        </button>
                    </div>

                    <div class="mb-8 p-6 rounded-3xl bg-slate-50 border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase italic mb-1 text-center">Memproses Pelamar</p>
                        <p class="text-lg font-black text-slate-900 uppercase text-center tracking-tight">{{ selectedApplicant?.name }}</p>
                    </div>

                    <form @submit.prevent="submitDecision" class="space-y-6">
                        <div class="space-y-2">
                            <label class="ml-4 text-[10px] font-black text-slate-400 uppercase italic">Update Status</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                <button v-for="st in ['reviewed', 'interview', 'accepted', 'rejected']" :key="st" type="button" 
                                    @click="form.status = st"
                                    :class="[form.status === st ? getStatusStyle(st) + ' border-2 scale-95 shadow-inner' : 'bg-white border-slate-100 text-slate-400', 'flex flex-col items-center justify-center p-4 rounded-2xl border transition-all hover:border-sky-200']"
                                >
                                    <component :is="st === 'interview' ? Calendar : (st === 'accepted' ? CheckCircle2 : (st === 'rejected' ? XCircle : Info))" class="h-5 w-5 mb-1" />
                                    <span class="text-[9px] font-black uppercase italic">{{ st }}</span>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="ml-4 text-[10px] font-black text-slate-400 uppercase italic">Catatan / Pesan untuk Pelamar</label>
                            <textarea v-model="form.catatan_mitra" placeholder="Contoh: Silakan datang interview hari Senin jam 10 pagi di kantor..." class="w-full rounded-3xl border border-slate-100 bg-slate-50 p-5 text-xs font-bold text-slate-700 outline-none focus:border-sky-700 focus:bg-white transition-all min-h-30"></textarea>
                        </div>

                        <div class="flex flex-col gap-3">
                            <button type="submit" :disabled="form.processing" class="flex w-full items-center justify-center gap-3 rounded-2xl bg-sky-700 py-5 text-xs font-black uppercase italic text-white shadow-xl shadow-sky-900/20 hover:bg-sky-800 transition-all disabled:opacity-50">
                                <span v-if="form.processing">MEMPROSES...</span>
                                <template v-else>SIMPAN KEPUTUSAN <Send class="h-4 w-4" /></template>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>