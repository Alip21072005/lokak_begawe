<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import {
    Search,
    FileText,
    CheckCircle2,
    XCircle,
    MessageSquare,
    X,
    Send,
    Calendar,
    Info,
    ChevronRight,
    Clock,
    Users,
    Briefcase,
    Filter,
    ChevronDown,
    Sparkles,
    UserCheck,
} from 'lucide-vue-next';
import axios from 'axios';
import Swal from 'sweetalert2';
import { computed, reactive, ref, watch } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/layouts/AppLayout.vue';

interface Applicant {
    id: string | number;
    user_id: string | number | null;
    name: string;
    email: string;
    phone: string;
    status: string;
    catatan_mitra?: string;
    applied_date: string;
    applied_human: string;
    avatar: string;
    cv?: string | null;
    lowongan_title: string;
}
interface GroupedApplicant {
    lowongan_id: string | number;
    lowongan_title: string;
    total: number;
    status_count: {
        pending: number;
        reviewed: number;
        interview: number;
        accepted: number;
        rejected: number;
    };
    applicants: Applicant[];
}

const props = defineProps<{
    auth: any;
    groupedApplicants: GroupedApplicant[];
    filters: { search?: string; status?: string; lowongan_id?: string };
    lowonganOptions: { id: string; title: string }[];
    overview: {
        total_lamaran: number;
        total_lowongan: number;
        pending: number;
        interview: number;
        accepted: number;
    };
}>();

defineOptions({ layout: AppLayout });

const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');
const lowonganId = ref(props.filters?.lowongan_id || '');

let debounceRef: ReturnType<typeof setTimeout> | null = null;

watch([search, status, lowonganId], () => {
    if (debounceRef) clearTimeout(debounceRef);
    debounceRef = setTimeout(() => {
        router.get(
            route('mitra.kelolapelamarkerja'),
            {
                search: search.value || undefined,
                status: status.value || undefined,
                lowongan_id: lowonganId.value || undefined,
            },
            { preserveState: true, replace: true, preserveScroll: true },
        );
    }, 350);
});

// Collapse state per lowongan
const collapseState = reactive<Record<string, boolean>>({});
props.groupedApplicants.forEach((g) => {
    collapseState[String(g.lowongan_id)] = false;
});
const toggleGroup = (id: string | number) => {
    const key = String(id);
    collapseState[key] = !collapseState[key];
};

const isModalOpen = ref(false);
const selectedApplicant = ref<Applicant | null>(null);

const form = useForm({
    status: '',
    catatan_mitra: '',
});

const getStatusStyle = (value: string) => {
    switch ((value || '').toLowerCase()) {
        case 'interview':
            return 'bg-purple-50 text-purple-700 border-purple-200';
        case 'rejected':
            return 'bg-rose-50 text-rose-700 border-rose-200';
        case 'accepted':
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        case 'reviewed':
            return 'bg-blue-50 text-blue-700 border-blue-200';
        default:
            return 'bg-amber-50 text-amber-700 border-amber-200';
    }
};

const statusLabel = (value: string) => {
    const map: Record<string, string> = {
        pending: 'Pending',
        reviewed: 'Reviewed',
        interview: 'Interview',
        accepted: 'Accepted',
        rejected: 'Rejected',
    };
    return map[(value || '').toLowerCase()] || value;
};

const totalDisplayed = computed(() =>
    props.groupedApplicants.reduce((acc, group) => acc + (group.applicants?.length || 0), 0),
);

const startChat = async (applicant: any) => {
    if (!applicant?.user_id) {
        Swal.fire('Error', 'ID User pelamar tidak ditemukan.', 'error');
        return;
    }
    try {
        Swal.fire({
            title: 'Memulai pesan...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading(),
        });
        await axios.post(route('messages.store'), {
            receiver_id: applicant.user_id,
            body: `Halo ${applicant.name}, kami telah meninjau lamaran Anda untuk posisi ${applicant.position || applicant.lowongan_title}.`,
        });
        Swal.close();
        // Masuk ke halaman inbox chat setelah percakapan dibuat / pesan terkirim
        router.visit(route('messages.index'));
    } catch (error) {
        Swal.fire('Gagal', 'Gagal memulai percakapan.', 'error');
    }
};

const openDecisionModal = (applicant: Applicant) => {
    selectedApplicant.value = applicant;
    form.status = applicant.status || 'pending';
    form.catatan_mitra = applicant.catatan_mitra || '';
    isModalOpen.value = true;
};

