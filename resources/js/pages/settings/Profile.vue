<!-- eslint-disable @typescript-eslint/no-unused-vars -->
<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import {
    ImagePlus,
    Building2,
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    MapPin,
    Globe,
    Info,
    Mail,
    User,
    Map,
    AlignLeft,
    Save,
    Lock,
    ShieldCheck,
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { computed, ref } from 'vue';
import DeleteUser from '@/components/DeleteUser.vue';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
    kategoris: any[];
    lokasis: any[];
    mitra: any;
}

const props = defineProps<Props>();
const page = usePage();
const user = computed(() => page.props.auth.user as any);

defineOptions({ layout: AppLayout });

const form = useForm({
    _method: 'patch',
    name: user.value.name,
    email: user.value.email,

    // Password Fields
    current_password: '',
    password: '',
    password_confirmation: '',

    // Data Mitra
    nama_mitra: props.mitra.nama_mitra,
    kategori_id: props.mitra.kategori_id,
    lokasi_id: props.mitra.lokasi_id,
    alamat_mitra: props.mitra.alamat_mitra,
    website_mitra: props.mitra.website_mitra || '',
    deskripsi_mitra: props.mitra.deskripsi_mitra,
    logo_mitra: null as any,
});

const logoPreview = ref<string | null>(
    props.mitra.logo_mitra ? `/storage/${props.mitra.logo_mitra}` : null,
);

