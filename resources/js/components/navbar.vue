<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

// State untuk membuka/menutup menu burger
const isOpen = ref(false);
</script>

<template>
    <header
        class="sticky top-0 z-50 w-full border-b border-slate-200 bg-white/90 text-lokak-text shadow-sm backdrop-blur-md transition-all"
    >
        <div
            class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 md:px-11"
        >
            <Link
                href="/"
                class="flex shrink-0 items-center gap-3 transition-transform hover:scale-[1.02]"
            >
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-lokak-brand font-black text-white italic shadow-inner"
                >
                    <img src="/Logo.png" alt="" />
                </div>
                <div class="flex flex-col leading-tight">
                    <span
                        class="text-sm font-black tracking-tighter text-lokak-text uppercase"
                    >
                        LOKAKBEGAWE
                    </span>
                    <span
                        class="text-[9px] font-bold text-lokak-text-muted uppercase italic"
                    >
                        Ado Lokak, Pela Begawe
                    </span>
                </div>
            </Link>

            <button
                @click="isOpen = !isOpen"
                class="z-50 rounded-lg p-2 text-lokak-text transition-colors hover:bg-slate-100 focus:outline-none lg:hidden"
            >
                <svg
                    v-if="!isOpen"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
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
                    class="h-6 w-6"
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
                class="hidden items-center gap-8 text-[11px] font-extrabold text-slate-500 uppercase lg:flex"
            >
                <Link
                    href="/"
                    class="py-1 transition-colors hover:text-lokak-brand"
                    :class="{
                        'border-b-2 border-lokak-brand text-lokak-brand':
                            $page.url === '/',
                    }"
                >
                    Beranda
                </Link>
                <Link
                    href="/lowongan"
                    class="py-1 transition-colors hover:text-lokak-brand"
                    :class="{
                        'border-b-2 border-lokak-brand text-lokak-brand':
                            $page.url.startsWith('/lowongan'),
                    }"
                >
                    Lowongan
                </Link>
                <Link
                    href="/mitra"
                    class="py-1 transition-colors hover:text-lokak-brand"
                    :class="{
                        'border-b-2 border-lokak-brand text-lokak-brand':
                            $page.url.startsWith('/mitra'),
                    }"
                >
                    Mitra
                </Link>

                <div
                    class="ml-4 flex items-center gap-3 border-l border-slate-200 pl-6"
                >
                    <template v-if="!$page.props.auth.user">
                        <Link
                            href="/login"
                            class="rounded-xl bg-lokak-brand px-5 py-2.5 text-[10px] font-black text-white italic shadow-lg shadow-lokak-brand/20 transition-all hover:bg-lokak-brand-dark active:scale-95"
                        >
                            LOGIN
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            v-if="$page.props.auth.user.role === 'admin'"
                            href="/dashboard/admin"
                            class="rounded-xl bg-lokak-brand px-5 py-2.5 text-[10px] font-black text-white italic shadow-lg shadow-lokak-brand/20 transition-all hover:bg-lokak-brand-dark active:scale-95"
                        >
                            DASHBOARD ADMIN
                        </Link>

                        <Link
                            v-else-if="$page.props.auth.user.role === 'pelamar'"
                            href="/dashboard/pelamar"
                            class="rounded-xl bg-lokak-brand px-5 py-2.5 text-[10px] font-black text-white italic shadow-lg shadow-lokak-brand/20 transition-all hover:bg-lokak-brand-dark active:scale-95"
                        >
                            DASHBOARD PELAMAR
                        </Link>

                        <Link
                            v-else-if="$page.props.auth.user.role === 'mitra'"
                            href="/dashboard/mitra"
                            class="rounded-xl bg-lokak-brand px-5 py-2.5 text-[10px] font-black text-white italic shadow-lg shadow-lokak-brand/20 transition-all hover:bg-lokak-brand-dark active:scale-95"
                        >
                            DASHBOARD MITRA
                        </Link>
                    </template>

                    <button
                        class="ml-2 rounded-full p-2 text-lg text-yellow-500 transition-colors hover:bg-slate-100"
                    >
                        🔔
                    </button>
                </div>
            </div>
        </div>

        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="transform -translate-y-4 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform -translate-y-4 opacity-0"
        >
            <div
                v-show="isOpen"
                class="absolute w-full border-t border-slate-100 bg-white shadow-xl lg:hidden"
            >
                <nav
                    class="flex flex-col gap-2 p-6 text-xs font-extrabold text-slate-500 uppercase"
                >
                    <Link
                        href="/"
                        @click="isOpen = false"
                        class="rounded-xl px-4 py-3 transition-colors"
                        :class="{
                            'bg-sky-50 text-lokak-brand': $page.url === '/',
                            'hover:bg-slate-50 hover:text-lokak-brand':
                                $page.url !== '/',
                        }"
                    >
                        Beranda
                    </Link>
                    <Link
                        href="/lowongan"
                        @click="isOpen = false"
                        class="rounded-xl px-4 py-3 transition-colors"
                        :class="{
                            'bg-sky-50 text-lokak-brand':
                                $page.url.startsWith('/lowongan'),
                            'hover:bg-slate-50 hover:text-lokak-brand':
                                !$page.url.startsWith('/lowongan'),
                        }"
                    >
                        Lowongan
                    </Link>
                    <Link
                        href="/mitra"
                        @click="isOpen = false"
                        class="rounded-xl px-4 py-3 transition-colors"
                        :class="{
                            'bg-sky-50 text-lokak-brand':
                                $page.url.startsWith('/mitra'),
                            'hover:bg-slate-50 hover:text-lokak-brand':
                                !$page.url.startsWith('/mitra'),
                        }"
                    >
                        Mitra
                    </Link>

                    <hr class="my-4 border-slate-100" />

                    <template v-if="!$page.props.auth.user">
                        <Link
                            href="/login"
                            @click="isOpen = false"
                            class="rounded-xl bg-lokak-brand py-3.5 text-center text-[11px] font-black text-white italic shadow-lg shadow-lokak-brand/20 transition-colors hover:bg-lokak-brand-dark"
                        >
                            LOGIN
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            v-if="$page.props.auth.user.role === 'admin'"
                            href="/dashboard/admin"
                            @click="isOpen = false"
                            class="rounded-xl bg-lokak-brand py-3.5 text-center text-[11px] font-black text-white italic shadow-lg shadow-lokak-brand/20 transition-colors hover:bg-lokak-brand-dark"
                        >
                            DASHBOARD ADMIN
                        </Link>

                        <Link
                            v-else-if="$page.props.auth.user.role === 'pelamar'"
                            href="/dashboard/pelamar"
                            @click="isOpen = false"
                            class="rounded-xl bg-lokak-brand py-3.5 text-center text-[11px] font-black text-white italic shadow-lg shadow-lokak-brand/20 transition-colors hover:bg-lokak-brand-dark"
                        >
                            DASHBOARD PELAMAR
                        </Link>

                        <Link
                            v-else-if="$page.props.auth.user.role === 'mitra'"
                            href="/dashboard/mitra"
                            @click="isOpen = false"
                            class="rounded-xl bg-lokak-brand py-3.5 text-center text-[11px] font-black text-white italic shadow-lg shadow-lokak-brand/20 transition-colors hover:bg-lokak-brand-dark"
                        >
                            DASHBOARD MITRA
                        </Link>
                    </template>
                </nav>
            </div>
        </transition>
    </header>
</template>
