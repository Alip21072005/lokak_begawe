<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout, // Menggunakan AppLayout sebagai persistent layout
});
</script>

<template>
    <Head title="Dashboard Mitra" />
    <div>
        <h1 class="text-3xl font-bold">Dashboard Mitra</h1>
    </div>
</template>
