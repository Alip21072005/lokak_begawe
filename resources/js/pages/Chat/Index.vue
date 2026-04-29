<script setup lang="ts">
import { Head, usePage, router } from '@inertiajs/vue3';
import axios from 'axios';
import {
    Send,
    Search,
    MoreVertical,
    CheckCheck,
    MessageSquare,
    Paperclip,
    Clock,
    AlertCircle,
    Phone,
    FileIcon,
    X,
    Menu,
    ChevronLeft,
} from 'lucide-vue-next';
import { ref, onMounted, nextTick, computed, watch } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps<{ conversations: any[] }>();
const page = usePage();

const authUser = computed(() => page.props.auth.user);
const authId = computed(() => String(authUser.value?.id).trim().toLowerCase());

// -------------------- state --------------------
const selectedConversation = ref<any>(null);
const messages = ref<any[]>([]);
const messageContainer = ref<HTMLElement | null>(null);
const searchContact = ref('');
const isLoading = ref(false);
const newMessage = ref('');
const isMobileSidebarOpen = ref(false);

// presence & typing
const onlineUsers = ref<Set<string>>(new Set());
const isPartnerTyping = ref(false);
let typingTimer: ReturnType<typeof setTimeout>;

// attachment
const fileInput = ref<HTMLInputElement | null>(null);
const selectedFile = ref<File | null>(null);
const filePreview = ref<string | null>(null);

// -------------------- helpers --------------------
const getPartner = (conv: any) => {
    if (!conv) return null;
    return String(conv.sender_id).toLowerCase() === authId.value ? conv.receiver : conv.sender;
};

const getAvatar = (user: any) => {
    if (user?.pelamar?.foto_pelamar) return `/storage/${user.pelamar.foto_pelamar}`;
    if (user?.mitra?.logo_mitra) return `/storage/${user.mitra.logo_mitra}`;
    return null;
};

const getDisplayName = (user: any) => user?.name || 'Pengguna';

const getLastMessageText = (conv: any) => {
    const msg = conv?.last_message || conv?.messages?.[0];
    if (!msg) return 'Belum ada pesan...';
    if (msg.attachment_url) return 'Lampiran terkirim';
    return msg.body || 'Belum ada pesan...';
};

const isPartnerOnline = computed(() => {
    if (!selectedConversation.value) return false;
    const partner = getPartner(selectedConversation.value);
    if (!partner?.id) return false;
    return onlineUsers.value.has(String(partner.id).toLowerCase());
});

const filteredConversations = computed(() => {
    const keyword = searchContact.value.toLowerCase().trim();
    if (!keyword) return props.conversations || [];

    return (props.conversations || []).filter((c) => {
        const partner = getPartner(c);
        return (partner?.name || '').toLowerCase().includes(keyword);
    });
});

const isNewDay = (currentMsg: any, prevMsg: any) => {
    if (!prevMsg) return true;
    const currentDate = new Date(currentMsg.created_at).setHours(0, 0, 0, 0);
    const prevDate = new Date(prevMsg.created_at).setHours(0, 0, 0, 0);
    return currentDate !== prevDate;
};

const formatDateSeparator = (dateString: string) => {
    const date = new Date(dateString);
    const today = new Date();
    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);

    if (date.toDateString() === today.toDateString()) return 'Hari Ini';
    if (date.toDateString() === yesterday.toDateString()) return 'Kemarin';
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

const formatClock = (dateString: string) =>
    new Date(dateString).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

// -------------------- websocket --------------------
onMounted(() => {
    if (!(window as any).Echo) return;

    (window as any).Echo.join('online')
        .here((users: any[]) => users.forEach((u) => onlineUsers.value.add(String(u.id).toLowerCase())))
        .joining((u: any) => onlineUsers.value.add(String(u.id).toLowerCase()))
        .leaving((u: any) => onlineUsers.value.delete(String(u.id).toLowerCase()));

    (window as any).Echo.private(`chat.${authId.value}`)
        .listen('.MessageSent', (e: any) => {
            if (selectedConversation.value && String(e.message.conversation_id) === String(selectedConversation.value.id)) {
                e.message.read = true;
                e.message.sending = false;
                messages.value.push(e.message);
                isPartnerTyping.value = false;
                clearTimeout(typingTimer);
                scrollToBottom();
                axios.post(route('messages.read', selectedConversation.value.id));
            } else {
                router.reload({ only: ['conversations'] });
            }
        })
        .listen('.MessageRead', (e: any) => {
            if (selectedConversation.value && String(e.conversation_id) === String(selectedConversation.value.id)) {
                messages.value.forEach((msg) => {
                    if (String(msg.sender_id).toLowerCase() === authId.value) msg.read = true;
                });
            }
            router.reload({ only: ['conversations'] });
        });
});

