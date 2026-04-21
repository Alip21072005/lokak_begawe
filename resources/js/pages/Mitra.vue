<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { computed, ref } from 'vue';
import {
    Search,
    MapPin,
    Star,
    ChevronRight,
    SlidersHorizontal,
} from 'lucide-vue-next';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

const props = defineProps<{
    mitras?: Array<{
        id: string;
        name: string;
        location: string;
        jobs_count: string;
        rating: string;
        logo: string | null;
    }>;
    filters?: any;
}>();

// 2. Gunakan Computed untuk safety
const listMitra = computed(() => props.mitras ?? []);

const search = ref(props.filters?.search || '');
const lokasi = ref(props.filters?.lokasi || '');

const handleSearch = () => {
    router.get(
        route('mitra.index'),
        { search: search.value, lokasi: lokasi.value },
        { preserveState: true, replace: true },
    );
};
</script>

<template>
    <Head title="Cari Mitra - Lokak Begawe" />

    <div
        class="min-h-screen overflow-x-hidden bg-lokak-bg font-sans text-lokak-text"
    >
        <Navbar />

        <main class="mt-12 w-full px-6 py-12 md:px-16 lg:px-24 xl:px-32">
            <section class="mb-20">
                <div class="mb-12 flex flex-col items-start space-y-4">
                    <h1
                        class="text-4xl font-black tracking-tighter text-lokak-text uppercase italic md:text-6xl"
                    >
                        CARI <span class="text-lokak-brand">MITRA</span>
                    </h1>
                    <div class="h-2 w-32 rounded-full bg-lokak-brand"></div>
                    <p
                        class="max-w-2xl text-xs font-bold tracking-widest text-slate-400 uppercase italic"
                    >
                        Temukan Perusahaan Penyedia Lowongan Kerja Terbaik di
                        Provinsi Bengkulu. Kolaborasi bersama mitra terpercaya
                        untuk karir masa depanmu.
                    </p>
                </div>

                <div
                    class="rounded-[2.5rem] border border-slate-100 bg-white p-6 shadow-2xl shadow-slate-200/50 md:p-10"
                >
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
                        <div class="relative">
                            <Search
                                class="absolute top-1/2 left-4 h-4 w-4 -translate-y-1/2 text-slate-400"
                            />
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Nama Mitra..."
                                class="h-14 w-full rounded-2xl border border-slate-100 bg-slate-50 pr-4 pl-11 text-xs font-bold transition-all outline-none focus:border-lokak-brand focus:bg-white focus:ring-4 focus:ring-lokak-brand/5"
                            />
                        </div>
                        <div class="relative">
                            <SlidersHorizontal
                                class="absolute top-1/2 left-4 h-4 w-4 -translate-y-1/2 text-slate-400"
                            />
                            <select
                                class="h-14 w-full appearance-none rounded-2xl border border-slate-100 bg-slate-50 pr-4 pl-11 text-xs font-bold text-slate-500 transition-all outline-none focus:border-lokak-brand focus:bg-white"
                            >
                                <option>Semua Industri</option>
                                <option>Teknologi</option>
                                <option>Pendidikan</option>
                            </select>
                        </div>
                        <div class="relative">
                            <MapPin
                                class="absolute top-1/2 left-4 h-4 w-4 -translate-y-1/2 text-slate-400"
                            />
                            <input
                                v-model="lokasi"
                                type="text"
                                placeholder="Lokasi (Contoh: Mukomuko)"
                                class="h-14 w-full rounded-2xl border border-slate-100 bg-slate-50 pr-4 pl-11 text-xs font-bold transition-all outline-none focus:border-lokak-brand focus:bg-white focus:ring-4 focus:ring-lokak-brand/5"
                            />
                        </div>
                        <button
                            @click="handleSearch"
                            class="flex h-14 items-center justify-center gap-3 rounded-2xl bg-lokak-brand text-xs font-black text-white uppercase italic shadow-xl shadow-sky-900/20 transition-all hover:-translate-y-1 hover:bg-lokak-brand-dark active:scale-95"
                        >
                            CARI SEKARANG
                        </button>
                    </div>
                </div>
            </section>

            <section class="mb-24">
                <div class="mb-12 flex items-center justify-between">
                    <div class="flex flex-col">
                        <h2
                            class="text-xl font-black tracking-tight text-lokak-text uppercase italic"
                        >
                            MITRA <span class="text-lokak-brand">TERATAS</span>
                        </h2>
                        <div
                            class="mt-2 h-1.5 w-16 rounded-full bg-slate-200"
                        ></div>
                    </div>
                    <span
                        class="text-[10px] font-black text-slate-400 uppercase italic"
                    >
                        Menampilkan {{ listMitra.length }} Mitra
                    </span>
                </div>

                <div
                    v-if="listMitra.length > 0"
                    class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5"
                >
                    <div
                        v-for="mitra in listMitra"
                        :key="mitra.id"
                        class="group relative flex flex-col items-center rounded-[2.5rem] border border-slate-100 bg-white p-8 text-center shadow-sm transition-all duration-500 hover:-translate-y-2 hover:border-sky-300 hover:shadow-2xl hover:shadow-sky-900/10"
                    >
                        <div
                            class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-lokak-brand px-4 py-1 text-[8px] font-black tracking-widest text-white uppercase italic opacity-0 transition-opacity group-hover:opacity-100"
                        >
                            Trusted Partner
                        </div>

                        <div
                            class="mb-6 flex h-24 w-24 items-center justify-center overflow-hidden rounded-full border border-slate-100 bg-slate-50 text-4xl shadow-inner transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6"
                        >
                            <img
                                v-if="mitra.logo && mitra.logo.includes('/')"
                                :src="mitra.logo"
                                class="h-full w-full object-cover"
                            />
                            <span v-else>{{ mitra.logo || '🏢' }}</span>
                        </div>

                        <h3
                            class="mb-2 text-sm font-black text-lokak-text uppercase italic transition-colors group-hover:text-lokak-brand"
                        >
                            {{ mitra.name }}
                        </h3>

                        <p
                            class="mb-6 flex items-center gap-1.5 text-[10px] font-bold text-slate-400 uppercase"
                        >
                            <MapPin class="h-3 w-3" /> {{ mitra.location }}
                        </p>

                        <div
                            class="mb-8 flex w-full items-center justify-between gap-2 border-t border-slate-50 pt-6"
                        >
                            <div
                                class="flex items-center gap-1 text-[10px] font-black text-amber-500"
                            >
                                <Star class="h-3 w-3 fill-amber-500" />
                                {{ mitra.rating }}
                            </div>
                            <span
                                class="rounded-xl bg-sky-50 px-3 py-1.5 text-[9px] font-black text-lokak-brand uppercase italic"
                            >
                                {{ mitra.jobs_count }}
                            </span>
                        </div>

                        <Link
                            :href="route('mitra.index')"
                            class="group/btn flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-900 py-4 text-[10px] font-black text-white uppercase italic transition-all hover:bg-lokak-brand active:scale-95"
                        >
                            LIHAT PROFIL
                            <ChevronRight
                                class="h-3 w-3 transition-transform group-hover/btn:translate-x-1"
                            />
                        </Link> 
                    </div>
                </div>

                <div v-else class="py-32 text-center">
                    <div class="mb-4 text-6xl">🔍</div>
                    <p class="font-black text-slate-400 uppercase italic">
                        Data mitra tidak ditemukan...
                    </p>
                </div>

                <div
                    v-if="listMitra.length > 0"
                    class="mt-16 flex items-center justify-center gap-3"
                >
                    <button
                        class="flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-100 bg-white shadow-sm transition-all hover:border-lokak-brand hover:text-lokak-brand"
                    >
                        ←
                    </button>
                    <div class="flex gap-2">
                        <button
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-lokak-brand text-xs font-black text-white shadow-lg shadow-sky-900/20"
                        >
                            1
                        </button>
                        <button
                            class="flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-100 bg-white text-xs font-black text-slate-400 hover:border-lokak-brand hover:text-lokak-brand"
                        >
                            2
                        </button>
                    </div>
                    <button
                        class="flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-100 bg-white shadow-sm transition-all hover:border-lokak-brand hover:text-lokak-brand"
                    >
                        →
                    </button>
                </div>
            </section>
        </main>

        <Footer />
    </div>
</template>

<style scoped>
*:focus {
    outline: none !important;
}
.transition-all {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
