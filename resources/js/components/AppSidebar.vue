<script setup lang="ts">
import { usePage, router } from '@inertiajs/vue3';
import {
    LayoutGrid,
    FileText,
    MessageSquare,
    Users,
    Briefcase,
    Building2,
    FilePlus,
} from 'lucide-vue-next';
import { computed, markRaw } from 'vue';
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

const page = usePage();
const userRole = computed(() => page.props.auth.user?.role);
const notif = computed(() => (page.props.notifications as any) || {});

const handleLogout = () => {
    router.post(route('logout'));
};

const roleLabel = computed(() => {
    const roles: Record<string, string> = {
        admin: 'Administrator',
        mitra: 'Mitra Perusahaan',
        pelamar: 'Pelamar Kerja',
    };
    return userRole.value ? (roles[userRole.value as string] || 'User') : 'User';
});

const ICONS = {
    Dashboard: markRaw(LayoutGrid),
    Lamaran: markRaw(FileText),
    Pesan: markRaw(MessageSquare),
    Users: markRaw(Users),
    Lowongan: markRaw(Briefcase),
    Mitra: markRaw(Building2),
    Pasang: markRaw(FilePlus),
};

const activeNavItems = computed(() => {
    const role = userRole.value;
    const n = notif.value;

    if (role === 'admin') {
        return [
            { title: 'Dashboard', href: route('admin.dashboard'), icon: ICONS.Dashboard },
            { title: 'Kelola Pelamar', href: route('admin.kelolapelamar'), icon: ICONS.Users },
            { title: 'Kelola Mitra', href: route('admin.kelolamitra'), icon: ICONS.Mitra },
            {
                title: 'Kelola Lowongan',
                href: route('admin.kelolalowongan'),
                icon: ICONS.Lowongan,
                badge: n.admin_loker_pending > 0 ? n.admin_loker_pending : null,
            },
            {
                title: 'Pesan',
                href: route('messages.index'),
                icon: ICONS.Pesan,
                badge: n.unread_messages > 0 ? n.unread_messages : null,
            },
        ];
    }

    if (role === 'mitra') {
        return [
            { title: 'Dashboard', href: route('mitra.dashboard'), icon: ICONS.Dashboard },
            { title: 'Pasang Lowongan', href: route('mitra.pasanglowongan'), icon: ICONS.Pasang },
            {
                title: 'Kelola Pelamar',
                href: route('mitra.kelolapelamarkerja'),
                icon: ICONS.Users,
                badge: n.mitra_lamaran_masuk > 0 ? n.mitra_lamaran_masuk : null,
            },
            {
                title: 'Pesan',
                href: route('messages.index'),
                icon: ICONS.Pesan,
                badge: n.unread_messages > 0 ? n.unread_messages : null,
            },
        ];
    }

    // Pelamar
    return [
        { title: 'Dashboard', href: route('pelamar.dashboard'), icon: ICONS.Dashboard },
        { title: 'Cari Mitra', href: route('pelamar.cari-mitra'), icon: ICONS.Mitra },
        { title: 'Lamaran Saya', href: route('pelamar.lamaran'), icon: ICONS.Lamaran },
        { title: 'Lowongan Kerja', href: route('pelamar.lowongankerja'), icon: ICONS.Lowongan },
        {
            title: 'Pesan',
            href: route('messages.index'),
            icon: ICONS.Pesan,
            badge: n.unread_messages > 0 ? n.unread_messages : null,
        },
    ];
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" class="z-40 border-r border-slate-800/50 bg-linear-to-b from-slate-900 via-slate-800 to-slate-900 shadow-2xl">
        <SidebarHeader class="px-4 py-6">
            <SidebarMenu>
                <SidebarMenuItem class="flex flex-col gap-4">
                    <AppLogo />
                    <div class="group-data-[collapsible=icon]:hidden px-1">
                        <span
                            class="inline-flex items-center rounded-lg border border-sky-500/20 bg-sky-500/10 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-sky-400"
                        >
                            {{ roleLabel }}
                        </span>
                    </div>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent class="custom-scrollbar px-3 pt-4">
            <div class="group-data-[collapsible=icon]:hidden mb-4 px-3">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-slate-500">
                    Menu Navigasi
                </p>
            </div>

            <NavMain :items="activeNavItems" />
        </SidebarContent>

        <SidebarFooter class="border-t border-slate-700/50 bg-slate-900/50 p-4 backdrop-blur-sm">
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 3px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(56, 189, 248, 0.2);
    border-radius: 20px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: rgba(56, 189, 248, 0.4);
}
* {
    transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease, border-color 0.2s ease;
}
</style>