<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';
import Swal from 'sweetalert2';
import {
    User,
    Building2,
    Save,
    Plus,
    Trash2,
    Upload,
    FileText,
    Briefcase,
    GraduationCap,
    Wrench,
    Lock,
    MapPin,
    Mail,
    Phone,
    Link as LinkIcon,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import DeleteUser from '@/components/DeleteUser.vue';

defineOptions({ layout: AppLayout });

interface OptionItem {
    id: string | number;
    nama_skill?: string;
    nama_instansi?: string;
    nama_perusahaan?: string;
    nama_kategori?: string;
    nama_lokasi?: string;
}

interface Props {
    role: 'admin' | 'mitra' | 'pelamar' | string;
    status?: string;
    kategoris?: OptionItem[];
    lokasis?: OptionItem[];
    masterSkills?: OptionItem[];
    masterInstansis?: OptionItem[];
    masterPerusahaans?: OptionItem[];
    mitra?: any;
    pelamar?: any;
}

const props = defineProps<Props>();
const page = usePage();
const authUser = computed(() => page.props.auth.user as any);

const form = useForm({
    name: authUser.value?.name ?? '',
    email: authUser.value?.email ?? '',
    current_password: '',
    password: '',
    password_confirmation: '',
    avatar: null as File | null,

    // Mitra
    nama_mitra: props.mitra?.nama_mitra ?? '',
    kategori_id: props.mitra?.kategori_id ?? '',
    lokasi_id: props.mitra?.lokasi_id ?? props.pelamar?.lokasi_id ?? '',
    alamat_mitra: props.mitra?.alamat_mitra ?? '',
    email_mitra: props.mitra?.email_mitra ?? authUser.value?.email ?? '',
    nohp_mitra: props.mitra?.nohp_mitra ?? '',
    website_mitra: props.mitra?.website_mitra ?? '',
    tahun_berdiri: props.mitra?.tahun_berdiri ?? '',
    skala_perusahaan: props.mitra?.skala_perusahaan ?? '',
    deskripsi_mitra: props.mitra?.deskripsi_mitra ?? '',
    logo_mitra: null as File | null,
    banner_mitra: null as File | null,
    dokumen_mitra: null as File | null,

    // Pelamar
    nama_pelamar: props.pelamar?.nama_pelamar ?? authUser.value?.name ?? '',
    nohp_pelamar: props.pelamar?.nohp_pelamar ?? '',
    jenis_kelamin: props.pelamar?.jenis_kelamin ?? '',
    alamat_pelamar: props.pelamar?.alamat_pelamar ?? '',
    bio: props.pelamar?.bio ?? '',
    website_portfolio: props.pelamar?.website_portfolio ?? '',
    foto_pelamar: null as File | null,
    cv_pelamar: null as File | null,

    // Portofolio
    skills: (props.pelamar?.skills ?? []).map((s: any) => ({
        master_skill_id: s.master_skill_id ?? '',
    })),
    pendidikans: (props.pelamar?.pendidikans ?? []).map((p: any) => ({
        master_instansi_id: p.master_instansi_id ?? '',
        gelar: p.gelar ?? '',
        tgl_mulai: p.tgl_mulai ?? '',
        tgl_lulus: p.tgl_lulus ?? '',
    })),
    pengalamans: (props.pelamar?.pengalamans ?? []).map((e: any) => ({
        master_perusahaan_id: e.master_perusahaan_id ?? '',
        posisi: e.posisi ?? '',
        tgl_mulai: e.tgl_mulai ?? '',
        tgl_selesai: e.tgl_selesai ?? '',
        is_current: !!e.is_current,
        deskripsi: e.deskripsi ?? '',
    })),
});

const profileImagePreview = ref<string | null>(
    props.role === 'mitra'
        ? props.mitra?.logo_url ?? null
        : props.role === 'pelamar'
          ? props.pelamar?.foto_url ?? null
          : authUser.value?.avatar
            ? `/storage/${authUser.value.avatar}`
            : null
);

const bannerPreview = ref<string | null>(props.mitra?.banner_url ?? null);
const cvPreviewUrl = ref<string | null>(props.pelamar?.cv_url ?? null);
const dokumenPreviewUrl = ref<string | null>(props.mitra?.dokumen_url ?? null);

const cvFileName = ref<string>(props.pelamar?.cv_pelamar ? 'CV tersimpan' : '');
const dokumenFileName = ref<string>(props.mitra?.dokumen_mitra ? 'Dokumen tersimpan' : '');

const roleLabel = computed(() => {
    if (props.role === 'admin') return 'Administrator';
    if (props.role === 'mitra') return 'Mitra Perusahaan';
    if (props.role === 'pelamar') return 'Pelamar';
    return 'Pengguna';
});

const roleIcon = computed(() => {
    if (props.role === 'admin') return User;
    if (props.role === 'mitra') return Building2;
    return User;
});

const sectionTitle = computed(() => {
    if (props.role === 'mitra') return 'Profil Perusahaan';
    if (props.role === 'pelamar') return 'Profil Pelamar';
    return 'Profil Akun';
});

const onImageFileChange = (event: Event, target: 'avatar' | 'logo_mitra' | 'foto_pelamar' | 'banner_mitra') => {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (e) => {
        const result = (e.target?.result as string) ?? null;
        if (target === 'banner_mitra') {
            bannerPreview.value = result;
        } else {
            profileImagePreview.value = result;
        }
    };
    reader.readAsDataURL(file);

    if (target === 'avatar') form.avatar = file;
    if (target === 'logo_mitra') form.logo_mitra = file;
    if (target === 'foto_pelamar') form.foto_pelamar = file;
    if (target === 'banner_mitra') form.banner_mitra = file;
};

const onPdfFileChange = (event: Event, target: 'cv_pelamar' | 'dokumen_mitra') => {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0];
    if (!file) return;

    if (file.type !== 'application/pdf') {
        Swal.fire('Format tidak valid', 'File harus format PDF.', 'error');
        input.value = '';
        return;
    }
    if (file.size > 5 * 1024 * 1024) {
        Swal.fire('Ukuran terlalu besar', 'Maksimal ukuran file adalah 5MB.', 'error');
        input.value = '';
        return;
    }

    const localUrl = URL.createObjectURL(file);

    if (target === 'cv_pelamar') {
        form.cv_pelamar = file;
        cvPreviewUrl.value = localUrl;
        cvFileName.value = file.name;
    } else {
        form.dokumen_mitra = file;
        dokumenPreviewUrl.value = localUrl;
        dokumenFileName.value = file.name;
    }
};