const handleLogoChange = (e: any) => {
    const file = e.target.files[0];

    if (file) {
        form.logo_mitra = file;
        const reader = new FileReader();
        reader.onload = (e) => (logoPreview.value = e.target?.result as string);
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    form.post((window as any).route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('current_password', 'password', 'password_confirmation');
            Swal.fire({
                title: 'Profil Diperbarui!',
                text: 'Data akun dan keamanan Anda berhasil diperbarui.',
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
            <h1
                class="text-3xl font-black tracking-tighter uppercase italic md:text-4xl"
            >
                PENGATURAN <span class="text-sky-700">PROFIL</span>
            </h1>
            <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
        </div>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
            <div class="space-y-10 lg:col-span-8">
                <div
                    class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm"
                >
                    <div class="mb-8 flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-700"
                        >
                            <Building2 class="h-5 w-5" />
                        </div>
                        <h2
                            class="text-lg font-black tracking-tight uppercase italic"
                        >
                            Identitas
                            <span class="text-sky-700">Perusahaan</span>
                        </h2>
                    </div>

                    <div class="space-y-8">
                        <div
                            class="flex flex-col items-center gap-6 md:flex-row"
                        >
                            <div
                                class="group relative h-32 w-32 shrink-0 overflow-hidden rounded-4xl border-4 border-slate-50 bg-slate-100 shadow-inner"
                            >
                                <img
                                    v-if="logoPreview"
                                    :src="logoPreview"
                                    class="h-full w-full object-cover"
                                />
                                <div
                                    v-else
                                    class="flex h-full w-full items-center justify-center text-slate-300"
                                >
                                    <Building2 class="h-12 w-12" />
                                </div>
                                <label
                                    class="absolute inset-0 flex cursor-pointer items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100"
                                >
                                    <ImagePlus class="h-8 w-8 text-white" />
                                    <input
                                        type="file"
                                        @change="handleLogoChange"
                                        class="hidden"
                                        accept="image/*"
                                    />
                                </label>
                            </div>
                            <div class="space-y-1 text-center md:text-left">
                                <h4 class="text-xs font-black uppercase italic">
                                    Logo Instansi
                                </h4>
                                <p
                                    class="text-[10px] font-bold text-slate-400 uppercase italic"
                                >
                                    Klik gambar untuk mengubah logo.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <label
                                    class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                                    >Nama Perusahaan</label
                                >
                                <input
                                    v-model="form.nama_mitra"
                                    class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20"
                                />
                                <InputError :message="form.errors.nama_mitra" />
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                                    >Website</label
                                >
                                <input
                                    v-model="form.website_mitra"
                                    placeholder="https://..."
                                    class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <label
                                    class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                                    >Kategori</label
                                >
                                <select
                                    v-model="form.kategori_id"
                                    class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none"
                                >
                                    <option
                                        v-for="kat in kategoris"
                                        :key="kat.id"
                                        :value="kat.id"
                                    >
                                        {{ kat.nama_kategori }}
                                    </option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                                    >Lokasi</label
                                >
                                <select
                                    v-model="form.lokasi_id"
                                    class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none"
                                >
                                    <option
                                        v-for="lok in lokasis"
                                        :key="lok.id"
                                        :value="lok.id"
                                    >
                                        {{ lok.nama_lokasi }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                                >Deskripsi</label
                            >
                            <textarea
                                v-model="form.deskripsi_mitra"
                                rows="4"
                                class="w-full rounded-3xl border border-slate-200 bg-slate-50 p-6 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm"
                >
                    <div class="mb-8 flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"
                        >
                            <User class="h-5 w-5" />
                        </div>
                        <h2
                            class="text-lg font-black tracking-tight uppercase italic"
                        >
                            Informasi <span class="text-indigo-600">Admin</span>
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <label
                                    class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                                    >Nama Admin</label
                                >
                                <input
                                    v-model="form.name"
                                    class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-indigo-600/20"
                                />
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                                    >Email Akun</label
                                >
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-indigo-600/20"
                                />
                            </div>
                        </div>

                        <hr class="border-slate-100" />

                        <div class="space-y-6">
                            <div class="flex items-center gap-2 text-rose-500">
                                <Lock class="h-4 w-4" />
                                <h3
                                    class="text-[10px] font-black tracking-widest uppercase italic"
                                >
                                    Ubah Kata Sandi (Opsional)
                                </h3>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <div class="space-y-2">
                                    <label
                                        class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                                        >Sandi Saat Ini</label
                                    >
                                    <input
                                        v-model="form.current_password"
                                        type="password"
                                        class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-rose-500/20"
                                    />
                                    <InputError
                                        :message="form.errors.current_password"
                                    />
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                                        >Sandi Baru</label
                                    >
                                    <input
                                        v-model="form.password"
                                        type="password"
                                        class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-rose-500/20"
                                    />
                                    <InputError
                                        :message="form.errors.password"
                                    />
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                                        >Konfirmasi Sandi</label
                                    >
                                    <input
                                        v-model="form.password_confirmation"
                                        type="password"
                                        class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-rose-500/20"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-10 flex items-center justify-between border-t border-slate-100 pt-8"
                    >
                        <p
                            class="text-[10px] font-bold text-slate-400 uppercase italic"
                        >
                            Klik simpan untuk memperbarui profil & keamanan.
                        </p>
                        <button
                            @click="submit"
                            :disabled="form.processing"
                            class="flex items-center gap-3 rounded-2xl bg-sky-700 px-8 py-5 text-sm font-black text-white uppercase italic shadow-xl shadow-sky-900/20 transition-all hover:bg-sky-800 disabled:opacity-50"
                        >
                            <Save class="h-5 w-5" />
                            {{
                                form.processing
                                    ? 'Menyimpan...'
                                    : 'Simpan Perubahan'
                            }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-8 lg:col-span-4">
                <div
                    class="rounded-[2.5rem] bg-slate-900 p-8 text-white shadow-xl"
                >
                    <div
                        class="mb-6 flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-emerald-400"
                    >
                        <ShieldCheck class="h-5 w-5" />
                    </div>
                    <h3 class="text-sm font-black uppercase italic">
                        Tips Keamanan
                    </h3>
                    <p
                        class="mt-4 text-[10px] leading-relaxed font-medium uppercase italic opacity-60"
                    >
                        Gunakan kombinasi huruf kapital, angka, dan simbol untuk
                        kata sandi yang kuat. Jangan pernah bagikan email dan
                        sandi login Anda kepada siapapun.
                    </p>
                </div>

                <div
                    class="rounded-[2.5rem] border border-rose-100 bg-rose-50/20 p-2"
                >
                    <div
                        class="rounded-[2.2rem] border border-rose-50 bg-white p-6 shadow-sm"
                    >
                        <DeleteUser />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