watch(
    () => selectedConversation.value?.id,
    () => {
        nextTick(() => scrollToBottom());
    },
);

// -------------------- actions --------------------
const selectConversation = async (conv: any) => {
    if (selectedConversation.value?.id === conv.id) {
        isMobileSidebarOpen.value = false;
        return;
    }

    if (selectedConversation.value && (window as any).Echo) {
        (window as any).Echo.leave(`conversation.${selectedConversation.value.id}`);
    }

    selectedConversation.value = conv;
    isMobileSidebarOpen.value = false;
    isLoading.value = true;
    messages.value = [];
    isPartnerTyping.value = false;
    selectedFile.value = null;
    filePreview.value = null;

    if ((window as any).Echo) {
        (window as any).Echo.private(`conversation.${conv.id}`).listenForWhisper('typing', () => {
            isPartnerTyping.value = true;
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => (isPartnerTyping.value = false), 2000);
            scrollToBottom();
        });
    }

    try {
        const response = await axios.get(route('messages.show', conv.id));
        messages.value = response.data.messages || [];
        scrollToBottom();
        axios.post(route('messages.read', conv.id));
    } catch {
        // noop
    } finally {
        isLoading.value = false;
    }
};

const sendTypingEvent = () => {
    if (!selectedConversation.value || !(window as any).Echo) return;
    (window as any).Echo.private(`conversation.${selectedConversation.value.id}`).whisper('typing', {
        conversation_id: selectedConversation.value.id,
    });
};

const handleFileSelect = (e: any) => {
    const file = e.target.files[0];
    if (!file) return;

    selectedFile.value = file;
    if (file.type.startsWith('image/')) {
        filePreview.value = URL.createObjectURL(file);
    } else {
        filePreview.value = null;
    }
};

const clearAttachment = () => {
    selectedFile.value = null;
    filePreview.value = null;
    if (fileInput.value) fileInput.value.value = '';
};

const sendMessage = async () => {
    if ((!newMessage.value.trim() && !selectedFile.value) || !selectedConversation.value) return;

    const partner = getPartner(selectedConversation.value);
    if (!partner?.id) return;

    const tempId = Date.now();
    const currentBody = newMessage.value.trim();

    const formData = new FormData();
    formData.append('receiver_id', partner.id);
    formData.append('conversation_id', selectedConversation.value.id);
    formData.append('body', currentBody || ' ');
    if (selectedFile.value) formData.append('file', selectedFile.value);

    messages.value.push({
        id: tempId,
        sender_id: authId.value,
        body: currentBody,
        attachment_url: filePreview.value,
        attachment_type: selectedFile.value
            ? selectedFile.value.type.startsWith('image/')
                ? 'image'
                : 'file'
            : null,
        read: false,
        created_at: new Date().toISOString(),
        sending: true,
    });

    newMessage.value = '';
    clearAttachment();
    scrollToBottom();

    try {
        const res = await axios.post(route('messages.store'), formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });

        const idx = messages.value.findIndex((m) => m.id === tempId);
        if (idx !== -1) {
            messages.value[idx] = { ...res.data, sending: false };
        }
        router.reload({ only: ['conversations'] });
    } catch {
        const idx = messages.value.findIndex((m) => m.id === tempId);
        if (idx !== -1) {
            messages.value[idx].sending = false;
            messages.value[idx].error = true;
        }
    }
};

const scrollToBottom = () => {
    nextTick(() => {
        if (messageContainer.value) {
            messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
        }
    });
};

const openAttachment = (msg: any) => {
    if (!msg?.attachment_url) return;
    if (msg.attachment_url.startsWith('blob:')) return;
    window.open(`/storage/${msg.attachment_url}`, '_blank');
};
</script>

