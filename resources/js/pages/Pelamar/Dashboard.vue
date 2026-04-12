<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { dashboard } from '@/routes';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Dashboard Pelamar" />
    <div>
        <h1 class="text-3xl font-bold">Dashboard Pelamar</h1>
    </div>
</template>
