<!-- eslint-disable @typescript-eslint/no-unused-vars -->
<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import {
    ImagePlus,
    Building2,
    Globe,
    Info,
    User,
    Save,
    Lock,
    ShieldCheck,
    Phone,
    MapPin,
    Briefcase,
    Eye,       // TAMBAHAN: Import icon Eye
    EyeOff     // TAMBAHAN: Import icon EyeOff
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { computed, ref } from 'vue';
import DeleteUser from '@/components/DeleteUser.vue';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface Props {
    mustVerifyEmail?: boolean;
    status?: string;
    role: string;
    kategoris?: any[];
    lokasis?: any[];
    mitra?: any;
    pelamar?: any;
}

const props = defineProps<Props>();
const page = usePage();
const user = computed(() => page.props.auth.user as any);

defineOptions({ layout: AppLayout });

const form = useForm({
    _method: 'patch',
    name: user.value.name,
    email: user.value.email,

    current_password: '',
    password: '',
    password_confirmation: '',

    nama_mitra: props.mitra?.nama_mitra || '',
    kategori_id: props.mitra?.kategori_id || '',
    lokasi_id: props.mitra?.lokasi_id || props.pelamar?.lokasi_id || '',
    alamat_mitra: props.mitra?.alamat_mitra || '',
    website_mitra: props.mitra?.website_mitra || '',
    deskripsi_mitra: props.mitra?.deskripsi_mitra || '',
    logo_mitra: null as any,

    nama_pelamar: props.pelamar?.nama_pelamar || user.value.name,
    nohp_pelamar: props.pelamar?.nohp_pelamar || '',
    alamat_pelamar: props.pelamar?.alamat_pelamar || '',
    jenis_kelamin: props.pelamar?.jenis_kelamin || '',
    cv_pelamar: null as any,
    foto_pelamar: null as any,
});

// TAMBAHAN: State untuk mengatur tampil/sembunyi password
const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

const imagePreview = ref<string | null>(
    props.role === 'mitra' && props.mitra?.logo_mitra ? `/storage/${props.mitra.logo_mitra}` : 
    props.role === 'pelamar' && props.pelamar?.foto_pelamar ? `/storage/${props.pelamar.foto_pelamar}` : null
);

const handleImageChange = (e: any) => {
    const file = e.target.files[0];

    if (file) {
        if (props.role === 'mitra') {
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
}
};

const submit = () => {
    form.post((window as any).route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('current_password', 'password', 'password_confirmation');
            Swal.fire({
                title: 'Profil Diperbarui!',
                text: 'Data akun Anda berhasil diperbarui.',
                icon: 'success',
                confirmButtonColor: '#0369a1',
                customClass: { popup: 'rounded-[2.5rem]' },
            });
        },
    });
};
</script>

