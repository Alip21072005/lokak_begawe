<script setup lang="ts">
import { Head, usePage, router } from '@inertiajs/vue3';
import axios from 'axios';
import { 
    Send, Search, MoreVertical, CheckCheck, 
    MessageSquare, Paperclip, Clock, AlertCircle, Phone, FileIcon, X
} from 'lucide-vue-next';
import { ref, onMounted, nextTick, computed } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{ conversations: any[] }>();
defineOptions({ layout: AppLayout });
const page = usePage();

// KUNCI KEAMANAN ID: Selalu gunakan string lowercase yang sudah di-trim
const authUser = computed(() => page.props.auth.user);
const authId = computed(() => String(authUser.value?.id).trim().toLowerCase());

// --- STATE MANAGEMENT ---
const selectedConversation = ref<any>(null);
const messages = ref<any[]>([]);
const messageContainer = ref<HTMLElement | null>(null);
const searchContact = ref('');
const isLoading = ref(false);
const newMessage = ref('');

// PRESENCE & TYPING STATE
const onlineUsers = ref<Set<string>>(new Set());
const isPartnerTyping = ref(false);
let typingTimer: ReturnType<typeof setTimeout>;

// ATTACHMENT STATE
const fileInput = ref<HTMLInputElement | null>(null);
const selectedFile = ref<File | null>(null);
const filePreview = ref<string | null>(null);

// --- HELPERS UMUM ---
const getPartner = (conv: any) => {
    if (!conv) {
return null;
}

    return String(conv.sender_id).toLowerCase() === authId.value ? conv.receiver : conv.sender;
};

const getAvatar = (user: any) => {
    if (user?.pelamar?.foto_pelamar) {
return `/storage/${user.pelamar.foto_pelamar}`;
}

    if (user?.mitra?.logo_mitra) {
return `/storage/${user.mitra.logo_mitra}`;
}

    return null;
};

const isPartnerOnline = computed(() => {
    if (!selectedConversation.value) {
return false;
}

    const partnerId = String(getPartner(selectedConversation.value)?.id).toLowerCase();

    return onlineUsers.value.has(partnerId);
});

// --- HELPERS DATE SEPARATOR ---
const isNewDay = (currentMsg: any, prevMsg: any) => {
    if (!prevMsg) {
return true;
}

    const currentDate = new Date(currentMsg.created_at).setHours(0,0,0,0);
    const prevDate = new Date(prevMsg.created_at).setHours(0,0,0,0);

    return currentDate !== prevDate;
};

const formatDateSeparator = (dateString: string) => {
    const date = new Date(dateString);
    const today = new Date();
    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);

    if (date.toDateString() === today.toDateString()) {
return 'Hari Ini';
}

    if (date.toDateString() === yesterday.toDateString()) {
return 'Kemarin';
}

    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

// --- LIFECYCLE & WEBSOCKET ---
onMounted(() => {
    if ((window as any).Echo) {
        // 1. PRESENCE (Status Online)
        (window as any).Echo.join('online')
            .here((users: any[]) => users.forEach(u => onlineUsers.value.add(String(u.id).toLowerCase())))
            .joining((user: any) => onlineUsers.value.add(String(user.id).toLowerCase()))
            .leaving((user: any) => onlineUsers.value.delete(String(user.id).toLowerCase()));

        // 2. PRIVATE CHANNEL (Pesan & Interaksi)
        (window as any).Echo.private(`chat.${authId.value}`)
            .listen('.MessageSent', (e: any) => {
                if (selectedConversation.value && String(e.message.conversation_id) === String(selectedConversation.value.id)) {
                    e.message.read = true;
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
                    messages.value.forEach(msg => {
                        if (String(msg.sender_id).toLowerCase() === authId.value) {
msg.read = true;
}
                    });
                }

                router.reload({ only: ['conversations'] });
            })
            .listenForWhisper('typing', (e: any) => {
                if (selectedConversation.value && String(e.conversation_id) === String(selectedConversation.value.id)) {
                    isPartnerTyping.value = true;
                    scrollToBottom();
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(() => isPartnerTyping.value = false, 2000);
                }
            });
    }
});

