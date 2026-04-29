<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue';
import { ChevronDown, Check } from 'lucide-vue-next';

interface Option {
    value: string;
    label: string;
}

interface Props {
    modelValue: string;
    options: Option[];
    placeholder?: string;
    label?: string;
    icon?: any;
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Pilih...',
    label: '',
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const isOpen = ref(false);
const dropdownRef = ref<HTMLElement | null>(null);
const selectedLabel = computed(() => {
    const option = props.options.find(opt => opt.value === props.modelValue);
    return option?.label || props.placeholder;
});

const toggle = () => {
    isOpen.value = !isOpen.value;
};

const select = (value: string) => {
    emit('update:modelValue', value);
    isOpen.value = false;
};

const handleClickOutside = (event: MouseEvent) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target as Node)) {
        isOpen.value = false;
    }
};

const handleKeydown = (event: KeyboardEvent) => {
    if (event.key === 'Escape') {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
    document.removeEventListener('keydown', handleKeydown);
});
</script>

<template>
    <div ref="dropdownRef" class="relative w-full">
        <!-- Trigger Button -->
        <button
            type="button"
            @click="toggle"
            class="group flex h-14 w-full items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 text-left transition-all duration-200 hover:border-sky-300 hover:shadow-md focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
            :class="{ 'border-sky-500 ring-2 ring-sky-500/20': isOpen }"
        >
            <component
                v-if="icon"
                :is="icon"
                class="h-5 w-5 shrink-0 text-slate-400 transition-colors group-hover:text-sky-500"
                :class="{ 'text-sky-500': isOpen }"
            />
            <span class="flex-1 truncate text-sm font-semibold" :class="modelValue ? 'text-slate-800' : 'text-slate-400'">
                {{ selectedLabel }}
            </span>
            <ChevronDown
                class="h-4 w-4 shrink-0 text-slate-400 transition-all duration-200"
                :class="{ 'rotate-180 text-sky-500': isOpen }"
            />
        </button>

        <!-- Dropdown Menu -->
        <Transition
            enter-active-class="transition-all duration-200 ease-out"
            enter-from-class="opacity-0 translate-y-2 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition-all duration-150 ease-in"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 translate-y-2 scale-95"
        >
            <div
                v-if="isOpen"
                class="absolute left-0 top-full z-50 mt-2 w-72 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl shadow-slate-300/30 sm:w-80 md:w-96"
            >
                <!-- Header -->
                <div v-if="label" class="border-b border-slate-100 bg-slate-50/80 px-4 py-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ label }}</span>
                </div>

                <!-- Options List -->
                <div class="max-h-80 overflow-y-auto py-2">
                    <button
                        v-for="option in options"
                        :key="option.value"
                        type="button"
                        @click="select(option.value)"
                        class="group flex w-full items-center gap-4 px-5 py-3.5 text-left transition-all duration-150"
                        :class="modelValue === option.value
                            ? 'bg-linear-to-r from-sky-50 to-sky-100/50 text-sky-700'
                            : 'hover:bg-slate-50 text-slate-700'
                        "
                    >
                        <div
                            class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md border-2 transition-all duration-150"
                            :class="modelValue === option.value
                                ? 'border-sky-500 bg-sky-500'
                                : 'border-slate-300 group-hover:border-sky-400'
                            "
                        >
                            <Check
                                v-if="modelValue === option.value"
                                class="h-4 w-4 text-white"
                            />
                        </div>
                        <span class="flex-1 truncate text-base font-medium">
                            {{ option.label }}
                        </span>
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
/* Custom Scrollbar */
.max-h-80::-webkit-scrollbar {
    width: 8px;
}

.max-h-80::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.max-h-80::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #94a3b8 0%, #64748b 100%);
    border-radius: 4px;
}

.max-h-80::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #64748b 0%, #475569 100%);
}
</style>
