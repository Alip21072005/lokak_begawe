<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import {
    LayoutGrid,
    Search,
    FileText,
    Bookmark,
    MessageSquare,
    Users,
    Briefcase,
    Building2,
} from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const page = usePage();
const userRole = computed(() => page.props.auth.user?.role);

const pelamarNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Cari Lowongan',
        href: '/lowongan',
        icon: Search,
    },
    {
        title: 'Lamaran Saya',
        href: '#',
        icon: FileText,
    },
    {
        title: 'Pekerjaan Disimpan',
        href: '#',
        icon: Bookmark,
    },
    {
        title: 'Pesan',
        href: '#',
        icon: MessageSquare,
    },
];

const adminNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Kelola Pelamar',
        href: '#',
        icon: Users,
    },
    {
        title: 'Kelola Mitra',
        href: '#',
        icon: Briefcase,
    },
    {
        title: 'Kelola Lowongan',
        href: '#',
        icon: FileText,
    },
];

const mitraNavItems: NavItem[] = [
    {
        title: 'Dashboard Mitra',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Pasang Lowongan',
        href: '#',
        icon: FileText,
    },
    {
        title: 'Kelola Pelamar',
        href: '#',
        icon: Users,
    },
    {
        title: 'Profil Perusahaan',
        href: '#',
        icon: Building2,
    },
];

const activeNavItems = computed(() => {
    if (userRole.value === 'admin') {
        return adminNavItems;
    }

    if (userRole.value === 'mitra') {
        return mitraNavItems;
    }

    return pelamarNavItems;
});
</script>

<template>
    <Sidebar
        collapsible="icon"
        variant="inset"
        class="z-40 border-r-0 shadow-xl"
    >
        <SidebarHeader class="border-b border-slate-100 py-4">
            <SidebarMenu>
                <SidebarMenuItem>
                    <AppLogo />
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent class="custom-scrollbar pt-4">
            <NavMain :items="activeNavItems" />
        </SidebarContent>

        <SidebarFooter class="border-t border-slate-100 pt-2 pb-4">
            <NavUser />
        </SidebarFooter>
    </Sidebar>

    <slot />
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: #94a3b8;
}
</style>
