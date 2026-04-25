<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import {
    ImagePlus, Building2, User, Save, ShieldCheck,
    Briefcase, FileText, GraduationCap,
    Wrench, Plus, Trash2} from 'lucide-vue-next';
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

// --- INITIAL DATA UNTUK PORTOFOLIO ---
const form = useForm({
    _method: 'patch', 
    name: user.value.name,
    email: user.value.email,
    current_password: '',
    password: '',
    password_confirmation: '',
    avatar: null as any,

    // Data Mitra
    nama_mitra: props.mitra?.nama_mitra || '',
    kategori_id: props.mitra?.kategori_id || '',
    lokasi_id: props.mitra?.lokasi_id || props.pelamar?.lokasi_id || '',
    alamat_mitra: props.mitra?.alamat_mitra || '',
    website_mitra: props.mitra?.website_mitra || '',
    deskripsi_mitra: props.mitra?.deskripsi_mitra || '',
    logo_mitra: null as any,

    // Data Pelamar Dasar
    nama_pelamar: props.pelamar?.nama_pelamar || user.value.name,
    nohp_pelamar: props.pelamar?.nohp_pelamar || '',
    alamat_pelamar: props.pelamar?.alamat_pelamar || '',
    jenis_kelamin: props.pelamar?.jenis_kelamin || '',
    bio: props.pelamar?.bio || '',
    website_portfolio: props.pelamar?.website_portfolio || '',
    cv_pelamar: null as any,
    foto_pelamar: null as any,

    // --- DATA PORTOFOLIO (Dinamis) ---
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

// --- LOGIKA ADD/REMOVE ITEM ---
const addSkill = () => form.skills.push({ master_skill_id: '' });
const removeSkill = (index: number) => form.skills.splice(index, 1);

const addEducation = () => form.pendidikans.push({ master_instansi_id: '', gelar: '', tgl_mulai: '', tgl_lulus: '' });
const removeEducation = (index: number) => form.pendidikans.splice(index, 1);

const addExperience = () => form.pengalamans.push({ master_perusahaan_id: '', posisi: '', tgl_mulai: '', tgl_selesai: '', is_current: false, deskripsi: '' });
const removeExperience = (index: number) => form.pengalamans.splice(index, 1);

// --- STATE UI ---

const imagePreview = ref<string | null>(
    user.value.avatar ? `/storage/${user.value.avatar}` : 
    (props.role === 'mitra' && props.mitra?.logo_mitra ? `/storage/${props.mitra.logo_mitra}` : 
    (props.role === 'pelamar' && props.pelamar?.foto_pelamar ? `/storage/${props.pelamar.foto_pelamar}` : null))
);

const cvFileName = ref<string | null>(props.pelamar?.cv_pelamar ? 'CV_Sudah_Terunggah.pdf' : null);

// --- HANDLERS ---
const handleImageChange = (e: any) => {
    const file = e.target.files[0];

    if (file) {
        if (props.role === 'admin') {
form.avatar = file;
} else if (props.role === 'mitra') {
form.logo_mitra = file;
} else {
form.foto_pelamar = file;
}

        const reader = new FileReader();
        reader.onload = (e) => (imagePreview.value = e.target?.result as string);
        reader.readAsDataURL(file);
    }
};

const handleCvChange = (e: any) => {
    const file = e.target.files[0];

    if (file) {
        form.cv_pelamar = file;
        cvFileName.value = file.name;
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
                text: 'Data portofolio Anda berhasil disimpan.',
                icon: 'success',
                confirmButtonColor: '#0369a1',
                customClass: { popup: 'rounded-[2.5rem]' },
            });
        },
        onError: () => Swal.close()
    });
};
</script>