<template>
    <Head title="Pesan - Lokak Begawe" />

    <div class="mx-3 my-3 flex h-[calc(100vh-130px)] overflow-hidden rounded-[2.2rem] border border-slate-200 bg-white shadow-xl">
        <!-- Sidebar desktop -->
        <aside class="hidden w-80 border-r border-slate-100 bg-slate-50/50 md:flex md:flex-col lg:w-96">
            <div class="px-6 pt-6">
                <h2 class="text-xl font-black uppercase italic tracking-tight text-slate-900">Inbox</h2>
            </div>

            <div class="px-6 py-4">
                <div class="relative">
                    <Search class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-300" />
                    <input
                        v-model="searchContact"
                        type="text"
                        placeholder="Cari kontak..."
                        class="w-full rounded-2xl border border-slate-100 bg-white py-3 pl-11 pr-4 text-xs font-bold italic text-slate-700 outline-none ring-0 transition focus:border-sky-200 focus:ring-4 focus:ring-sky-700/10"
                    />
                </div>
            </div>

            <div class="custom-scrollbar flex-1 space-y-2 overflow-y-auto px-4 pb-4">
                <button
                    v-for="conv in filteredConversations"
                    :key="conv.id"
                    @click="selectConversation(conv)"
                    :class="[
                        selectedConversation?.id === conv.id
                            ? 'bg-white ring-1 ring-sky-700/10 shadow-sm'
                            : 'hover:bg-white/70',
                    ]"
                    class="flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-left transition"
                >
                    <div class="relative h-12 w-12 shrink-0 overflow-hidden rounded-xl border border-white bg-sky-700 text-white">
                        <img
                            v-if="getAvatar(getPartner(conv))"
                            :src="getAvatar(getPartner(conv)) ?? undefined"
                            class="h-full w-full object-cover"
                        />
                        <div v-else class="flex h-full w-full items-center justify-center text-sm font-black italic">
                            {{ getDisplayName(getPartner(conv)).charAt(0) }}
                        </div>
                        <span
                            v-if="onlineUsers.has(String(getPartner(conv)?.id).toLowerCase())"
                            class="absolute -right-0.5 -top-0.5 h-3 w-3 rounded-full border-2 border-white bg-emerald-500"
                        />
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <p class="truncate text-xs font-black uppercase italic text-slate-900">
                                {{ getDisplayName(getPartner(conv)) }}
                            </p>
                            <span class="text-[9px] font-bold text-slate-300">
                                {{ formatClock(conv.updated_at) }}
                            </span>
                        </div>
                        <p class="truncate text-[10px] font-bold italic text-slate-400">
                            {{ getLastMessageText(conv) }}
                        </p>
                    </div>
                </button>

                <div v-if="filteredConversations.length === 0" class="px-3 py-8 text-center">
                    <p class="text-xs font-bold italic text-slate-400">Belum ada percakapan</p>
                </div>
            </div>
        </aside>

        <!-- Main -->
        <main class="flex min-w-0 flex-1 flex-col bg-white">
            <!-- Topbar -->
            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-4 sm:px-6">
                <div class="flex min-w-0 items-center gap-3">
                    <!-- mobile open sidebar -->
                    <button
                        class="rounded-xl border border-slate-200 bg-white p-2 text-slate-500 md:hidden"
                        @click="isMobileSidebarOpen = true"
                    >
                        <Menu class="h-4 w-4" />
                    </button>

                    <template v-if="selectedConversation">
                        <div class="h-10 w-10 overflow-hidden rounded-xl border border-sky-100 bg-sky-50 text-sky-700">
                            <img
                                v-if="getAvatar(getPartner(selectedConversation))"
                                :src="getAvatar(getPartner(selectedConversation)) ?? undefined"
                                class="h-full w-full object-cover"
                            />
                            <div v-else class="flex h-full w-full items-center justify-center text-sm font-black italic">
                                {{ getDisplayName(getPartner(selectedConversation)).charAt(0) }}
                            </div>
                        </div>

                        <div class="min-w-0">
                            <p class="truncate text-sm font-black uppercase italic text-slate-900">
                                {{ getDisplayName(getPartner(selectedConversation)) }}
                            </p>
                            <p class="text-[10px] font-black uppercase tracking-wider" :class="isPartnerOnline ? 'text-emerald-500' : 'text-slate-400'">
                                {{ isPartnerOnline ? 'Online' : 'Offline' }}
                            </p>
                        </div>
                    </template>

                    <template v-else>
                        <p class="text-sm font-black uppercase italic text-slate-700">Pesan</p>
                    </template>
                </div>

                <div class="flex items-center gap-2 text-slate-400">
                    <button class="rounded-xl p-2 hover:bg-slate-50"><Phone class="h-4 w-4" /></button>
                    <button class="rounded-xl p-2 hover:bg-slate-50"><MoreVertical class="h-4 w-4" /></button>
                </div>
            </div>

            <!-- Message list -->
            <div ref="messageContainer" class="custom-scrollbar flex-1 space-y-3 overflow-y-auto bg-slate-50/20 px-3 py-5 sm:px-6">
                <div v-if="!selectedConversation" class="flex h-full items-center justify-center">
                    <div class="text-center">
                        <div class="mx-auto mb-5 flex h-24 w-24 items-center justify-center rounded-[2rem] border border-slate-100 bg-white shadow-sm">
                            <MessageSquare class="h-10 w-10 text-sky-200" />
                        </div>
                        <h3 class="text-lg font-black uppercase italic text-slate-900">
                            Pilih Percakapan
                        </h3>
                        <p class="mt-1 text-xs font-bold italic text-slate-400">
                            Mulai komunikasi profesional dengan pelamar/mitra.
                        </p>
                    </div>
                </div>

                <template v-else>
                    <div v-if="isLoading" class="py-10 text-center text-xs font-bold italic text-slate-400">
                        Memuat pesan...
                    </div>

                    <template v-else>
                        <div v-for="(msg, index) in messages" :key="msg.id" class="w-full">
                            <div v-if="isNewDay(msg, messages[index - 1])" class="my-5 flex justify-center">
                                <span class="rounded-full border border-sky-100 bg-sky-50 px-3 py-1 text-[9px] font-black uppercase italic tracking-widest text-sky-700">
                                    {{ formatDateSeparator(msg.created_at) }}
                                </span>
                            </div>

                            <div class="mb-2 flex" :class="String(msg.sender_id).toLowerCase() === authId ? 'justify-end' : 'justify-start'">
                                <div class="max-w-[85%] sm:max-w-[70%]">
                                    <div
                                        class="rounded-[1.4rem] p-2 shadow-sm transition"
                                        :class="String(msg.sender_id).toLowerCase() === authId
                                            ? 'rounded-tr-sm bg-slate-900 text-white'
                                            : 'rounded-tl-sm border border-slate-100 bg-white text-slate-700'"
                                    >
                                        <div v-if="msg.attachment_url" class="mb-1">
                                            <img
                                                v-if="msg.attachment_type === 'image'"
                                                :src="msg.attachment_url.startsWith('blob:') ? msg.attachment_url : `/storage/${msg.attachment_url}`"
                                                class="max-h-72 w-full rounded-xl object-cover"
                                                @click="openAttachment(msg)"
                                            />
                                            <a
                                                v-else
                                                :href="msg.attachment_url.startsWith('blob:') ? '#' : `/storage/${msg.attachment_url}`"
                                                target="_blank"
                                                class="flex items-center gap-2 rounded-xl bg-black/10 p-3"
                                            >
                                                <FileIcon class="h-5 w-5" />
                                                <span class="text-[11px] font-bold">Lampiran dokumen</span>
                                            </a>
                                        </div>

                                        <p v-if="msg.body && msg.body.trim() !== ''" class="px-2 py-1 text-[11px] font-bold italic leading-relaxed">
                                            {{ msg.body }}
                                        </p>
                                    </div>

                                    <div class="mt-1 flex items-center gap-1 px-1 text-[8px] font-bold uppercase italic text-slate-400">
                                        <span>{{ formatClock(msg.created_at) }}</span>

                                        <template v-if="String(msg.sender_id).toLowerCase() === authId">
                                            <Clock v-if="msg.sending" class="h-2.5 w-2.5 animate-spin" />
                                            <CheckCheck v-else-if="msg.read" class="h-3 w-3 text-sky-500" />
                                            <CheckCheck v-else class="h-3 w-3" />
                                        </template>

                                        <AlertCircle v-if="msg.error" class="h-3 w-3 text-rose-500" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="isPartnerTyping" class="flex justify-start">
                            <div class="rounded-[1.2rem] border border-slate-100 bg-white px-4 py-2 shadow-sm">
                                <p class="text-[10px] font-black uppercase italic tracking-wider text-slate-500">
                                    Sedang mengetik...
                                </p>
                            </div>
                        </div>
                    </template>
                </template>
            </div>

            <!-- Attachment preview -->
            <div v-if="selectedConversation && (filePreview || selectedFile)" class="border-t border-slate-100 bg-slate-50 px-4 py-3 sm:px-6">
                <div class="flex items-center gap-3">
                    <div class="h-16 w-16 overflow-hidden rounded-xl border border-white bg-white shadow-sm">
                        <img v-if="filePreview" :src="filePreview" class="h-full w-full object-cover" />
                        <div v-else class="flex h-full w-full items-center justify-center text-slate-400">
                            <FileIcon class="h-6 w-6" />
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs font-black uppercase italic text-slate-700">{{ selectedFile?.name }}</p>
                        <p class="text-[10px] font-bold italic text-slate-400">
                            {{ selectedFile?.size ? (selectedFile.size / 1024 / 1024).toFixed(2) + ' MB' : '' }}
                        </p>
                    </div>
                    <button class="rounded-full p-1.5 text-slate-500 hover:bg-white" @click="clearAttachment">
                        <X class="h-4 w-4" />
                    </button>
                </div>
            </div>

            <!-- Composer -->
            <div v-if="selectedConversation" class="border-t border-slate-100 bg-white px-3 py-3 sm:px-6">
                <div class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 focus-within:border-sky-200 focus-within:ring-4 focus-within:ring-sky-700/10">
                    <input
                        ref="fileInput"
                        type="file"
                        class="hidden"
                        accept="image/*,.pdf,.doc,.docx"
                        @change="handleFileSelect"
                    />

                    <button class="rounded-full p-2 text-slate-500 hover:bg-white hover:text-sky-700" @click="fileInput?.click()">
                        <Paperclip class="h-4 w-4" />
                    </button>

                    <input
                        v-model="newMessage"
                        type="text"
                        placeholder="Tulis pesan..."
                        class="flex-1 border-none bg-transparent py-2 text-xs font-bold italic text-slate-700 outline-none"
                        @input="sendTypingEvent"
                        @keyup.enter="sendMessage"
                    />

                    <button
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-700 text-white transition hover:bg-sky-800 disabled:opacity-40"
                        :disabled="(!newMessage.trim() && !selectedFile) || isLoading"
                        @click="sendMessage"
                    >
                        <Send class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </main>
    </div>

    <!-- Mobile Sidebar -->
    <Teleport to="body">
        <Transition name="fade">
            <div v-if="isMobileSidebarOpen" class="fixed inset-0 z-50 md:hidden">
                <div class="absolute inset-0 bg-slate-900/40" @click="isMobileSidebarOpen = false" />
                <div class="absolute inset-y-0 left-0 w-[86%] max-w-sm border-r border-slate-200 bg-white shadow-xl">
                    <div class="flex items-center justify-between border-b border-slate-100 px-4 py-4">
                        <h3 class="text-base font-black uppercase italic text-slate-900">Inbox</h3>
                        <button class="rounded-xl p-2 text-slate-500 hover:bg-slate-50" @click="isMobileSidebarOpen = false">
                            <ChevronLeft class="h-4 w-4" />
                        </button>
                    </div>

                    <div class="px-4 py-3">
                        <div class="relative">
                            <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-300" />
                            <input
                                v-model="searchContact"
                                type="text"
                                placeholder="Cari kontak..."
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-3 text-xs font-bold italic outline-none"
                            />
                        </div>
                    </div>

                    <div class="custom-scrollbar h-[calc(100%-116px)] space-y-2 overflow-y-auto px-3 pb-3">
                        <button
                            v-for="conv in filteredConversations"
                            :key="`mobile-${conv.id}`"
                            class="flex w-full items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-3 py-3 text-left"
                            @click="selectConversation(conv)"
                        >
                            <div class="h-10 w-10 overflow-hidden rounded-xl bg-sky-700 text-white">
                                <img
                                    v-if="getAvatar(getPartner(conv))"
                                    :src="getAvatar(getPartner(conv)) ?? undefined"
                                    class="h-full w-full object-cover"
                                />
                                <div v-else class="flex h-full w-full items-center justify-center text-sm font-black italic">
                                    {{ getDisplayName(getPartner(conv)).charAt(0) }}
                                </div>
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-xs font-black uppercase italic text-slate-900">{{ getDisplayName(getPartner(conv)) }}</p>
                                <p class="truncate text-[10px] font-bold italic text-slate-400">{{ getLastMessageText(conv) }}</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>