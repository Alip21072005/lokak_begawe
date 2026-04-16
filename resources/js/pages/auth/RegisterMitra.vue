<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';

const form = useForm({
    company_name: '',
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/register/mitra', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Daftar Mitra Perusahaan" />

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
                    Registrasi
                </h2>
                <h1
                    class="text-3xl leading-tight font-black tracking-tight text-slate-900 uppercase italic"
                >
                    Halo, <span class="text-sky-700">Mitra</span>
                </h1>
                <p
                    class="mt-2 text-xs font-medium tracking-wide text-slate-500 uppercase"
                >
                    Pasang lowongan dan temukan talenta terbaik
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div class="grid gap-2">
                    <Label
                        for="company_name"
                        class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                    >
                        Nama Perusahaan
                    </Label>
                    <Input
                        id="company_name"
                        v-model="form.company_name"
                        required
                        autofocus
                        placeholder="Contoh: PT. Maju Bersama"
                        class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 text-sm font-medium shadow-sm transition-all placeholder:text-slate-400 focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                    />
                    <InputError :message="form.errors.company_name" />
                </div>

                <div class="grid gap-2">
                    <Label
                        for="name"
                        class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                    >
                        Nama Penanggung Jawab (PIC)
                    </Label>
                    <Input
                        id="name"
                        v-model="form.name"
                        required
                        placeholder="Masukkan nama lengkap"
                        class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 text-sm font-medium shadow-sm transition-all placeholder:text-slate-400 focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label
                        for="email"
                        class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                    >
                        Email Perusahaan
                    </Label>
                    <Input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        placeholder="hrd@perusahaan.com"
                        class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 text-sm font-medium shadow-sm transition-all placeholder:text-slate-400 focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="grid gap-2">
                        <Label
                            for="password"
                            class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                        >
                            Password
                        </Label>
                        <Input
                            id="password"
                            type="password"
                            v-model="form.password"
                            required
                            placeholder="••••••••"
                            class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 text-sm font-medium shadow-sm transition-all placeholder:text-slate-400 focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Label
                            for="password_confirmation"
                            class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                        >
                            Konfirmasi
                        </Label>
                        <Input
                            id="password_confirmation"
                            type="password"
                            v-model="form.password_confirmation"
                            required
                            placeholder="••••••••"
                            class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 text-sm font-medium shadow-sm transition-all placeholder:text-slate-400 focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        />
                    </div>
                </div>

                <div class="pt-4">
                    <Button
                        type="submit"
                        class="h-14 w-full rounded-2xl bg-sky-700 font-bold tracking-[0.15em] text-white uppercase shadow-lg shadow-sky-700/20 transition-all hover:bg-sky-800 hover:shadow-sky-800/30 active:scale-[0.98]"
                        :disabled="form.processing"
                    >
                        <Spinner
                            v-if="form.processing"
                            class="mr-2 h-5 w-5 text-white"
                        />
                        <span v-if="!form.processing"
                            >Daftar Sebagai Mitra</span
                        >
                        <span v-else>Memproses...</span>
                    </Button>
                </div>

                <div class="space-y-5 pt-6 text-center">
                    <p
                        class="text-xs font-semibold tracking-wide text-slate-500 uppercase"
                    >
                        Sudah punya akun?
                        <Link
                            href="/login"
                            class="ml-1 text-sky-700 transition-all hover:text-sky-800 hover:underline hover:underline-offset-4"
                        >
                            Masuk di sini
                        </Link>
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
                            >
                                ATAU
                            </span>
                        </div>
                    </div>

                    <p
                        class="text-xs font-semibold tracking-wide text-slate-500 uppercase"
                    >
                        Sedang mencari kerja?
                        <Link
                            href="/register/pelamar"
                            class="ml-1 text-slate-700 transition-all hover:text-slate-900 hover:underline hover:underline-offset-4"
                        >
                            Daftar Pelamar
                        </Link>
                    </p>
                </div>
            </form>
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
