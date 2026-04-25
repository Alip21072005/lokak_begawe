<script setup lang="ts">
defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="flex min-h-svh flex-col items-center justify-center bg-background"
    >
        <div class="w-full">
            <div class="text-center">
                <h1 class="text-xl font-medium">{{ title }}</h1>
                {{ description }}
            </div>
        </div>
        <slot />
    </div>
</template>