// --- ACTIONS ---
const selectConversation = async (conv: any) => {
    if (selectedConversation.value?.id === conv.id) {
return;
}

    selectedConversation.value = conv;
    isLoading.value = true;
    messages.value = [];
    isPartnerTyping.value = false;
    
    // Reset Attachment saat ganti chat
    selectedFile.value = null;
    filePreview.value = null;
    
    try {
        const response = await axios.get(route('messages.show', conv.id));
        messages.value = response.data.messages || [];
        scrollToBottom();
        axios.post(route('messages.read', conv.id));
    } catch (error) {
        console.error("Gagal memuat pesan.", error);
    } finally {
        isLoading.value = false;
    }
};

const handleFileSelect = (e: any) => {
    const file = e.target.files[0];

    if (!file) {
return;
}

    selectedFile.value = file;

    if (file.type.startsWith('image/')) {
        filePreview.value = URL.createObjectURL(file);
    } else {
        filePreview.value = null; // Reset preview kalau bukan gambar
    }
};

const sendTypingEvent = () => {
    if (!selectedConversation.value) {
return;
}

    const partnerId = String(getPartner(selectedConversation.value)?.id).toLowerCase();
    (window as any).Echo.private(`chat.${partnerId}`).whisper('typing', { conversation_id: selectedConversation.value.id });
};

