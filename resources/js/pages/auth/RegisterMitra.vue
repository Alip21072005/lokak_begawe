<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Eye, EyeOff, Building2, User } from 'lucide-vue-next';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';

defineProps({
    lokasis: Array as () => Array<{ id: string; nama_lokasi: string }>,
    kategoris: Array as () => Array<{ id: string; nama_kategori: string }>,
});

// --- STATE & ROUTE ---
const currentStep = ref(1);
const showPassword = ref(false);
const showPasswordConfirm = ref(false);
const routeLogin = (window as any).route('login');
const routeRegisterPelamar = (window as any).route('register.pelamar');
const routeRegisterMitraPost = (window as any).route('register.mitra.post');

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    nama_mitra: '',
    nohp_mitra: '',
    lokasi_id: '',
    kategori_id: '',
    alamat_mitra: '',
    deskripsi_mitra: '',
    logo_mitra: null as File | null,
    banner_mitra: null as File | null,
});

// --- LOGIC ---
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

const submit = () => {
    form.post(routeRegisterMitraPost, {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Daftar Mitra Perusahaan - Lokak Begawe" />

    <div
        class="flex min-h-screen w-full items-center justify-center bg-slate-50 p-4 py-10 text-slate-900 sm:p-6"
    >
        <div
            class="w-full max-w-xl rounded-[2.5rem] border border-slate-100/50 bg-white p-8 shadow-[0_20px_50px_rgba(0,0,0,0.05)] sm:p-12"
        >
            <div class="mb-10 flex flex-col items-center text-center">
                <div
                    class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-50 text-sky-700 shadow-sm"
                >
                    <User v-if="currentStep === 1" class="h-6 w-6" />
                    <Building2 v-else class="h-6 w-6" />
                </div>
                <h2
                    class="mb-2 text-[10px] font-black tracking-[0.3em] text-slate-300 uppercase italic"
                >
                    TAHAP {{ currentStep }} DARI 2
                </h2>
                <h1
                    class="text-3xl leading-tight font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl"
                >
                    HALO, <span class="text-sky-700">MITRA</span>
                </h1>

                <div class="mt-6 flex w-full max-w-xs gap-3">
                    <div
                        :class="[
                            'h-1.5 w-1/2 rounded-full transition-all duration-500',
                            currentStep >= 1
                                ? 'bg-sky-700 shadow-[0_0_10px_rgba(3,105,161,0.3)]'
                                : 'bg-slate-100',
                        ]"
                    ></div>
                    <div
                        :class="[
                            'h-1.5 w-1/2 rounded-full transition-all duration-500',
                            currentStep === 2
                                ? 'bg-sky-700 shadow-[0_0_10px_rgba(3,105,161,0.3)]'
                                : 'bg-slate-100',
                        ]"
                    ></div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6" novalidate>
                <div
                    v-if="currentStep === 1"
                    class="animate-in space-y-5 duration-500 fade-in slide-in-from-bottom-4"
                >
                    <div class="space-y-2">
                        <Label
                            for="name"
                            class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                            >Nama PIC / Penanggung Jawab</Label
                        >
                        <Input
                            id="name"
                            v-model="form.name"
                            placeholder="Masukkan nama lengkap"
                            class="h-12 rounded-xl border-slate-200 bg-slate-50 px-5 font-bold focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="space-y-2">
                        <Label
                            for="email"
                            class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                            >Email Perusahaan</Label
                        >
                        <Input
                            id="email"
                            type="email"
                            v-model="form.email"
                            placeholder="hrd@perusahaan.com"
                            class="h-12 rounded-xl border-slate-200 bg-slate-50 px-5 font-bold focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="space-y-2">
                            <Label
                                class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                                >Password</Label
                            >
                            <div class="relative flex items-center">
                                <Input
                                    :type="showPassword ? 'text' : 'password'"
                                    v-model="form.password"
                                    placeholder="••••••••"
                                    class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-5 pr-12 font-bold focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-4 text-slate-400 hover:text-sky-700"
                                >
                                    <Eye
                                        v-if="!showPassword"
                                        class="h-4 w-4"
                                    /><EyeOff v-else class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <Label
                                class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                                >Konfirmasi</Label
                            >
                            <Input
                                :type="
                                    showPasswordConfirm ? 'text' : 'password'
                                "
                                v-model="form.password_confirmation"
                                placeholder="••••••••"
                                class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-5 font-bold focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                            />
                        </div>
                    </div>
                    <InputError :message="form.errors.password" />

                    <div class="pt-4">
                        <Button
                            type="button"
                            @click="nextStep"
                            class="h-14 w-full rounded-2xl bg-sky-700 text-[11px] font-black tracking-[0.2em] text-white uppercase italic shadow-xl shadow-sky-900/20 hover:bg-sky-800 active:scale-[0.98]"
                            >Selanjutnya</Button
                        >
                    </div>
                </div>

                <div
                    v-if="currentStep === 2"
                    class="animate-in space-y-5 duration-500 fade-in slide-in-from-bottom-4"
                >
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div class="space-y-2">
                            <Label
                                class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                                >Nama Instansi</Label
                            >
                            <Input
                                v-model="form.nama_mitra"
                                placeholder="PT. Maju Mundur"
                                class="h-12 rounded-xl border-slate-200 bg-slate-50 px-5 font-bold focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                            />
                        </div>
                        <div class="space-y-2">
                            <Label
                                class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                                >No. WhatsApp</Label
                            >
                            <Input
                                v-model="form.nohp_mitra"
                                placeholder="08..."
                                class="h-12 rounded-xl border-slate-200 bg-slate-50 px-5 font-bold focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                            />
                        </div>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div class="space-y-2">
                            <Label
                                class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                                >Area</Label
                            >
                            <select
                                v-model="form.lokasi_id"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:border-sky-600 focus:ring-1 focus:ring-sky-600"
                            >
                                <option value="" disabled>Pilih Lokasi</option>
                                <option
                                    v-for="l in lokasis"
                                    :key="l.id"
                                    :value="l.id"
                                >
                                    {{ l.nama_lokasi }}
                                </option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <Label
                                class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                                >Kategori</Label
                            >
                            <select
                                v-model="form.kategori_id"
                                class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:border-sky-600 focus:ring-1 focus:ring-sky-600"
                            >
                                <option value="" disabled>Pilih Bidang</option>
                                <option
                                    v-for="k in kategoris"
                                    :key="k.id"
                                    :value="k.id"
                                >
                                    {{ k.nama_kategori }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label
                            class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                            >Alamat Lengkap</Label
                        >
                        <textarea
                            v-model="form.alamat_mitra"
                            rows="2"
                            class="w-full rounded-xl border-slate-200 bg-slate-50 p-4 text-sm font-bold outline-none focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label
                                class="text-[9px] font-black text-slate-400 uppercase italic"
                                >Logo Perusahaan</Label
                            >
                            <input
                                type="file"
                                @change="handleLogoUpload"
                                class="text-[10px] file:mr-2 file:rounded-md file:border-0 file:bg-sky-50 file:px-2 file:py-1 file:text-sky-700"
                            />
                        </div>
                        <div class="space-y-2">
                            <Label
                                class="text-[9px] font-black text-slate-400 uppercase italic"
                                >Banner Profil</Label
                            >
                            <input
                                type="file"
                                @change="handleBannerUpload"
                                class="text-[10px] file:mr-2 file:rounded-md file:border-0 file:bg-slate-100 file:px-2 file:py-1 file:text-slate-600"
                            />
                        </div>
                    </div>

                    <div class="flex gap-3 pt-6">
                        <Button
                            type="button"
                            variant="outline"
                            @click="currentStep = 1"
                            class="h-14 w-1/3 rounded-2xl border-slate-200 font-black text-slate-400 italic"
                            >Kembali</Button
                        >
                        <Button
                            type="submit"
                            :disabled="form.processing"
                            class="h-14 w-2/3 rounded-2xl bg-sky-700 text-[11px] font-black tracking-[0.2em] text-white uppercase italic shadow-xl shadow-sky-900/20 hover:bg-sky-800"
                        >
                            <Spinner
                                v-if="form.processing"
                                class="mr-2 h-4 w-4"
                            />
                            {{
                                form.processing
                                    ? 'MEMPROSES...'
                                    : 'DAFTAR SEKARANG'
                            }}
                        </Button>
                    </div>
                </div>

                <div
                    class="mt-8 flex flex-col items-center space-y-4 border-t border-slate-50 pt-8 text-center"
                >
                    <p
                        class="text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                    >
                        Sudah punya akun?
                        <Link
                            :href="routeLogin"
                            class="ml-1 text-sky-700 hover:underline"
                            >Masuk Disini</Link
                        >
                    </p>
                    <div class="h-1 w-8 rounded-full bg-slate-100"></div>
                    <p
                        class="text-[10px] font-black tracking-widest text-slate-300 uppercase italic"
                    >
                        Sedang mencari kerja?
                        <Link
                            :href="routeRegisterPelamar"
                            class="ml-1 text-slate-500 hover:underline"
                            >Daftar Pelamar</Link
                        >
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
/* DOUBLE EYE FIX */
input::-ms-reveal,
input::-ms-clear {
    display: none !important;
}
input:focus {
    outline: none !important;
}
input::placeholder {
    font-weight: 700;
    font-style: italic;
    text-transform: uppercase;
    font-size: 10px;
    letter-spacing: 0.1em;
    opacity: 0.3;
}
</style>
