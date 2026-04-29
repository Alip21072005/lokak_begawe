<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div
        class="flex items-center gap-3 transition-all duration-300 hover:scale-[1.02] group-data-[collapsible=icon]:justify-center"
    >
        <div class="relative flex h-10 w-10 items-center justify-center rounded-xl bg-linear-to-br from-sky-500 to-sky-600 shadow-lg shadow-sky-500/25">
            <AppLogoIcon class="size-6 object-contain text-white" />
        </div>

        <div class="flex flex-col justify-center group-data-[collapsible=icon]:hidden">
            <span
                class="truncate text-lg leading-none font-black tracking-tighter text-white uppercase"
            >
                LOKAKBEGAWE
            </span>
            <span
                class="mt-1 text-[9px] leading-none font-bold tracking-wide text-slate-400 uppercase italic"
            >
                Ado Lokak, Pela Begawe
            </span>
        </div>
    </div>
</template>
