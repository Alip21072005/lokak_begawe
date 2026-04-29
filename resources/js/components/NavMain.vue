<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    SidebarGroup,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavItem } from '@/types';

defineProps<{
    items: NavItem[];
}>();

const { isCurrentUrl } = useCurrentUrl();
</script>

<template>
    <SidebarGroup class="px-1 py-0">
        <SidebarMenu class="gap-1">
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton
                    as-child
                    :is-active="isCurrentUrl(item.href)"
                    :tooltip="item.title"
                    class="group relative h-11 rounded-xl border border-transparent px-3 text-slate-400 transition-all duration-300 hover:border-sky-500/30 hover:bg-sky-500/10 hover:text-sky-400 data-[active=true]:border-sky-500/30 data-[active=true]:bg-linear-to-r data-[active=true]:from-sky-500/20 data-[active=true]:to-sky-400/10 data-[active=true]:text-sky-400 data-[active=true]:shadow-lg data-[active=true]:shadow-sky-500/20"
                >
                    <Link :href="item.href" class="flex w-full items-center justify-between">
                        <div class="flex items-center gap-3">
                            <component :is="item.icon" class="h-4.5 w-4.5 shrink-0 transition-transform duration-300 group-hover:scale-110" />
                            <span class="font-bold tracking-wide">{{ item.title }}</span>
                        </div>

                        <div 
                            v-if="item.badge" 
                            class="flex h-5 min-w-5 items-center justify-center rounded-full bg-linear-to-br from-red-500 to-rose-600 px-1.5 text-[10px] font-bold text-white shadow-lg shadow-red-500/30"
                        >
                            {{ item.badge }}
                        </div>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>