<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const isMobileMenuOpen = ref(false);
const isScrolled = ref(false);

// Deteksi scroll untuk efek transparan di desktop
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
                ? 'border-b border-white/20 bg-white/40 py-3 shadow-sm backdrop-blur-md'
                : 'border-b border-slate-100 bg-white py-5',
        ]"
    >
        <div
            class="flex w-full items-center justify-between px-6 md:px-16 lg:px-24 xl:px-32"
        >
            <Link href="/" class="group flex shrink-0 items-center gap-4">
                <div
                    class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-2xl bg-lokak-brand shadow-lg transition-transform group-hover:rotate-6"
                >
                    <img
                        src="/Logo.png"
                        alt="Logo"
                        class="h-full w-full object-cover"
                    />
                </div>
                <div class="flex flex-col leading-none">
                    <span
                        class="text-lg font-black tracking-tighter text-lokak-text uppercase italic"
                    >
                        LOKAK<span class="text-lokak-brand">BEGAWE</span>
                    </span>
                    <span
                        class="mt-1 text-[11px] font-bold tracking-widest text-slate-400 uppercase italic"
                    >
                        Bengkulu Job Portal
                    </span>
                </div>
            </Link>

            <nav
                class="hidden items-center gap-10 text-sm font-black tracking-widest uppercase italic lg:flex"
            >
                <Link
                    href="/"
                    class="link-desktop"
                    :class="{ active: $page.url === '/' }"
                    >Beranda</Link
                >
                <Link
                    href="/lowongan"
                    class="link-desktop"
                    :class="{ active: $page.url.startsWith('/lowongan') }"
                    >Lowongan</Link
                >
                <Link
                    href="/mitra"
                    class="link-desktop"
                    :class="{ active: $page.url.startsWith('/mitra') }"
                    >Mitra</Link
                >

                <div
                    class="ml-6 flex items-center gap-4 border-l border-slate-200/50 pl-10"
                >
                    <template v-if="!$page.props.auth.user">
                        <Link href="/login" class="btn-primary-small text-xs"
                            >MASUK</Link
                        >
                    </template>
                    <template v-else>
                        <Link href="/dashboard" class="btn-primary-small text-xs"
                            >DASHBOARD</Link
                        >
                    </template>
                    <button class="icon-btn-custom">🔔</button>
                </div>
            </nav>

            <div class="flex items-center gap-3 lg:hidden">
                <button class="icon-btn-custom h-10 w-10 text-lg">🔔</button>
                <button
                    @click="isMobileMenuOpen = true"
                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-900 text-white shadow-md active:scale-95"
                >
                    ☰
                </button>
            </div>
        </div>
    </header>

    <Teleport to="body">
        <Transition name="fade">
            <div
                v-if="isMobileMenuOpen"
                @click="isMobileMenuOpen = false"
                class="fixed inset-0 z-100 bg-slate-900/40 backdrop-blur-sm lg:hidden"
            ></div>
        </Transition>

        <Transition name="slide">
            <div
                v-if="isMobileMenuOpen"
                class="fixed top-0 right-0 z-101 h-full w-72 bg-white shadow-2xl lg:hidden"
            >
                <div class="flex h-full flex-col">
                    <div
                        class="flex items-center justify-between border-b border-slate-100 p-6"
                    >
                        <span class="text-lg font-black text-lokak-text italic"
                            >MENU</span
                        >
                        <button
                            @click="isMobileMenuOpen = false"
                            class="text-2xl text-slate-400"
                        >
                            ✕
                        </button>
                    </div>

                    <div class="flex flex-col gap-2 p-6">
                        <Link
                            href="/"
                            @click="isMobileMenuOpen = false"
                            class="rounded-xl px-4 py-3 text-base font-bold text-slate-700 uppercase italic hover:bg-slate-50"
                            >Beranda</Link
                        >
                        <Link
                            href="/lowongan"
                            @click="isMobileMenuOpen = false"
                            class="rounded-xl px-4 py-3 text-base font-bold text-slate-700 uppercase italic hover:bg-slate-50"
                            >Lowongan</Link
                        >
                        <Link
                            href="/mitra"
                            @click="isMobileMenuOpen = false"
                            class="rounded-xl px-4 py-3 text-base font-bold text-slate-700 uppercase italic hover:bg-slate-50"
                            >Mitra</Link
                        >
                    </div>

                    <div class="mt-auto border-t border-slate-100 p-6">
                        <template v-if="!$page.props.auth.user">
                            <Link
                                href="/login"
                                class="flex w-full justify-center rounded-xl bg-lokak-brand py-4 text-sm font-bold text-white shadow-lg shadow-lokak-brand/20"
                                >MASUK</Link
                            >
                        </template>
                        <template v-else>
                            <Link
                                href="/dashboard"
                                class="flex w-full justify-center rounded-xl bg-lokak-brand py-4 text-sm font-bold text-white"
                                >DASHBOARD</Link
                            >
                        </template>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
/* Tambahkan transisi biar smooth */
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.slide-enter-active, .slide-leave-active { transition: transform 0.3s ease-out; }
.slide-enter-from, .slide-leave-to { transform: translateX(100%); }
</style>