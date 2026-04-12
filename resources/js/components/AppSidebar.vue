<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    LayoutGrid,
    Search,
    FileText,
    Bookmark,
    MessageSquare,
    User,
    Settings,
    Users,
    Briefcase,
} from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
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
        title: 'Dashboard Admin',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Kelola Pengguna',
        href: '#',
        icon: Users,
    },
    {
        title: 'Semua Lowongan',
        href: '#',
        icon: Briefcase,
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

const footerNavItems: NavItem[] = [
    {
        title: 'Profil & CV',
        href: '#',
        icon: User,
    },
    {
        title: 'Pengaturan Akun',
        href: '#',
        icon: Settings,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="activeNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
