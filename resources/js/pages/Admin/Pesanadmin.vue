<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    Send,
    User,
    Building2,
    Megaphone,
    MoreVertical,
    CheckCheck,
} from 'lucide-vue-next';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const activeTab = ref('inbox'); // 'inbox' atau 'broadcast'
const activeChat = ref({
    id: 1,
    name: 'Alip Maulana',
    role: 'Pelamar',
    status: 'Online',
    avatar: 'AM',
});

const messages = [
    {
        id: 1,
        sender: 'Alip Maulana',
        text: 'Halo Admin, saya mengalami kendala saat mengunggah CV.',
        time: '10:00 AM',
        isMe: false,
    },
    {
        id: 2,
        sender: 'Admin',
        text: 'Halo Alip! Silakan pastikan ukuran file tidak lebih dari 2MB ya.',
        time: '10:05 AM',
        isMe: true,
    },
];

const chatList = [
    {
        id: 1,
        name: 'Alip Maulana',
        role: 'Pelamar',
        lastMsg: 'Kendala upload CV...',
        time: '10m ago',
        unread: 1,
        type: 'pelamar',
        status: 'Online',
        avatar: 'AM',
    },
    {
        id: 2,
        name: 'Universitas Dehasen',
        role: 'Mitra',
        lastMsg: 'Verifikasi loker kami...',
        time: '1h ago',
        unread: 0,
        type: 'mitra',
        status: 'Offline',
        avatar: 'UD',
    },
    {
        id: 3,
        name: 'Budi Setiawan',
        role: 'Pelamar',
        lastMsg: 'Terima kasih bantuannya.',
        time: '2h ago',
        unread: 0,
        type: 'pelamar',
        status: 'Offline',
        avatar: 'BS',
    },
];
</script>

