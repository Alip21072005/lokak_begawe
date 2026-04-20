<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Eye, EyeOff } from 'lucide-vue-next';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';

// --- DEFINISI RUTE (TS SAFE) ---
const routeLogin = (window as any).route('login');
const routeRegisterMitra = (window as any).route('register.mitra');
const routeRegisterPost = (window as any).route('register.pelamar.post');
const routeGoogleRedirect = (window as any).route('google.redirect');

const showPassword = ref(false);
const showPasswordConfirm = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(routeRegisterPost, {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const loginWithGoogle = () => {
    window.location.href = routeGoogleRedirect;
};
</script>

<template>
    <Head title="Daftar Pencari Kerja - Lokak Begawe" />

    <div
        class="flex min-h-screen w-full items-center justify-center bg-slate-50 p-4 py-10 text-slate-900 sm:p-6"
    >
        <div
            class="w-full max-w-xl rounded-[2.5rem] border border-slate-100 bg-white p-8 shadow-[0_20px_50px_rgba(0,0,0,0.05)] sm:p-12"
        >
            <div class="mb-10 flex flex-col items-center text-center">
                <h2
                    class="mb-2 text-[10px] font-black tracking-[0.3em] text-slate-300 uppercase italic"
                >
                    GATEWAY REGISTRASI
                </h2>
                <h1
                    class="text-4xl leading-tight font-black tracking-tighter text-slate-900 uppercase italic"
                >
                    HALO, <span class="text-sky-700">PENCARI KERJA</span>
                </h1>
                <div class="mt-4 h-1.5 w-24 rounded-full bg-slate-100"></div>
                <p
                    class="mt-4 text-[10px] leading-relaxed font-bold tracking-widest text-slate-400 uppercase italic"
                >
                    Daftar sekarang untuk mengakses ribuan loker <br />
                    di wilayah Bengkulu dan sekitarnya.
                </p>
            </div>

            <div class="mb-8">
                <Button
                    @click="loginWithGoogle"
                    variant="outline"
                    class="group flex h-16 w-full items-center justify-center rounded-2xl border-slate-200 bg-white text-xs font-black tracking-widest text-slate-600 uppercase italic shadow-sm transition-all hover:border-sky-700/30 hover:bg-slate-50 hover:text-sky-700 active:scale-[0.98]"
                    type="button"
                >
                    <img
                        src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                        class="mr-3 h-6 w-6 transition-transform group-hover:scale-110"
                        alt="Google Logo"
                    />
                    Masuk Menggunakan Google
                </Button>
            </div>

            <div class="relative mb-10">
                <div class="absolute inset-0 flex items-center">
                    <span class="w-full border-t border-slate-100"></span>
                </div>
                <div class="relative flex justify-center text-[10px] uppercase">
                    <span
                        class="bg-white px-6 font-black tracking-[0.3em] text-slate-300 italic"
                    >
                        ATAU KREDENSIAL EMAIL
                    </span>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="space-y-2">
                    <Label
                        for="name"
                        class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                        >Nama Lengkap Anda</Label
                    >
                    <Input
                        id="name"
                        v-model="form.name"
                        placeholder="Masukkan nama sesuai identitas"
                        class="h-14 rounded-2xl border-slate-200 bg-slate-50 px-6 font-bold shadow-sm transition-all focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="space-y-2">
                    <Label
                        for="email"
                        class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                        >Alamat Email Aktif</Label
                    >
                    <Input
                        id="email"
                        type="email"
                        v-model="form.email"
                        placeholder="nama@email.com"
                        class="h-14 rounded-2xl border-slate-200 bg-slate-50 px-6 font-bold shadow-sm transition-all focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="space-y-2">
                        <Label
                            for="password"
                            class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                            >Password</Label
                        >
                        <div class="relative flex items-center">
                            <Input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                v-model="form.password"
                                placeholder="••••••••"
                                class="no-browser-eye h-14 w-full rounded-2xl border-slate-200 bg-slate-50 px-6 font-bold shadow-sm transition-all focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-5 z-10 text-slate-400 transition-colors hover:text-sky-700"
                            >
                                <Eye v-if="!showPassword" class="h-5 w-5" />
                                <EyeOff v-else class="h-5 w-5" />
                            </button>
                        </div>
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="space-y-2">
                        <Label
                            for="password_confirmation"
                            class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                            >Konfirmasi</Label
                        >
                        <div class="relative flex items-center">
                            <Input
                                id="password_confirmation"
                                :type="
                                    showPasswordConfirm ? 'text' : 'password'
                                "
                                v-model="form.password_confirmation"
                                placeholder="••••••••"
                                class="no-browser-eye h-14 w-full rounded-2xl border-slate-200 bg-slate-50 px-6 font-bold shadow-sm transition-all focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                            />
                            <button
                                type="button"
                                @click="
                                    showPasswordConfirm = !showPasswordConfirm
                                "
                                class="absolute right-5 z-10 text-slate-400 transition-colors hover:text-sky-700"
                            >
                                <Eye
                                    v-if="!showPasswordConfirm"
                                    class="h-5 w-5"
                                />
                                <EyeOff v-else class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <Button
                        type="submit"
                        :disabled="form.processing"
                        class="h-16 w-full rounded-3xl bg-sky-700 text-sm font-black tracking-[0.2em] text-white uppercase italic shadow-2xl shadow-sky-900/30 transition-all hover:scale-[1.02] hover:bg-sky-800 active:scale-[0.98] disabled:opacity-50"
                    >
                        <Spinner v-if="form.processing" class="mr-3 h-5 w-5" />
                        {{
                            form.processing
                                ? 'SEDANG MENDAFTAR...'
                                : 'DAFTARKAN AKUN SAYA'
                        }}
                    </Button>
                </div>

                <div
                    class="mt-10 flex flex-col items-center space-y-4 border-t border-slate-50 pt-10 text-center"
                >
                    <p
                        class="text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                    >
                        Sudah punya akun?
                        <Link
                            :href="routeLogin"
                            class="ml-1 font-black text-sky-700 decoration-sky-300 underline-offset-4 hover:underline"
                            >MASUK DISINI</Link
                        >
                    </p>
                    <div class="h-1 w-8 rounded-full bg-slate-100"></div>
                    <p
                        class="text-[10px] font-black tracking-widest text-slate-300 uppercase italic"
                    >
                        Ingin cari karyawan?
                        <Link
                            :href="routeRegisterMitra"
                            class="ml-1 font-black text-slate-400 transition-colors hover:text-slate-600"
                            >DAFTAR SEBAGAI MITRA</Link
                        >
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
/* MEMBERSIHKAN MATA BAWAAN BROWSER 
   Ini yang bakal ilangin ikon 'double' Lip!
*/
input::-ms-reveal,
input::-ms-clear {
    display: none !important;
}

/* Untuk browser berbasis webkit jika muncul otomatis */
input::-webkit-contacts-auto-fill-button,
input::-webkit-credentials-auto-fill-button {
    visibility: hidden;
    display: none !important;
    pointer-events: none;
    position: absolute;
    right: 0;
}

input:focus {
    outline: none !important;
}
</style>
