<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Bot, Menu, X, LayoutDashboard, LogIn, UserCircle } from 'lucide-vue-next';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { route } from 'ziggy-js';

const isMobileMenuOpen = ref(false);
const isScrolled = ref(false);

const page = usePage();
const auth = computed(() => (page.props.auth as any));

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
                ? 'bg-white/80 py-3 shadow-md backdrop-blur-lg border-b border-slate-200/50'
                : 'bg-white py-5 border-b border-slate-100',
        ]"
    >
        <div class="w-full px-6 md:px-16 lg:px-24 xl:px-32">
            <div class="flex items-center justify-between">
                
                <Link href="/" class="group flex shrink-0 items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-xl bg-lokak-brand shadow-lg transition-transform group-hover:rotate-6 md:h-11 md:w-11">
                        <img src="/Logo.png" alt="Logo" class="h-full w-full object-cover" />
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="text-lg font-black tracking-tighter text-slate-900 uppercase italic md:text-xl">
                            LOKAK<span class="text-lokak-brand">BEGAWE</span>
                        </span>
                        <span class="mt-0.5 text-[10px] font-bold tracking-widest text-slate-400 uppercase italic">
                            Bengkulu Job Portal
                        </span>
                    </div>
                </Link>

                <nav class="hidden items-center gap-8 text-sm font-bold tracking-wide lg:flex">
                    <Link :href="route('welcome')" 
                        class="transition-colors hover:text-lokak-brand" 
                        :class="$page.url === '/' ? 'text-lokak-brand' : 'text-slate-600'">
                        Beranda
                    </Link>
                    <Link :href="route('lowongan.index')" 
                        class="transition-colors hover:text-lokak-brand" 
                        :class="$page.url.startsWith('/lowongan') ? 'text-lokak-brand' : 'text-slate-600'">
                        Lowongan
                    </Link>
                    <Link :href="route('mitra.index')" 
                        class="transition-colors hover:text-lokak-brand" 
                        :class="$page.url.startsWith('/mitra') ? 'text-lokak-brand' : 'text-slate-600'">
                        Mitra
                    </Link>

                    <div class="ml-4 flex items-center gap-4 border-l border-slate-200 pl-8">
                        <template v-if="!auth.user">
                            <Link :href="route('login')" class="rounded-full bg-slate-900 px-6 py-2.5 text-xs font-bold text-white transition-all hover:bg-lokak-brand hover:shadow-lg active:scale-95">
                                MASUK
                            </Link>
                        </template>
                        
                        <template v-else>
                            <Link href="/dashboard" class="group flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-slate-700 transition-all hover:bg-lokak-brand hover:text-white">
                                <UserCircle class="h-5 w-5" />
                                <span class="max-w-30 truncate text-xs font-bold uppercase">
                                    {{ auth.user.name.split(' ')[0] }}
                                </span>
                            </Link>
                        </template>
                        
                        <button class="relative flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 transition-colors hover:bg-sky-50">
                            <Bot class="h-5 w-5 text-slate-500 transition-colors hover:text-lokak-brand" />
                            <span class="absolute -right-0.5 -top-0.5 flex h-2.5 w-2.5">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-sky-400 opacity-75"></span>
                                <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-sky-500"></span>
                            </span>
                        </button>
                    </div>
                </nav>

                <div class="flex items-center lg:hidden">
                    <button @click="isMobileMenuOpen = true" class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-900 text-white shadow-md active:scale-95 transition-all">
                        <Menu class="h-5 w-5" />
                    </button>
                </div>
            </div>
        </div>
    </header>

    <Teleport to="body">
        <Transition name="fade">
            <div v-if="isMobileMenuOpen" @click="isMobileMenuOpen = false" class="fixed inset-0 z-100 bg-slate-900/60 backdrop-blur-sm lg:hidden"></div>
        </Transition>
        <Transition name="slide">
            <div v-if="isMobileMenuOpen" class="fixed right-0 top-0 z-101 h-full w-72 bg-white shadow-2xl lg:hidden">
                <div class="flex h-full flex-col p-6">
                    <div class="mb-8 flex items-center justify-between border-b border-slate-100 pb-6">
                        <span class="text-sm font-black tracking-widest text-slate-400 uppercase italic">Navigasi</span>
                        <button @click="isMobileMenuOpen = false" class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:text-rose-500">
                            <X class="h-5 w-5" />
                        </button>
                    </div>

                    <div class="flex flex-col gap-2">
                        <Link 
                            :href="route('welcome')" 
                            @click="isMobileMenuOpen = false" 
                            class="mobile-link"
                            :class="{ 'bg-sky-50 text-lokak-brand shadow-sm': $page.url === '/' }"
                        >
                            Beranda
                        </Link>
                        <Link 
                            :href="route('lowongan.index')" 
                            @click="isMobileMenuOpen = false" 
                            class="mobile-link"
                            :class="{ 'bg-sky-50 text-lokak-brand shadow-sm': $page.url.startsWith('/lowongan') }"
                        >
                            Lowongan
                        </Link>
                        <Link 
                            :href="route('mitra.index')" 
                            @click="isMobileMenuOpen = false" 
                            class="mobile-link"
                            :class="{ 'bg-sky-50 text-lokak-brand shadow-sm': $page.url.startsWith('/mitra') }"
                        >
                            Mitra
                        </Link>
                    </div>

                    <div class="mt-auto border-t border-slate-100 pt-6">
                        <Link v-if="!auth.user" :href="route('login')" class="flex w-full items-center justify-center gap-3 rounded-2xl bg-slate-900 py-4 text-xs font-bold text-white uppercase italic shadow-xl">
                            <LogIn class="h-4 w-4" /> MASUK KE AKUN
                        </Link>
                        <Link v-else href="/dashboard" class="flex w-full items-center justify-center gap-3 rounded-2xl bg-lokak-brand py-4 text-xs font-bold text-white uppercase italic shadow-xl">
                            <LayoutDashboard class="h-4 w-4" /> DASHBOARD SAYA
                        </Link>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>

.fade-enter-active, .fade-leave-active { transition: opacity 0.4s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
.slide-enter-active, .slide-leave-active { transition: transform 0.4s cubic-bezier(0.23, 1, 0.32, 1); }
.slide-enter-from, .slide-leave-to { transform: translateX(100%); }
</style>