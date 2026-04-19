<!-- eslint-disable @typescript-eslint/no-unused-vars -->
<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import {
    Send,
    Info,
    Eye,
    MapPin,
    Briefcase,
    Banknote,
    AlignLeft,
    Trash2,
    Edit3,
    X,
    Clock,
    AlertCircle,
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { computed, ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    auth: any;
    lokasis: any[];
    myJobs: any[];
}>();

defineOptions({ layout: AppLayout });

// --- STATE MANAGEMENT ---
const isEditing = ref(false);
const editId = ref<string | null>(null);
const isModalOpen = ref(false);
const selectedJob = ref<any>(null);

const form = useForm({
    judul_lowongan: '',
    tipe_pekerjaan: 'Full-time',
    gaji_min: '',
    gaji_max: '',
    lokasi_id: '',
    deskripsi_lowongan: '',
});

// --- LOGIKA SUBMIT (TAMBAH & EDIT) ---
const submit = () => {
    const routeFunc = (window as any).route || (window as any).Ziggy?.route;

    if (!routeFunc) {
        console.error('Ziggy Route tidak ditemukan!');

        return;
    }

    if (isEditing.value && editId.value) {
        // Mode Update
        form.put(routeFunc('mitra.pasanglowongan.update', editId.value), {
            onSuccess: () => {
                resetForm();
                Swal.fire({
                    title: 'Berhasil Diperbarui!',
                    icon: 'success',
                    confirmButtonColor: '#0369a1',
                    customClass: { popup: 'rounded-[2.5rem]' },
                });
            },
        });
    } else {
        // Mode Store (Baru)
        form.post(routeFunc('mitra.pasanglowongan.store'), {
            onSuccess: () => {
                resetForm();
                Swal.fire({
                    title: 'Berhasil Ditayangkan!',
                    text: 'Lowongan masuk ke antrean verifikasi admin.',
                    icon: 'success',
                    confirmButtonColor: '#0369a1',
                    customClass: { popup: 'rounded-[2.5rem]' },
                });
            },
        });
    }
};

// --- FITUR AKSI TABEL ---

// 1. Persiapan Edit (Naikkan data ke form)
const editJob = (job: any) => {
    isEditing.value = true;
    editId.value = job.id;
    form.judul_lowongan = job.judul_lowongan;
    form.tipe_pekerjaan = job.tipe_pekerjaan;
    form.gaji_min = job.gaji_min;
    form.gaji_max = job.gaji_max;
    form.lokasi_id = job.lokasi_id;
    form.deskripsi_lowongan = job.deskripsi_lowongan;

    // Scroll ke atas biar mitra gak bingung
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

// 2. Fitur Hapus
const deleteJob = (id: string) => {
    Swal.fire({
        title: 'Hapus Lowongan?',
        text: 'Data yang dihapus tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#cbd5e1',
        confirmButtonText: 'Ya, Hapus!',
        customClass: { popup: 'rounded-[2.5rem]' },
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(
                (window as any).route('mitra.pasanglowongan.destroy', id),
                {
                    onSuccess: () =>
                        Swal.fire({
                            title: 'Terhapus!',
                            icon: 'success',
                            customClass: { popup: 'rounded-[2.5rem]' },
                        }),
                },
            );
        }
    });
};

// 3. Lihat Detail (Modal)
const openDetail = (job: any) => {
    selectedJob.value = job;
    isModalOpen.value = true;
};

const resetForm = () => {
    form.reset();
    isEditing.value = false;
    editId.value = null;
};

// --- COMPUTED PREVIEW ---
const selectedLocationName = computed(() => {
    const loc = props.lokasis.find((l) => l.id === form.lokasi_id);

    return loc ? loc.nama_lokasi : 'Lokasi Penempatan';
});

const formattedSalaryPreview = computed(() => {
    if (!form.gaji_min && !form.gaji_max) {
        return 'Rp -';
    }

    const min = form.gaji_min
        ? new Intl.NumberFormat('id-ID').format(Number(form.gaji_min))
        : '0';
    const max = form.gaji_max
        ? new Intl.NumberFormat('id-ID').format(Number(form.gaji_max))
        : '0';

    return `Rp ${min} - ${max}`;
});
</script>

<template>
    <Head title="Manajemen Lowongan - Lokak Begawe" />

    <div class="space-y-12 p-6 lg:p-10">
        <div class="flex flex-col gap-2">
            <h1
                class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl"
            >
                {{ isEditing ? 'EDIT' : 'PASANG' }}
                <span class="text-sky-700">LOWONGAN</span>
            </h1>
            <div class="h-1.5 w-24 rounded-full bg-slate-200"></div>
            <p
                class="mt-2 text-[11px] font-bold tracking-widest text-slate-400 uppercase italic"
            >
                Kelola iklan pekerjaan Anda untuk mendapatkan talenta terbaik.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div
                class="space-y-8 rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm lg:col-span-8"
            >
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label
                            class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                            >Judul Posisi</label
                        >
                        <input
                            v-model="form.judul_lowongan"
                            type="text"
                            placeholder="Contoh: Admin Gudang"
                            class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-xs font-bold transition-all outline-none focus:ring-2 focus:ring-sky-700/20"
                        />
                    </div>
                    <div class="space-y-2">
                        <label
                            class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                            >Tipe Kerja</label
                        >
                        <select
                            v-model="form.tipe_pekerjaan"
                            class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20"
                        >
                            <option>Full-time</option>
                            <option>Part-time</option>
                            <option>Freelance</option>
                            <option>Internship</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label
                            class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                            >Gaji Minimal (Rp)</label
                        >
                        <input
                            v-model="form.gaji_min"
                            type="number"
                            placeholder="3000000"
                            class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20"
                        />
                    </div>
                    <div class="space-y-2">
                        <label
                            class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                            >Gaji Maksimal (Rp)</label
                        >
                        <input
                            v-model="form.gaji_max"
                            type="number"
                            placeholder="5000000"
                            class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20"
                        />
                    </div>
                </div>
                <div class="space-y-2">
                    <label
                        class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                        >Wilayah Penempatan</label
                    >
                    <select
                        v-model="form.lokasi_id"
                        class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20"
                    >
                        <option value="" disabled>
                            Pilih Kabupaten/Kota...
                        </option>
                        <option
                            v-for="loc in lokasis"
                            :key="loc.id"
                            :value="loc.id"
                        >
                            {{ loc.nama_lokasi }}
                        </option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label
                        class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                        >Deskripsi Pekerjaan</label
                    >
                    <textarea
                        v-model="form.deskripsi_lowongan"
                        rows="5"
                        placeholder="Tuliskan syarat & tanggung jawab..."
                        class="w-full rounded-3xl border-slate-200 bg-slate-50 p-6 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20"
                    ></textarea>
                </div>

                <div class="flex gap-4">
                    <button
                        v-if="isEditing"
                        @click="resetForm"
                        class="w-1/3 rounded-2xl bg-slate-100 py-5 text-xs font-black text-slate-400 uppercase italic transition-all hover:bg-slate-200"
                    >
                        Batal Edit
                    </button>
                    <button
                        @click="submit"
                        :disabled="form.processing"
                        class="flex-1 rounded-2xl bg-sky-700 py-5 text-sm font-black text-white uppercase italic shadow-xl shadow-sky-900/20 transition-all hover:bg-sky-800 disabled:opacity-50"
                    >
                        <Send v-if="!isEditing" class="mr-2 inline h-4 w-4" />
                        {{
                            isEditing
                                ? 'Simpan Perubahan'
                                : 'Tayangkan Lowongan Sekarang'
                        }}
                    </button>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="sticky top-10 space-y-6">
                    <div
                        class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm"
                    >
                        <div
                            class="mb-6 flex items-center gap-2 text-slate-400"
                        >
                            <Eye class="h-4 w-4" />
                            <span
                                class="text-[10px] font-black uppercase italic"
                                >Live Preview</span
                            >
                        </div>
                        <div
                            class="rounded-2xl border border-slate-100 bg-slate-50 p-6"
                        >
                            <div
                                class="mb-4 flex h-14 w-14 items-center justify-center overflow-hidden rounded-xl border bg-white shadow-sm"
                            >
                                <img
                                    v-if="auth.user.mitra.logo_mitra"
                                    :src="`/storage/${auth.user.mitra.logo_mitra}`"
                                    class="h-full w-full object-cover"
                                />
                                <span
                                    v-else
                                    class="text-xl font-black text-sky-700 italic"
                                    >{{
                                        auth.user.mitra.nama_mitra.charAt(0)
                                    }}</span
                                >
                            </div>
                            <h4
                                class="text-sm leading-tight font-black text-slate-900 uppercase"
                            >
                                {{ form.judul_lowongan || 'Judul Lowongan' }}
                            </h4>
                            <p
                                class="mb-3 text-[9px] font-bold text-slate-400 uppercase italic"
                            >
                                {{ auth.user.mitra.nama_mitra }}
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    class="rounded bg-sky-100 px-2 py-0.5 text-[8px] font-black text-sky-700 uppercase italic"
                                    >{{ form.tipe_pekerjaan }}</span
                                >
                                <span
                                    class="text-[8px] font-black text-slate-400 uppercase italic"
                                    ><MapPin class="inline h-2 w-2" />
                                    {{ selectedLocationName }}</span
                                >
                            </div>
                            <div
                                v-if="form.deskripsi_lowongan"
                                class="mt-4 border-t border-slate-200 pt-4"
                            >
                                <p
                                    class="line-clamp-3 text-[9px] leading-relaxed font-bold text-slate-500 italic"
                                >
                                    "{{ form.deskripsi_lowongan }}"
                                </p>
                            </div>
                            <div
                                class="mt-4 border-t border-dashed border-slate-200 pt-4"
                            >
                                <p
                                    class="text-[10px] font-black text-emerald-600 uppercase"
                                >
                                    {{ formattedSalaryPreview }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6 pt-10">
            <div class="flex items-center justify-between">
                <h2
                    class="text-2xl font-black tracking-tighter text-slate-900 uppercase italic"
                >
                    RIWAYAT <span class="text-sky-700">LOWONGAN</span>
                </h2>
                <span
                    class="rounded-lg bg-slate-100 px-3 py-1 text-[10px] font-black text-slate-400 uppercase italic"
                    >Total: {{ myJobs.length }}</span
                >
            </div>

            <div
                class="rounded-[2.5rem] border border-slate-200 bg-white p-4 shadow-sm lg:p-8"
            >
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr
                                class="border-b border-slate-100 text-left text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                            >
                                <th class="px-4 pb-4">Info Lowongan</th>
                                <th class="px-4 pb-4">Tipe</th>
                                <th class="px-4 pb-4">Status</th>
                                <th class="px-4 pb-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-if="myJobs.length === 0">
                                <td
                                    colspan="4"
                                    class="py-20 text-center text-[10px] font-black text-slate-300 uppercase italic"
                                >
                                    Belum ada lowongan terdaftar
                                </td>
                            </tr>
                            <tr
                                v-for="job in myJobs"
                                :key="job.id"
                                class="group transition-all hover:bg-slate-50"
                            >
                                <td class="px-4 py-5">
                                    <p
                                        class="text-xs leading-none font-black text-slate-900 uppercase"
                                    >
                                        {{ job.judul_lowongan }}
                                    </p>
                                    <p
                                        class="mt-1 text-[9px] font-bold text-slate-400 italic"
                                    >
                                        {{ job.lokasi?.nama_lokasi }}
                                    </p>
                                </td>
                                <td class="px-4 py-5">
                                    <span
                                        class="rounded-lg bg-slate-100 px-2 py-1 text-[9px] font-black text-slate-500 uppercase"
                                        >{{ job.tipe_pekerjaan }}</span
                                    >
                                </td>
                                <td class="px-4 py-5">
                                    <span
                                        :class="{
                                            'border-amber-200 bg-amber-100 text-amber-700':
                                                job.status_lowongan ===
                                                'pending',
                                            'border-emerald-200 bg-emerald-100 text-emerald-700':
                                                job.status_lowongan ===
                                                'verified',
                                            'border-rose-200 bg-rose-100 text-rose-700':
                                                job.status_lowongan ===
                                                'rejected',
                                        }"
                                        class="rounded-full border px-3 py-1 text-[8px] font-black uppercase italic"
                                    >
                                        {{ job.status_lowongan }}
                                    </span>
                                </td>
                                <td class="space-x-1 px-4 py-5 text-right">
                                    <button
                                        @click="openDetail(job)"
                                        class="p-2 text-slate-400 transition-all hover:text-sky-700"
                                        title="Detail"
                                    >
                                        <Eye class="h-4 w-4" />
                                    </button>
                                    <button
                                        @click="editJob(job)"
                                        class="p-2 text-slate-400 transition-all hover:text-amber-600"
                                        title="Edit"
                                    >
                                        <Edit3 class="h-4 w-4" />
                                    </button>
                                    <button
                                        @click="deleteJob(job.id)"
                                        class="p-2 text-slate-400 transition-all hover:text-rose-600"
                                        title="Hapus"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-100 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
        >
            <div
                class="relative w-full max-w-xl animate-in rounded-[3rem] bg-white p-10 shadow-2xl transition-all duration-200 zoom-in-95"
            >
                <button
                    @click="isModalOpen = false"
                    class="absolute top-8 right-8 text-slate-400 transition-all hover:text-rose-500"
                >
                    <X />
                </button>
                <div v-if="selectedJob" class="space-y-6 text-lokak-text">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-2xl border border-sky-100 bg-sky-50 text-xl font-black text-sky-700 italic"
                        >
                            {{ auth.user.mitra.nama_mitra.charAt(0) }}
                        </div>
                        <div>
                            <h2
                                class="text-2xl leading-tight font-black tracking-tighter text-slate-900 uppercase italic"
                            >
                                {{ selectedJob.judul_lowongan }}
                            </h2>
                            <p
                                class="text-[10px] font-bold text-sky-700 uppercase italic"
                            >
                                {{ auth.user.mitra.nama_mitra }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="grid grid-cols-2 gap-4 rounded-3xl border border-slate-100 bg-slate-50 p-6"
                    >
                        <div class="space-y-1">
                            <p
                                class="text-[9px] font-black text-slate-400 uppercase"
                            >
                                Estimasi Gaji
                            </p>
                            <p class="text-xs font-bold text-emerald-600">
                                Rp {{ selectedJob.gaji_min.toLocaleString() }} -
                                {{ selectedJob.gaji_max.toLocaleString() }}
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p
                                class="text-[9px] font-black text-slate-400 uppercase"
                            >
                                Penempatan
                            </p>
                            <p class="text-xs font-bold text-slate-700">
                                {{ selectedJob.lokasi?.nama_lokasi }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <h4
                            class="flex items-center gap-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                        >
                            <AlignLeft class="h-3 w-3" /> Deskripsi & Syarat
                        </h4>
                        <div
                            class="max-h-60 overflow-y-auto rounded-3xl border border-slate-100 bg-slate-50/30 p-6"
                        >
                            <p
                                class="text-[11px] leading-relaxed font-medium whitespace-pre-line text-slate-600 italic"
                            >
                                {{ selectedJob.deskripsi_lowongan }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-between border-t border-slate-100 pt-4"
                    >
                        <div
                            class="flex items-center gap-2 text-[9px] font-bold text-slate-400 italic"
                        >
                            <Clock class="h-3 w-3" /> Diposting:
                            {{
                                new Date(
                                    selectedJob.created_at,
                                ).toLocaleDateString('id-ID')
                            }}
                        </div>
                        <span
                            class="text-[9px] font-black text-slate-300 uppercase italic"
                            >ID: {{ selectedJob.id.split('-')[0] }}</span
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Hilangkan panah input number */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-clamp: 3; /* Add this line */
}
</style>
