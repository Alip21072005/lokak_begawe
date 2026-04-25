<!-- eslint-disable @typescript-eslint/no-unused-vars -->
<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { 
    ArrowLeft, Building2, Lock, 
    Save, Image as ImageIcon, Eye, EyeOff,
    Info
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { ref } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });
const props = defineProps<{ kategoris: any[], lokasis: any[] }>();

const showPassword = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    nama_mitra: '',
    nohp_mitra: '',
    lokasi_id: '',
    kategori_id: '',
    alamat_mitra: '',
    deskripsi_mitra: '',
    logo_mitra: null as File | null,
    banner_mitra: null as File | null,
});

// Preview sederhana untuk admin agar tahu file sudah terpilih
const logoPreview = ref<string | null>(null);
const handleLogoUpload = (e: any) => { 
    const file = e.target.files[0];
    form.logo_mitra = file;

    if (file) {
        logoPreview.value = URL.createObjectURL(file);
    }
};

const handleBannerUpload = (e: any) => {
    form.banner_mitra = e.target.files[0]; 
};

const submit = () => {
    form.post(route('admin.mitra.store'), {
        forceFormData: true, // PENTING: Wajib agar file logo/banner terkirim
        onBefore: () => {
            Swal.fire({
                title: 'Sedang Memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
 Swal.showLoading(); 
},
                customClass: { popup: 'rounded-[3rem]' }
            });
        },
        onSuccess: () => {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Akun Mitra telah dibuat dan otomatis terverifikasi.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                customClass: { popup: 'rounded-[3rem]' }
            });
        },
        onError: (errors) => {
            Swal.close();
            // Menampilkan pesan error spesifik jika email duplikat atau validasi gagal
            let errorMsg = 'Terjadi kesalahan. Silakan periksa kembali data inputan.';

            if (errors.email) {
errorMsg = 'Email tersebut sudah terdaftar!';
}
            
            Swal.fire({
                title: 'Gagal Simpan',
                text: errorMsg,
                icon: 'error',
                confirmButtonColor: '#e11d48',
                customClass: { popup: 'rounded-[2.5rem]' }
            });
        }
    });
};
</script>

