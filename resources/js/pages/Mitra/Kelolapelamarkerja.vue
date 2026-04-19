<!-- eslint-disable @typescript-eslint/no-unused-vars -->
<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import {
    Search,
    Filter,
    FileText,
    CheckCircle2,
    XCircle,
    UserCheck,
    Star,
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
        {
            preserveState: true,
            replace: true,
        },
    );
});

// --- HELPER STATUS STYLE ---
const getStatusStyle = (status: string) => {
    switch (status.toLowerCase()) {
        case 'interview':
            return 'bg-emerald-100 text-emerald-700 border-emerald-200';
        case 'rejected':
            return 'bg-rose-100 text-rose-700 border-rose-200';
        case 'accepted':
            return 'bg-sky-100 text-sky-700 border-sky-200';
        default:
            return 'bg-amber-100 text-amber-700 border-amber-200'; // Untuk status 'pending' atau 'review'
    }
};

// --- ACTION HANDLER ---
const updateStatus = (id: string, name: string, status: string) => {
    const actionText = status === 'interview' ? 'Panggil Interview' : 'Tolak';
    const confirmColor = status === 'interview' ? '#10b981' : '#f43f5e';

    Swal.fire({
        title: `${actionText} Pelamar?`,
        text: `Apakah Anda yakin ingin memproses ${name}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        confirmButtonText: 'Ya, Proses!',
        customClass: { popup: 'rounded-[2.5rem]' },
    }).then((result) => {
        if (result.isConfirmed) {
            router.patch(
                (window as any).route('mitra.pelamar.status', id),
                { status },
                {
                    onSuccess: () =>
                        Swal.fire({
                            title: 'Berhasil!',
                            icon: 'success',
                            customClass: { popup: 'rounded-[2.5rem]' },
                        }),
                },
            );
        }
    });
};
</script>

<template>
    <Head title="Kelola Pelamar - Mitra" />

    <div class="space-y-8 p-6 lg:p-10">
        <div
            class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <h1
                    class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl"
                >
                    KELOLA <span class="text-sky-700">PELAMAR</span>
                </h1>
                <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
                <p
                    class="mt-4 text-[11px] font-bold tracking-widest text-slate-400 uppercase italic"
                >
                    Seleksi kandidat terbaik untuk tim Anda di Bengkulu.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative w-full sm:w-64">
                    <Search
                        class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400"
                    />
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari nama pelamar..."
                        class="h-11 w-full rounded-xl border border-slate-200 bg-white pr-4 pl-10 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20"
                    />
                </div>
            </div>
        </div>

        <div
            class="rounded-[2.5rem] border border-slate-200 bg-white p-4 shadow-sm lg:p-8"
        >
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr
                            class="border-b border-slate-100 text-left text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase italic"
                        >
                            <th class="px-4 pb-6">Kandidat</th>
                            <th class="px-4 pb-6">Posisi Dilamar</th>
                            <th class="px-4 pb-6 text-center">
                                Skor Kecocokan
                            </th>
                            <th class="px-4 pb-6">Status</th>
                            <th class="px-4 pb-6 text-right">Aksi Seleksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-if="applicants.length === 0">
                            <td
                                colspan="5"
                                class="py-20 text-center text-[10px] font-black text-slate-300 uppercase italic"
                            >
                                Belum ada pelamar masuk
                            </td>
                        </tr>
                        <tr
                            v-for="applicant in applicants"
                            :key="applicant.id"
                            class="group transition-colors hover:bg-slate-50/50"
                        >
                            <td class="px-4 py-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-2xl border border-sky-100 bg-sky-50 font-black text-sky-700 uppercase shadow-inner transition-all group-hover:bg-white"
                                    >
                                        {{ applicant.avatar }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm leading-tight font-black text-slate-900 uppercase"
                                            >{{ applicant.name }}</span
                                        >
                                        <span
                                            class="text-[10px] font-bold text-slate-400 uppercase italic"
                                            >Tgl Lamar:
                                            {{ applicant.appliedDate }}</span
                                        >
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-6">
                                <span
                                    class="text-[11px] font-black tracking-tight text-slate-900 uppercase italic"
                                    >{{ applicant.position }}</span
                                >
                            </td>
                            <td class="px-4 py-6 text-center">
                                <div
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-3 py-1 text-xs font-black text-slate-900"
                                >
                                    <Star
                                        class="h-3 w-3 fill-amber-500 text-amber-500"
                                    />
                                    {{ applicant.matchScore }}%
                                </div>
                            </td>
                            <td class="px-4 py-6">
                                <span
                                    :class="[
                                        getStatusStyle(applicant.status),
                                        'inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-[9px] font-black tracking-widest uppercase italic',
                                    ]"
                                >
                                    {{ applicant.status }}
                                </span>
                            </td>
                            <td class="px-4 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <button
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-400 shadow-sm hover:border-sky-700 hover:text-sky-700"
                                        title="Lihat CV"
                                    >
                                        <FileText class="h-4 w-4" />
                                    </button>
                                    <button
                                        v-if="applicant.status !== 'rejected'"
                                        @click="
                                            updateStatus(
                                                applicant.id,
                                                applicant.name,
                                                'interview',
                                            )
                                        "
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-700 text-white shadow-lg shadow-sky-900/20 hover:bg-sky-800"
                                        title="Panggil Interview"
                                    >
                                        <UserCheck class="h-4 w-4" />
                                    </button>
                                    <button
                                        v-if="applicant.status !== 'rejected'"
                                        @click="
                                            updateStatus(
                                                applicant.id,
                                                applicant.name,
                                                'rejected',
                                            )
                                        "
                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-400 shadow-sm hover:text-rose-500"
                                        title="Tolak"
                                    >
                                        <XCircle class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
