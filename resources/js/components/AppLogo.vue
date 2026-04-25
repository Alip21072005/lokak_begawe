<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div
        class="flex items-center gap-3 transition-transform hover:scale-[1.02]"
    >
        <AppLogoIcon class="size-9 object-contain drop-shadow-md" />

        <div class="flex flex-col justify-center">
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
