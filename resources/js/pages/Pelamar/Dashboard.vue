<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout, // Menggunakan AppLayout sebagai persistent layout
});
</script>

<template>
    <Head title="Dashboard Pelamar" />
    <div>
        <h1 class="text-3xl font-bold">Dashboard Pelamar</h1>
    </div>
</template>
