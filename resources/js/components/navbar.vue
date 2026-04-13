<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

// State untuk membuka/menutup menu burger
const isOpen = ref(false);
</script>

<template>
    <header class="w-full text-[#DDEEF3] sticky top-0 bg-[#598392] z-50 shadow-md">
        <div class="py-3 px-4 flex items-center justify-between max-w-7xl mx-auto">
            
            <Link href="/" class="flex items-center gap-2 shrink-0">
                <div class="w-8 h-8 bg-slate-700 rounded-xl flex items-center justify-center font-black text-white italic">L</div>
                <div class="flex flex-col leading-none">
                    <span class="font-black text-sm uppercase tracking-tighter text-white">LOKAKBEGAWE</span>
                    <span class="text-[8px] opacity-60 font-bold uppercase italic text-white">Ado Lokak, Pela Begawe</span>
                </div>
            </Link>

            <button @click="isOpen = !isOpen" class="lg:hidden p-1 text-white focus:outline-none z-50">
                <svg v-if="!isOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="hidden lg:flex items-center gap-7 uppercase font-extrabold text-slate-300 text-[10px]">
                <Link href="/" :class="{'text-white border-b-2 border-white pb-1': $page.url === '/'}">Beranda</Link>
                <Link href="/lowongan" :class="{'text-white border-b-2 border-white pb-1': $page.url.startsWith('/lowongan')}">Lowongan</Link>
                <Link href="/carimitra" :class="{'text-white border-b-2 border-white pb-1': $page.url.startsWith('/carimitra')}">Mitra</Link>
                
                <template v-if="!$page.props.auth.user">
                    <Link href="/login" class="px-4 py-1.5 rounded-lg border border-slate-700 hover:border-white transition text-white">LOGIN USER</Link>
                    <Link href="/login-mitra" class="px-4 py-1.5 rounded-lg bg-[#1A313C] text-white italic">LOGIN MITRA</Link>
                </template>
                <Link v-else href="/dashboard" class="px-4 py-1.5 rounded-lg bg-[#1A313C] text-white italic">DASHBOARD</Link>
                
                <button class="text-yellow-400 text-lg">🔔</button>
            </div>
        </div>
    
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="transform -translate-y-10 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform -translate-y-10 opacity-0"
        >
            <div v-show="isOpen" 
                class="lg:hidden bg-[#4a6e7a] border-t border-white/10 overflow-y-auto max-h-[calc(100vh-60px)] shadow-inner">
                <nav class="flex flex-col p-6 gap-5 uppercase font-extrabold text-[13px] text-slate-300">
                    <Link href="/" @click="isOpen = false" :class="{'text-white': $page.url === '/'}">Beranda</Link>
                    <Link href="/lowongan" @click="isOpen = false" :class="{'text-white': $page.url.startsWith('/lowongan')}">Lowongan</Link>
                    <Link href="/carimitra" @click="isOpen = false" :class="{'text-white': $page.url.startsWith('/carimitra')}">Mitra</Link>
                    
                    <hr class="border-white/10 my-2">
                    
                    <template v-if="!$page.props.auth.user">
                        <Link href="/login" @click="isOpen = false" class="py-3 text-center border border-slate-700 rounded-xl text-white">LOGIN USER</Link>
                        <Link href="/login-mitra" @click="isOpen = false" class="py-3 text-center bg-[#1A313C] rounded-xl text-white italic">LOGIN MITRA</Link>
                    </template>
                    <Link v-else href="/dashboard" @click="isOpen = false" class="py-3 text-center bg-[#1A313C] rounded-xl text-white italic">DASHBOARD</Link>

                    <div class="h-10"></div>
                </nav>
            </div>
        </transition>
    </header>
</template>