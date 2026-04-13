<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

// State untuk membuka/menutup menu burger
const isOpen = ref(false);
</script>

<template>
    <header
        class="sticky top-0 z-50 w-full bg-[#598392] text-[#DDEEF3] shadow-md"
    >
        <div
            class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3"
        >
            <Link href="/" class="flex shrink-0 items-center gap-2">
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-700 font-black text-white italic"
                >
                    L
                </div>
                <div class="flex flex-col leading-none">
                    <span
                        class="text-sm font-black tracking-tighter text-white uppercase"
                        >LOKAKBEGAWE</span
                    >
                    <span
                        class="text-[8px] font-bold text-white uppercase italic opacity-60"
                        >Ado Lokak, Pela Begawe</span
                    >
                </div>
            </Link>

            <button
                @click="isOpen = !isOpen"
                class="z-50 p-1 text-white focus:outline-none lg:hidden"
            >
                <svg
                    v-if="!isOpen"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-7 w-7"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16m-7 6h7"
                    />
                </svg>
                <svg
                    v-else
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-7 w-7"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>

            <div
                class="hidden items-center gap-7 text-[10px] font-extrabold text-slate-300 uppercase lg:flex"
            >
                <Link
                    href="/"
                    :class="{
                        'border-b-2 border-white pb-1 text-white':
                            $page.url === '/',
                    }"
                    >Beranda</Link
                >
                <Link
                    href="/lowongan"
                    :class="{
                        'border-b-2 border-white pb-1 text-white':
                            $page.url.startsWith('/lowongan'),
                    }"
                    >Lowongan</Link
                >
                <Link
                    href="/mitra"
                    :class="{
                        'border-b-2 border-white pb-1 text-white':
                            $page.url.startsWith('/mitra'),
                    }"
                    >Mitra</Link
                >

                <template v-if="!$page.props.auth.user">
                    <Link
                        href="/login"
                        class="rounded-lg border border-slate-700 px-4 py-1.5 text-white transition hover:border-white"
                        >LOGIN PELAMAR</Link
                    >
                    <Link
                        href="/login"
                        class="rounded-lg bg-[#1A313C] px-4 py-1.5 text-white italic"
                        >LOGIN MITRA</Link
                    >
                </template>
                <template v-else>
                    <Link
                        v-if="$page.props.auth.user.role === 'admin'"
                        href="/dashboard/admin"
                        @click="isOpen = false"
                        class="rounded-xl bg-[#1A313C] px-4 py-1.5 text-center text-white italic"
                    >
                        DASHBOARD ADMIN
                    </Link>

                    <Link
                        v-else-if="$page.props.auth.user.role === 'pelamar'"
                        href="/dashboard/pelamar"
                        @click="isOpen = false"
                        class="rounded-xl bg-[#1A313C] px-4 py-1.5 text-center text-white italic"
                    >
                        DASHBOARD PELAMAR
                    </Link>

                    <Link
                        v-else-if="$page.props.auth.user.role === 'mitra'"
                        href="/dashboard/mitra"
                        @click="isOpen = false"
                        class="rounded-xl bg-[#1A313C] px-4 py-1.5 text-center text-white italic"
                    >
                        DASHBOARD MITRA
                    </Link>
                </template>

                <button class="text-lg text-yellow-400">🔔</button>
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
            <div
                v-show="isOpen"
                class="max-h-[calc(100vh-60px)] overflow-y-auto border-t border-white/10 bg-[#4a6e7a] shadow-inner lg:hidden"
            >
                <nav
                    class="flex flex-col gap-5 p-6 text-[13px] font-extrabold text-slate-300 uppercase"
                >
                    <Link
                        href="/"
                        @click="isOpen = false"
                        :class="{ 'text-white': $page.url === '/' }"
                        >Beranda</Link
                    >
                    <Link
                        href="/lowongan"
                        @click="isOpen = false"
                        :class="{
                            'text-white': $page.url.startsWith('/lowongan'),
                        }"
                        >Lowongan</Link
                    >
                    <Link
                        href="/mitra"
                        @click="isOpen = false"
                        :class="{
                            'text-white': $page.url.startsWith('/mitra'),
                        }"
                        >Mitra</Link
                    >

                    <hr class="my-2 border-white/10" />

                    <template v-if="!$page.props.auth.user">
                        <Link
                            href="/login"
                            @click="isOpen = false"
                            class="rounded-xl border border-slate-700 py-3 text-center text-white"
                            >LOGIN USER</Link
                        >
                        <Link
                            href="/login"
                            @click="isOpen = false"
                            class="rounded-xl bg-[#1A313C] py-3 text-center text-white italic"
                            >LOGIN MITRA</Link
                        >
                    </template>
                    <Link
                        v-if="
                            $page.props.auth.user &&
                            $page.props.auth.user.role === 'admin'
                        "
                        href="/dashboard/admin"
                        @click="isOpen = false"
                        class="rounded-xl bg-[#1A313C] py-3 text-center text-white italic"
                    >
                        DASHBOARD ADMIN
                    </Link>

                    <Link
                        v-else-if="
                            $page.props.auth.user &&
                            $page.props.auth.user.role === 'pelamar'
                        "
                        href="/dashboard/pelamar"
                        @click="isOpen = false"
                        class="rounded-xl bg-[#1A313C] py-3 text-center text-white italic"
                    >
                        DASHBOARD PELAMAR
                    </Link>

                    <Link
                        v-else-if="
                            $page.props.auth.user &&
                            $page.props.auth.user.role === 'mitra'
                        "
                        href="/dashboard/mitra"
                        @click="isOpen = false"
                        class="rounded-xl bg-[#1A313C] py-3 text-center text-white italic"
                    >
                        DASHBOARD MITRA
                    </Link>

                    <div class="h-10"></div>
                </nav>
            </div>
        </transition>
    </header>
</template>
