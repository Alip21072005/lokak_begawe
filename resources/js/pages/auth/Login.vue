<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

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

// Fungsi untuk menangani klik tombol Google
const loginWithGoogle = () => {
    // Arahkan langsung ke rute backend Laravel untuk inisiasi OAuth Google
    window.location.href = '/auth/google/redirect';
};
</script>

<template>
    <Head title="Log in" />

    <div
        class="flex min-h-screen w-full items-center justify-center bg-slate-50 p-4 sm:p-6"
    >
        <div
            class="w-full max-w-xl rounded-[2.5rem] bg-white p-8 shadow-[0_20px_50px_rgba(0,0,0,0.05)] sm:p-12"
        >
            <div class="mb-10 flex flex-col items-center text-center">
                <h2
                    class="mb-2 text-sm font-bold tracking-[0.2em] text-slate-400 uppercase"
                >
                    Login
                </h2>
                <h1
                    class="text-3xl leading-tight font-black tracking-tight text-slate-900 uppercase italic"
                >
                    Halo, <span class="text-sky-700">Pencari Lokak</span>
                </h1>
                <p
                    class="mt-2 text-xs font-medium tracking-wide text-slate-500 uppercase"
                >
                    Masuk untuk mengakses dashboard Anda
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
                    class="mb-6 rounded-xl border border-green-100 bg-green-50 p-4 text-center text-sm font-semibold text-green-700"
                >
                    {{ status }}
                </div>
            </Transition>

            <div class="mb-8">
                <Button
                    @click="loginWithGoogle"
                    variant="outline"
                    class="flex h-12 w-full items-center justify-center rounded-xl border-slate-200 bg-white font-semibold text-slate-600 shadow-sm transition-all hover:bg-slate-50 hover:text-slate-900 active:scale-[0.98]"
                    type="button"
                >
                    <img
                        src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                        class="mr-3 h-5 w-5"
                        alt="Google"
                    />
                    Lanjutkan dengan Google
                </Button>
            </div>

            <div class="relative mb-8">
                <div class="absolute inset-0 flex items-center">
                    <span class="w-full border-t border-slate-200"></span>
                </div>
                <div class="relative flex justify-center text-[10px] uppercase">
                    <span
                        class="bg-white px-3 font-bold tracking-widest text-slate-400"
                    >
                        Atau Gunakan Email
                    </span>
                </div>
            </div>

            <Form
                v-bind="store.form()"
                :reset-on-success="['password']"
                v-slot="{ errors, processing }"
                class="space-y-5"
            >
                <div class="grid gap-5">
                    <div class="grid gap-2">
                        <Label
                            for="email"
                            class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                        >
                            Alamat Email
                        </Label>
                        <Input
                            id="email"
                            type="email"
                            name="email"
                            required
                            autofocus
                            placeholder="Masukkan email kamu"
                            class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 text-sm font-medium shadow-sm transition-all placeholder:text-slate-400 focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        />
                        <InputError :message="errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <div class="flex items-center justify-between">
                            <Label
                                for="password"
                                class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                            >
                                Kata Sandi
                            </Label>
                            <TextLink
                                v-if="canResetPassword"
                                :href="request()"
                                class="text-[10px] font-bold tracking-wider text-sky-700 uppercase transition-all hover:text-sky-800 hover:underline hover:underline-offset-4"
                            >
                                Lupa Sandi?
                            </TextLink>
                        </div>
                        <PasswordInput
                            id="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 text-sm font-medium shadow-sm transition-all placeholder:text-slate-400 focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        />
                        <InputError :message="errors.password" />
                    </div>

                    <div class="pt-2">
                        <Button
                            type="submit"
                            class="h-14 w-full rounded-2xl bg-sky-700 font-bold tracking-[0.15em] text-white uppercase shadow-lg shadow-sky-700/20 transition-all hover:bg-sky-800 hover:shadow-sky-800/30 active:scale-[0.98]"
                            :disabled="processing"
                        >
                            <Spinner
                                v-if="processing"
                                class="mr-2 h-5 w-5 text-white"
                            />
                            <span v-if="!processing">GASAK!</span>
                            <span v-else>Memproses...</span>
                        </Button>
                    </div>
                </div>

                <div class="space-y-6 pt-8 text-center" v-if="canRegister">
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
                            >
                                Belum Punya Akun?
                            </span>
                        </div>
                    </div>

                    <div
                        class="flex flex-col items-center justify-center gap-3 sm:flex-row sm:gap-6"
                    >
                        <Link
                            href="/register/pelamar"
                            class="text-xs font-bold tracking-wider text-slate-600 uppercase transition-all hover:text-sky-700 hover:underline hover:underline-offset-4"
                        >
                            Daftar Pelamar
                        </Link>

                        <div
                            class="hidden h-1 w-1 rounded-full bg-slate-300 sm:block"
                        ></div>

                        <Link
                            href="/register/mitra"
                            class="text-xs font-bold tracking-wider text-slate-600 uppercase transition-all hover:text-sky-700 hover:underline hover:underline-offset-4"
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
/* Menghilangkan ring fokus default browser */
input:focus {
    outline: none !important;
}

/* Memastikan placeholder memiliki warna yang konsisten */
input::placeholder {
    font-weight: 500;
    opacity: 0.6;
}
</style>
