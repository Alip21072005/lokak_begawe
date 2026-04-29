<!-- eslint-disable @typescript-eslint/no-unused-vars -->
<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import {
    Eye,
    MapPin,
    X,
    Wrench,
    Save,
    Info,
    Search,
    FileText,
    Edit3,
    Trash2,
    Clock,
    Banknote,
    GraduationCap,
    Briefcase,
    Sparkles,
    BadgeInfo,
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    auth: any;
    lokasis: any[];
    myJobs: any[];
    masterSkills: any[];
}>();

defineOptions({ layout: AppLayout });

const isEditing = ref(false);
const editId = ref<string | null>(null);
const skillSearch = ref('');
const isPreviewModalOpen = ref(false);
const selectedJob = ref<any>(null);

const form = useForm({
    judul_lowongan: '',
    tipe_pekerjaan: 'Full-time',
    gaji_min: '',
    gaji_max: '',
    lokasi_id: '',
    deskripsi_lowongan: '',
    minimal_pendidikan: '',
    minimal_pengalaman: 0,
    required_skills: [] as string[],
});

// -------------------- HELPERS --------------------
const onlyDigits = (v: string | number | null | undefined) => String(v ?? '').replace(/[^\d]/g, '');

const formatWithDots = (v: string | number | null | undefined) => {
    const raw = onlyDigits(v);
    if (!raw) return '';
    return new Intl.NumberFormat('id-ID').format(Number(raw));
};

const parseToInt = (v: string | number | null | undefined) => {
    const raw = onlyDigits(v);
    return raw ? Number(raw) : 0;
};

const allowNumericOnly = (e: KeyboardEvent) => {
    const allowedKeys = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Home', 'End'];
    if (allowedKeys.includes(e.key)) return;
    if (!/^\d$/.test(e.key)) e.preventDefault();
};

const handleSalaryInput = (field: 'gaji_min' | 'gaji_max', value: string) => {
    form[field] = formatWithDots(value) as any;
};

const handleSalaryPaste = (e: ClipboardEvent, field: 'gaji_min' | 'gaji_max') => {
    e.preventDefault();
    const text = e.clipboardData?.getData('text') ?? '';
    form[field] = formatWithDots(text) as any;
};

const handleExpInput = (value: string | number) => {
    const n = Number(String(value).replace(/[^\d-]/g, ''));
    if (Number.isNaN(n) || n < 0) form.minimal_pengalaman = 0;
    else form.minimal_pengalaman = Math.min(n, 50);
};

const validateSalary = () => {
    const min = parseToInt(form.gaji_min);
    const max = parseToInt(form.gaji_max);

    if (min > 0 && max > 0 && min > max) {
        Swal.fire({
            title: 'Rentang gaji tidak valid',
            text: 'Gaji minimum tidak boleh lebih besar dari gaji maksimum.',
            icon: 'warning',
            confirmButtonColor: '#0369a1',
            customClass: { popup: 'rounded-[2rem]' },
        });
        return false;
    }
    return true;
};

const formatCurrency = (value: any) => {
    if (value === null || value === undefined || value === '') return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(Number(value));
};

// -------------------- SKILL LOGIC --------------------
const filteredSkills = computed(() => {
    return props.masterSkills.filter(
        (skill) =>
            skill.nama_skill.toLowerCase().includes(skillSearch.value.toLowerCase()) &&
            !form.required_skills.includes(skill.id),
    );
});

const getSkillName = (id: string) => props.masterSkills.find((s) => s.id === id)?.nama_skill || 'Unknown';

const toggleSkill = (skillId: string) => {
    const idx = form.required_skills.indexOf(skillId);
    if (idx === -1) form.required_skills.push(skillId);
    else form.required_skills.splice(idx, 1);
};

// -------------------- DETAIL / PREVIEW --------------------
const handleViewDetail = (job: any) => {
    if (job.status_lowongan === 'verified') {
        router.visit(route('detail.lowongan', job.id));
    } else {
        selectedJob.value = job;
        isPreviewModalOpen.value = true;
    }
};