<template>
    <Head title="Pengaturan Profil - Lokak Begawe" />

    <div class="space-y-12 p-6 text-slate-900 lg:p-10">
        <header class="flex flex-col items-start">
            <h1 class="text-3xl font-black tracking-tighter uppercase italic md:text-4xl">
                PENGATURAN <span class="text-sky-700">PROFIL</span>
            </h1>
            <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
        </header>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
            <div class="space-y-10 lg:col-span-8">
                
                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <div class="mb-8 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-700">
                            <ShieldCheck v-if="props.role === 'admin'" class="h-5 w-5" />
                            <Building2 v-else-if="props.role === 'mitra'" class="h-5 w-5" />
                            <Briefcase v-else class="h-5 w-5" />
                        </div>
                        <h2 class="text-lg font-black tracking-tight uppercase italic">
                            Identitas <span class="text-sky-700">{{ props.role.toUpperCase() }}</span>
                        </h2>
                    </div>

                    <div class="space-y-8">
                        <div class="flex flex-col items-center gap-6 md:flex-row">
                            <div class="group relative h-32 w-32 shrink-0 overflow-hidden rounded-4xl border-4 border-slate-50 bg-slate-100 shadow-inner">
                                <img v-if="imagePreview" :src="imagePreview" class="h-full w-full object-cover" />
                                <div v-else class="flex h-full w-full items-center justify-center text-slate-300">
                                    <User class="h-12 w-12" />
                                </div>
                                <label class="absolute inset-0 flex cursor-pointer items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100">
                                    <ImagePlus class="h-8 w-8 text-white" />
                                    <input type="file" @change="handleImageChange" class="hidden" accept="image/*" />
                                </label>
                            </div>
                            <div class="space-y-1 text-center md:text-left">
                                <h4 class="text-xs font-black uppercase italic">Foto Profil / Logo</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase italic">Maksimal 2MB (JPG/PNG/WEBP)</p>
                            </div>
                        </div>

                        <template v-if="props.role === 'pelamar'">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="ml-2 text-[10px] font-black uppercase text-slate-400 italic">Nama Lengkap</label>
                                    <input v-model="form.nama_pelamar" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20" />
                                </div>
                                <div class="space-y-2">
                                    <label class="ml-2 text-[10px] font-black uppercase text-slate-400 italic">WhatsApp</label>
                                    <input v-model="form.nohp_pelamar" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20" />
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="ml-2 text-[10px] font-black uppercase text-slate-400 italic">Bio Singkat</label>
                                <textarea v-model="form.bio" rows="3" class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20 italic"></textarea>
                            </div>
                        </template>
                    </div>
                </div>

                <template v-if="props.role === 'pelamar'">
                    
                    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                        <div class="mb-6 flex items-center justify-between">
                            <h3 class="flex items-center gap-3 text-[11px] font-black uppercase italic text-slate-800">
                                <Wrench class="h-4 w-4 text-sky-700" /> Daftar Keahlian
                            </h3>
                            <button @click="addSkill" type="button" class="flex items-center gap-1 text-[9px] font-black text-sky-700 uppercase italic hover:underline">
                                <Plus class="h-3 w-3" /> Tambah Skill
                            </button>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div v-for="(skill, index) in form.skills" :key="index" class="flex gap-2">
                                <select v-model="skill.master_skill_id" class="h-11 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-[10px] font-black uppercase italic outline-none focus:ring-2 focus:ring-sky-700/20">
                                    <option value="" disabled>Pilih Keahlian</option>
                                    <option v-for="s in masterSkills" :key="s.id" :value="s.id">{{ s.nama_skill }}</option>
                                </select>
                                <button @click="removeSkill(Number(index))" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-100 transition-colors">
                                    <Trash2 class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                        <div class="mb-6 flex items-center justify-between">
                            <h3 class="flex items-center gap-3 text-[11px] font-black uppercase italic text-slate-800">
                                <GraduationCap class="h-4 w-4 text-sky-700" /> Riwayat Pendidikan
                            </h3>
                            <button @click="addEducation" type="button" class="flex items-center gap-1 text-[9px] font-black text-sky-700 uppercase italic hover:underline">
                                <Plus class="h-3 w-3" /> Tambah Sekolah
                            </button>
                        </div>
                        <div class="space-y-6">
                            <div v-for="(edu, index) in form.pendidikans" :key="index" class="relative rounded-3xl bg-slate-50 p-6 border border-slate-100">
                                <button @click="removeEducation(Number(index))" class="absolute top-4 right-4 text-rose-400 hover:text-rose-600">
                                    <Trash2 class="h-4 w-4" />
                                </button>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div class="space-y-1">
                                        <label class="ml-1 text-[9px] font-black uppercase text-slate-400 italic">Nama Instansi</label>
                                        <select v-model="edu.master_instansi_id" class="h-11 w-full rounded-xl border-slate-200 bg-white px-4 text-[10px] font-black outline-none">
                                            <option value="" disabled>Pilih Instansi</option>
                                            <option v-for="ins in masterInstansis" :key="ins.id" :value="ins.id">{{ ins.nama_instansi }}</option>
                                        </select>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="ml-1 text-[9px] font-black uppercase text-slate-400 italic">Gelar / Jurusan</label>
                                        <input v-model="edu.gelar" placeholder="S1 Sistem Informasi" class="h-11 w-full rounded-xl border-slate-200 bg-white px-4 text-[10px] font-black outline-none" />
                                    </div>
                                    <div class="space-y-1">
                                        <label class="ml-1 text-[9px] font-black uppercase text-slate-400 italic">Tahun Mulai</label>
                                        <input v-model="edu.tgl_mulai" type="date" class="h-11 w-full rounded-xl border-slate-200 bg-white px-4 text-[10px] font-black outline-none" />
                                    </div>
                                    <div class="space-y-1">
                                        <label class="ml-1 text-[9px] font-black uppercase text-slate-400 italic">Tahun Lulus</label>
                                        <input v-model="edu.tgl_lulus" type="date" class="h-11 w-full rounded-xl border-slate-200 bg-white px-4 text-[10px] font-black outline-none" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                        <div class="mb-6 flex items-center justify-between">
                            <h3 class="flex items-center gap-3 text-[11px] font-black uppercase italic text-slate-800">
                                <Briefcase class="h-4 w-4 text-sky-700" /> Pengalaman Kerja
                            </h3>
                            <button @click="addExperience" type="button" class="flex items-center gap-1 text-[9px] font-black text-sky-700 uppercase italic hover:underline">
                                <Plus class="h-3 w-3" /> Tambah Pengalaman
                            </button>
                        </div>
                        <div class="space-y-6">
                            <div v-for="(exp, index) in form.pengalamans" :key="index" class="relative rounded-3xl bg-slate-50 p-6 border border-slate-100">
                                <button @click="removeExperience(Number(index))" class="absolute top-4 right-4 text-rose-400 hover:text-rose-600">
                                    <Trash2 class="h-4 w-4" />
                                </button>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 mb-4">
                                    <div class="space-y-1">
                                        <label class="ml-1 text-[9px] font-black uppercase text-slate-400 italic">Perusahaan</label>
                                        <select v-model="exp.master_perusahaan_id" class="h-11 w-full rounded-xl border-slate-200 bg-white px-4 text-[10px] font-black outline-none">
                                            <option value="" disabled>Pilih Perusahaan</option>
                                            <option v-for="p in masterPerusahaans" :key="p.id" :value="p.id">{{ p.nama_perusahaan }}</option>
                                        </select>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="ml-1 text-[9px] font-black uppercase text-slate-400 italic">Posisi / Jabatan</label>
                                        <input v-model="exp.posisi" placeholder="Frontend Developer" class="h-11 w-full rounded-xl border-slate-200 bg-white px-4 text-[10px] font-black outline-none" />
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 mb-4">
                                    <div class="space-y-1">
                                        <label class="ml-1 text-[9px] font-black uppercase text-slate-400 italic">Mulai</label>
                                        <input v-model="exp.tgl_mulai" type="date" class="h-11 w-full rounded-xl border-slate-200 bg-white px-4 text-[10px] font-black outline-none" />
                                    </div>
                                    <div class="space-y-1">
                                        <label class="ml-1 text-[9px] font-black uppercase text-slate-400 italic">Selesai</label>
                                        <input v-model="exp.tgl_selesai" type="date" :disabled="exp.is_current" class="h-11 w-full rounded-xl border-slate-200 bg-white px-4 text-[10px] font-black outline-none disabled:opacity-50" />
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 mb-4">
                                    <input type="checkbox" v-model="exp.is_current" :id="'current-'+index" class="h-4 w-4 rounded border-slate-300 text-sky-700" />
                                    <label :for="'current-'+index" class="text-[10px] font-black uppercase italic text-slate-500">Masih bekerja di sini</label>
                                </div>
                                <div class="space-y-1">
                                    <label class="ml-1 text-[9px] font-black uppercase text-slate-400 italic">Deskripsi Pekerjaan</label>
                                    <textarea v-model="exp.deskripsi" rows="2" class="w-full rounded-2xl border-slate-200 bg-white p-4 text-xs font-medium outline-none focus:ring-2 focus:ring-sky-700/20"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                        <label class="mb-4 block text-[10px] font-black uppercase italic text-slate-400">Dokumen CV (PDF)</label>
                        <div v-if="cvFileName" class="mb-4 flex items-center gap-4 rounded-2xl bg-emerald-50 p-4 border border-emerald-100">
                            <FileText class="h-6 w-6 text-emerald-500" />
                            <span class="text-xs font-black text-slate-700 italic truncate">{{ cvFileName }}</span>
                        </div>
                        <label class="flex cursor-pointer items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50 p-8 hover:border-sky-700 transition-all">
                            <span class="text-[10px] font-black uppercase italic text-slate-400">Pilih File Baru</span>
                            <input type="file" @change="handleCvChange" accept=".pdf" class="hidden" />
                        </label>
                    </div>

                </template>

                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <div class="mb-8 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <User class="h-5 w-5" />
                        </div>
                        <h2 class="text-lg font-black tracking-tight uppercase italic text-slate-800">
                            Akses <span class="text-indigo-600">Akun</span>
                        </h2>
                    </div>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Username</label>
                            <input v-model="form.name" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-indigo-600/20" />
                        </div>
                        <div class="space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Email Akun</label>
                            <input v-model="form.email" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-indigo-600/20" />
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button @click="submit" :disabled="form.processing" class="flex items-center gap-3 rounded-2xl bg-sky-700 px-12 py-5 text-xs font-black text-white uppercase italic shadow-2xl transition-all hover:bg-sky-800 disabled:opacity-50">
                        <Save class="h-4 w-4" /> {{ form.processing ? 'Menyimpan...' : 'Simpan Semua Perubahan' }}
                    </button>
                </div>

            </div>

            <div class="space-y-8 lg:col-span-4">
                <div class="rounded-[2.5rem] bg-slate-900 p-8 text-white shadow-xl shadow-slate-200">
                    <ShieldCheck class="h-8 w-8 text-emerald-400 mb-6" />
                    <h3 class="text-sm font-black uppercase italic tracking-tighter">Profil Publik</h3>
                    <p class="mt-4 text-[10px] leading-relaxed font-bold uppercase italic opacity-50">
                        Data yang Anda isi di sini (Skill, Pendidikan, Pengalaman) akan ditampilkan secara profesional di halaman profil publik Anda.
                    </p>
                </div>
                <DeleteUser />
            </div>
        </div>
    </div>
</template>

<style scoped>
select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1rem;
}
</style>