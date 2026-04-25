<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <img
        src="/logo.png"
        alt="Logo Lokak Begawe"
        :class="[
            'shrink-0 rounded-xl object-contain transition-all',
            className || 'h-9 w-9',
        ]"
        v-bind="$attrs"
    />
</template>