const closeDecisionModal = () => {
    isModalOpen.value = false;
    selectedApplicant.value = null;
    form.reset();
};

const submitDecision = () => {
    if (!selectedApplicant.value) return;

    form.patch(route('mitra.pelamar.status', selectedApplicant.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeDecisionModal();
            Swal.fire({
                title: 'Berhasil',
                text: 'Status pelamar telah diperbarui.',
                icon: 'success',
                timer: 1800,
                showConfirmButton: false,
                customClass: { popup: 'rounded-[2rem]' },
            });
        },
    });
};
</script>

<template>
    <Head title="Kelola Pelamar - Mitra" />

    <div class="space-y-6 p-4 md:p-6 lg:p-8">
        <!-- Header -->
        <section class="rounded-[2.2rem] border border-slate-200 bg-white p-5 shadow-sm md:p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="space-y-2">
                    <h1 class="text-2xl font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl">
                        KELOLA <span class="text-sky-700">PELAMAR</span>
                    </h1>
                    <p class="flex items-center gap-2 text-[10px] font-black tracking-[0.18em] text-slate-400 uppercase italic">
                        <Sparkles class="h-3 w-3 text-sky-700" />
                        Diklasifikasikan berdasarkan lowongan aktif
                    </p>
                </div>
            </div>

            <!-- Filter -->
            <div class="mt-5 grid grid-cols-1 gap-3 md:grid-cols-3">
                <div class="relative">
                    <Search class="absolute top-1/2 left-4 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari pelamar / email / lowongan..."
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-white pr-4 pl-12 text-xs font-bold outline-none ring-0 transition focus:border-sky-300 focus:ring-4 focus:ring-sky-700/10"
                    />
                </div>

                <div class="relative">
                    <Filter class="pointer-events-none absolute top-1/2 left-4 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <select
                        v-model="status"
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-white pr-4 pl-12 text-xs font-bold outline-none transition focus:border-sky-300 focus:ring-4 focus:ring-sky-700/10"
                    >
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="reviewed">Reviewed</option>
                        <option value="interview">Interview</option>
                        <option value="accepted">Accepted</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <select
                    v-model="lowonganId"
                    class="h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-xs font-bold outline-none transition focus:border-sky-300 focus:ring-4 focus:ring-sky-700/10"
                >
                    <option value="">Semua Lowongan</option>
                    <option v-for="l in lowonganOptions" :key="l.id" :value="l.id">
                        {{ l.title }}
                    </option>
                </select>
            </div>
        </section>

        <!-- Stats -->
        <section class="grid grid-cols-2 gap-3 md:grid-cols-5">
            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-[9px] font-black tracking-widest text-slate-400 uppercase">Total Lamaran</p>
                <p class="mt-1 text-2xl font-black text-slate-900">{{ overview.total_lamaran }}</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-[9px] font-black tracking-widest text-slate-400 uppercase">Lowongan</p>
                <p class="mt-1 text-2xl font-black text-slate-900">{{ overview.total_lowongan }}</p>
            </article>
            <article class="rounded-2xl border border-amber-100 bg-amber-50/60 p-4 shadow-sm">
                <p class="text-[9px] font-black tracking-widest text-amber-700 uppercase">Pending</p>
                <p class="mt-1 text-2xl font-black text-amber-800">{{ overview.pending }}</p>
            </article>
            <article class="rounded-2xl border border-purple-100 bg-purple-50/60 p-4 shadow-sm">
                <p class="text-[9px] font-black tracking-widest text-purple-700 uppercase">Interview</p>
                <p class="mt-1 text-2xl font-black text-purple-800">{{ overview.interview }}</p>
            </article>
            <article class="rounded-2xl border border-emerald-100 bg-emerald-50/60 p-4 shadow-sm">
                <p class="text-[9px] font-black tracking-widest text-emerald-700 uppercase">Accepted</p>
                <p class="mt-1 text-2xl font-black text-emerald-800">{{ overview.accepted }}</p>
            </article>
        </section>

        <!-- Group by lowongan -->
        <section class="space-y-4">
            <article
                v-for="group in groupedApplicants"
                :key="group.lowongan_id"
                class="rounded-[2rem] border border-slate-200 bg-white p-3 shadow-sm md:p-4"
            >
                <button
                    type="button"
                    @click="toggleGroup(group.lowongan_id)"
                    class="flex w-full items-center justify-between rounded-2xl bg-slate-50 px-4 py-3 text-left transition hover:bg-slate-100"
                >
                    <div class="min-w-0">
                        <p class="line-clamp-1 text-sm font-black uppercase italic text-slate-900 md:text-base">
                            {{ group.lowongan_title }}
                        </p>
                        <div class="mt-1 flex flex-wrap items-center gap-2 text-[10px] font-bold uppercase italic text-slate-500">
                            <span class="inline-flex items-center gap-1">
                                <Briefcase class="h-3 w-3" /> {{ group.total }} Pelamar
                            </span>
                            <span class="rounded-full bg-amber-50 px-2 py-0.5 text-amber-700">P: {{ group.status_count.pending }}</span>
                            <span class="rounded-full bg-blue-50 px-2 py-0.5 text-blue-700">R: {{ group.status_count.reviewed }}</span>
                            <span class="rounded-full bg-purple-50 px-2 py-0.5 text-purple-700">I: {{ group.status_count.interview }}</span>
                            <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-emerald-700">A: {{ group.status_count.accepted }}</span>
                        </div>
                    </div>

                    <ChevronDown
                        :class="[
                            'h-5 w-5 text-slate-500 transition-transform',
                            collapseState[String(group.lowongan_id)] ? 'rotate-180' : '',
                        ]"
                    />
                </button>

                <div v-if="collapseState[String(group.lowongan_id)]" class="mt-3 space-y-3">
                    <!-- Desktop table -->
                    <div class="hidden overflow-x-auto md:block">
                        <table class="w-full border-separate border-spacing-y-2">
                            <thead>
                                <tr class="text-left text-[9px] font-black tracking-[0.2em] text-slate-400 uppercase italic">
                                    <th class="px-4 py-2">Pelamar</th>
                                    <th class="px-4 py-2">Kontak</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Tanggal</th>
                                    <th class="px-4 py-2 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="applicant in group.applicants"
                                    :key="applicant.id"
                                    class="rounded-2xl bg-slate-50/60 transition hover:bg-slate-100/70"
                                >
                                    <td class="rounded-l-2xl px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-700 text-sm font-black text-white">
                                                {{ applicant.avatar }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="truncate text-xs font-black uppercase italic text-slate-900">{{ applicant.name }}</p>
                                                <p class="truncate text-[10px] font-semibold text-slate-500">{{ applicant.email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-[11px] font-semibold text-slate-600">{{ applicant.phone || '-' }}</td>
                                    <td class="px-4 py-4">
                                        <span :class="[getStatusStyle(applicant.status), 'inline-flex rounded-full border px-3 py-1 text-[9px] font-black uppercase italic']">
                                            {{ statusLabel(applicant.status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-[11px] font-semibold text-slate-500">
                                        <span class="inline-flex items-center gap-1">
                                            <Calendar class="h-3.5 w-3.5" /> {{ applicant.applied_date }}
                                        </span>
                                    </td>
                                    <td class="rounded-r-2xl px-4 py-4">
                                        <div class="flex justify-end gap-2">
                                            <button
                                                type="button"
                                                @click="startChat(applicant)"
                                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition hover:border-sky-200 hover:text-sky-700"
                                                title="Mulai chat"
                                            >
                                                <MessageSquare class="h-4 w-4" />
                                            </button>

                                            <a
                                                v-if="applicant.cv"
                                                :href="applicant.cv"
                                                target="_blank"
                                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition hover:border-emerald-200 hover:text-emerald-700"
                                                title="Lihat CV"
                                            >
                                                <FileText class="h-4 w-4" />
                                            </a>

                                            <button
                                                type="button"
                                                @click="openDecisionModal(applicant)"
                                                class="flex h-9 items-center gap-1 rounded-lg bg-slate-900 px-3 text-[10px] font-black uppercase italic text-white transition hover:bg-sky-700"
                                            >
                                                Update <ChevronRight class="h-3 w-3" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile cards -->
                    <div class="space-y-3 md:hidden">
                        <article
                            v-for="applicant in group.applicants"
                            :key="`mobile-${applicant.id}`"
                            class="rounded-xl border border-slate-200 bg-slate-50/60 p-3"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-black uppercase italic text-slate-900">{{ applicant.name }}</p>
                                    <p class="truncate text-xs font-semibold text-slate-500">{{ applicant.email }}</p>
                                </div>
                                <span :class="[getStatusStyle(applicant.status), 'inline-flex rounded-full border px-2 py-1 text-[9px] font-black uppercase italic']">
                                    {{ statusLabel(applicant.status) }}
                                </span>
                            </div>

                            <div class="mt-2 flex items-center gap-2 text-[11px] font-semibold text-slate-500">
                                <Clock class="h-3.5 w-3.5" /> {{ applicant.applied_human }}
                            </div>

                            <div class="mt-3 flex gap-2">
                                <button
                                    type="button"
                                    @click="startChat(applicant)"
                                    class="flex flex-1 items-center justify-center gap-1 rounded-lg border border-slate-200 bg-white py-2 text-[10px] font-bold uppercase text-slate-600"
                                >
                                    <MessageSquare class="h-3.5 w-3.5" /> Chat
                                </button>

                                <a
                                    v-if="applicant.cv"
                                    :href="applicant.cv"
                                    target="_blank"
                                    class="flex flex-1 items-center justify-center gap-1 rounded-lg border border-slate-200 bg-white py-2 text-[10px] font-bold uppercase text-slate-600"
                                >
                                    <FileText class="h-3.5 w-3.5" /> CV
                                </a>

                                <button
                                    type="button"
                                    @click="openDecisionModal(applicant)"
                                    class="flex flex-1 items-center justify-center gap-1 rounded-lg bg-slate-900 py-2 text-[10px] font-black uppercase text-white"
                                >
                                    <UserCheck class="h-3.5 w-3.5" /> Update
                                </button>
                            </div>
                        </article>
                    </div>
                </div>
            </article>

            <div v-if="totalDisplayed === 0" class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
                <p class="text-sm font-bold text-slate-500">Belum ada pelamar yang cocok dengan filter.</p>
            </div>
        </section>
    </div>

    <!-- Modal update status -->
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="isModalOpen"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
            >
                <div class="relative w-full max-w-xl rounded-[2rem] bg-white p-6 shadow-2xl md:p-8">
                    <button
                        type="button"
                        @click="closeDecisionModal"
                        class="absolute right-4 top-4 rounded-full p-2 text-slate-400 transition hover:bg-slate-100"
                    >
                        <X class="h-5 w-5" />
                    </button>

                    <div class="mb-6">
                        <h2 class="text-xl font-black uppercase italic tracking-tight text-slate-900">
                            Update <span class="text-sky-700">Status Pelamar</span>
                        </h2>
                        <p class="mt-1 text-xs font-bold uppercase italic text-slate-400">
                            {{ selectedApplicant?.name }} • {{ selectedApplicant?.lowongan_title }}
                        </p>
                    </div>

                    <form class="space-y-5" @submit.prevent="submitDecision">
                        <div class="grid grid-cols-2 gap-2 md:grid-cols-5">
                            <button
                                v-for="st in ['pending', 'reviewed', 'interview', 'accepted', 'rejected']"
                                :key="st"
                                type="button"
                                @click="form.status = st"
                                :class="[
                                    form.status === st
                                        ? getStatusStyle(st) + ' ring-2 ring-sky-700/20'
                                        : 'border-slate-200 bg-white text-slate-500',
                                    'rounded-xl border px-2 py-3 text-[10px] font-black uppercase italic transition',
                                ]"
                            >
                                {{ statusLabel(st) }}
                            </button>
                        </div>

                        <div>
                            <label class="mb-2 block text-[10px] font-black uppercase tracking-widest text-slate-400">
                                Catatan Mitra
                            </label>
                            <textarea
                                v-model="form.catatan_mitra"
                                rows="4"
                                placeholder="Contoh: Kandidat cocok, lanjut jadwal interview hari Senin..."
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm font-semibold text-slate-700 outline-none transition focus:border-sky-300 focus:bg-white focus:ring-4 focus:ring-sky-700/10"
                            />
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-sky-700 py-3 text-xs font-black uppercase italic text-white transition hover:bg-sky-800 disabled:opacity-60"
                        >
                            <span v-if="form.processing">Memproses...</span>
                            <template v-else>Update Status <Send class="h-4 w-4" /></template>
                        </button>
                    </form>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.modal-enter-active {
    transition: all 0.24s ease-out;
}
.modal-leave-active {
    transition: all 0.18s ease-in;
}
.modal-enter-from {
    opacity: 0;
    transform: translateY(12px) scale(0.98);
}
.modal-leave-to {
    opacity: 0;
    transform: scale(0.98);
}
</style>