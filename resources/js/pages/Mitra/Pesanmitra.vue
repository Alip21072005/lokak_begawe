<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    Search,
    Send,
    MoreVertical,
    Paperclip,
    CheckCheck,
    Briefcase,
    User,
} from 'lucide-vue-next';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const searchQuery = ref('');
const newMessage = ref('');

// Data Dummy Chat List (Kandidat yang melamar)
const candidateChats = [
    {
        id: 1,
        name: 'Alip Maulana',
        appliedFor: 'Frontend Developer',
        lastMsg: 'Baik pak, saya akan hadir tepat waktu.',
        time: '14:20',
        unread: 0,
        online: true,
        avatar: 'AM',
    },
    {
        id: 2,
        name: 'Siti Aminah',
        appliedFor: 'UI/UX Designer',
        lastMsg: 'Berikut adalah portofolio terbaru saya...',
        time: '11:05',
        unread: 3,
        online: false,
        avatar: 'SA',
    },
    {
        id: 3,
        name: 'Budi Setiawan',
        appliedFor: 'Cyber Security',
        lastMsg: 'Apakah masih ada lowongan?',
        time: 'Kemarin',
        unread: 0,
        online: false,
        avatar: 'BS',
    },
];

const activeChat = ref(candidateChats[0]);
</script>

<template>
    <Head title="Pesan Kandidat - Lokak Begawe" />

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
                        PESAN <span class="text-lokak-brand">KANDIDAT</span>
                    </h2>
                    <div class="relative">
                        <Search
                            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-slate-400"
                        />
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Cari nama pelamar..."
                            class="h-11 w-full rounded-xl border border-slate-100 bg-slate-50 pr-4 pl-10 text-xs font-bold outline-none focus:ring-2 focus:ring-lokak-brand/20"
                        />
                    </div>
                </div>

                <div class="custom-scrollbar flex-1 overflow-y-auto">
                    <div
                        v-for="chat in candidateChats"
                        :key="chat.id"
                        @click="activeChat = chat"
                        :class="[
                            'flex cursor-pointer items-center gap-4 px-6 py-5 transition-all hover:bg-slate-50',
                            activeChat.id === chat.id
                                ? 'border-r-4 border-lokak-brand bg-sky-50/50'
                                : '',
                        ]"
                    >
                        <div class="relative">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full border border-slate-100 bg-white font-black text-lokak-brand uppercase shadow-sm"
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
                            <div class="mt-0.5 flex items-center gap-1">
                                <Briefcase class="h-2.5 w-2.5 text-slate-400" />
                                <span
                                    class="truncate text-[8px] font-bold text-slate-400 uppercase italic"
                                    >{{ chat.appliedFor }}</span
                                >
                            </div>
                            <p
                                class="mt-1 truncate text-[10px] font-medium text-slate-500 italic"
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
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-[9px] font-bold tracking-widest text-slate-400 uppercase"
                                    >{{ activeChat.appliedFor }}</span
                                >
                                <span
                                    class="h-1 w-1 rounded-full bg-slate-300"
                                ></span>
                                <span
                                    class="text-[9px] font-bold text-emerald-500 uppercase italic"
                                    >Pelamar Aktif</span
                                >
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            class="flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-[10px] font-black text-lokak-text uppercase transition-all hover:bg-slate-50"
                        >
                            <User class="h-3.5 w-3.5" /> Lihat Profil
                        </button>
                        <button
                            class="rounded-xl p-2 text-slate-400 transition-all hover:bg-slate-50"
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
                            class="mt-auto flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-slate-100 text-[10px] font-black"
                        >
                            {{ activeChat.avatar }}
                        </div>
                        <div
                            class="rounded-4xl rounded-bl-none border border-slate-100 bg-white p-5 shadow-sm"
                        >
                            <p
                                class="text-xs leading-relaxed font-medium text-lokak-text italic"
                            >
                                Halo HRD Lokak Begawe, saya sangat tertarik
                                dengan posisi ini. Kapan jadwal interviewnya ya?
                            </p>
                            <span
                                class="mt-2 block text-[9px] font-bold text-slate-400 uppercase"
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
                                Halo {{ activeChat.name }}, kami sudah meninjau
                                profil Anda. Apakah Anda bersedia interview
                                online besok pukul 10:00 WIB?
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
                        class="flex items-center gap-3 rounded-3xl border border-slate-100 bg-slate-50 p-2 pl-6 transition-all focus-within:border-lokak-brand"
                    >
                        <button
                            class="text-slate-400 transition-colors hover:text-lokak-brand"
                        >
                            <Paperclip class="h-5 w-5" />
                        </button>
                        <input
                            v-model="newMessage"
                            type="text"
                            placeholder="Balas pesan pelamar..."
                            class="flex-1 bg-transparent py-2 text-xs font-bold outline-none"
                        />
                        <button
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-lokak-brand text-white shadow-lg shadow-sky-900/20 transition-all hover:bg-lokak-brand-dark active:scale-95"
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
