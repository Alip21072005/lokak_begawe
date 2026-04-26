<!-- eslint-disable @typescript-eslint/no-unused-vars -->
<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import {
    Search, FileText, CheckCircle2, XCircle, 
    MessageSquare, X, Send, Calendar, 
    Info, User, ChevronRight, Clock, Users
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { ref, watch } from 'vue';
import { route } from 'ziggy-js';
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
        route('mitra.kelolapelamarkerja'),
        { search: value },
        { preserveState: true, replace: true },
    );
});

// --- STATE MANAGEMENT ---
const isModalOpen = ref(false);
const selectedApplicant = ref<any>(null);

const form = useForm({
    status: '',
    catatan_mitra: '',
});

// --- UI HELPERS ---
const getStatusStyle = (status: string) => {
    switch (status?.toLowerCase()) {
        case 'interview': return 'bg-purple-50 text-purple-700 border-purple-200';
        case 'rejected': return 'bg-rose-50 text-rose-700 border-rose-200';
        case 'accepted': return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        case 'reviewed': return 'bg-blue-50 text-blue-700 border-blue-200';
        default: return 'bg-amber-50 text-amber-700 border-amber-200';
    }
};

// --- ACTION HANDLERS ---

/**
 * FIX: Pastikan fungsi ini dipanggil dengan objek applicant yang memiliki user_id
 */
const startChat = (applicant: any) => {
    // Validasi sederhana sebelum kirim
    if (!applicant.user_id) {
        Swal.fire('Error', 'ID User pelamar tidak ditemukan.', 'error');

        return;
    }

    Swal.fire({
        title: 'Memulai Pesan...',
        allowOutsideClick: false,
        didOpen: () => {
 Swal.showLoading(); 
}
    });

    router.post(route('messages.store'), {
        receiver_id: applicant.user_id,
        body: `Halo ${applicant.name}, kami telah meninjau lamaran Anda untuk posisi ${applicant.position}.`
    }, {
        onSuccess: () => {
            Swal.close();
            router.visit(route('messages.index'));
        },
        onError: () => {
            Swal.fire('Gagal', 'Gagal memulai percakapan.', 'error');
        }
    });
};

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
    form.patch(route('mitra.pelamar.status', selectedApplicant.value.id), {
        onSuccess: () => {
            closeDecisionModal();
            Swal.fire({
                title: 'Berhasil!',
                text: 'Status pelamar telah diperbarui.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                customClass: { popup: 'rounded-[2.5rem]' },
            });
        },
    });
};
</script>