<template>
    <Head title="Pesan Admin - Lokak Begawe" />

    <div class="flex h-[calc(100vh-140px)] gap-6 p-6 lg:p-10">
        <div
            class="flex w-full flex-col overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white shadow-sm md:w-80 lg:w-96"
        >
            <div class="p-6">
                <h1
                    class="text-2xl font-black tracking-tighter text-lokak-text uppercase italic"
                >
                    ADMIN <span class="text-lokak-brand">MESSAGES</span>
                </h1>

                <div class="mt-6 flex gap-2 rounded-xl bg-slate-100 p-1">
                    <button
                        @click="activeTab = 'inbox'"
                        :class="
                            activeTab === 'inbox'
                                ? 'bg-white text-lokak-brand shadow-sm'
                                : 'text-slate-500'
                        "
                        class="flex-1 rounded-lg py-2 text-[10px] font-black uppercase italic transition-all"
                    >
                        Inbox
                    </button>
                    <button
                        @click="activeTab = 'broadcast'"
                        :class="
                            activeTab === 'broadcast'
                                ? 'bg-white text-lokak-brand shadow-sm'
                                : 'text-slate-500'
                        "
                        class="flex-1 rounded-lg py-2 text-[10px] font-black uppercase italic transition-all"
                    >
                        Broadcast
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-2">
                <div
                    v-for="chat in chatList"
                    :key="chat.id"
                    @click="activeChat = chat"
                    :class="
                        activeChat.id === chat.id
                            ? 'border-r-4 border-lokak-brand bg-sky-50/50'
                            : ''
                    "
                    class="group flex cursor-pointer items-center gap-4 rounded-2xl p-4 transition-all hover:bg-slate-50"
                >
                    <div
                        class="relative flex h-12 w-12 items-center justify-center rounded-full border border-slate-100 bg-white font-black text-lokak-brand shadow-sm"
                    >
                        {{ chat.name.charAt(0) }}
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <div class="flex items-center justify-between">
                            <h3
                                class="truncate text-[11px] font-black text-lokak-text uppercase"
                            >
                                {{ chat.name }}
                            </h3>
                            <span class="text-[9px] font-bold text-slate-400">{{
                                chat.time
                            }}</span>
                        </div>
                        <div class="mt-0.5 flex items-center gap-1.5">
                            <User
                                v-if="chat.type === 'pelamar'"
                                class="h-3 w-3 text-sky-500"
                            />
                            <Building2
                                v-else
                                class="h-3 w-3 text-emerald-500"
                            />
                            <span
                                class="text-[9px] font-bold text-slate-400 uppercase italic"
                                >{{ chat.role }}</span
                            >
                        </div>
                        <p
                            class="mt-1 truncate text-[10px] font-medium text-slate-500"
                        >
                            {{ chat.lastMsg }}
                        </p>
                    </div>
                    <div
                        v-if="chat.unread > 0"
                        class="h-2 w-2 rounded-full bg-lokak-brand shadow-lg shadow-sky-500/50"
                    ></div>
                </div>
            </div>
        </div>

        <div
            class="hidden flex-1 flex-col overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white shadow-sm md:flex"
        >
            <div
                class="flex items-center justify-between border-b border-slate-50 px-8 py-5"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-lokak-brand text-xs font-black text-white"
                    >
                        {{ activeChat.avatar }}
                    </div>
                    <div>
                        <h2
                            class="text-sm font-black text-lokak-text uppercase"
                        >
                            {{ activeChat.name }}
                        </h2>
                        <div class="flex items-center gap-1.5">
                            <div
                                class="h-1.5 w-1.5 rounded-full bg-emerald-500"
                            ></div>
                            <span
                                class="text-[10px] font-bold tracking-wider text-emerald-500 uppercase italic"
                                >Active Support</span
                            >
                        </div>
                    </div>
                </div>
                <button
                    class="rounded-xl p-2 text-slate-300 transition-all hover:bg-slate-50 hover:text-lokak-brand"
                >
                    <MoreVertical class="h-5 w-5" />
                </button>
            </div>

            <div class="flex-1 space-y-6 overflow-y-auto bg-slate-50/30 p-8">
                <div
                    v-for="msg in messages"
                    :key="msg.id"
                    :class="msg.isMe ? 'ml-auto flex-row-reverse' : ''"
                    class="flex max-w-[75%] gap-4"
                >
                    <div
                        class="mt-auto flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-slate-100 bg-white text-[10px] font-black"
                    >
                        {{ msg.sender.charAt(0) }}
                    </div>
                    <div
                        :class="
                            msg.isMe
                                ? 'rounded-br-none bg-lokak-brand text-white'
                                : 'rounded-bl-none border border-slate-100 bg-white text-lokak-text'
                        "
                        class="rounded-3xl p-4 shadow-sm"
                    >
                        <p class="text-xs leading-relaxed font-medium">
                            {{ msg.text }}
                        </p>
                        <div
                            :class="
                                msg.isMe ? 'text-white/60' : 'text-slate-400'
                            "
                            class="mt-2 flex items-center gap-1 text-[9px] font-bold italic"
                        >
                            {{ msg.time }}
                            <CheckCheck v-if="msg.isMe" class="h-3 w-3" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div
                    v-if="activeTab === 'broadcast'"
                    class="mb-4 flex items-center gap-3 rounded-xl border border-amber-100 bg-amber-50 p-3"
                >
                    <Megaphone class="h-4 w-4 text-amber-600" />
                    <p
                        class="text-[10px] font-bold text-amber-700 uppercase italic"
                    >
                        Mode Broadcast: Pesan akan dikirim ke semua pengguna
                        aktif.
                    </p>
                </div>

                <div
                    class="flex items-center gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-2 pl-6 shadow-inner transition-all focus-within:border-lokak-brand"
                >
                    <input
                        type="text"
                        placeholder="Tulis instruksi atau bantuan..."
                        class="flex-1 bg-transparent py-2 text-xs font-bold outline-none"
                    />
                    <button
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-lokak-brand text-white shadow-lg shadow-sky-900/20 transition-all hover:bg-lokak-brand-dark active:scale-90"
                    >
                        <Send class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Transisi halus */
.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
