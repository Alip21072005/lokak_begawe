<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';

const props = defineProps({
    lokasis: Array as () => Array<{ id: string; nama_lokasi: string }>,
    kategoris: Array as () => Array<{ id: string; nama_kategori: string }>,
});

const currentStep = ref(1);
const showPassword = ref(false);
const showPasswordConfirm = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    nama_mitra: '', // Berubah dari company_name
    nohp_mitra: '',
    lokasi_id: '',
    kategori_id: '',
    alamat_mitra: '',
    deskripsi_mitra: '', // Berubah dari deksipsi_mitra
    logo_mitra: null as File | null,
    banner_mitra: null as File | null,
});

const handleLogoUpload = (e: Event) => {
    const target = e.target as HTMLInputElement;

    if (target.files) {
        form.logo_mitra = target.files[0];
    }
};

const handleBannerUpload = (e: Event) => {
    const target = e.target as HTMLInputElement;

    if (target.files) {
        form.banner_mitra = target.files[0];
    }
};

const nextStep = () => {
    form.clearErrors();

    if (!form.name || !form.email || !form.password) {
        if (!form.name) {
            form.setError('name', 'Nama penanggung jawab wajib diisi.');
        }

        if (!form.email) {
            form.setError('email', 'Email akun wajib diisi.');
        }

        if (!form.password) {
            form.setError('password', 'Password wajib diisi.');
        }

        return;
    }

    if (form.password !== form.password_confirmation) {
        form.setError(
            'password_confirmation',
            'Konfirmasi password tidak sesuai.',
        );

        return;
    }

    currentStep.value = 2;
};

const prevStep = () => {
    currentStep.value = 1;
};