<template>
    <Head title="Kelola Pelamar - Lokak Begawe" />

    <div class="space-y-10 p-6 lg:p-10">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="space-y-2">
                <h1 class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic md:text-5xl">
                    KELOLA <span class="text-sky-700">TALENTA</span>
                </h1>
                <p class="flex items-center gap-2 text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase italic">
                    <Users class="h-3 w-3 text-sky-700" /> {{ applicants.length }} Kandidat Terdaftar
                </p>
            </div>

            <div class="relative w-full lg:w-80">
                <Search class="absolute top-1/2 left-4 h-4 w-4 -translate-y-1/2 text-slate-400" />
                <input v-model="search" type="text" placeholder="Cari nama pelamar..." class="h-14 w-full rounded-2xl border-none bg-white pr-4 pl-12 text-xs font-bold shadow-sm ring-1 ring-slate-100 outline-none focus:ring-4 focus:ring-sky-700/5 transition-all italic" />
            </div>
        </div>

        <div class="rounded-[3rem] border border-slate-100 bg-white p-2 shadow-xl shadow-slate-200/50">
            <div class="overflow-x-auto">
                <table class="w-full border-separate border-spacing-y-3 px-4">
                    <thead>
                        <tr class="text-left text-[9px] font-black tracking-[0.3em] text-slate-400 uppercase italic">
                            <th class="px-6 py-4">Informasi Pelamar</th>
                            <th class="px-6 py-4">Posisi</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="applicant in applicants" :key="applicant.id" class="group transition-all">
                            <td class="rounded-l-4xl bg-slate-50/50 px-6 py-6 group-hover:bg-slate-100/50">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-3xl bg-sky-700 font-black text-white shadow-lg shadow-sky-900/20 italic text-xl">
                                        {{ applicant.avatar || applicant.name.charAt(0) }}
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-sm font-black text-slate-900 uppercase italic tracking-tight">{{ applicant.name }}</span>
                                        <div class="flex items-center gap-2 text-[9px] font-bold text-slate-400 uppercase italic">
                                            <Clock class="h-2.5 w-2.5" /> {{ applicant.appliedDate }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="bg-slate-50/50 px-6 py-6 group-hover:bg-slate-100/50">
                                <span class="rounded-lg bg-white border border-slate-100 px-3 py-1.5 text-[10px] font-black text-slate-600 uppercase italic">
                                    {{ applicant.position }}
                                </span>
                            </td>
                            <td class="bg-slate-50/50 px-6 py-6 text-center group-hover:bg-slate-100/50">
                                <span :class="[getStatusStyle(applicant.status), 'inline-flex items-center gap-1.5 rounded-full border px-4 py-1.5 text-[9px] font-black uppercase italic']">
                                    <div class="h-1.5 w-1.5 rounded-full bg-current"></div>
                                    {{ applicant.status }}
                                </span>
                            </td>
                            <td class="rounded-r-4xl bg-slate-50/50 px-6 py-6 text-right group-hover:bg-slate-100/50">
                                <div class="flex justify-end gap-3">
                                    <button 
                                        @click="startChat(applicant)"
                                        type="button"
                                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-400 shadow-sm border border-slate-100 hover:text-sky-700 hover:border-sky-200 transition-all active:scale-90"
                                    >
                                        <MessageSquare class="h-4 w-4" />
                                    </button>

                                    <a :href="applicant.cv" target="_blank" v-if="applicant.cv" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-400 shadow-sm border border-slate-100 hover:text-emerald-600 hover:border-emerald-200 transition-all active:scale-90">
                                        <FileText class="h-4 w-4" />
                                    </a>

                                    <button @click="openDecisionModal(applicant)" class="flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-5 text-[10px] font-black text-white uppercase italic hover:bg-sky-700 transition-all shadow-lg shadow-slate-900/10">
                                        Update <ChevronRight class="h-3 w-4" />
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
        <Transition name="modal">
            <div v-if="isModalOpen" class="fixed inset-0 z-100 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
                <div class="w-full max-w-xl rounded-[3.5rem] bg-white p-10 shadow-2xl relative overflow-hidden animate-in zoom-in duration-300">
                    <button @click="closeDecisionModal" class="absolute top-8 right-8 rounded-full p-2 hover:bg-slate-100 transition-colors">
                        <X class="h-6 w-6 text-slate-400" />
                    </button>

                    <div class="mb-10 text-center">
                        <h2 class="text-2xl font-black italic tracking-tighter text-slate-900 uppercase">
                            Proses <span class="text-sky-700">Seleksi</span>
                        </h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase italic mt-2">{{ selectedApplicant?.name }}</p>
                    </div>

                    <form @submit.prevent="submitDecision" class="space-y-8">
                        <div class="space-y-3">
                            <label class="ml-4 text-[10px] font-black text-slate-400 uppercase italic tracking-widest">Pilih Status Baru</label>
                            <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                                <button v-for="st in ['reviewed', 'interview', 'accepted', 'rejected']" :key="st" type="button" 
                                    @click="form.status = st"
                                    :class="[form.status === st ? getStatusStyle(st) + ' ring-2 ring-sky-700/20 shadow-inner' : 'bg-white border-slate-100 text-slate-400', 'flex flex-col items-center justify-center gap-2 py-5 rounded-3xl border transition-all hover:border-sky-200']"
                                >
                                    <component :is="st === 'interview' ? Calendar : (st === 'accepted' ? CheckCircle2 : (st === 'rejected' ? XCircle : Info))" class="h-5 w-5" />
                                    <span class="text-[9px] font-black uppercase italic">{{ st }}</span>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="ml-4 text-[10px] font-black text-slate-400 uppercase italic tracking-widest">Catatan / Pesan</label>
                            <textarea v-model="form.catatan_mitra" placeholder="Tulis catatan atau instruksi seleksi..." class="w-full rounded-[2.5rem] border-none bg-slate-50 p-6 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-sky-700/5 focus:bg-white transition-all min-h-32 italic"></textarea>
                        </div>

                        <button type="submit" :disabled="form.processing" class="flex w-full items-center justify-center gap-3 rounded-3xl bg-sky-700 py-6 text-xs font-black uppercase italic text-white shadow-xl shadow-sky-900/30 hover:bg-sky-800 transition-all disabled:opacity-50">
                            <span v-if="form.processing">MEMPROSES...</span>
                            <template v-else>UPDATE STATUS <Send class="h-4 w-4" /></template>
                        </button>
                    </form>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.modal-enter-active { transition: all 0.3s ease-out; }
.modal-leave-active { transition: all 0.2s ease-in; }
.modal-enter-from { opacity: 0; transform: scale(0.9) translateY(20px); }
.modal-leave-to { opacity: 0; transform: scale(0.95); }
</style>