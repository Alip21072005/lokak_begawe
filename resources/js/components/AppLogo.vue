<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div class="flex items-center gap-3">
        <AppLogoIcon class="size-8 object-contain" />

        <div class="flex flex-col">
            <span
                class="truncate text-lg leading-none font-extrabold tracking-tight text-sidebar-accent"
            >
                Lokak Begawe
            </span>
            <span
                class="mt-1 text-[10px] leading-none text-muted-foreground italic"
            >
                Ado Lokak, Pela Begawe
            </span>
        </div>
    </div>
</template>