const sendMessage = async () => {
    if ((!newMessage.value.trim() && !selectedFile.value) || !selectedConversation.value) {
return;
}

    const currentConvId = selectedConversation.value.id;
    const partnerId = getPartner(selectedConversation.value).id;
    const tempId = Date.now();

    const formData = new FormData();
    formData.append('receiver_id', partnerId);
    formData.append('conversation_id', currentConvId);
    
    if (newMessage.value.trim()) {
        formData.append('body', newMessage.value);
    }

    let tempAttachmentType = null;
    let tempPreviewUrl = null;

    if (selectedFile.value) {
        formData.append('file', selectedFile.value);
        tempAttachmentType = selectedFile.value.type.startsWith('image/') ? 'image' : 'file';
        tempPreviewUrl = filePreview.value; 
    }

    // OPTIMISTIC UI
    messages.value.push({
        id: tempId,
        sender_id: authId.value, 
        body: newMessage.value,
        attachment_url: tempPreviewUrl, // Blob URL sementara
        attachment_type: tempAttachmentType,
        read: false,
        created_at: new Date().toISOString(),
        sending: true
    });
    
    newMessage.value = ''; 
    selectedFile.value = null;
    filePreview.value = null;

    if (fileInput.value) {
fileInput.value.value = '';
} // Reset input file DOM

    scrollToBottom();

    try {
        const res = await axios.post(route('messages.store'), formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        
        const index = messages.value.findIndex(m => m.id === tempId);

        if (index !== -1) {
            messages.value[index] = res.data;
            messages.value[index].sending = false;
        }
    } catch (e: any) {
        console.error("Gagal Kirim:", e);
        const index = messages.value.findIndex(m => m.id === tempId);

        if (index !== -1) {
            messages.value[index].sending = false;
            messages.value[index].error = true;
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

const filteredConversations = computed(() => {
    return (props.conversations || []).filter(c => 
        getPartner(c)?.name.toLowerCase().includes(searchContact.value.toLowerCase())
    );
});

// --- METHODS ---
const openAttachment = (msg: any) => {
    if (!msg.attachment_url.startsWith('blob:')) {
        window.open(`/storage/${msg.attachment_url}`, '_blank');
    }
};
</script>

<template>
    <Head title="Pesan - Lokak Begawe" />

    <div class="flex h-[calc(100vh-140px)] overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white shadow-2xl mx-4 my-4">
        
        <div class="hidden w-80 flex-col bg-slate-50/40 border-r border-slate-100 md:flex lg:w-96">
            <div class="p-8 pb-4 italic font-black text-2xl text-slate-900 uppercase tracking-tighter">INBOX</div>
            <div class="px-8 mb-6">
                <div class="relative">
                    <Search class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-300" />
                    <input v-model="searchContact" type="text" placeholder="Cari kontak..." class="w-full rounded-2xl border-none bg-white px-12 py-4 text-[11px] font-bold shadow-sm outline-none ring-1 ring-slate-100 focus:ring-4 focus:ring-sky-700/5 transition-all italic" />
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-2 custom-scrollbar">
                <button v-for="conv in filteredConversations" :key="conv.id" @click="selectConversation(conv)"
                    :class="[selectedConversation?.id === conv.id ? 'bg-white shadow-xl ring-1 ring-sky-700/10' : 'hover:bg-white/60']"
                    class="flex w-full items-center gap-4 rounded-[2.2rem] p-4 transition-all duration-300 relative active:scale-95">
                    
                    <div class="h-14 w-14 shrink-0 rounded-[1.2rem] overflow-hidden bg-sky-700 flex items-center justify-center border-2 border-white shadow-sm relative">
                        <img v-if="getAvatar(getPartner(conv))" :src="getAvatar(getPartner(conv)) ?? undefined" class="h-full w-full object-cover" />
                        <span v-else class="text-white font-black italic">{{ getPartner(conv)?.name.charAt(0) }}</span>
                    </div>

                    <div v-if="onlineUsers.has(String(getPartner(conv)?.id).toLowerCase())" class="absolute left-14 top-4 h-3.5 w-3.5 bg-emerald-500 border-2 border-white rounded-full"></div>

                    <div class="flex-1 text-left truncate">
                        <div class="flex justify-between items-center mb-1">
                            <p class="text-xs font-black uppercase italic text-slate-900 truncate pr-2">{{ getPartner(conv)?.name }}</p>
                            <span class="text-[8px] font-black text-slate-300 uppercase italic">
                                {{ new Date(conv.updated_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}
                            </span>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 truncate italic leading-none">
                            <template v-if="conv.last_message?.attachment_url">🖼️ Foto/File terlampir</template>
                            <template v-else>{{ conv.last_message?.body || 'Belum ada pesan...' }}</template>
                        </p>
                    </div>
                </button>
            </div>
        </div>

        <div class="flex flex-1 flex-col bg-white overflow-hidden">
            <template v-if="selectedConversation">
                <div class="flex items-center justify-between border-b border-slate-50 p-6 px-8 bg-white/90 backdrop-blur-md z-10">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-sky-50 flex items-center justify-center text-sky-700 font-black italic border border-sky-100 overflow-hidden shadow-inner">
                            <img v-if="getAvatar(getPartner(selectedConversation))" :src="getAvatar(getPartner(selectedConversation)) ?? undefined" class="h-full w-full object-cover" />
                            <span v-else>{{ getPartner(selectedConversation)?.name.charAt(0) }}</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-black uppercase italic text-slate-900 tracking-tight">{{ getPartner(selectedConversation)?.name }}</h3>
                            <div v-if="isPartnerOnline" class="flex items-center gap-1.5 mt-0.5">
                                <div class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                <p class="text-[9px] font-black text-emerald-500 uppercase italic tracking-widest">Online</p>
                            </div>
                            <div v-else class="flex items-center gap-1.5 mt-0.5">
                                <div class="h-1.5 w-1.5 rounded-full bg-slate-300"></div>
                                <p class="text-[9px] font-black text-slate-400 uppercase italic tracking-widest">Offline</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="p-3 rounded-2xl text-slate-400 hover:bg-slate-50 transition-all"><Phone class="h-4 w-4" /></button>
                        <button class="p-3 rounded-2xl text-slate-400 hover:bg-slate-50 transition-all"><MoreVertical class="h-4 w-4" /></button>
                    </div>
                </div>

                <div ref="messageContainer" class="flex-1 overflow-y-auto p-8 px-10 space-y-4 bg-slate-50/20 custom-scrollbar scroll-smooth flex flex-col">
                    <div v-for="(msg, index) in messages" :key="msg.id" class="flex flex-col w-full">
                        
                        <div v-if="isNewDay(msg, messages[index - 1])" class="flex justify-center w-full my-6 mb-8">
                            <span class="bg-sky-50 text-sky-700 text-[9px] font-black uppercase italic tracking-widest px-4 py-1.5 rounded-full border border-sky-100/50 shadow-sm">
                                {{ formatDateSeparator(msg.created_at) }}
                            </span>
                        </div>

                        <div class="flex w-full mb-2" :class="[String(msg.sender_id).toLowerCase() === authId ? 'justify-end' : 'justify-start']">
                            <div class="max-w-[75%] lg:max-w-[60%] flex flex-col" 
                                 :class="[String(msg.sender_id).toLowerCase() === authId ? 'items-end ml-auto' : 'items-start mr-auto']">
                                
                                <div :class="[
                                    String(msg.sender_id).toLowerCase() === authId 
                                    ? 'bg-slate-900 text-white rounded-[1.8rem] rounded-tr-none shadow-xl shadow-slate-900/10' 
                                    : 'bg-white text-slate-700 border border-slate-100 rounded-[1.8rem] rounded-tl-none shadow-sm'
                                ]" class="p-2 relative transition-all duration-300">
                                    
                                    <div v-if="msg.attachment_url" class="mb-1">
                                        <img v-if="msg.attachment_type === 'image'" 
                                             :src="msg.attachment_url.startsWith('blob:') ? msg.attachment_url : `/storage/${msg.attachment_url}`" 
                                             class="max-w-full h-auto rounded-[1.4rem] cursor-pointer hover:opacity-95 transition-opacity" 
                                             @click="openAttachment(msg)" />
                                        
                                        <a v-else :href="msg.attachment_url.startsWith('blob:') ? '#' : `/storage/${msg.attachment_url}`" 
                                           target="_blank" 
                                           class="flex items-center gap-3 p-4 bg-black/10 rounded-[1.4rem] hover:bg-black/20 transition-colors">
                                            <FileIcon class="h-6 w-6" />
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-black italic uppercase">Lihat Dokumen</span>
                                                <span class="text-[8px] opacity-70">Lampiran File</span>
                                            </div>
                                        </a>
                                    </div>

                                    <p v-if="msg.body" class="p-3 px-4 text-[11px] font-bold leading-relaxed italic">{{ msg.body }}</p>
                                    
                                    <div v-if="msg.error" class="absolute -right-8 top-1/2 -translate-y-1/2 text-rose-500 animate-bounce" title="Gagal Terkirim">
                                        <AlertCircle class="h-4 w-4" />
                                    </div>
                                </div>
                                
                                <div :class="[String(msg.sender_id).toLowerCase() === authId ? 'flex-row-reverse' : '']" 
                                     class="mt-2 flex items-center gap-1.5 px-1 opacity-50">
                                    <span class="text-[7px] font-black uppercase italic tracking-tighter">
                                        {{ new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}
                                    </span>
                                    <template v-if="String(msg.sender_id).toLowerCase() === authId">
                                        <Clock v-if="msg.sending" class="h-2.5 w-2.5 animate-spin" />
                                        <CheckCheck v-else-if="msg.read" class="h-3 w-3 text-sky-400" />
                                        <CheckCheck v-else class="h-3 w-3 text-slate-400" />
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="isPartnerTyping" class="flex w-full justify-start animate-pulse mt-2">
                        <div class="max-w-[75%] flex flex-col items-start mr-auto">
                            <div class="bg-white text-slate-500 border border-slate-100 rounded-[1.8rem] rounded-tl-none p-4 px-5 shadow-sm flex items-center gap-3">
                                <p class="text-[9px] font-black italic uppercase tracking-widest">Sedang mengetik</p>
                                <div class="flex gap-1 mt-0.5">
                                    <span class="h-1.5 w-1.5 bg-sky-700 rounded-full animate-bounce"></span>
                                    <span class="h-1.5 w-1.5 bg-sky-700 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                                    <span class="h-1.5 w-1.5 bg-sky-700 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="filePreview || selectedFile" class="p-4 px-8 bg-slate-50 border-t border-slate-100 flex items-center gap-4 transition-all duration-300">
                    <div class="relative h-24 w-24 rounded-2xl overflow-hidden border-4 border-white shadow-lg bg-white flex items-center justify-center group">
                        <img v-if="filePreview" :src="filePreview" class="h-full w-full object-cover" />
                        <div v-else class="flex flex-col items-center gap-1 text-slate-400">
                            <FileIcon class="h-8 w-8" />
                            <span class="text-[8px] font-black uppercase">Dokumen</span>
                        </div>
                        <button @click="selectedFile = null; filePreview = null; if(fileInput) fileInput.value = ''" 
                                class="absolute top-1 right-1 bg-rose-500/90 hover:bg-rose-600 text-white rounded-full p-1 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">
                            <X class="h-3 w-3" />
                        </button>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-[11px] font-black italic text-slate-700 uppercase truncate max-w-50">{{ selectedFile?.name }}</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5">{{ selectedFile?.size ? (selectedFile.size / 1024 / 1024).toFixed(2) + ' MB' : '' }}</p>
                    </div>
                </div>

                <div class="p-8 bg-white border-t border-slate-50 z-10">
                    <div class="flex items-center gap-4 bg-slate-50 border border-slate-100 rounded-[2.5rem] p-2 pl-8 focus-within:ring-4 focus-within:ring-sky-700/5 focus-within:bg-white transition-all duration-300 shadow-sm">
                        <input type="file" ref="fileInput" class="hidden" @change="handleFileSelect" accept="image/*,.pdf,.doc,.docx" />
                        
                        <button @click="fileInput?.click()" class="text-slate-400 hover:text-sky-700 transition-colors p-2 rounded-full hover:bg-sky-50">
                            <Paperclip class="h-5 w-5" />
                        </button>
                        
                        <input 
                            v-model="newMessage" 
                            @input="sendTypingEvent"
                            @keyup.enter="sendMessage" 
                            type="text" 
                            placeholder="Tulis pesan anda..." 
                            class="flex-1 border-none bg-transparent py-4 text-[11px] font-bold outline-none italic text-slate-700 placeholder:text-slate-300 focus:ring-0" 
                        />
                        
                        <button 
                            @click="sendMessage"
                            :disabled="(!newMessage.trim() && !selectedFile)"
                            class="h-14 w-14 flex items-center justify-center rounded-full bg-sky-700 text-white shadow-xl shadow-sky-900/20 hover:bg-sky-800 active:scale-90 transition-all disabled:opacity-30 disabled:grayscale disabled:hover:scale-100"
                        >
                            <Send class="h-5 w-5" />
                        </button>
                    </div>
                </div>
            </template>

            <div v-else class="flex h-full flex-col items-center justify-center text-center p-10 bg-slate-50/20">
                <div class="h-40 w-40 rounded-[4rem] bg-white shadow-2xl flex items-center justify-center mb-8 border border-slate-50 relative group cursor-default">
                    <div class="absolute inset-0 bg-sky-700/5 rounded-[4rem] group-hover:animate-ping transition-all"></div>
                    <MessageSquare class="h-16 w-16 text-sky-700/20 relative z-10 group-hover:scale-110 transition-transform duration-500" />
                </div>
                <h3 class="text-2xl font-black uppercase italic text-slate-900 tracking-tighter">Lokak <span class="text-sky-700">Chat</span></h3>
                <p class="text-[11px] font-bold text-slate-400 uppercase italic tracking-[0.2em] mt-3 max-w-xs leading-relaxed">
                    Pilih percakapan di samping untuk memulai komunikasi profesional dengan pelamar.
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

@keyframes popIn {
    from { opacity: 0; transform: scale(0.95) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-bubble {
    animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
}
</style>