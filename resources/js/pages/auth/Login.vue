<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
// eslint-disable-next-line @typescript-eslint/no-unused-vars
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

// --- DEFINISI RUTE (TS SAFE) ---
const routeRegisterPelamar = (window as any).route('register.pelamar');
const routeRegisterMitra = (window as any).route('register.mitra');
const routeGoogleRedirect = (window as any).route('google.redirect');

defineOptions({
    layout: {
        title: 'Masuk ke Akun Anda',
        description:
            'Masukkan email dan kata sandi Anda untuk mengakses dashboard',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();

const loginWithGoogle = () => {
    window.location.href = routeGoogleRedirect;
};
</script>

<template>
    <Head title="Masuk - Lokak Begawe" />

    <div
        class="flex min-h-screen w-full items-center justify-center bg-slate-50 p-4 py-10 text-slate-900 sm:p-6"
    >
        <div
            class="w-full max-w-xl rounded-[2.5rem] border border-slate-100/50 bg-white p-8 shadow-[0_20px_50px_rgba(0,0,0,0.05)] sm:p-12"
        >
            <div class="mb-10 flex flex-col items-center text-center">
                <h2
                    class="mb-2 text-[10px] font-black tracking-[0.3em] text-slate-300 uppercase italic"
                >
                    GATEWAY LOGIN
                </h2>
                <h1
                    class="text-3xl leading-tight font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl"
                >
                    HALO, <span class="text-sky-700">PENCARI LOKAK</span>
                </h1>
                <div class="mt-4 h-1.5 w-24 rounded-full bg-slate-100"></div>
                <p
                    class="mt-4 text-[10px] leading-relaxed font-bold tracking-widest text-slate-400 uppercase italic"
                >
                    Masuk untuk mengelola karir dan lowongan <br />
                    Anda di platform Lokak Begawe.
                </p>
            </div>

            <Transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="transform opacity-0 -translate-y-2"
                enter-to-class="transform opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="transform opacity-100 translate-y-0"
                leave-to-class="transform opacity-0 -translate-y-2"
            >
                <div
                    v-if="status"
                    class="mb-8 rounded-2xl border border-emerald-100 bg-emerald-50 px-6 py-4 text-center text-[11px] font-black tracking-widest text-emerald-700 uppercase italic"
                >
                    {{ status }}
                </div>
            </Transition>

            <div class="mb-8">
                <Button
                    @click="loginWithGoogle"
                    variant="outline"
                    class="group flex h-14 w-full items-center justify-center rounded-2xl border-slate-200 bg-white text-[11px] font-black tracking-widest text-slate-600 uppercase italic shadow-sm transition-all hover:border-sky-700/30 hover:bg-slate-50 hover:text-sky-700 active:scale-[0.98]"
                    type="button"
                >
                    <img
                        src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                        class="mr-3 h-5 w-5 transition-transform group-hover:scale-110"
                        alt="Google"
                    />
                    Lanjutkan dengan Google
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

            <Form
                v-bind="store.form()"
                :reset-on-success="['password']"
                v-slot="{ errors, processing }"
                class="space-y-6"
            >
                <div class="space-y-6">
                    <div class="space-y-2">
                        <Label
                            for="email"
                            class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                            >Alamat Email</Label
                        >
                        <Input
                            id="email"
                            type="email"
                            name="email"
                            required
                            autofocus
                            placeholder="Maukkan email terdaftar"
                            class="h-12 rounded-xl border-slate-200 bg-slate-50 px-5 font-bold shadow-sm transition-all focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        />
                        <InputError :message="errors.email" />
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between px-2">
                            <Label
                                for="password"
                                class="text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                                >Kata Sandi</Label
                            >
                            <Link
                                v-if="canResetPassword"
                                :href="request()"
                                class="text-[9px] font-black tracking-widest text-sky-700 uppercase italic transition-all hover:text-sky-800"
                            >
                                LUPA SANDI?
                            </Link>
                        </div>
                        <PasswordInput
                            id="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="no-browser-eye h-12 rounded-xl border-slate-200 bg-slate-50 px-5 font-bold shadow-sm transition-all focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        />
                        <InputError :message="errors.password" />
                    </div>

                    <div class="pt-4">
                        <Button
                            type="submit"
                            :disabled="processing"
                            class="h-14 w-full rounded-2xl bg-sky-700 text-[11px] font-black tracking-[0.2em] text-white uppercase italic shadow-xl shadow-sky-900/20 transition-all hover:scale-[1.02] hover:bg-sky-800 active:scale-[0.98] disabled:opacity-50"
                        >
                            <Spinner v-if="processing" class="mr-3 h-4 w-4" />
                            {{ processing ? 'MEMPROSES...' : 'GASAK MASUK!' }}
                        </Button>
                    </div>
                </div>

                <div
                    class="mt-8 flex flex-col items-center space-y-5 border-t border-slate-50 pt-8 text-center"
                    v-if="canRegister"
                >
                    <p
                        class="text-[10px] font-black tracking-widest text-slate-300 uppercase italic"
                    >
                        BELUM PUNYA AKUN?
                    </p>

                    <div
                        class="flex flex-col items-center justify-center gap-4 sm:flex-row sm:gap-8"
                    >
                        <Link
                            :href="routeRegisterPelamar"
                            class="text-[10px] font-black tracking-widest text-slate-500 uppercase italic underline-offset-4 transition-all hover:text-sky-700 hover:underline"
                        >
                            Daftar Pelamar
                        </Link>

                        <div
                            class="hidden h-1 w-1 rounded-full bg-slate-200 sm:block"
                        ></div>

                        <Link
                            :href="routeRegisterMitra"
                            class="text-[10px] font-black tracking-widest text-slate-500 uppercase italic underline-offset-4 transition-all hover:text-sky-700 hover:underline"
                        >
                            Daftar Mitra
                        </Link>
                    </div>
                </div>
            </Form>
        </div>
    </div>
</template>

<style scoped>
/* MAGIC CSS: Menghilangkan mata bawaan browser agar tidak double eye */
:deep(input::-ms-reveal),
:deep(input::-ms-clear) {
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
