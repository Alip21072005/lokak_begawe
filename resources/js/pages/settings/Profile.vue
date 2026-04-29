<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import {
    ImagePlus, Building2, User, Save, ShieldCheck,
    Briefcase, FileText, GraduationCap,
    Wrench, Plus, Trash2, MapPin, Link as LinkIcon, 
    UploadCloud, CheckCircle2, Eye, KeyRound
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { computed, ref } from 'vue';
import DeleteUser from '@/components/DeleteUser.vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface Props {
    mustVerifyEmail?: boolean;
    status?: string;
    role: string;
    kategoris?: any[];
    lokasis?: any[];
    masterSkills?: any[];
    masterInstansis?: any[];
    masterPerusahaans?: any[];
    mitra?: any;
    pelamar?: any;
}

const props = defineProps<Props>();
const page = usePage();
const user = computed(() => page.props.auth.user as any);

defineOptions({ layout: AppLayout });

// --- INITIAL DATA ---
const form = useForm({
    _method: 'patch', 
    name: user.value.name,
    email: user.value.email,
    current_password: '',
    password: '',
    password_confirmation: '',
    avatar: null as any,

    // --- DATA MITRA ---
    nama_mitra: props.mitra?.nama_mitra || '',
    kategori_id: props.mitra?.kategori_id || '',
    lokasi_id: props.mitra?.lokasi_id || props.pelamar?.lokasi_id || '',
    alamat_mitra: props.mitra?.alamat_mitra || '',
    email_mitra: props.mitra?.email_mitra || user.value.email,
    nohp_mitra: props.mitra?.nohp_mitra || '',
    website_mitra: props.mitra?.website_mitra || '',
    tahun_berdiri: props.mitra?.tahun_berdiri || '',
    skala_perusahaan: props.mitra?.skala_perusahaan || '',
    deskripsi_mitra: props.mitra?.deskripsi_mitra || '',
    logo_mitra: null as any,
    banner_mitra: null as any,
    dokumen_mitra: null as any,

    // --- DATA PELAMAR ---
    nama_pelamar: props.pelamar?.nama_pelamar || user.value.name,
    email_pelamar: props.pelamar?.email_pelamar || user.value.email,
    nohp_pelamar: props.pelamar?.nohp_pelamar || '',
    alamat_pelamar: props.pelamar?.alamat_pelamar || '',
    jenis_kelamin: props.pelamar?.jenis_kelamin || '',
    bio: props.pelamar?.bio || '',
    website_portfolio: props.pelamar?.website_portfolio || '',
    cv_pelamar: null as any,
    foto_pelamar: null as any,

    // --- DATA PORTOFOLIO ---
    skills: props.pelamar?.skills?.map((s: any) => ({ master_skill_id: s.master_skill_id })) || [],
    pendidikans: props.pelamar?.pendidikans?.map((p: any) => ({
        master_instansi_id: p.master_instansi_id,
        gelar: p.gelar,
        tgl_mulai: p.tgl_mulai,
        tgl_lulus: p.tgl_lulus
    })) || [],
    pengalamans: props.pelamar?.pengalamans?.map((ex: any) => ({
        master_perusahaan_id: ex.master_perusahaan_id,
        posisi: ex.posisi,
        tgl_mulai: ex.tgl_mulai,
        tgl_selesai: ex.tgl_selesai,
        is_current: ex.is_current,
        deskripsi: ex.deskripsi
    })) || [],
});

// --- LOGIKA ADD/REMOVE ITEM PORTOFOLIO ---
const addSkill = () => form.skills.push({ master_skill_id: '' });
const removeSkill = (index: number) => form.skills.splice(index, 1);
const addEducation = () => form.pendidikans.push({ master_instansi_id: '', gelar: '', tgl_mulai: '', tgl_lulus: '' });
const removeEducation = (index: number) => form.pendidikans.splice(index, 1);
const addExperience = () => form.pengalamans.push({ master_perusahaan_id: '', posisi: '', tgl_mulai: '', tgl_selesai: '', is_current: false, deskripsi: '' });
const removeExperience = (index: number) => form.pengalamans.splice(index, 1);

