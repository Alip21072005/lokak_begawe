<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import {
    Eye,
    MapPin,
    Trash2,
    Edit3,
    X,
    ArrowRight,
    Clock,
    AlertTriangle
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

// --- LOGIKA CEK EXPIRED ---
const isExpired = (date: string) => {
    return new Date(date) < new Date();
};

// --- LOGIKA SUBMIT ---
const submit = () => {
    const routeFunc = (window as any).route || (window as any).Ziggy?.route;

    if (!routeFunc) {
return;
}

    if (isEditing.value && editId.value) {
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
        form.post(routeFunc('mitra.pasanglowongan.store'), {
            preserveScroll: true,
            onBefore: () => {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang menyimpan lowongan Anda',
                    allowOutsideClick: false,
                    didOpen: () => {
 Swal.showLoading(); 
},
                    customClass: { popup: 'rounded-[2.5rem]' },
                });
            },
            onSuccess: () => {
                resetForm();
                Swal.close();
            },
            onError: () => {
 Swal.close(); 
}
        });
    }
};

// --- FITUR AKSI TABEL ---
const editJob = (job: any) => {
    isEditing.value = true;
    editId.value = job.id;
    form.judul_lowongan = job.judul_lowongan;
    form.tipe_pekerjaan = job.tipe_pekerjaan;
    form.gaji_min = job.gaji_min;
    form.gaji_max = job.gaji_max;
    form.lokasi_id = job.lokasi_id;
    form.deskripsi_lowongan = job.deskripsi_lowongan;
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

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
            router.delete((window as any).route('mitra.pasanglowongan.destroy', id), {
                onSuccess: () => Swal.fire({ title: 'Terhapus!', icon: 'success' }),
            });
        }
    });
};

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

    const min = form.gaji_min ? new Intl.NumberFormat('id-ID').format(Number(form.gaji_min)) : '0';
    const max = form.gaji_max ? new Intl.NumberFormat('id-ID').format(Number(form.gaji_max)) : '0';

    return `Rp ${min} - ${max}`;
});
</script>

