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
        href: '#',
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
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <AppLogo />
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="activeNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
