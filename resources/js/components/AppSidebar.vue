<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import {
    LayoutGrid,
    FileText,
    MessageSquare,
    Users,
    Briefcase,
    Building2,
    FilePlus,
} from 'lucide-vue-next';
import { computed } from 'vue';
import { route } from 'ziggy-js';
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
import {
    kelolalowongan,
    kelolamitra,
    kelolapelamar,
} from '@/routes/admin';
import { kelolapelamarkerja, pasanglowongan } from '@/routes/mitra';
import { lamaran, lowongankerja } from '@/routes/pelamar';
import type { NavItem } from '@/types';

const page = usePage();
const userRole = computed(() => page.props.auth.user?.role);

// --- HELPER UNTUK ROUTE CHAT ---
// Kita arahkan semua ke rute universal 'messages.index' sesuai ChatController
const chatRoute = () => route('messages.index');

const pelamarNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Lamaran Saya',
        href: lamaran(),
        icon: FileText,
    },
    {
        title: 'Lowongan Kerja',
        href: lowongankerja(),
        icon: Briefcase,
    },
    {
        title: 'Pesan',
        href: chatRoute(), // DIARAHKAN KE CHAT
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
        href: kelolapelamar(),
        icon: Users,
    },
    {
        title: 'Kelola Mitra',
        href: kelolamitra(),
        icon: Building2,
    },
    {
        title: 'Kelola Lowongan',
        href: kelolalowongan(),
        icon: Briefcase,
    },
    {
        title: 'Pesan',
        href: chatRoute(), // DIARAHKAN KE CHAT
        icon: MessageSquare,
    },
];

const mitraNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Pasang Lowongan',
        href: pasanglowongan(),
        icon: FilePlus,
    },
    {
        title: 'Kelola Pelamar',
        href: kelolapelamarkerja(),
        icon: Users,
    },
    {
        title: 'Pesan',
        href: chatRoute(), // DIARAHKAN KE CHAT
        icon: MessageSquare,
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