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
        alt="Logo"
        :class="['h-8 w-8 rounded-lg object-cover', className]"
        v-bind="$attrs"
    />
</template>