// -------------------- CRUD --------------------
const submit = () => {
    if (!validateSalary()) return;

    const payloadTransformer = (data: any) => ({
        ...data,
        gaji_min: parseToInt(data.gaji_min),
        gaji_max: parseToInt(data.gaji_max),
        minimal_pengalaman: Math.max(0, Number(data.minimal_pengalaman || 0)),
    });

    if (isEditing.value && editId.value) {
        form
            .transform(payloadTransformer)
            .put((window as any).route('mitra.pasanglowongan.update', editId.value), {
                onSuccess: () => {
                    resetForm();
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Lowongan diperbarui dan kembali antre verifikasi.',
                        icon: 'success',
                        timer: 1800,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-[2rem]' },
                    });
                },
                onFinish: () => form.transform((d) => d),
            });
    } else {
        form
            .transform(payloadTransformer)
            .post((window as any).route('mitra.pasanglowongan.store'), {
                onSuccess: () => {
                    resetForm();
                    Swal.fire({
                        title: 'Sukses Terbit!',
                        text: 'Lowongan berhasil dibuat. Lanjut ke pemilihan paket.',
                        icon: 'success',
                        timer: 1800,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-[2rem]' },
                    });
                },
                onFinish: () => form.transform((d) => d),
            });
    }
};

const editJob = (job: any) => {
    isEditing.value = true;
    editId.value = job.id;
    form.judul_lowongan = job.judul_lowongan;
    form.tipe_pekerjaan = job.tipe_pekerjaan;
    form.gaji_min = formatWithDots(job.gaji_min);
    form.gaji_max = formatWithDots(job.gaji_max);
    form.lokasi_id = job.lokasi_id;
    form.deskripsi_lowongan = job.deskripsi_lowongan;
    form.minimal_pendidikan = job.minimal_pendidikan || '';
    form.minimal_pengalaman = Math.max(0, Number(job.minimal_pengalaman || 0));
    form.required_skills = job.skills ? job.skills.map((s: any) => s.id) : [];
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const deleteJob = (id: string) => {
    Swal.fire({
        title: 'Hapus lowongan?',
        text: 'Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0369a1',
        cancelButtonColor: '#f43f5e',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-[2rem]' },
    }).then((res) => {
        if (res.isConfirmed) {
            router.delete((window as any).route('mitra.pasanglowongan.destroy', id), {
                onSuccess: () => {
                    Swal.fire({
                        title: 'Terhapus',
                        icon: 'success',
                        timer: 1000,
                        showConfirmButton: false,
                    });
                },
            });
        }
    });
};

const resetForm = () => {
    form.reset();
    form.tipe_pekerjaan = 'Full-time';
    form.minimal_pengalaman = 0;
    isEditing.value = false;
    editId.value = null;
};

const formattedSalaryPreview = computed(() => {
    const min = parseToInt(form.gaji_min);
    const max = parseToInt(form.gaji_max);
    if (!min && !max) return 'Rp -';
    return `Rp ${new Intl.NumberFormat('id-ID').format(min)} - ${new Intl.NumberFormat('id-ID').format(max)}`;
});

const stats = computed(() => ({
    total: props.myJobs.length,
    verified: props.myJobs.filter((j: any) => j.status_lowongan === 'verified').length,
    pending: props.myJobs.filter((j: any) => j.status_lowongan === 'pending').length,
    rejected: props.myJobs.filter((j: any) => j.status_lowongan === 'rejected').length,
}));
</script>

<template>
    <Head title="Pasang Lowongan - Lokak Begawe" />

    <div class="space-y-10 p-4 md:p-6 lg:p-10">
        <!-- Header -->
        <section class="rounded-[2.5rem] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl">
                        {{ isEditing ? 'REVISI' : 'PASANG' }} <span class="text-sky-700">LOWONGAN</span>
                    </h1>
                    <p class="mt-2 inline-flex items-center gap-2 text-[10px] font-black uppercase italic tracking-[0.16em] text-slate-400">
                        <Sparkles class="h-3.5 w-3.5 text-sky-700" /> cepat, rapi, dan siap verifikasi
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-center">
                        <p class="text-[9px] font-black uppercase text-slate-400">Total</p>
                        <p class="text-lg font-black text-slate-900">{{ stats.total }}</p>
                    </div>
                    <div class="rounded-xl border border-emerald-100 bg-emerald-50/60 px-3 py-2 text-center">
                        <p class="text-[9px] font-black uppercase text-emerald-700">Verified</p>
                        <p class="text-lg font-black text-emerald-800">{{ stats.verified }}</p>
                    </div>
                    <div class="rounded-xl border border-amber-100 bg-amber-50/60 px-3 py-2 text-center">
                        <p class="text-[9px] font-black uppercase text-amber-700">Pending</p>
                        <p class="text-lg font-black text-amber-800">{{ stats.pending }}</p>
                    </div>
                    <div class="rounded-xl border border-rose-100 bg-rose-50/60 px-3 py-2 text-center">
                        <p class="text-[9px] font-black uppercase text-rose-700">Rejected</p>
                        <p class="text-lg font-black text-rose-800">{{ stats.rejected }}</p>
                    </div>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="space-y-8 lg:col-span-8">
                <!-- Informasi utama -->
                <section class="rounded-[2.5rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    <h3 class="mb-6 flex items-center gap-3 text-[11px] font-black uppercase italic tracking-widest text-slate-400">
                        <Info class="h-4 w-4 text-sky-700" /> Informasi Utama
                    </h3>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div class="space-y-2 md:col-span-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Judul Lowongan</label>
                            <input
                                v-model="form.judul_lowongan"
                                type="text"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none transition focus:border-sky-300 focus:ring-4 focus:ring-sky-700/10"
                                placeholder="Senior Backend Developer"
                            />
                        </div>

                        <div class="space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Tipe Kontrak</label>
                            <select
                                v-model="form.tipe_pekerjaan"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none transition focus:border-sky-300 focus:ring-4 focus:ring-sky-700/10"
                            >
                                <option>Full-time</option>
                                <option>Part-time</option>
                                <option>Internship</option>
                                <option>Freelance</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Wilayah Bengkulu</label>
                            <select
                                v-model="form.lokasi_id"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none transition focus:border-sky-300 focus:ring-4 focus:ring-sky-700/10"
                            >
                                <option value="" disabled>Pilih Kabupaten/Kota...</option>
                                <option v-for="loc in lokasis" :key="loc.id" :value="loc.id">{{ loc.nama_lokasi }}</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Gaji Minimum</label>
                            <input
                                :value="form.gaji_min"
                                type="text"
                                inputmode="numeric"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none transition focus:border-sky-300 focus:ring-4 focus:ring-sky-700/10"
                                placeholder="3.000.000"
                                @keydown="allowNumericOnly"
                                @paste="handleSalaryPaste($event, 'gaji_min')"
                                @input="handleSalaryInput('gaji_min', ($event.target as HTMLInputElement).value)"
                            />
                        </div>

                        <div class="space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Gaji Maksimum</label>
                            <input
                                :value="form.gaji_max"
                                type="text"
                                inputmode="numeric"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none transition focus:border-sky-300 focus:ring-4 focus:ring-sky-700/10"
                                placeholder="7.000.000"
                                @keydown="allowNumericOnly"
                                @paste="handleSalaryPaste($event, 'gaji_max')"
                                @input="handleSalaryInput('gaji_max', ($event.target as HTMLInputElement).value)"
                            />
                        </div>
                    </div>
                </section>

                <!-- Kualifikasi -->
                <section class="rounded-[2.5rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    <h3 class="mb-6 flex items-center gap-3 text-[11px] font-black uppercase italic tracking-widest text-slate-400">
                        <Wrench class="h-4 w-4 text-sky-700" /> Persyaratan Kualifikasi
                    </h3>

                    <div class="mb-6 grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Pendidikan Minimal</label>
                            <select
                                v-model="form.minimal_pendidikan"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none transition focus:border-sky-300 focus:ring-4 focus:ring-sky-700/10"
                            >
                                <option value="">Semua Jenjang</option>
                                <option value="SMA/SMK">SMA/SMK Sederajat</option>
                                <option value="D3">Diploma (D3)</option>
                                <option value="S1">Sarjana (S1)</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Pengalaman (Tahun)</label>
                            <input
                                :value="form.minimal_pengalaman"
                                type="number"
                                min="0"
                                step="1"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none transition focus:border-sky-300 focus:ring-4 focus:ring-sky-700/10"
                                placeholder="0"
                                @input="handleExpInput(($event.target as HTMLInputElement).value)"
                            />
                            <p class="text-[10px] font-bold text-slate-400 italic">Nilai tidak bisa minus.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="relative">
                            <Search class="absolute left-4 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" />
                            <input
                                v-model="skillSearch"
                                type="text"
                                placeholder="Cari keahlian spesifik..."
                                class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-[11px] font-bold italic outline-none transition focus:border-sky-300 focus:ring-4 focus:ring-sky-700/10"
                            />
                        </div>

                        <div class="min-h-10 rounded-xl border border-slate-100 bg-slate-50 p-3">
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="sId in form.required_skills"
                                    :key="sId"
                                    class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-3 py-1.5 text-[9px] font-black uppercase italic text-white"
                                >
                                    {{ getSkillName(sId) }}
                                    <button type="button" class="hover:text-rose-300" @click="toggleSkill(sId)">
                                        <X class="h-3 w-3" />
                                    </button>
                                </span>
                                <span v-if="form.required_skills.length === 0" class="text-[10px] font-bold text-slate-400 italic">
                                    Belum ada skill dipilih.
                                </span>
                            </div>
                        </div>

                        <div class="max-h-36 overflow-y-auto rounded-2xl border border-slate-100 p-4">
                            <div class="flex flex-wrap gap-2">
                                <button
                                    v-for="skill in filteredSkills"
                                    :key="skill.id"
                                    type="button"
                                    class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[9px] font-black uppercase text-slate-500 transition hover:border-sky-600 hover:text-sky-700"
                                    @click="toggleSkill(skill.id)"
                                >
                                    + {{ skill.nama_skill }}
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Deskripsi -->
                <section class="rounded-[2.5rem] border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                    <h3 class="mb-6 flex items-center gap-3 text-[11px] font-black uppercase italic tracking-widest text-slate-400">
                        <FileText class="h-4 w-4 text-sky-700" /> Deskripsi Pekerjaan
                    </h3>

                    <textarea
                        v-model="form.deskripsi_lowongan"
                        rows="7"
                        class="w-full rounded-3xl border border-slate-200 bg-slate-50 p-5 text-xs font-bold italic outline-none transition focus:border-sky-300 focus:ring-4 focus:ring-sky-700/10"
                        placeholder="Jelaskan detail pekerjaan, tanggung jawab, benefit, dan budaya kerja..."
                    />

                    <div class="mt-8 flex flex-wrap justify-end gap-3">
                        <button
                            v-if="isEditing"
                            type="button"
                            class="rounded-2xl bg-slate-100 px-7 py-3 text-xs font-black uppercase italic text-slate-500 transition hover:bg-slate-200"
                            @click="resetForm"
                        >
                            Batalkan
                        </button>
                        <button
                            type="button"
                            :disabled="form.processing"
                            class="inline-flex items-center gap-2 rounded-2xl bg-sky-700 px-8 py-3.5 text-xs font-black uppercase italic text-white shadow-lg shadow-sky-900/20 transition hover:bg-sky-800 disabled:opacity-60"
                            @click="submit"
                        >
                            <Save class="h-4 w-4" />
                            {{ isEditing ? 'Simpan Perubahan' : 'Terbitkan Sekarang' }}
                        </button>
                    </div>
                </section>
            </div>

            <!-- Sidebar preview -->
            <div class="lg:col-span-4">
                <div class="sticky top-6 space-y-5">
                    <section class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-7 text-white shadow-2xl">
                        <div class="absolute -right-10 -top-10 h-28 w-28 rounded-full bg-sky-500/20 blur-2xl" />
                        <div class="mb-5 inline-flex items-center gap-2 text-[10px] font-black uppercase italic tracking-widest text-sky-300">
                            <Eye class="h-4 w-4" /> Live Preview
                        </div>

                        <div class="space-y-5">
                            <div>
                                <h4 class="text-xl font-black uppercase italic leading-tight tracking-tighter">
                                    {{ form.judul_lowongan || 'JUDUL POSISI' }}
                                </h4>
                                <p class="mt-1 text-[10px] font-bold uppercase italic text-slate-400">
                                    <MapPin class="mr-1 inline h-3 w-3" />
                                    {{ props.lokasis.find((l) => l.id === form.lokasi_id)?.nama_lokasi || 'Bengkulu' }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <span class="rounded-lg bg-sky-700 px-3 py-1 text-[8px] font-black uppercase italic">
                                    {{ form.tipe_pekerjaan }}
                                </span>
                                <span class="rounded-lg border border-slate-700 bg-slate-800 px-3 py-1 text-[8px] font-black uppercase italic text-slate-300">
                                    {{ form.minimal_pendidikan || 'Pendidikan Bebas' }}
                                </span>
                            </div>

                            <div class="rounded-2xl border border-slate-700 bg-slate-800/70 p-4 text-center">
                                <p class="text-[10px] font-black uppercase italic tracking-widest text-slate-400">Rentang Gaji</p>
                                <p class="mt-1 text-base font-black italic tracking-tight text-emerald-400">
                                    {{ formattedSalaryPreview }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-slate-700 bg-slate-800/70 p-4">
                                <p class="text-[10px] font-black uppercase italic tracking-widest text-slate-400">Pengalaman Minimal</p>
                                <p class="mt-1 inline-flex items-center gap-2 text-xs font-black uppercase italic text-white">
                                    <Briefcase class="h-3.5 w-3.5 text-sky-300" />
                                    {{ form.minimal_pengalaman }} Tahun
                                </p>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="inline-flex items-start gap-2 text-[10px] font-bold italic text-slate-500">
                            <BadgeInfo class="mt-0.5 h-4 w-4 text-sky-700" />
                            Setelah terbit, lowongan masuk antrean verifikasi admin sebelum tayang.
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <!-- Riwayat -->
        <section class="space-y-6 border-t border-slate-100 pt-10">
            <h2 class="text-2xl font-black tracking-tighter text-slate-900 uppercase italic">
                RIWAYAT <span class="text-sky-700">PUBLIKASI</span>
            </h2>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                <article
                    v-for="job in myJobs"
                    :key="job.id"
                    class="group flex flex-col justify-between rounded-[2.2rem] border border-slate-200 bg-white p-6 transition hover:border-sky-300 hover:shadow-lg"
                >
                    <div class="space-y-4">
                        <div class="flex items-start justify-between">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-100 bg-slate-50 text-lg font-black italic text-sky-700">
                                {{ job.judul_lowongan?.charAt(0) }}
                            </div>
                            <span
                                :class="{
                                    'bg-emerald-50 text-emerald-600 border-emerald-100': job.status_lowongan === 'verified',
                                    'bg-amber-50 text-amber-600 border-amber-100': job.status_lowongan === 'pending',
                                    'bg-rose-50 text-rose-600 border-rose-100': job.status_lowongan === 'rejected',
                                }"
                                class="rounded-full border px-3 py-1 text-[8px] font-black uppercase italic"
                            >
                                {{ job.status_lowongan }}
                            </span>
                        </div>

                        <div>
                            <h4 class="text-lg font-black uppercase italic leading-tight text-slate-900 transition group-hover:text-sky-700">
                                {{ job.judul_lowongan }}
                            </h4>
                            <div class="mt-2 flex flex-wrap items-center gap-3 text-[9px] font-bold uppercase italic text-slate-400">
                                <span><MapPin class="mr-1 inline h-2.5 w-2.5" /> {{ job.lokasi?.nama_lokasi }}</span>
                                <span><Clock class="mr-1 inline h-2.5 w-2.5" /> {{ job.tipe_pekerjaan }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center gap-2 border-t border-slate-50 pt-4">
                        <button
                            type="button"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-slate-900 py-2.5 text-[10px] font-black uppercase italic text-white transition hover:bg-sky-700"
                            @click="handleViewDetail(job)"
                        >
                            <Eye class="h-3.5 w-3.5" /> Lihat Detail
                        </button>
                        <button
                            type="button"
                            class="rounded-xl border border-slate-100 bg-white p-2.5 text-slate-400 transition hover:border-amber-200 hover:text-amber-600"
                            @click="editJob(job)"
                        >
                            <Edit3 class="h-4 w-4" />
                        </button>
                        <button
                            type="button"
                            class="rounded-xl border border-slate-100 bg-white p-2.5 text-slate-400 transition hover:border-rose-200 hover:text-rose-600"
                            @click="deleteJob(job.id)"
                        >
                            <Trash2 class="h-4 w-4" />
                        </button>
                    </div>
                </article>
            </div>
        </section>

        <!-- Modal pratinjau internal -->
        <Teleport to="body">
            <Transition name="fade">
                <div
                    v-if="isPreviewModalOpen"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-md"
                >
                    <div class="relative w-full max-w-2xl overflow-hidden rounded-[2.5rem] bg-white shadow-2xl">
                        <div class="bg-slate-900 p-8 text-white">
                            <button
                                class="absolute right-6 top-6 text-slate-500 transition hover:text-white"
                                @click="isPreviewModalOpen = false"
                            >
                                <X class="h-6 w-6" />
                            </button>

                            <h3 class="text-2xl font-black uppercase italic tracking-tighter">{{ selectedJob?.judul_lowongan }}</h3>
                            <p class="mt-1 text-[10px] font-bold uppercase italic text-slate-400">
                                {{ selectedJob?.tipe_pekerjaan }} • {{ selectedJob?.lokasi?.nama_lokasi }}
                            </p>
                        </div>

                        <div class="max-h-[55vh] space-y-6 overflow-y-auto p-8">
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <p class="text-[9px] font-black uppercase italic text-slate-400">Pendidikan</p>
                                    <p class="mt-1 inline-flex items-center gap-2 text-xs font-black uppercase italic text-slate-700">
                                        <GraduationCap class="h-4 w-4 text-sky-700" />
                                        {{ selectedJob?.minimal_pendidikan || 'Umum' }}
                                    </p>
                                </div>
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <p class="text-[9px] font-black uppercase italic text-slate-400">Pengalaman</p>
                                    <p class="mt-1 inline-flex items-center gap-2 text-xs font-black uppercase italic text-slate-700">
                                        <Briefcase class="h-4 w-4 text-sky-700" />
                                        {{ Math.max(0, Number(selectedJob?.minimal_pengalaman || 0)) }} Tahun
                                    </p>
                                </div>
                            </div>

                            <div>
                                <h5 class="mb-3 text-[11px] font-black uppercase italic text-slate-900">Keahlian Dibutuhkan</h5>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="skill in selectedJob?.skills || []"
                                        :key="skill.id"
                                        class="rounded-lg bg-slate-900 px-3 py-1.5 text-[9px] font-black uppercase italic text-white"
                                    >
                                        # {{ skill.nama_skill }}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <h5 class="mb-2 text-[11px] font-black uppercase italic text-slate-900">Deskripsi Tugas</h5>
                                <p class="whitespace-pre-line text-xs font-medium italic leading-relaxed text-slate-600">
                                    {{ selectedJob?.deskripsi_lowongan || '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50 px-8 py-5">
                            <div class="inline-flex items-center gap-2 text-sm font-black italic text-emerald-600">
                                <Banknote class="h-5 w-5" />
                                {{ formatCurrency(selectedJob?.gaji_min) }} - {{ formatCurrency(selectedJob?.gaji_max) }}
                            </div>
                            <button
                                type="button"
                                class="rounded-xl bg-slate-900 px-6 py-2.5 text-[10px] font-black uppercase italic text-white"
                                @click="isPreviewModalOpen = false"
                            >
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.25s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>