<template>
    <Head title="Manajemen Lowongan - Lokak Begawe" />

    <div class="space-y-12 p-6 lg:p-10">
        <div class="flex flex-col gap-2">
            <h1 class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl">
                {{ isEditing ? 'EDIT' : 'PASANG' }}
                <span class="text-sky-700">LOWONGAN</span>
            </h1>
            <div class="h-1.5 w-24 rounded-full bg-slate-200"></div>
            <p class="mt-2 text-[11px] font-bold tracking-widest text-slate-400 uppercase italic leading-relaxed">
                Pasang iklan lowongan kerja Anda dan temukan talenta terbaik Bengkulu.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="space-y-8 rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm lg:col-span-8">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase italic">Judul Posisi</label>
                        <input v-model="form.judul_lowongan" type="text" placeholder="Contoh: Admin Gudang" class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20" />
                    </div>
                    <div class="space-y-2">
                        <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase italic">Tipe Kerja</label>
                        <select v-model="form.tipe_pekerjaan" class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-xs font-bold outline-none">
                            <option>Full-time</option>
                            <option>Part-time</option>
                            <option>Freelance</option>
                            <option>Internship</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase italic">Gaji Minimal (Rp)</label>
                        <input v-model="form.gaji_min" type="number" placeholder="3000000" class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-xs font-bold outline-none" />
                    </div>
                    <div class="space-y-2">
                        <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase italic">Gaji Maksimal (Rp)</label>
                        <input v-model="form.gaji_max" type="number" placeholder="5000000" class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-xs font-bold outline-none" />
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase italic">Wilayah Penempatan</label>
                    <select v-model="form.lokasi_id" class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-xs font-bold outline-none">
                        <option value="" disabled>Pilih Kabupaten/Kota...</option>
                        <option v-for="loc in lokasis" :key="loc.id" :value="loc.id">{{ loc.nama_lokasi }}</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase italic">Deskripsi & Syarat Pekerjaan</label>
                    <textarea v-model="form.deskripsi_lowongan" rows="5" class="w-full rounded-3xl border-slate-200 bg-slate-50 p-6 text-xs font-bold outline-none" placeholder="Tuliskan syarat, tanggung jawab, dan kriteria pelamar..."></textarea>
                </div>

                <div class="flex gap-4">
                    <button v-if="isEditing" @click="resetForm" class="w-1/3 rounded-2xl bg-slate-100 py-5 text-xs font-black text-slate-400 uppercase italic transition-all hover:bg-slate-200">
                        Batal
                    </button>
                    <button @click="submit" :disabled="form.processing" class="flex-1 rounded-2xl bg-slate-900 py-5 text-sm font-black text-white uppercase italic shadow-xl transition-all hover:bg-sky-700 disabled:opacity-50">
                        <span v-if="isEditing">Simpan Perubahan</span>
                        <span v-else class="flex items-center justify-center gap-2">
                            Lanjut ke Pilih Paket <ArrowRight class="h-4 w-4" />
                        </span>
                    </button>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="sticky top-10 space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-6 flex items-center gap-2 text-slate-400 font-black text-[10px] uppercase italic">
                            <Eye class="h-4 w-4" /> Live Preview
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-6">
                            <div class="mb-4 flex h-14 w-14 items-center justify-center overflow-hidden rounded-xl border bg-white shadow-sm font-black text-sky-700 italic">
                                <img v-if="auth.user.mitra?.logo_mitra" :src="`/storage/${auth.user.mitra.logo_mitra}`" class="h-full w-full object-cover" />
                                <span v-else>{{ auth.user.mitra?.nama_mitra.charAt(0) }}</span>
                            </div>
                            <h4 class="text-sm font-black text-slate-900 uppercase leading-tight">{{ form.judul_lowongan || 'Judul Posisi' }}</h4>
                            <p class="mb-3 text-[9px] font-bold text-slate-400 uppercase italic">{{ auth.user.mitra?.nama_mitra }}</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="rounded bg-sky-100 px-2 py-0.5 text-[8px] font-black text-sky-700 uppercase italic">{{ form.tipe_pekerjaan }}</span>
                                <span class="text-[8px] font-black text-slate-400 uppercase italic"><MapPin class="inline h-2 w-2" /> {{ selectedLocationName }}</span>
                            </div>
                            <div v-if="form.deskripsi_lowongan" class="mt-4 border-t border-slate-200 pt-4">
                                <p class="line-clamp-3 text-[9px] font-bold text-slate-500 italic leading-relaxed">"{{ form.deskripsi_lowongan }}"</p>
                            </div>
                            <div class="mt-4 border-t border-dashed border-slate-200 pt-4 font-black text-[10px] text-emerald-600 uppercase italic tracking-tighter">
                                {{ formattedSalaryPreview }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6 pt-10">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-black tracking-tighter text-slate-900 uppercase italic">RIWAYAT <span class="text-sky-700">LOWONGAN</span></h2>
                <span class="rounded-lg bg-slate-100 px-3 py-1 text-[10px] font-black text-slate-400 uppercase italic border border-slate-200">Total: {{ myJobs.length }}</span>
            </div>

            <div class="rounded-[2.5rem] border border-slate-200 bg-white p-4 shadow-sm lg:p-8">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-100 text-left text-[10px] font-black text-slate-400 uppercase italic tracking-widest">
                                <th class="px-4 pb-4">Info Lowongan</th>
                                <th class="px-4 pb-4">Tipe</th>
                                <th class="px-4 pb-4">Status</th>
                                <th class="px-4 pb-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-if="myJobs.length === 0">
                                <td colspan="4" class="py-20 text-center text-[10px] font-black text-slate-300 uppercase italic">Belum ada lowongan terdaftar</td>
                            </tr>
                            <tr v-for="job in myJobs" :key="job.id" class="group hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-5">
                                    <p class="text-xs font-black text-slate-900 uppercase leading-none">{{ job.judul_lowongan }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 italic mt-1">{{ job.lokasi?.nama_lokasi }}</p>
                                </td>
                                <td class="px-4 py-5">
                                    <span class="rounded-lg bg-slate-100 px-2 py-1 text-[9px] font-black text-slate-500 uppercase italic">{{ job.tipe_pekerjaan }}</span>
                                </td>
                                <td class="px-4 py-5">
                                    <div class="flex items-center gap-2">
                                        <span v-if="isExpired(job.tanggal_expired)" 
                                            class="rounded-full border border-slate-200 bg-slate-100 px-3 py-1 text-[8px] font-black uppercase italic text-slate-400 flex items-center gap-1">
                                            <Clock class="h-2 w-2" /> Expired
                                        </span>
                                        <span v-else :class="{
                                            'bg-amber-100 text-amber-700 border-amber-200': job.status_lowongan === 'pending',
                                            'bg-emerald-100 text-emerald-700 border-emerald-200': job.status_lowongan === 'verified',
                                            'bg-rose-100 text-rose-700 border-rose-200': job.status_lowongan === 'rejected',
                                        }" class="rounded-full border px-3 py-1 text-[8px] font-black uppercase italic">
                                            {{ job.status_lowongan }}
                                        </span>
                                    </div>
                                </td>
                                <td class="space-x-1 px-4 py-5 text-right">
                                    <button @click="openDetail(job)" class="p-2 text-slate-400 hover:text-sky-700 transition-colors"><Eye class="h-4 w-4" /></button>
                                    <button @click="editJob(job)" class="p-2 text-slate-400 hover:text-amber-600 transition-colors"><Edit3 class="h-4 w-4" /></button>
                                    <button @click="deleteJob(job.id)" class="p-2 text-slate-400 hover:text-rose-600 transition-colors"><Trash2 class="h-4 w-4" /></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm">
             <div class="relative w-full max-w-xl animate-in rounded-[3rem] bg-white p-10 shadow-2xl transition-all duration-200 zoom-in-95">
                <button @click="isModalOpen = false" class="absolute top-8 right-8 text-slate-400 hover:text-rose-500"><X /></button>
                <div v-if="selectedJob" class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-sky-100 bg-sky-50 text-xl font-black text-sky-700 italic">
                            {{ auth.user.mitra?.nama_mitra.charAt(0) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-black tracking-tighter text-slate-900 uppercase italic leading-none">{{ selectedJob.judul_lowongan }}</h2>
                            <p class="text-[10px] font-bold text-sky-700 uppercase italic mt-1">{{ auth.user.mitra?.nama_mitra }}</p>
                        </div>
                    </div>
                    <div v-if="isExpired(selectedJob.tanggal_expired)" class="bg-rose-50 border border-rose-100 rounded-2xl p-4 flex items-center gap-3 text-rose-700">
                        <AlertTriangle class="h-5 w-5" />
                        <span class="text-[10px] font-black uppercase italic">Lowongan ini sudah kedaluwarsa dan tidak tampil di halaman pencarian.</span>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-6 border border-slate-100">
                        <p class="text-[11px] font-medium leading-relaxed italic text-slate-600 whitespace-pre-line">{{ selectedJob.deskripsi_lowongan }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.line-clamp-3 {
    display: -webkit-box;
    line-clamp: 3;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>