// --- STATE UI & PREVIEWS ---
const imagePreview = ref<string | null>(
    user.value.avatar ? `/storage/${user.value.avatar}` : 
    (props.role === 'mitra' && props.mitra?.logo_mitra ? `/storage/${props.mitra.logo_mitra}` : 
    (props.role === 'pelamar' && props.pelamar?.foto_pelamar ? `/storage/${props.pelamar.foto_pelamar}` : null))
);

const bannerPreview = ref<string | null>(
    props.role === 'mitra' && props.mitra?.banner_mitra ? `/storage/${props.mitra.banner_mitra}` : null
);

// --- LOGIKA UPLOAD CV & DOKUMEN (PREVIEW LOKAL) ---
const cvInput = ref<HTMLInputElement | null>(null);
const dokumenInput = ref<HTMLInputElement | null>(null);

const cvFileName = ref<string | null>(props.pelamar?.cv_pelamar ? 'CV_Tersimpan.pdf' : null);
const dokumenFileName = ref<string | null>(props.mitra?.dokumen_mitra ? 'Dokumen_Tersimpan.pdf' : null);

// URL Preview (Bisa dari database, atau generate URL lokal saat user pilih file)
const cvPreviewUrl = ref<string | null>(props.pelamar?.cv_url || null);
const dokumenPreviewUrl = ref<string | null>(props.mitra?.dokumen_url || null);

const triggerFileSelect = (type: 'cv' | 'dokumen') => {
    const hasExisting = type === 'cv' ? cvFileName.value : dokumenFileName.value;

    if (hasExisting) {
        Swal.fire({
            title: 'Ganti Berkas?',
            text: 'Berkas lama akan tertimpa setelah Anda menekan tombol "Simpan Semua Perubahan".',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0369a1',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Pilih File Baru',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                if (type === 'cv') cvInput.value?.click();
                else dokumenInput.value?.click();
            }
        });
    } else {
        if (type === 'cv') cvInput.value?.click();
        else dokumenInput.value?.click();
    }
};

const handleFileChange = (e: any, type: 'cv' | 'dokumen') => {
    const file = e.target.files[0];
    if (file) {
        if (file.type !== 'application/pdf') {
            Swal.fire('Format Tidak Sesuai', 'Pastikan file Anda berformat PDF.', 'error');
            e.target.value = '';
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            Swal.fire('Ukuran Terlalu Besar', 'Maksimal ukuran file adalah 5MB.', 'error');
            e.target.value = '';
            return;
        }

        // Generate URL Lokal untuk Preview sebelum di-upload ke server
        const objectUrl = URL.createObjectURL(file);

        if (type === 'cv') {
            form.cv_pelamar = file;
            cvFileName.value = file.name;
            cvPreviewUrl.value = objectUrl;
        } else {
            form.dokumen_mitra = file;
            dokumenFileName.value = file.name;
            dokumenPreviewUrl.value = objectUrl;
        }
    }
};

const handleImageChange = (e: any, type: 'avatar' | 'banner' = 'avatar') => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        if (type === 'banner' && props.role === 'mitra') {
            form.banner_mitra = file;
            reader.onload = (e) => (bannerPreview.value = e.target?.result as string);
        } else {
            if (props.role === 'admin') form.avatar = file;
            else if (props.role === 'mitra') form.logo_mitra = file;
            else form.foto_pelamar = file;
            reader.onload = (e) => (imagePreview.value = e.target?.result as string);
        }
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    form.post((window as any).route('profile.update'), {
        preserveScroll: true,
        forceFormData: true,
        onBefore: () => Swal.showLoading(),
        onSuccess: () => {
            form.reset('current_password', 'password', 'password_confirmation');
            Swal.fire({
                title: 'Profil Diperbarui!',
                text: 'Data Anda berhasil disimpan.',
                icon: 'success',
                confirmButtonColor: '#0ea5e9',
                customClass: { popup: 'rounded-[2.5rem]' },
            });
        },
        onError: () => Swal.close()
    });
};
</script>

