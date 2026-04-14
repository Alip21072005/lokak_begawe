<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    Search,
    Send,
    MoreVertical,
    Phone,
    Video,
    Paperclip,
    Smile,
    CheckCheck,
} from 'lucide-vue-next';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const searchQuery = ref('');
const newMessage = ref('');

// Data Dummy Chat List
const chats = [
    {
        id: 1,
        name: 'HRD Universitas Dehasen',
        lastMsg: 'Halo Alip, apakah besok bisa interview online?',
        time: '10:25',
        unread: 2,
        online: true,
        avatar: 'UD',
    },
    {
        id: 2,
        name: 'Code 21 Bengkulu',
        lastMsg: 'Terima kasih atas lamarannya. Kami akan segera...',
        time: 'Kemarin',
        unread: 0,
        online: false,
        avatar: 'C2',
    },
    {
        id: 3,
        name: 'PT. Mukomuko Jaya',
        lastMsg: 'Berkas Anda sudah kami terima.',
        time: '08 April',
        unread: 0,
        online: false,
        avatar: 'MJ',
    },
];

const activeChat = ref(chats[0]);
</script>

<template>
    <Head title="Pesan - Lokak Begawe" />

    <div class="flex h-[calc(100vh-120px)] overflow-hidden p-6 lg:p-10">
        <div
            class="flex w-full overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white shadow-sm"
        >
            <div
                class="flex w-full flex-col border-r border-slate-100 md:w-80 lg:w-96"
            >
                <div class="p-6">
                    <h2
                        class="mb-4 text-xl font-black tracking-tighter text-lokak-text uppercase italic"
                    >
                        PESAN <span class="text-lokak-brand">MASUK</span>
                    </h2>
                    <div class="relative">
                        <Search
                            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400"
                        />
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Cari percakapan..."
                            class="h-11 w-full rounded-xl border border-slate-100 bg-slate-50 pr-4 pl-10 text-xs font-bold outline-none focus:ring-2 focus:ring-lokak-brand/20"
                        />
                    </div>
                </div>

                <div class="custom-scrollbar flex-1 overflow-y-auto">
                    <div
                        v-for="chat in chats"
                        :key="chat.id"
                        @click="activeChat = chat"
                        :class="[
                            'flex cursor-pointer items-center gap-4 px-6 py-4 transition-all hover:bg-slate-50',
                            activeChat.id === chat.id
                                ? 'border-r-4 border-lokak-brand bg-sky-50/50'
                                : '',
                        ]"
                    >
                        <div class="relative">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full border border-slate-100 bg-white font-black text-lokak-brand shadow-sm"
                            >
                                {{ chat.avatar }}
                            </div>
                            <div
                                v-if="chat.online"
                                class="absolute right-0 bottom-0 h-3 w-3 rounded-full border-2 border-white bg-emerald-500"
                            ></div>
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <div class="flex items-center justify-between">
                                <h3
                                    class="truncate text-xs font-black text-lokak-text uppercase"
                                >
                                    {{ chat.name }}
                                </h3>
                                <span
                                    class="text-[9px] font-bold text-slate-400"
                                    >{{ chat.time }}</span
                                >
                            </div>
                            <p
                                class="truncate text-[10px] font-medium text-slate-500"
                            >
                                {{ chat.lastMsg }}
                            </p>
                        </div>
                        <div
                            v-if="chat.unread > 0"
                            class="flex h-5 w-5 items-center justify-center rounded-full bg-lokak-brand text-[9px] font-black text-white"
                        >
                            {{ chat.unread }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden flex-1 flex-col bg-slate-50/30 md:flex">
                <div
                    class="flex items-center justify-between border-b border-slate-100 bg-white px-8 py-4"
                >
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-lokak-brand text-xs font-black text-white"
                        >
                            {{ activeChat.avatar }}
                        </div>
                        <div>
                            <h3
                                class="text-sm font-black text-lokak-text uppercase"
                            >
                                {{ activeChat.name }}
                            </h3>
                            <span
                                class="text-[10px] font-bold text-emerald-500 uppercase italic"
                                >Online Sekarang</span
                            >
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            class="rounded-xl p-2 text-slate-400 transition-all hover:bg-slate-50 hover:text-lokak-brand"
                        >
                            <Phone class="h-5 w-5" />
                        </button>
                        <button
                            class="rounded-xl p-2 text-slate-400 transition-all hover:bg-slate-50 hover:text-lokak-brand"
                        >
                            <Video class="h-5 w-5" />
                        </button>
                        <button
                            class="rounded-xl p-2 text-slate-400 transition-all hover:bg-slate-50 hover:text-lokak-brand"
                        >
                            <MoreVertical class="h-5 w-5" />
                        </button>
                    </div>
                </div>

                <div
                    class="custom-scrollbar flex-1 space-y-6 overflow-y-auto p-8"
                >
                    <div class="flex max-w-[80%] gap-3">
                        <div
                            class="mt-auto h-8 w-8 shrink-0 rounded-full bg-slate-200"
                        ></div>
                        <div
                            class="rounded-4xl rounded-bl-none border border-slate-100 bg-white p-5 shadow-sm"
                        >
                            <p
                                class="text-xs leading-relaxed font-medium text-lokak-text"
                            >
                                Halo Alip, kami sudah meninjau portofolio kamu.
                                Apakah kamu ada waktu untuk interview online
                                besok jam 10 pagi?
                            </p>
                            <span
                                class="mt-2 block text-[9px] font-bold text-slate-400"
                                >10:25 AM</span
                            >
                        </div>
                    </div>

                    <div
                        class="ml-auto flex max-w-[80%] flex-col items-end gap-2"
                    >
                        <div
                            class="rounded-4xl rounded-br-none bg-lokak-brand p-5 text-white shadow-lg shadow-sky-900/10"
                        >
                            <p class="text-xs leading-relaxed font-medium">
                                Selamat siang! Tentu, saya bersedia untuk
                                interview besok jam 10 pagi. Terima kasih atas
                                kesempatannya.
                            </p>
                        </div>
                        <div
                            class="flex items-center gap-1 text-[9px] font-bold text-slate-400"
                        >
                            10:30 AM
                            <CheckCheck class="h-3 w-3 text-lokak-brand" />
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 bg-white p-6">
                    <div
                        class="flex items-center gap-3 rounded-3xl border border-slate-100 bg-slate-50 p-2 pl-4"
                    >
                        <button class="text-slate-400 hover:text-lokak-brand">
                            <Paperclip class="h-5 w-5" />
                        </button>
                        <input
                            v-model="newMessage"
                            type="text"
                            placeholder="Tulis pesan kamu..."
                            class="flex-1 bg-transparent py-2 text-xs font-bold outline-none"
                        />
                        <button class="text-slate-400 hover:text-lokak-brand">
                            <Smile class="h-5 w-5" />
                        </button>
                        <button
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-lokak-brand text-white shadow-lg shadow-sky-900/20 transition-all hover:bg-lokak-brand-dark"
                        >
                            <Send class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
</style>