<template>
    <Head title="Tambah Mitra Manual - Admin" />
    
    <div class="space-y-10 p-6 lg:p-10 max-w-350 mx-auto">
        <Link :href="route('admin.kelolamitra')" class="group inline-flex items-center gap-3 text-[10px] font-black uppercase italic text-slate-400 hover:text-sky-700 transition-colors">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-sky-50 transition-colors">
                <ArrowLeft class="h-4 w-4 transition-transform group-hover:-translate-x-1" />
            </div>
            Kembali ke Manajemen Mitra
        </Link>

        <header class="flex flex-col gap-2">
            <h1 class="text-4xl font-black tracking-tighter text-slate-900 uppercase italic md:text-5xl">
                REGISTRASI <span class="text-sky-700">MANUAL</span> MITRA
            </h1>
            <div class="h-1.5 w-32 rounded-full bg-slate-200"></div>
            <p class="text-[11px] font-bold tracking-[0.2em] text-slate-400 uppercase italic leading-relaxed">
                Otoritas Admin: Lokak Begawe Indonesia
            </p>
        </header>

        <form @submit.prevent="submit" class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            
            <div class="lg:col-span-4 space-y-8">
                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <h3 class="mb-8 flex items-center gap-3 text-[10px] font-black uppercase italic text-slate-400">
                        <Lock class="h-4 w-4 text-sky-700" /> Kredensial Akses
                    </h3>
                    <div class="space-y-5">
                        <div class="space-y-2">
                            <label class="ml-2 text-[9px] font-black uppercase text-slate-400 italic">Nama PJ Perusahaan</label>
                            <input v-model="form.name" type="text" class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20" placeholder="Asep Mujikno" />
                        </div>
                        <div class="space-y-2">
                            <label class="ml-2 text-[9px] font-black uppercase text-slate-400 italic">Email Login</label>
                            <input v-model="form.email" type="email" class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20" placeholder="hrd@perusahaan.com" />
                        </div>
                        <div class="space-y-2">
                            <label class="ml-2 text-[9px] font-black uppercase text-slate-400 italic tracking-widest">Kata Sandi Sementara</label>
                            <div class="relative group">
                                <input 
                                    :type="showPassword ? 'text' : 'password'" 
                                    v-model="form.password" 
                                    class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 pr-12 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20 shadow-inner" 
                                    placeholder="••••••••" 
                                />
                                <button 
                                    type="button" 
                                    @click="showPassword = !showPassword"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-sky-700 transition-colors"
                                >
                                    <Eye v-if="!showPassword" class="h-4 w-4" />
                                    <EyeOff v-else class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <h3 class="mb-8 flex items-center gap-3 text-[10px] font-black uppercase italic text-slate-400">
                        <ImageIcon class="h-4 w-4 text-sky-700" /> Aset Branding
                    </h3>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 flex items-center justify-center">
                                <img v-if="logoPreview" :src="logoPreview" class="h-full w-full object-cover" />
                                <Building2 v-else class="h-6 w-6 text-slate-300" />
                            </div>
                            <div class="flex-1 space-y-1">
                                <label class="text-[9px] font-black uppercase text-slate-400 italic">Logo Perusahaan</label>
                                <input type="file" @change="handleLogoUpload" class="block w-full text-[10px] text-slate-400 file:mr-2 file:rounded-lg file:border-0 file:bg-sky-50 file:px-2 file:py-1 file:font-black file:text-sky-700" />
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="ml-2 text-[9px] font-black uppercase text-slate-400 italic">Banner Profil (Header)</label>
                            <input type="file" @change="handleBannerUpload" class="block w-full text-[10px] text-slate-400 file:mr-2 file:rounded-lg file:border-0 file:bg-slate-50 file:px-2 file:py-1 file:font-black file:text-slate-600" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 space-y-8">
                <div class="rounded-[3rem] border border-slate-200 bg-white p-10 shadow-sm relative overflow-hidden">
                    <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-sky-50/50 blur-3xl"></div>

                    <h3 class="mb-10 flex items-center gap-3 text-[10px] font-black uppercase italic text-slate-400">
                        <Building2 class="h-4 w-4 text-sky-700" /> Profil Instansi Lengkap
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="ml-2 text-[9px] font-black uppercase text-slate-400 italic tracking-widest">Nama Entitas Bisnis</label>
                            <input v-model="form.nama_mitra" type="text" class="w-full rounded-2xl border-slate-200 bg-slate-50 p-5 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20 shadow-sm" placeholder="PT. Inovasi Bengkulu Mandiri" />
                        </div>
                        <div class="space-y-2">
                            <label class="ml-2 text-[9px] font-black uppercase text-slate-400 italic tracking-widest">Kontak WhatsApp</label>
                            <input v-model="form.nohp_mitra" type="text" class="w-full rounded-2xl border-slate-200 bg-slate-50 p-5 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20 shadow-sm" placeholder="08..." />
                        </div>
                        <div class="space-y-2">
                            <label class="ml-2 text-[9px] font-black uppercase text-sky-700 italic tracking-widest">Wilayah Operasional</label>
                            <select v-model="form.lokasi_id" class="w-full rounded-2xl border-slate-200 bg-slate-50 p-5 text-[11px] font-black outline-none italic shadow-sm appearance-none cursor-pointer">
                                <option value="" disabled>Pilih Area Penempatan</option>
                                <option v-for="l in lokasis" :key="l.id" :value="l.id">{{ l.nama_lokasi }}</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="ml-2 text-[9px] font-black uppercase text-sky-700 italic tracking-widest">Sektor Industri</label>
                            <select v-model="form.kategori_id" class="w-full rounded-2xl border-slate-200 bg-slate-50 p-5 text-[11px] font-black outline-none italic shadow-sm appearance-none cursor-pointer">
                                <option value="" disabled>Pilih Bidang Bisnis</option>
                                <option v-for="k in kategoris" :key="k.id" :value="k.id">{{ k.nama_kategori }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-8 space-y-8">
                        <div class="space-y-2">
                            <label class="ml-2 text-[9px] font-black uppercase text-slate-400 italic tracking-widest">Alamat Kantor Pusat</label>
                            <textarea v-model="form.alamat_mitra" rows="2" class="w-full rounded-3xl border-slate-200 bg-slate-50 p-6 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20 italic shadow-sm" placeholder="Jl. P. Natadirja No..."></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="ml-2 text-[9px] font-black uppercase text-slate-400 italic tracking-widest">Deskripsi Perusahaan</label>
                            <textarea v-model="form.deskripsi_mitra" rows="5" class="w-full rounded-3xl border-slate-200 bg-slate-50 p-6 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20 italic shadow-sm" placeholder="Tuliskan sejarah singkat, visi, atau keunggulan perusahaan..."></textarea>
                        </div>
                    </div>

                    <div class="mt-12 flex flex-col sm:flex-row items-center justify-between gap-6 border-t border-slate-50 pt-10">
                        <div class="flex items-center gap-3 text-amber-600 bg-amber-50 px-5 py-3 rounded-2xl border border-amber-100">
                            <Info class="h-4 w-4" />
                            <span class="text-[9px] font-black uppercase italic leading-tight">Akun akan otomatis aktif & diverifikasi</span>
                        </div>
                        <button 
                            :disabled="form.processing" 
                            class="group w-full sm:w-auto flex items-center justify-center gap-4 rounded-2xl bg-slate-900 px-12 py-6 text-xs font-black text-white uppercase italic shadow-2xl transition-all hover:bg-sky-700 active:scale-95 disabled:opacity-50"
                        >
                            <Save class="h-5 w-5 transition-transform group-hover:scale-110" /> 
                            Simpan Data Mitra
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<style scoped>
/* Styling Dropdown Kustom */
select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1.25rem center;
    background-size: 1rem;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input:focus, select:focus, textarea:focus {
    box-shadow: 0 10px 25px -5px rgba(3, 105, 161, 0.1);
}
</style>