<template>
    <Head title="Pengaturan Profil - Lokak Begawe" />

    <div class="w-full bg-slate-50 font-sans text-slate-900 px-6 py-12 md:px-16 lg:px-24 xl:px-32 min-h-screen">
        
        <header class="mb-12 flex flex-col items-start">
            <h1 class="text-3xl font-black tracking-tighter uppercase italic md:text-5xl text-slate-900">
                PENGATURAN <span class="text-lokak-brand">PROFIL</span>
            </h1>
            <p class="mt-2 max-w-2xl text-sm font-medium text-slate-500">
                Perbarui identitas, kontak, portofolio, serta atur keamanan sandi Anda.
            </p>
        </header>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
            <div class="space-y-10 lg:col-span-8">
                
                <div class="rounded-[2.5rem] border border-slate-200 bg-white shadow-sm overflow-hidden">
                    
                    <div v-if="props.role === 'mitra'" class="group relative h-48 w-full bg-slate-200">
                        <img v-if="bannerPreview" :src="bannerPreview" class="h-full w-full object-cover" />
                        <div v-else class="flex h-full w-full items-center justify-center bg-slate-100 text-slate-400">
                            <ImagePlus class="h-10 w-10 opacity-50" />
                        </div>
                        <label class="absolute inset-0 flex cursor-pointer items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100">
                            <span class="rounded-full bg-white/20 px-4 py-2 text-xs font-bold text-white backdrop-blur-md uppercase tracking-wider italic">Ubah Banner</span>
                            <input type="file" @change="(e) => handleImageChange(e, 'banner')" class="hidden" accept="image/*" />
                        </label>
                    </div>

                    <div class="p-8">
                        <div class="mb-8 flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-50 text-lokak-brand">
                                <ShieldCheck v-if="props.role === 'admin'" class="h-6 w-6" />
                                <Building2 v-else-if="props.role === 'mitra'" class="h-6 w-6" />
                                <User v-else class="h-6 w-6" />
                            </div>
                            <div>
                                <h2 class="text-xl font-black tracking-tight uppercase italic text-slate-800">
                                    Identitas <span class="text-lokak-brand">{{ props.role === 'mitra' ? 'Perusahaan' : 'Diri' }}</span>
                                </h2>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Informasi Dasar</p>
                            </div>
                        </div>

                        <div class="mb-10 flex flex-col items-center gap-6 md:flex-row" :class="{'md:-mt-20': props.role === 'mitra'}">
                            <div class="group relative h-32 w-32 shrink-0 overflow-hidden rounded-full border-4 border-white bg-slate-100 shadow-lg z-10">
                                <img v-if="imagePreview" :src="imagePreview" class="h-full w-full object-cover" />
                                <div v-else class="flex h-full w-full items-center justify-center text-slate-300">
                                    <User class="h-12 w-12" />
                                </div>
                                <label class="absolute inset-0 flex cursor-pointer items-center justify-center bg-black/50 opacity-0 transition-opacity group-hover:opacity-100">
                                    <ImagePlus class="h-8 w-8 text-white" />
                                    <input type="file" @change="(e) => handleImageChange(e, 'avatar')" class="hidden" accept="image/*" />
                                </label>
                            </div>
                            <div class="space-y-1 text-center md:text-left z-10" :class="{'md:mt-12': props.role === 'mitra'}">
                                <h4 class="text-sm font-black uppercase italic text-slate-700">Foto Profil / Logo</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase italic">Maksimal 2MB (JPG/PNG/WEBP)</p>
                            </div>
                        </div>

                        <div v-if="props.role === 'mitra'" class="space-y-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="form-group">
                                    <label class="form-label">Nama Perusahaan</label>
                                    <input v-model="form.nama_mitra" class="form-input" placeholder="PT Loker Bengkulu" />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email Perusahaan</label>
                                    <input v-model="form.email_mitra" type="email" class="form-input" />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Kategori Industri</label>
                                    <select v-model="form.kategori_id" class="form-select">
                                        <option value="" disabled>Pilih Kategori</option>
                                        <option v-for="kat in kategoris" :key="kat.id" :value="kat.id">{{ kat.nama_kategori }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Lokasi Perusahaan</label>
                                    <select v-model="form.lokasi_id" class="form-select">
                                        <option value="" disabled>Pilih Lokasi</option>
                                        <option v-for="lok in lokasis" :key="lok.id" :value="lok.id">{{ lok.nama_lokasi }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tahun Berdiri</label>
                                    <input v-model="form.tahun_berdiri" type="number" class="form-input" placeholder="Contoh: 2015" />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Skala Perusahaan</label>
                                    <select v-model="form.skala_perusahaan" class="form-select">
                                        <option value="" disabled>Pilih Skala</option>
                                        <option value="Mikro (1-10 Karyawan)">Mikro (1-10 Karyawan)</option>
                                        <option value="Kecil (11-50 Karyawan)">Kecil (11-50 Karyawan)</option>
                                        <option value="Menengah (51-200 Karyawan)">Menengah (51-200 Karyawan)</option>
                                        <option value="Besar (>200 Karyawan)">Besar (>200 Karyawan)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">No. WhatsApp/Telepon</label>
                                    <input v-model="form.nohp_mitra" class="form-input" placeholder="0812xxxx" />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Website Perusahaan</label>
                                    <input v-model="form.website_mitra" class="form-input" placeholder="https://..." />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea v-model="form.alamat_mitra" rows="3" class="form-textarea"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Deskripsi Perusahaan</label>
                                <textarea v-model="form.deskripsi_mitra" rows="4" class="form-textarea" placeholder="Ceritakan tentang visi, misi, dan budaya perusahaan Anda..."></textarea>
                            </div>
                        </div>

                        <div v-if="props.role === 'pelamar'" class="space-y-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="form-group">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input v-model="form.nama_pelamar" class="form-input" />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email Kontak</label>
                                    <input v-model="form.email_pelamar" type="email" class="form-input" />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">No. WhatsApp</label>
                                    <input v-model="form.nohp_pelamar" class="form-input" placeholder="0812xxxx" />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select v-model="form.jenis_kelamin" class="form-select">
                                        <option value="" disabled>Pilih Gender</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Domisili (Lokasi)</label>
                                    <select v-model="form.lokasi_id" class="form-select">
                                        <option value="" disabled>Pilih Lokasi</option>
                                        <option v-for="lok in lokasis" :key="lok.id" :value="lok.id">{{ lok.nama_lokasi }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Link Portofolio / LinkedIn</label>
                                    <input v-model="form.website_portfolio" class="form-input" placeholder="https://linkedin.com/in/..." />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea v-model="form.alamat_pelamar" rows="2" class="form-textarea"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bio Singkat (Tentang Saya)</label>
                                <textarea v-model="form.bio" rows="3" class="form-textarea" placeholder="Ceritakan keahlian dan minat karir Anda secara singkat..."></textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div v-if="props.role === 'mitra' || props.role === 'pelamar'" class="rounded-[2.5rem] border border-slate-200 bg-white p-6 md:p-8 shadow-sm transition-all hover:border-lokak-brand/30">
                    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 shadow-sm">
                                <FileText class="h-6 w-6" />
                            </div>
                            <div>
                                <h3 class="text-sm font-black uppercase italic tracking-tight text-slate-900">
                                    {{ props.role === 'mitra' ? 'DOKUMEN LEGALITAS' : 'CURRICULUM VITAE (CV)' }}
                                </h3>
                                <p class="text-[10px] font-bold uppercase italic text-slate-400 tracking-wider">Format PDF, Maks 5MB</p>
                            </div>
                        </div>
                    </div>

                    <div v-if="(props.role === 'pelamar' && cvFileName) || (props.role === 'mitra' && dokumenFileName)" class="mb-6 flex items-center justify-between rounded-3xl border border-emerald-100 bg-emerald-50/50 p-4 transition-all hover:bg-emerald-50">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                <CheckCircle2 class="h-5 w-5" />
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-emerald-700 truncate max-w-37.5 md:max-w-75">
                                    {{ props.role === 'pelamar' ? cvFileName : dokumenFileName }}
                                </span>
                                <span class="text-[10px] font-black uppercase italic text-emerald-500 tracking-wider">Berkas Dipilih</span>
                            </div>
                        </div>
                        
                        <a :href="props.role === 'pelamar' ? (cvPreviewUrl || '#') : (dokumenPreviewUrl || '#')" target="_blank" class="flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-[10px] font-black uppercase italic tracking-widest text-emerald-600 shadow-sm transition-all hover:bg-emerald-600 hover:text-white border border-emerald-100 active:scale-95">
                            <Eye class="h-4 w-4 shrink-0" /> 
                            <span class="hidden sm:inline">Lihat Berkas</span>
                        </a>
                    </div>

                    <input type="file" ref="cvInput" class="hidden" accept=".pdf" @change="(e) => handleFileChange(e, 'cv')" />
                    <input type="file" ref="dokumenInput" class="hidden" accept=".pdf" @change="(e) => handleFileChange(e, 'dokumen')" />
                    
                    <button 
                        type="button" 
                        @click="triggerFileSelect(props.role === 'mitra' ? 'dokumen' : 'cv')" 
                        class="group flex w-full flex-col items-center justify-center gap-3 rounded-4xl border-2 border-dashed border-slate-200 bg-slate-50 py-10 transition-all hover:border-lokak-brand hover:bg-sky-50 active:scale-95"
                    >
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white shadow-sm text-slate-400 transition-all group-hover:text-lokak-brand group-hover:scale-110">
                            <UploadCloud class="h-6 w-6" />
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <span class="text-xs font-black uppercase italic tracking-widest text-slate-600 group-hover:text-lokak-brand">
                                {{ ((props.role === 'pelamar' && cvFileName) || (props.role === 'mitra' && dokumenFileName)) ? 'GANTI DENGAN FILE BARU' : 'PILIH FILE SEKARANG' }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pilih file untuk dipersiapkan sebelum menyimpan</span>
                        </div>
                    </button>
                </div>

                <template v-if="props.role === 'pelamar'">
                    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                        <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                            <h3 class="flex items-center gap-3 text-sm font-black uppercase italic text-slate-800">
                                <Wrench class="h-5 w-5 text-lokak-brand" /> Daftar Keahlian
                            </h3>
                            <button @click="addSkill" type="button" class="flex items-center gap-1.5 rounded-full bg-slate-900 px-4 py-2 text-[10px] font-black text-white uppercase italic hover:bg-lokak-brand transition-all">
                                <Plus class="h-3 w-3" /> Tambah
                            </button>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div v-for="(skill, index) in form.skills" :key="index" class="flex gap-2">
                                <select v-model="skill.master_skill_id" class="form-select flex-1">
                                    <option value="" disabled>Pilih Keahlian</option>
                                    <option v-for="s in masterSkills" :key="s.id" :value="s.id">{{ s.nama_skill }}</option>
                                </select>
                                <button @click="removeSkill(Number(index))" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                                    <Trash2 class="h-5 w-5" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                        <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                            <h3 class="flex items-center gap-3 text-sm font-black uppercase italic text-slate-800">
                                <GraduationCap class="h-5 w-5 text-lokak-brand" /> Riwayat Pendidikan
                            </h3>
                            <button @click="addEducation" type="button" class="flex items-center gap-1.5 rounded-full bg-slate-900 px-4 py-2 text-[10px] font-black text-white uppercase italic hover:bg-lokak-brand transition-all">
                                <Plus class="h-3 w-3" /> Tambah
                            </button>
                        </div>
                        <div class="space-y-6">
                            <div v-for="(edu, index) in form.pendidikans" :key="index" class="relative rounded-3xl bg-slate-50 p-6 border border-slate-100">
                                <button @click="removeEducation(Number(index))" class="absolute top-4 right-4 text-rose-400 hover:text-rose-600 transition-colors">
                                    <Trash2 class="h-5 w-5" />
                                </button>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div class="form-group">
                                        <label class="form-label">Nama Instansi</label>
                                        <select v-model="edu.master_instansi_id" class="form-select bg-white">
                                            <option value="" disabled>Pilih Instansi</option>
                                            <option v-for="ins in masterInstansis" :key="ins.id" :value="ins.id">{{ ins.nama_instansi }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Gelar / Jurusan</label>
                                        <input v-model="edu.gelar" placeholder="Contoh: S1 Sistem Informasi" class="form-input bg-white" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Tahun Mulai</label>
                                        <input v-model="edu.tgl_mulai" type="date" class="form-input bg-white" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Tahun Lulus</label>
                                        <input v-model="edu.tgl_lulus" type="date" class="form-input bg-white" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                        <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                            <h3 class="flex items-center gap-3 text-sm font-black uppercase italic text-slate-800">
                                <Briefcase class="h-5 w-5 text-lokak-brand" /> Pengalaman Kerja
                            </h3>
                            <button @click="addExperience" type="button" class="flex items-center gap-1.5 rounded-full bg-slate-900 px-4 py-2 text-[10px] font-black text-white uppercase italic hover:bg-lokak-brand transition-all">
                                <Plus class="h-3 w-3" /> Tambah
                            </button>
                        </div>
                        <div class="space-y-6">
                            <div v-for="(exp, index) in form.pengalamans" :key="index" class="relative rounded-3xl bg-slate-50 p-6 border border-slate-100">
                                <button @click="removeExperience(Number(index))" class="absolute top-4 right-4 text-rose-400 hover:text-rose-600 transition-colors">
                                    <Trash2 class="h-5 w-5" />
                                </button>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 mb-4">
                                    <div class="form-group">
                                        <label class="form-label">Nama Perusahaan</label>
                                        <select v-model="exp.master_perusahaan_id" class="form-select bg-white">
                                            <option value="" disabled>Pilih Perusahaan</option>
                                            <option v-for="p in masterPerusahaans" :key="p.id" :value="p.id">{{ p.nama_perusahaan }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Posisi / Jabatan</label>
                                        <input v-model="exp.posisi" placeholder="Contoh: Frontend Developer" class="form-input bg-white" />
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 mb-4">
                                    <div class="form-group">
                                        <label class="form-label">Tanggal Mulai</label>
                                        <input v-model="exp.tgl_mulai" type="date" class="form-input bg-white" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Tanggal Selesai</label>
                                        <input v-model="exp.tgl_selesai" type="date" :disabled="exp.is_current" class="form-input bg-white disabled:opacity-50" />
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 mb-4 pl-1">
                                    <input type="checkbox" v-model="exp.is_current" :id="'current-'+index" class="h-4 w-4 rounded border-slate-300 text-lokak-brand focus:ring-lokak-brand" />
                                    <label :for="'current-'+index" class="text-[10px] font-black uppercase italic text-slate-500 cursor-pointer">Masih bekerja di sini</label>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Deskripsi Pekerjaan</label>
                                    <textarea v-model="exp.deskripsi" rows="3" class="form-textarea bg-white"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <div class="mb-8 flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                            <KeyRound class="h-6 w-6" />
                        </div>
                        <div>
                            <h2 class="text-xl font-black tracking-tight uppercase italic text-slate-800">
                                Akses <span class="text-indigo-600">Akun</span>
                            </h2>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Login & Keamanan Sandi</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input v-model="form.name" class="form-input focus:border-indigo-600 focus:ring-indigo-600/20" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Akun (Login)</label>
                            <input v-model="form.email" type="email" class="form-input focus:border-indigo-600 focus:ring-indigo-600/20" />
                        </div>
                    </div>

                    <div class="mt-8 border-t border-slate-100 pt-6">
                        <h4 class="text-xs font-black uppercase tracking-widest text-slate-700 mb-4">Ubah Kata Sandi (Opsional)</h4>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="form-group md:col-span-2">
                                <label class="form-label">Kata Sandi Saat Ini</label>
                                <input type="password" v-model="form.current_password" class="form-input focus:border-indigo-600 focus:ring-indigo-600/20" placeholder="Biarkan kosong jika tidak ingin mengubah" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kata Sandi Baru</label>
                                <input type="password" v-model="form.password" class="form-input focus:border-indigo-600 focus:ring-indigo-600/20" placeholder="••••••••" />
                            </div>
                            <div class="form-group">
                                <label class="form-label">Konfirmasi Sandi Baru</label>
                                <input type="password" v-model="form.password_confirmation" class="form-input focus:border-indigo-600 focus:ring-indigo-600/20" placeholder="••••••••" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button @click="submit" :disabled="form.processing" class="flex w-full md:w-auto items-center justify-center gap-3 rounded-full bg-lokak-brand px-12 py-5 text-xs font-black text-white uppercase italic shadow-xl transition-all hover:-translate-y-1 hover:bg-blue-700 hover:shadow-2xl disabled:opacity-50 active:scale-95">
                        <Save class="h-5 w-5" /> {{ form.processing ? 'Menyimpan Data...' : 'Simpan Semua Perubahan' }}
                    </button>
                </div>

                <div class="lg:col-span-4">
                    <DeleteUser />
                </div>
            </div>
        </div>
    </div>
</template>