const addSkill = () => form.skills.push({ master_skill_id: '' });
const removeSkill = (index: number) => form.skills.splice(index, 1);

const addEducation = () =>
    form.pendidikans.push({
        master_instansi_id: '',
        gelar: '',
        tgl_mulai: '',
        tgl_lulus: '',
    });
const removeEducation = (index: number) => form.pendidikans.splice(index, 1);

const addExperience = () =>
    form.pengalamans.push({
        master_perusahaan_id: '',
        posisi: '',
        tgl_mulai: '',
        tgl_selesai: '',
        is_current: false,
        deskripsi: '',
    });
const removeExperience = (index: number) => form.pengalamans.splice(index, 1);

const hasErrors = computed(() => Object.keys(form.errors).length > 0);

const submit = () => {
    if (props.role === 'mitra' && form.email_mitra) {
        form.email = form.email_mitra;
    }

    form.patch(route('profile.update'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset('current_password', 'password', 'password_confirmation');
            Swal.fire({
                icon: 'success',
                title: 'Perubahan tersimpan',
                text: 'Profil berhasil diperbarui.',
                confirmButtonColor: '#0f172a',
            });
        },
    });
};
</script>

<template>
    <Head title="Pengaturan Profil" />

    <div class="min-h-screen bg-slate-50 pb-28">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <!-- Header -->
            <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="space-y-1">
                        <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">
                            Pengaturan Profil
                        </h1>
                        <p class="text-sm text-slate-600">
                            Kelola informasi akun, data profil, dan portofolio Anda.
                        </p>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700">
                        <component :is="roleIcon" class="h-4 w-4" />
                        {{ roleLabel }}
                    </div>
                </div>

                <div v-if="props.status" class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
                    {{ props.status }}
                </div>

                <div v-if="hasErrors" class="mt-4 rounded-lg border border-rose-200 bg-rose-50 p-3">
                    <p class="mb-2 text-sm font-semibold text-rose-700">Periksa data berikut:</p>
                    <ul class="list-disc pl-5 text-sm text-rose-700">
                        <li v-for="(msg, key) in form.errors" :key="key">{{ msg }}</li>
                    </ul>
                </div>
            </section>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
                <!-- Left panel -->
                <aside class="space-y-6 lg:col-span-4">
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div v-if="props.role === 'mitra'" class="relative h-28 w-full bg-slate-100">
                            <img v-if="bannerPreview" :src="bannerPreview" class="h-full w-full object-cover" />
                            <label class="absolute right-3 top-3 cursor-pointer rounded-md bg-black/60 px-2.5 py-1 text-xs font-medium text-white">
                                Ubah Banner
                                <input type="file" class="hidden" accept="image/*" @change="onImageFileChange($event, 'banner_mitra')" />
                            </label>
                        </div>

                        <div class="p-5">
                            <div class="flex items-start gap-4">
                                <div class="relative">
                                    <div class="h-20 w-20 overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                                        <img v-if="profileImagePreview" :src="profileImagePreview" class="h-full w-full object-cover" />
                                        <div v-else class="flex h-full w-full items-center justify-center text-slate-400">
                                            <User class="h-8 w-8" />
                                        </div>
                                    </div>
                                    <label class="absolute -bottom-2 -right-2 cursor-pointer rounded-full border border-slate-200 bg-white p-1.5 shadow-sm">
                                        <Upload class="h-3.5 w-3.5 text-slate-600" />
                                        <input
                                            type="file"
                                            class="hidden"
                                            accept="image/*"
                                            @change="
                                                onImageFileChange(
                                                    $event,
                                                    props.role === 'mitra' ? 'logo_mitra' : props.role === 'pelamar' ? 'foto_pelamar' : 'avatar'
                                                )
                                            "
                                        />
                                    </label>
                                </div>
                                <div class="min-w-0">
                                    <h2 class="truncate text-base font-semibold text-slate-900">{{ sectionTitle }}</h2>
                                    <p class="mt-1 truncate text-sm text-slate-600">{{ form.name }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ form.email }}</p>
                                </div>
                            </div>

                            <div class="mt-5 grid grid-cols-1 gap-2 text-sm">
                                <div class="inline-flex items-center gap-2 text-slate-600">
                                    <Mail class="h-4 w-4" /> {{ form.email || '-' }}
                                </div>
                                <div class="inline-flex items-center gap-2 text-slate-600">
                                    <MapPin class="h-4 w-4" />
                                    {{
                                        (lokasis?.find((x) => String(x.id) === String(form.lokasi_id))?.nama_lokasi as string) || 'Lokasi belum dipilih'
                                    }}
                                </div>
                                <div class="inline-flex items-center gap-2 text-slate-600">
                                    <Phone class="h-4 w-4" />
                                    {{
                                        props.role === 'mitra'
                                            ? form.nohp_mitra || '-'
                                            : props.role === 'pelamar'
                                              ? form.nohp_pelamar || '-'
                                              : '-'
                                    }}
                                </div>
                            </div>
                        </div>
                    </section>

                    <section v-if="props.role === 'pelamar' || props.role === 'mitra'" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h3 class="mb-4 text-sm font-semibold text-slate-900">
                            {{ props.role === 'pelamar' ? 'Dokumen CV' : 'Dokumen Legalitas' }}
                        </h3>

                        <div class="space-y-3">
                            <label class="block cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 text-center transition hover:border-slate-400 hover:bg-slate-100">
                                <p class="text-xs font-medium text-slate-700">Pilih file PDF (maks 5MB)</p>
                                <input
                                    type="file"
                                    class="hidden"
                                    accept=".pdf"
                                    @change="onPdfFileChange($event, props.role === 'pelamar' ? 'cv_pelamar' : 'dokumen_mitra')"
                                />
                            </label>

                            <div v-if="props.role === 'pelamar' ? cvFileName : dokumenFileName" class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                <p class="truncate text-xs font-medium text-slate-700">
                                    {{ props.role === 'pelamar' ? cvFileName : dokumenFileName }}
                                </p>
                            </div>

                            <a
                                v-if="props.role === 'pelamar' ? cvPreviewUrl : dokumenPreviewUrl"
                                :href="props.role === 'pelamar' ? (cvPreviewUrl as string) : (dokumenPreviewUrl as string)"
                                target="_blank"
                                class="inline-flex items-center gap-2 text-xs font-medium text-sky-700 hover:underline"
                            >
                                <FileText class="h-4 w-4" />
                                Lihat dokumen
                            </a>
                        </div>
                    </section>
                </aside>

                <!-- Right panel -->
                <section class="space-y-6 lg:col-span-8">
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <h3 class="mb-4 text-base font-semibold text-slate-900">Data Utama</h3>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <label class="space-y-1.5">
                                <span class="text-sm text-slate-600">Nama Akun</span>
                                <input v-model="form.name" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                            <label class="space-y-1.5">
                                <span class="text-sm text-slate-600">Email Akun</span>
                                <input v-model="form.email" type="email" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                        </div>

                        <!-- Mitra form -->
                        <div v-if="props.role === 'mitra'" class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <label class="space-y-1.5">
                                <span class="text-sm text-slate-600">Nama Perusahaan</span>
                                <input v-model="form.nama_mitra" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                            <label class="space-y-1.5">
                                <span class="text-sm text-slate-600">Email Perusahaan</span>
                                <input v-model="form.email_mitra" type="email" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                            <label class="space-y-1.5">
                                <span class="text-sm text-slate-600">Kategori</span>
                                <select v-model="form.kategori_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    <option value="">Pilih kategori</option>
                                    <option v-for="item in kategoris" :key="item.id" :value="item.id">{{ item.nama_kategori }}</option>
                                </select>
                            </label>
                            <label class="space-y-1.5">
                                <span class="text-sm text-slate-600">Lokasi</span>
                                <select v-model="form.lokasi_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    <option value="">Pilih lokasi</option>
                                    <option v-for="item in lokasis" :key="item.id" :value="item.id">{{ item.nama_lokasi }}</option>
                                </select>
                            </label>
                            <label class="space-y-1.5">
                                <span class="text-sm text-slate-600">No. Telepon</span>
                                <input v-model="form.nohp_mitra" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                            <label class="space-y-1.5">
                                <span class="text-sm text-slate-600">Website</span>
                                <input v-model="form.website_mitra" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                            <label class="space-y-1.5">
                                <span class="text-sm text-slate-600">Tahun Berdiri</span>
                                <input v-model="form.tahun_berdiri" type="number" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                            <label class="space-y-1.5">
                                <span class="text-sm text-slate-600">Skala Perusahaan</span>
                                <input v-model="form.skala_perusahaan" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                            <label class="space-y-1.5 md:col-span-2">
                                <span class="text-sm text-slate-600">Alamat</span>
                                <textarea v-model="form.alamat_mitra" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                            <label class="space-y-1.5 md:col-span-2">
                                <span class="text-sm text-slate-600">Deskripsi Perusahaan</span>
                                <textarea v-model="form.deskripsi_mitra" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                        </div>

                        <!-- Pelamar form -->
                        <div v-if="props.role === 'pelamar'" class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <label class="space-y-1.5">
                                <span class="text-sm text-slate-600">Nama Lengkap</span>
                                <input v-model="form.nama_pelamar" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                            <label class="space-y-1.5">
                                <span class="text-sm text-slate-600">No. WhatsApp</span>
                                <input v-model="form.nohp_pelamar" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                            <label class="space-y-1.5">
                                <span class="text-sm text-slate-600">Jenis Kelamin</span>
                                <select v-model="form.jenis_kelamin" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    <option value="">Pilih</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </label>
                            <label class="space-y-1.5">
                                <span class="text-sm text-slate-600">Lokasi</span>
                                <select v-model="form.lokasi_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    <option value="">Pilih lokasi</option>
                                    <option v-for="item in lokasis" :key="item.id" :value="item.id">{{ item.nama_lokasi }}</option>
                                </select>
                            </label>
                            <label class="space-y-1.5 md:col-span-2">
                                <span class="text-sm text-slate-600">Alamat</span>
                                <textarea v-model="form.alamat_pelamar" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                            <label class="space-y-1.5 md:col-span-2">
                                <span class="text-sm text-slate-600">Bio Profesional</span>
                                <textarea v-model="form.bio" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                            <label class="space-y-1.5 md:col-span-2">
                                <span class="inline-flex items-center gap-1 text-sm text-slate-600">
                                    <LinkIcon class="h-4 w-4" /> Portofolio / LinkedIn
                                </span>
                                <input v-model="form.website_portfolio" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                        </div>
                    </section>

                    <!-- Portfolio section for pelamar -->
                    <template v-if="props.role === 'pelamar'">
                        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="inline-flex items-center gap-2 text-base font-semibold text-slate-900">
                                    <Wrench class="h-4 w-4" /> Keahlian
                                </h3>
                                <button type="button" @click="addSkill" class="inline-flex items-center gap-1 rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-medium text-white">
                                    <Plus class="h-3.5 w-3.5" /> Tambah
                                </button>
                            </div>

                            <div class="space-y-2">
                                <div v-for="(skill, i) in form.skills" :key="i" class="flex flex-col gap-2 sm:flex-row">
                                    <select v-model="skill.master_skill_id" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                        <option value="">Pilih keahlian</option>
                                        <option v-for="item in masterSkills" :key="item.id" :value="item.id">{{ item.nama_skill }}</option>
                                    </select>
                                    <button type="button" @click="removeSkill(i)" class="inline-flex items-center justify-center gap-1 rounded-lg border border-rose-300 px-3 py-2 text-sm text-rose-600">
                                        <Trash2 class="h-4 w-4" /> Hapus
                                    </button>
                                </div>
                            </div>
                        </section>

                        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="inline-flex items-center gap-2 text-base font-semibold text-slate-900">
                                    <GraduationCap class="h-4 w-4" /> Pendidikan
                                </h3>
                                <button type="button" @click="addEducation" class="inline-flex items-center gap-1 rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-medium text-white">
                                    <Plus class="h-3.5 w-3.5" /> Tambah
                                </button>
                            </div>

                            <div class="space-y-3">
                                <div v-for="(edu, i) in form.pendidikans" :key="i" class="rounded-xl border border-slate-200 p-3">
                                    <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
                                        <select v-model="edu.master_instansi_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                            <option value="">Pilih instansi</option>
                                            <option v-for="item in masterInstansis" :key="item.id" :value="item.id">{{ item.nama_instansi }}</option>
                                        </select>
                                        <input v-model="edu.gelar" placeholder="Gelar / Jurusan" class="rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                                        <input v-model="edu.tgl_mulai" type="date" class="rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                                        <input v-model="edu.tgl_lulus" type="date" class="rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                                    </div>
                                    <button type="button" @click="removeEducation(i)" class="mt-2 inline-flex items-center gap-1 text-sm text-rose-600">
                                        <Trash2 class="h-4 w-4" /> Hapus pendidikan
                                    </button>
                                </div>
                            </div>
                        </section>

                        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="inline-flex items-center gap-2 text-base font-semibold text-slate-900">
                                    <Briefcase class="h-4 w-4" /> Pengalaman Kerja
                                </h3>
                                <button type="button" @click="addExperience" class="inline-flex items-center gap-1 rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-medium text-white">
                                    <Plus class="h-3.5 w-3.5" /> Tambah
                                </button>
                            </div>

                            <div class="space-y-3">
                                <div v-for="(exp, i) in form.pengalamans" :key="i" class="rounded-xl border border-slate-200 p-3">
                                    <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
                                        <select v-model="exp.master_perusahaan_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                            <option value="">Pilih perusahaan</option>
                                            <option v-for="item in masterPerusahaans" :key="item.id" :value="item.id">{{ item.nama_perusahaan }}</option>
                                        </select>
                                        <input v-model="exp.posisi" placeholder="Posisi" class="rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                                        <input v-model="exp.tgl_mulai" type="date" class="rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                                        <input v-model="exp.tgl_selesai" type="date" :disabled="exp.is_current" class="rounded-lg border border-slate-300 px-3 py-2 text-sm disabled:bg-slate-100" />
                                    </div>
                                    <label class="mt-2 inline-flex items-center gap-2 text-sm text-slate-600">
                                        <input type="checkbox" v-model="exp.is_current" />
                                        Masih bekerja di sini
                                    </label>
                                    <textarea v-model="exp.deskripsi" rows="2" placeholder="Deskripsi tugas dan pencapaian" class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                                    <button type="button" @click="removeExperience(i)" class="mt-2 inline-flex items-center gap-1 text-sm text-rose-600">
                                        <Trash2 class="h-4 w-4" /> Hapus pengalaman
                                    </button>
                                </div>
                            </div>
                        </section>
                    </template>

                    <!-- Security -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <h3 class="mb-4 inline-flex items-center gap-2 text-base font-semibold text-slate-900">
                            <Lock class="h-4 w-4" /> Keamanan Akun
                        </h3>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <label class="space-y-1.5">
                                <span class="text-sm text-slate-600">Password saat ini</span>
                                <input v-model="form.current_password" type="password" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                            <label class="space-y-1.5">
                                <span class="text-sm text-slate-600">Password baru</span>
                                <input v-model="form.password" type="password" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                            <label class="space-y-1.5">
                                <span class="text-sm text-slate-600">Konfirmasi password</span>
                                <input v-model="form.password_confirmation" type="password" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                            </label>
                        </div>
                    </section>

                    <DeleteUser />
                </section>
            </div>
        </div>

        <!-- Sticky action bar -->
        <div class="fixed bottom-0 left-0 right-0 border-t border-slate-200 bg-white/95 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                <p class="text-xs text-slate-500">
                    Pastikan data sudah benar sebelum menyimpan.
                </p>
                <button
                    type="button"
                    @click="submit"
                    :disabled="form.processing"
                    class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-black disabled:opacity-60"
                >
                    <Save class="h-4 w-4" />
                    {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                </button>
            </div>
        </div>
    </div>
</template>