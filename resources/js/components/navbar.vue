<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Bot, Menu, X, LayoutDashboard, LogIn } from 'lucide-vue-next';
import { ref, onMounted, onUnmounted } from 'vue';

const isMobileMenuOpen = ref(false);
const isScrolled = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <header
        class="fixed top-0 z-50 w-full transition-all duration-500"
        :class="[
            isScrolled
                ? 'border-b border-white/20 bg-white/70 py-3 shadow-sm backdrop-blur-md'
                : 'border-b border-slate-100 bg-white py-5',
        ]"
    >
        <div class="flex w-full items-center justify-between px-6 md:px-16 lg:px-24 xl:px-32">
            <Link href="/" class="group flex shrink-0 items-center gap-4">
                <div class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-2xl bg-lokak-brand shadow-lg transition-transform group-hover:rotate-6">
                    <img src="/Logo.png" alt="Logo" class="h-full w-full object-cover" />
                </div>
                <div class="flex flex-col leading-none">
                    <span class="text-lg font-black tracking-tighter text-lokak-text uppercase italic">
                        LOKAK<span class="text-lokak-brand">BEGAWE</span>
                    </span>
                    <span class="mt-1 text-[11px] font-bold tracking-widest text-slate-400 uppercase italic">
                        Bengkulu Job Portal
                    </span>
                </div>
            </Link>

            <nav class="hidden items-center gap-10 text-sm font-black tracking-widest uppercase italic lg:flex">
                <Link href="/" class="link-desktop" :class="{ active: $page.url === '/' }">Beranda</Link>
                <Link href="/lowongan" class="link-desktop" :class="{ active: $page.url.startsWith('/lowongan') }">Lowongan</Link>
                <Link href="/mitra" class="link-desktop" :class="{ active: $page.url.startsWith('/mitra') }">Mitra</Link>

                <div class="ml-6 flex items-center gap-4 border-l border-slate-200/50 pl-10">
                    <template v-if="!$page.props.auth.user">
                        <Link href="/login" class="btn-primary-small text-xs">MASUK</Link>
                    </template>
                    <template v-else>
                        <Link href="/dashboard" class="btn-primary-small text-xs">DASHBOARD</Link>
                    </template>
                    
                    <button class="icon-btn-custom group relative" title="AI Chatbot (Coming Soon)">
                        <Bot class="h-5 w-5 text-slate-600 transition-colors group-hover:text-lokak-brand" />
                        <span class="absolute -top-1 -right-1 flex h-2 w-2 rounded-full bg-lokak-brand animate-pulse"></span>
                    </button>
                </div>
            </nav>

            <div class="flex items-center gap-3 lg:hidden">
                <button class="icon-btn-custom h-11 w-11">
                    <Bot class="h-5 w-5 text-slate-600" />
                </button>
                <button @click="isMobileMenuOpen = true" class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-900 text-white shadow-md active:scale-95 transition-all">
                    <Menu class="h-5 w-5" />
                </button>
            </div>
        </div>
    </header>

    <Teleport to="body">
        <Transition name="fade">
            <div v-if="isMobileMenuOpen" @click="isMobileMenuOpen = false" class="fixed inset-0 z-100 bg-slate-900/60 backdrop-blur-sm lg:hidden"></div>
        </Transition>
        <Transition name="slide">
            <div v-if="isMobileMenuOpen" class="fixed top-0 right-0 z-101 h-full w-80 bg-white shadow-2xl lg:hidden">
                <div class="flex h-full flex-col p-8">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-8 mb-8">
                        <div class="flex flex-col">
                            <span class="text-xl font-black text-lokak-text italic tracking-tighter uppercase">MENU</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase italic">Navigasi Cepat</span>
                        </div>
                        <button @click="isMobileMenuOpen = false" class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-500">
                            <X class="h-5 w-5" />
                        </button>
                    </div>

                    <div class="flex flex-col gap-6">
                        <Link href="/" @click="isMobileMenuOpen = false" class="mobile-link" :class="{ 'text-lokak-brand': $page.url === '/' }">Beranda</Link>
                        <Link href="/lowongan" @click="isMobileMenuOpen = false" class="mobile-link" :class="{ 'text-lokak-brand': $page.url.startsWith('/lowongan') }">Lowongan</Link>
                        <Link href="/mitra" @click="isMobileMenuOpen = false" class="mobile-link" :class="{ 'text-lokak-brand': $page.url.startsWith('/mitra') }">Mitra</Link>
                    </div>

                    <div class="mt-auto border-t border-slate-100 pt-8">
                        <template v-if="!$page.props.auth.user">
                            <Link href="/login" @click="isMobileMenuOpen = false" class="flex w-full items-center justify-center gap-3 rounded-2xl bg-slate-900 py-4 text-xs font-black text-white uppercase italic shadow-xl transition-all active:scale-95">
                                <LogIn class="h-4 w-4" /> MASUK KE AKUN
                            </Link>
                        </template>
                        <template v-else>
                            <Link href="/dashboard" @click="isMobileMenuOpen = false" class="flex w-full items-center justify-center gap-3 rounded-2xl bg-lokak-brand py-4 text-xs font-black text-white uppercase italic shadow-xl shadow-sky-900/20 transition-all active:scale-95">
                                <LayoutDashboard class="h-4 w-4" /> DASHBOARD SAYA
                            </Link>
                        </template>
                        <p class="mt-6 text-center text-[9px] font-bold text-slate-300 uppercase italic tracking-widest">
                            © 2026 Lokak Begawe Bengkulu
                        </p>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

