<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/register/pelamar', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Daftar sebagai Pelamar" />

    <div class="flex min-h-screen items-center justify-center bg-gray-50">
        <div
            class="w-full max-w-md space-y-6 rounded-lg bg-white p-8 shadow-md"
        >
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900">
                    Daftar sebagai Pelamar
                </h2>
                <p class="mt-2 text-sm text-gray-500">
                    Mulai cari pekerjaan impianmu hari ini.
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <Label for="name">Nama Lengkap</Label>
                    <Input
                        id="name"
                        type="text"
                        v-model="form.name"
                        required
                        autofocus
                    />
                    <span
                        v-if="form.errors.name"
                        class="text-sm text-red-600"
                        >{{ form.errors.name }}</span
                    >
                </div>

                <div>
                    <Label for="email">Email</Label>
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
                    class="w-full"
                    :disabled="form.processing"
                >
                    Daftar Sekarang
                </Button>
            </form>

            <div class="text-center text-sm text-gray-600">
                Sudah punya akun?
                <Link href="/login" class="text-blue-600 hover:underline"
                    >Masuk di sini</Link
                >
                <br />
                <span class="mt-2 block text-xs text-gray-400">
                    Perusahaan yang ingin merekrut?
                    <Link
                        href="/register/mitra"
                        class="text-blue-600 hover:underline"
                        >Daftar sebagai Mitra</Link
                    >
                </span>
            </div>
        </div>
    </div>
</template>
