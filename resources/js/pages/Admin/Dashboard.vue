<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Users, Building2, Briefcase } from 'lucide-vue-next';
import { computed } from 'vue';
import { dashboard } from '@/routes';

// 1. Definisikan tipe data props agar TypeScript tidak error
const props = defineProps<{
    counts?: {
        pelamar: number;
        mitra: number;
        lowongan: number;
    };
}>();

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

// 2. Mapping data dari props ke dalam array stats
const stats = computed(() => [
    {
        name: 'Jumlah Pelamar',
        value: props.counts?.pelamar.toLocaleString('id-ID'), // Format angka: 1.234
        icon: Users,
        color: 'text-blue-600',
        bg: 'bg-blue-100',
    },
    {
        name: 'Jumlah Mitra',
        value: props.counts?.mitra.toLocaleString('id-ID'),
        icon: Building2,
        color: 'text-green-600',
        bg: 'bg-green-100',
    },
    {
        name: 'Jumlah Lowongan',
        value: props.counts?.lowongan.toLocaleString('id-ID'),
        icon: Briefcase,
        color: 'text-purple-600',
        bg: 'bg-purple-100',
    },
]);
</script>

<template>
    <Head title="Dashboard Admin" />

    <div class="space-y-6 p-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Admin</h1>
            <p class="text-gray-500">
                Selamat datang kembali! Berikut adalah ringkasan data hari ini.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div
                v-for="stat in stats"
                :key="stat.name"
                class="flex items-center space-x-4 rounded-xl border border-gray-100 bg-white p-6 shadow-sm"
            >
                <div :class="[stat.bg, 'rounded-lg p-3']">
                    <component
                        :is="stat.icon"
                        :class="[stat.color, 'h-8 w-8']"
                    />
                </div>
                <div>
                    <p
                        class="text-sm font-medium tracking-wider text-gray-500 uppercase"
                    >
                        {{ stat.name }}
                    </p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ stat.value }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div
                class="flex h-64 items-center justify-center rounded-xl border border-gray-100 bg-white p-6 text-gray-400 shadow-sm"
            >
                Grafik Tren Pelamar (Placeholder)
            </div>
            <div
                class="flex h-64 items-center justify-center rounded-xl border border-gray-100 bg-white p-6 text-gray-400 shadow-sm"
            >
                Aktivitas Terbaru (Placeholder)
            </div>
        </div>
    </div>
</template>
