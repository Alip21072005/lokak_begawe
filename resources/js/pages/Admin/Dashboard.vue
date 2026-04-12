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
    <Head title="Dashboard Admin" />
    <div>
        <h1 class="text-3xl font-bold">Dashboard Admin</h1>
    </div>
</template>
