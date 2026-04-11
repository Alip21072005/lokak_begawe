<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'; // Import Link di sini

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="space-y-4 p-8">
        <h1 class="text-3xl font-bold">Welcome ke Lokak Begawe</h1>

        <div class="flex flex-col space-y-2">
            <Link href="/dashboard" class="text-blue-500 hover:underline">
                Go to Dashboard
            </Link>

            <template v-if="canRegister">
                <Link
                    href="/register/pelamar"
                    class="text-blue-500 hover:underline"
                >
                    Daftar sebagai Pelamar
                </Link>

                <Link
                    href="/register/mitra"
                    class="text-blue-500 hover:underline"
                >
                    Daftar sebagai Mitra Perusahaan
                </Link>
            </template>
        </div>
    </div>
</template>
