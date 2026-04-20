<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { ChevronsUpDown } from 'lucide-vue-next';
import { computed } from 'vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from '@/components/ui/sidebar';
import UserInfo from '@/components/UserInfo.vue';
import UserMenuContent from '@/components/UserMenuContent.vue';

const page = usePage();
const user = computed(() => page.props.auth.user as any);
const { isMobile, state } = useSidebar();

// Deteksi apakah sidebar sedang tertutup (collapsed)
const isCollapsed = computed(() => state.value === 'collapsed');
</script>

<template>
    <SidebarMenu>
        <SidebarMenuItem>
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <SidebarMenuButton
                        size="lg"
                        class="group mt-4 h-14 rounded-2xl border border-white/5 bg-white/5 px-3 shadow-lg transition-all duration-300 hover:border-sky-500/30 hover:bg-white/10 data-[state=open]:bg-white/10 data-[state=open]:text-sidebar-accent-foreground"
                        data-test="sidebar-menu-button"
                    >
                        <UserInfo
                            :user="user"
                            :show-email="!isCollapsed"
                            :is-dark="true"
                        />

                        <ChevronsUpDown
                            v-if="!isCollapsed"
                            class="ml-auto size-4 text-slate-500 transition-colors duration-300 group-hover:text-sky-400"
                        />
                    </SidebarMenuButton>
                </DropdownMenuTrigger>

                <DropdownMenuContent
                    class="w-[--radix-dropdown-menu-trigger-width] min-w-64 animate-in rounded-4xl border-slate-200 bg-white p-2 shadow-2xl fade-in-0 zoom-in-95"
                    :side="isMobile ? 'bottom' : isCollapsed ? 'right' : 'top'"
                    :side-offset="12"
                    align="end"
                >
                    <UserMenuContent :user="user" />
                </DropdownMenuContent>
            </DropdownMenu>
        </SidebarMenuItem>
    </SidebarMenu>
</template>

<style scoped>
/* Transisi scale dikit pas diklik biar kerasa feedback-nya */
button:active {
    transform: scale(0.98);
}

/* Memastikan font smoothing agar teks miring (italic) tetap tajam */
span {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
</style>
