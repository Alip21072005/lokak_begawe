<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import type { User } from '@/types';

type Props = {
    user?: User | any | null;
    showEmail?: boolean;
    isDark?: boolean; 
};

const props = withDefaults(defineProps<Props>(), {
    user: null,
    showEmail: false,
    isDark: false,
});

const { getInitials } = useInitials();
const page = usePage();

// --- LOGIKA FIX AVATAR URL ---
const avatarUrl = computed(() => {
    const authUser = props.user || (page.props.auth as any).user;
    
    // 1. Cek Avatar (Admin)
    if (authUser?.avatar) {
return `/storage/${authUser.avatar}`;
}
    
    // 2. Cek Logo Mitra
    if (authUser?.mitra?.logo_mitra) {
return `/storage/${authUser.mitra.logo_mitra}`;
}
    
    // 3. Cek Foto Pelamar
    if (authUser?.pelamar?.foto_pelamar) {
return `/storage/${authUser.pelamar.foto_pelamar}`;
}

    return null;
});
</script>

<template>
    <div class="flex items-center gap-3">
        <template v-if="user">
            <Avatar class="h-9 w-9 shrink-0 overflow-hidden rounded-xl border border-slate-200/50 bg-white shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:border-sky-500/50">
                <AvatarImage
                    v-if="avatarUrl"
                    :src="avatarUrl"
                    class="object-cover h-full w-full"
                />
                <AvatarFallback class="rounded-xl bg-slate-100 font-black text-sky-700 uppercase italic">
                    {{ getInitials(user.name) }}
                </AvatarFallback>
            </Avatar>

            <div class="grid flex-1 text-left text-sm leading-tight">
                <span
                    :class="[isDark ? 'text-white' : 'text-slate-900']"
                    class="truncate font-black tracking-tighter uppercase italic transition-colors duration-300 group-hover:text-sky-400"
                >
                    {{ user.name }}
                </span>
                <span
                    v-if="showEmail"
                    class="truncate text-[10px] font-bold tracking-widest text-slate-400 uppercase italic opacity-80"
                >
                    {{ user.email }}
                </span>
            </div>
        </template>
    </div>
</template>