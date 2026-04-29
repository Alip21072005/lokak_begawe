<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItem } from '@/types';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);
</script>

<template>
    <header
        class="sticky top-0 z-30 flex h-14 shrink-0 items-center gap-3 border-b border-slate-200/80 bg-white/95 px-4 shadow-sm backdrop-blur-md transition-all duration-300 md:h-16 md:px-6"
    >
        <div class="flex w-full items-center justify-between">
            <div class="flex items-center gap-2">
                <SidebarTrigger
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition-all hover:bg-slate-100 hover:text-lokak-brand"
                />

                <div class="mx-1 hidden h-5 w-px bg-slate-200 md:block"></div>

                <template v-if="breadcrumbs && breadcrumbs.length > 0">
                    <Breadcrumbs :breadcrumbs="breadcrumbs" />
                </template>
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center gap-2">
                <!-- Mobile Menu Button Placeholder -->
            </div>
        </div>
    </header>
</template>