const submit = () => {
    form.post('/register/mitra', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Daftar Mitra Perusahaan" />

    <div
        class="flex min-h-screen w-full items-center justify-center bg-slate-50 p-4 py-10 text-slate-900 sm:p-6"
    >
        <div
            class="w-full max-w-xl rounded-[2.5rem] bg-white p-8 shadow-[0_20px_50px_rgba(0,0,0,0.05)] sm:p-12"
        >
            <div class="mb-10 flex flex-col items-center text-center">
                <h2
                    class="mb-2 text-sm font-bold tracking-[0.2em] text-slate-400 uppercase"
                >
                    Registrasi Tahap {{ currentStep }} dari 2
                </h2>
                <h1
                    class="text-3xl leading-tight font-black tracking-tight text-slate-900 uppercase italic"
                >
                    Halo, <span class="text-sky-700">Mitra</span>
                </h1>

                <div class="mt-6 flex w-full max-w-xs gap-2">
                    <div
                        :class="[
                            'h-2 w-1/2 rounded-full transition-all',
                            currentStep >= 1 ? 'bg-sky-700' : 'bg-slate-200',
                        ]"
                    ></div>
                    <div
                        :class="[
                            'h-2 w-1/2 rounded-full transition-all',
                            currentStep === 2 ? 'bg-sky-700' : 'bg-slate-200',
                        ]"
                    ></div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5" novalidate>
                <div
                    v-show="currentStep === 1"
                    class="flex animate-in flex-col space-y-5 duration-500 fade-in"
                >
                    <div class="grid gap-2">
                        <Label
                            for="name"
                            class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                            >Nama Penanggung Jawab (PIC)</Label
                        >
                        <Input
                            id="name"
                            v-model="form.name"
                            placeholder="Masukkan nama lengkap"
                            class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 text-sm font-medium shadow-sm transition-all focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label
                            for="email"
                            class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                            >Email Akun</Label
                        >
                        <Input
                            id="email"
                            type="email"
                            v-model="form.email"
                            placeholder="hrd@perusahaan.com"
                            class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 text-sm font-medium shadow-sm transition-all focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div
                        class="grid grid-cols-1 items-start gap-4 sm:grid-cols-2"
                    >
                        <div class="flex flex-col gap-2">
                            <Label
                                for="password"
                                class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                                >Password</Label
                            >
                            <div class="relative flex items-center">
                                <Input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    v-model="form.password"
                                    placeholder="••••••••"
                                    class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 pr-12 text-sm font-medium shadow-sm transition-all focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-0 flex h-full items-center px-4 text-slate-400 transition-colors hover:text-sky-700 focus:outline-none"
                                >
                                    <svg
                                        v-if="!showPassword"
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="18"
                                        height="18"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path
                                            d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"
                                        />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <svg
                                        v-else
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="18"
                                        height="18"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path
                                            d="M9.88 9.88a3 3 0 1 0 4.24 4.24"
                                        />
                                        <path
                                            d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"
                                        />
                                        <path
                                            d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"
                                        />
                                        <line x1="2" x2="22" y1="2" y2="22" />
                                    </svg>
                                </button>
                            </div>
                            <InputError
                                :message="form.errors.password"
                                class="mt-1"
                            />
                        </div>

                        <div class="flex flex-col gap-2">
                            <Label
                                for="password_confirmation"
                                class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                                >Konfirmasi</Label
                            >
                            <div class="relative flex items-center">
                                <Input
                                    id="password_confirmation"
                                    :type="
                                        showPasswordConfirm
                                            ? 'text'
                                            : 'password'
                                    "
                                    v-model="form.password_confirmation"
                                    placeholder="••••••••"
                                    @keydown.enter.prevent="nextStep"
                                    class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 pr-12 text-sm font-medium shadow-sm transition-all focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                                />
                                <button
                                    type="button"
                                    @click="
                                        showPasswordConfirm =
                                            !showPasswordConfirm
                                    "
                                    class="absolute right-0 flex h-full items-center px-4 text-slate-400 transition-colors hover:text-sky-700 focus:outline-none"
                                >
                                    <svg
                                        v-if="!showPasswordConfirm"
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="18"
                                        height="18"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path
                                            d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"
                                        />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <svg
                                        v-else
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="18"
                                        height="18"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <path
                                            d="M9.88 9.88a3 3 0 1 0 4.24 4.24"
                                        />
                                        <path
                                            d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"
                                        />
                                        <path
                                            d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"
                                        />
                                        <line x1="2" x2="22" y1="2" y2="22" />
                                    </svg>
                                </button>
                            </div>
                            <div
                                v-if="
                                    !form.errors.password_confirmation &&
                                    form.errors.password
                                "
                                class="mt-1 h-5"
                            ></div>
                            <InputError
                                :message="form.errors.password_confirmation"
                                class="mt-1"
                            />
                        </div>
                    </div>

                    <div class="pt-4">
                        <Button
                            type="button"
                            @click="nextStep"
                            class="h-14 w-full rounded-2xl bg-sky-700 font-bold tracking-[0.15em] text-white uppercase shadow-lg shadow-sky-700/20 transition-all hover:bg-sky-800 hover:shadow-sky-800/30 active:scale-[0.98]"
                        >
                            Selanjutnya
                        </Button>
                    </div>
                </div>

                <div
                    v-show="currentStep === 2"
                    class="flex animate-in flex-col space-y-5 duration-500 fade-in"
                >
                    <div class="grid gap-2">
                        <Label
                            for="nama_mitra"
                            class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                            >Nama Perusahaan</Label
                        >
                        <Input
                            id="nama_mitra"
                            v-model="form.nama_mitra"
                            placeholder="Contoh: PT. Maju Bersama"
                            class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 text-sm font-medium shadow-sm transition-all focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        />
                        <InputError :message="form.errors.nama_mitra" />
                    </div>

                    <div class="grid gap-2">
                        <Label
                            for="nohp_mitra"
                            class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                            >Nomor Telepon / WhatsApp Perusahaan</Label
                        >
                        <Input
                            id="nohp_mitra"
                            v-model="form.nohp_mitra"
                            placeholder="Contoh: 08123456789"
                            class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 text-sm font-medium shadow-sm transition-all focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        />
                        <InputError :message="form.errors.nohp_mitra" />
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="grid gap-2">
                            <Label
                                for="lokasi_id"
                                class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                                >Area / Lokasi</Label
                            >
                            <div class="relative">
                                <select
                                    id="lokasi_id"
                                    v-model="form.lokasi_id"
                                    class="h-12 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%2394a3b8%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E')] bg-size-[1.25rem_1.25rem] bg-position-[right_1rem_center] bg-no-repeat px-4 pr-10 text-sm font-medium shadow-sm transition-all outline-none focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                                >
                                    <option value="" disabled>
                                        Pilih Lokasi
                                    </option>
                                    <option
                                        v-for="lokasi in props.lokasis"
                                        :key="lokasi.id"
                                        :value="lokasi.id"
                                    >
                                        {{ lokasi.nama_lokasi }}
                                    </option>
                                </select>
                            </div>
                            <InputError :message="form.errors.lokasi_id" />
                        </div>

                        <div class="grid gap-2">
                            <Label
                                for="kategori_id"
                                class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                                >Bidang Industri</Label
                            >
                            <div class="relative">
                                <select
                                    id="kategori_id"
                                    v-model="form.kategori_id"
                                    class="h-12 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%2394a3b8%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E')] bg-size-[1.25rem_1.25rem] bg-position-[right_1rem_center] bg-no-repeat px-4 pr-10 text-sm font-medium shadow-sm transition-all outline-none focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                                >
                                    <option value="" disabled>
                                        Pilih Bidang
                                    </option>
                                    <option
                                        v-for="kategori in props.kategoris"
                                        :key="kategori.id"
                                        :value="kategori.id"
                                    >
                                        {{ kategori.nama_kategori }}
                                    </option>
                                </select>
                            </div>
                            <InputError :message="form.errors.kategori_id" />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label
                            for="alamat_mitra"
                            class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                            >Alamat Lengkap Perusahaan</Label
                        >
                        <textarea
                            id="alamat_mitra"
                            v-model="form.alamat_mitra"
                            rows="3"
                            placeholder="Masukkan nama jalan, gedung, atau patokan"
                            class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm font-medium shadow-sm transition-all outline-none focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        ></textarea>
                        <InputError :message="form.errors.alamat_mitra" />
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="grid min-w-0 gap-2">
                            <Label
                                for="logo_mitra"
                                class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                                >Upload Logo</Label
                            >
                            <input
                                id="logo_mitra"
                                type="file"
                                @change="handleLogoUpload"
                                accept="image/*"
                                class="block w-full cursor-pointer truncate text-[11px] text-slate-500 transition-all file:mr-2 file:rounded-lg file:border-0 file:bg-sky-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-sky-700 hover:file:bg-sky-100"
                            />
                            <InputError :message="form.errors.logo_mitra" />
                        </div>

                        <div class="grid min-w-0 gap-2">
                            <Label
                                for="banner_mitra"
                                class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                                >Upload Banner</Label
                            >
                            <input
                                id="banner_mitra"
                                type="file"
                                @change="handleBannerUpload"
                                accept="image/*"
                                class="block w-full cursor-pointer truncate text-[11px] text-slate-500 transition-all file:mr-2 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200"
                            />
                            <InputError :message="form.errors.banner_mitra" />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label
                            for="deskripsi_mitra"
                            class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                            >Deskripsi Perusahaan</Label
                        >
                        <textarea
                            id="deskripsi_mitra"
                            v-model="form.deskripsi_mitra"
                            rows="4"
                            placeholder="Ceritakan singkat profil perusahaan Anda..."
                            class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm font-medium shadow-sm transition-all outline-none focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        ></textarea>
                        <InputError :message="form.errors.deskripsi_mitra" />
                    </div>

                    <div class="flex gap-3 pt-4">
                        <Button
                            type="button"
                            variant="outline"
                            @click="prevStep"
                            class="h-14 w-1/3 rounded-2xl border border-slate-300 font-bold tracking-widest text-slate-600 uppercase transition-all hover:bg-slate-100"
                        >
                            Kembali
                        </Button>
                        <Button
                            type="submit"
                            :disabled="form.processing"
                            class="h-14 w-2/3 rounded-2xl bg-sky-700 font-bold tracking-[0.15em] text-white uppercase shadow-lg shadow-sky-700/20 transition-all hover:bg-sky-800 hover:shadow-sky-800/30 active:scale-[0.98]"
                        >
                            <Spinner
                                v-if="form.processing"
                                class="mr-2 h-5 w-5 text-white"
                            />
                            <span v-if="!form.processing">Daftar Sekarang</span>
                            <span v-else>Memproses...</span>
                        </Button>
                    </div>
                </div>

                <div class="space-y-5 pt-6 text-center">
                    <p
                        class="text-xs font-semibold tracking-wide text-slate-500 uppercase"
                    >
                        Sudah punya akun?
                        <Link
                            href="/login"
                            class="ml-1 text-sky-700 transition-all hover:text-sky-800 hover:underline hover:underline-offset-4"
                            >Masuk di sini</Link
                        >
                    </p>
                    <div class="relative py-2">
                        <div class="absolute inset-0 flex items-center">
                            <span
                                class="w-full border-t border-slate-200"
                            ></span>
                        </div>
                        <div
                            class="relative flex justify-center text-[10px] uppercase"
                        >
                            <span
                                class="bg-white px-3 font-bold tracking-widest text-slate-400"
                                >ATAU</span
                            >
                        </div>
                    </div>
                    <p
                        class="text-xs font-semibold tracking-wide text-slate-500 uppercase"
                    >
                        Sedang mencari kerja?
                        <Link
                            href="/register/pelamar"
                            class="ml-1 text-slate-700 transition-all hover:text-slate-900 hover:underline hover:underline-offset-4"
                            >Daftar Pelamar</Link
                        >
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
input:focus,
textarea:focus,
select:focus {
    outline: none !important;
}

input::placeholder,
textarea::placeholder {
    font-weight: 500;
    opacity: 0.6;
}

input::-ms-reveal,
input::-ms-clear,
input::-webkit-credentials-auto-fill-button {
    display: none !important;
}
</style>
