<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const form = useForm({
    name: '',
    company_name: '',
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
    <Head title="Daftar sebagai Mitra Perusahaan" />

    <div class="flex min-h-screen items-center justify-center bg-slate-50">
        <div
            class="w-full max-w-md space-y-6 rounded-lg border-t-4 border-blue-600 bg-white p-8 shadow-md"
        >
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900">
                    Daftar sebagai Mitra
                </h2>
                <p class="mt-2 text-sm text-gray-500">
                    Pasang lowongan dan temukan talenta terbaik.
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <Label for="company_name">Nama Perusahaan</Label>
                    <Input
                        id="company_name"
                        type="text"
                        v-model="form.company_name"
                        required
                        autofocus
                    />
                    <span
                        v-if="form.errors.company_name"
                        class="text-sm text-red-600"
                        >{{ form.errors.company_name }}</span
                    >
                </div>

                <div>
                    <Label for="name">Nama Penanggung Jawab (PIC)</Label>
                    <Input id="name" type="text" v-model="form.name" required />
                    <span
                        v-if="form.errors.name"
                        class="text-sm text-red-600"
                        >{{ form.errors.name }}</span
                    >
                </div>

                <div>
                    <Label for="email">Email Perusahaan</Label>
                    <Input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                    />
                    <span
                        v-if="form.errors.email"
                        class="text-sm text-red-600"
                        >{{ form.errors.email }}</span
                    >
                </div>

                <div>
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                    />
                    <span
                        v-if="form.errors.password"
                        class="text-sm text-red-600"
                        >{{ form.errors.password }}</span
                    >
                </div>

                <div>
                    <Label for="password_confirmation"
                        >Konfirmasi Password</Label
                    >
                    <Input
                        id="password_confirmation"
                        type="password"
                        v-model="form.password_confirmation"
                        required
                    />
                </div>

                <Button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700"
                    :disabled="form.processing"
                >
                    Daftar sebagai Mitra
                </Button>
            </form>

            <div class="text-center text-sm text-gray-600">
                Sudah punya akun?
                <Link href="/login" class="text-blue-600 hover:underline"
                    >Masuk di sini</Link
                >
                <br />
                <span class="mt-2 block text-xs text-gray-400">
                    Sedang mencari kerja?
                    <Link
                        href="/register/pelamar"
                        class="text-blue-600 hover:underline"
                        >Daftar sebagai Pelamar</Link
                    >
                </span>
            </div>
        </div>
    </div>
</template>