<template>
    <Head title="Pengaturan Profil - Lokak Begawe" />

    <div class="space-y-8 p-6 text-slate-900 lg:p-10">
        <div class="flex flex-col items-start">
            <h1 class="text-3xl font-black tracking-tighter uppercase italic md:text-4xl">
                PENGATURAN <span class="text-sky-700">PROFIL</span>
            </h1>
            <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
        </div>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
            <div class="space-y-10 lg:col-span-8">
                
                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <div class="mb-8 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-700">
                            <Building2 v-if="props.role === 'mitra'" class="h-5 w-5" />
                            <Briefcase v-else class="h-5 w-5" />
                        </div>
                        <h2 class="text-lg font-black tracking-tight uppercase italic">
                            Identitas
                            <span class="text-sky-700">{{ props.role === 'mitra' ? 'Perusahaan' : 'Pribadi' }}</span>
                        </h2>
                    </div>

                    <div class="space-y-8">
                        <div class="flex flex-col items-center gap-6 md:flex-row">
                            <div class="group relative h-32 w-32 shrink-0 overflow-hidden rounded-4xl border-4 border-slate-50 bg-slate-100 shadow-inner">
                                <img v-if="imagePreview" :src="imagePreview" class="h-full w-full object-cover" />
                                <div v-else class="flex h-full w-full items-center justify-center text-slate-300">
                                    <Building2 v-if="props.role === 'mitra'" class="h-12 w-12" />
                                    <User v-else class="h-12 w-12" />
                                </div>
                                <label class="absolute inset-0 flex cursor-pointer items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100">
                                    <ImagePlus class="h-8 w-8 text-white" />
                                    <input type="file" @change="handleImageChange" class="hidden" accept="image/*" />
                                </label>
                            </div>
                            <div class="space-y-1 text-center md:text-left">
                                <h4 class="text-xs font-black uppercase italic">
                                    {{ props.role === 'mitra' ? 'Logo Instansi' : 'Foto Profil' }}
                                </h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase italic">Klik gambar untuk mengubah foto.</p>
                            </div>
                        </div>

                        <template v-if="props.role === 'mitra'">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">Nama Perusahaan</label>
                                    <input v-model="form.nama_mitra" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20" />
                                </div>
                                <div class="space-y-2">
                                    <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">Website</label>
                                    <input v-model="form.website_mitra" placeholder="https://..." class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">Kategori</label>
                                    <select v-model="form.kategori_id" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none">
                                        <option v-for="kat in kategoris" :key="kat.id" :value="kat.id">{{ kat.nama_kategori }}</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">Lokasi Utama</label>
                                    <select v-model="form.lokasi_id" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none">
                                        <option v-for="lok in lokasis" :key="lok.id" :value="lok.id">{{ lok.nama_lokasi }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">Deskripsi Singkat</label>
                                <textarea v-model="form.deskripsi_mitra" rows="3" class="w-full rounded-3xl border border-slate-200 bg-slate-50 p-6 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20"></textarea>
                            </div>
                        </template>

                        <template v-if="props.role === 'pelamar'">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">Nama Lengkap</label>
                                    <input v-model="form.nama_pelamar" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20" />
                                </div>
                                <div class="space-y-2">
                                    <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">No. Handphone (WhatsApp)</label>
                                    <input v-model="form.nohp_pelamar" type="tel" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20" />
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">Domisili Saat Ini</label>
                                    <select v-model="form.lokasi_id" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none">
                                        <option value="">Pilih Kota/Kabupaten</option>
                                        <option v-for="lok in lokasis" :key="lok.id" :value="lok.id">{{ lok.nama_lokasi }}</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">Jenis Kelamin</label>
                                    <select v-model="form.jenis_kelamin" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none">
                                        <option value="">Pilih...</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">Alamat Lengkap</label>
                                <textarea v-model="form.alamat_pelamar" rows="3" class="w-full rounded-3xl border border-slate-200 bg-slate-50 p-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20"></textarea>
                            </div>

                            <div class="space-y-2 border-t border-slate-100 pt-6">
                                <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">Upload CV (PDF)</label>
                                <input type="file" @change="handleCvChange" accept=".pdf" class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-sky-50 file:px-4 file:py-3 file:text-xs file:font-bold file:text-sky-700 hover:file:bg-sky-100" />
                            </div>
                        </template>
                    </div>

                    <div class="mt-10 flex items-center justify-between border-t border-slate-100 pt-8">
                        <p class="text-[10px] font-bold text-slate-400 uppercase italic">Klik simpan untuk memperbarui profil.</p>
                        <button @click="submit" :disabled="form.processing" class="flex items-center gap-3 rounded-2xl bg-sky-700 px-8 py-5 text-sm font-black text-white uppercase italic shadow-xl shadow-sky-900/20 transition-all hover:bg-sky-800 disabled:opacity-50">
                            <Save class="h-5 w-5" />
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                        </button>
                    </div>
                </div>

                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <div class="mb-8 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <User class="h-5 w-5" />
                        </div>
                        <h2 class="text-lg font-black tracking-tight uppercase italic">
                            Informasi <span class="text-indigo-600">Login</span>
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">Nama Akun Utama</label>
                                <input v-model="form.name" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-indigo-600/20" />
                            </div>
                            <div class="space-y-2">
                                <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">Email Akun</label>
                                <input v-model="form.email" type="email" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-indigo-600/20" />
                            </div>
                        </div>

                        <hr class="border-slate-100" />

                        <div class="space-y-6">
                            <div class="flex items-center gap-2 text-rose-500">
                                <Lock class="h-4 w-4" />
                                <h3 class="text-[10px] font-black tracking-widest uppercase italic">Ubah Kata Sandi (Opsional)</h3>
                            </div>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                
                                <div class="space-y-2">
                                    <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">Sandi Saat Ini</label>
                                    <div class="relative">
                                        <input :type="showCurrentPassword ? 'text' : 'password'" v-model="form.current_password" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 pr-12 text-xs font-bold outline-none focus:ring-2 focus:ring-rose-500/20 hide-password-toggle" />
                                        <button type="button" @click="showCurrentPassword = !showCurrentPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors hover:text-rose-500">
                                            <EyeOff v-if="!showCurrentPassword" class="h-4 w-4" />
                                            <Eye v-else class="h-4 w-4" />
                                        </button>
                                    </div>
                                    <InputError :message="form.errors.current_password" />
                                </div>

                                <div class="space-y-2">
                                    <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">Sandi Baru</label>
                                    <div class="relative">
                                        <input :type="showNewPassword ? 'text' : 'password'" v-model="form.password" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 pr-12 text-xs font-bold outline-none focus:ring-2 focus:ring-rose-500/20 hide-password-toggle" />
                                        <button type="button" @click="showNewPassword = !showNewPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors hover:text-rose-500">
                                            <EyeOff v-if="!showNewPassword" class="h-4 w-4" />
                                            <Eye v-else class="h-4 w-4" />
                                        </button>
                                    </div>
                                    <InputError :message="form.errors.password" />
                                </div>

                                <div class="space-y-2">
                                    <label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">Konfirmasi Sandi</label>
                                    <div class="relative">
                                        <input :type="showConfirmPassword ? 'text' : 'password'" v-model="form.password_confirmation" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 pr-12 text-xs font-bold outline-none focus:ring-2 focus:ring-rose-500/20 hide-password-toggle" />
                                        <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors hover:text-rose-500">
                                            <EyeOff v-if="!showConfirmPassword" class="h-4 w-4" />
                                            <Eye v-else class="h-4 w-4" />
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-8 lg:col-span-4">
                <div class="rounded-[2.5rem] bg-slate-900 p-8 text-white shadow-xl">
                    <div class="mb-6 flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-emerald-400">
                        <ShieldCheck class="h-5 w-5" />
                    </div>
                    <h3 class="text-sm font-black uppercase italic">Tips Keamanan</h3>
                    <p class="mt-4 text-[10px] leading-relaxed font-medium uppercase italic opacity-60">
                        Gunakan kombinasi huruf kapital, angka, dan simbol untuk kata sandi yang kuat. Jangan pernah bagikan email dan sandi login Anda kepada siapapun.
                    </p>
                </div>

                <div class="rounded-[2.5rem] border border-rose-100 bg-rose-50/20 p-2">
                    <div class="rounded-[2.2rem] border border-rose-50 bg-white p-6 shadow-sm">
                        <DeleteUser />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* CSS UNTUK MEMATIKAN MATA (EYE ICON) BAWAAN MICROSOFT EDGE/IE */
.hide-password-toggle::-ms-reveal,
.hide-password-toggle::-ms-clear {
    display: none;
}
</style>