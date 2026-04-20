<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, onUnmounted } from 'vue';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

// Definisi props dari Controller
const props = defineProps<{
    lowonganTerbaru: Array<any>;
    mitraTeratas: Array<any>;
}>();

const isModalOpen = ref(false);
const selectedJob = ref<any>(null);

const openJobDetail = (job: any) => {
    selectedJob.value = job;
    isModalOpen.value = true;
    document.body.style.overflow = 'hidden';
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedJob.value = null;
    document.body.style.overflow = 'auto';
};

onUnmounted(() => {
    document.body.style.overflow = 'auto';
});

// Helper untuk format rupiah
const formatRupiah = (value: any) => {
    if (!value) return 'Bersaing';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
};
</script>

<template>
    <Head title="Lokak Begawe - Portal Lowongan Kerja Bengkulu" />

    <div class="mt-12 min-h-screen overflow-x-hidden bg-lokak-bg font-sans text-lokak-text">
        <Navbar />

        <main class="w-full px-6 py-12 md:px-16 lg:px-24 xl:px-32">
              <section
                class="relative mb-28 flex min-h-[500px] flex-col items-stretch overflow-hidden rounded-[3.5rem] bg-lokak-brand text-white shadow-2xl shadow-sky-900/20 lg:flex-row"
            >
                <div
                    class="relative z-20 flex w-full flex-col justify-center p-12 md:p-20 lg:w-3/5"
                >
                    <h1
                        class="mb-10 text-6xl leading-[0.8] font-black tracking-tighter uppercase italic md:text-8xl xl:text-7xl"
                    >
                        CARI GAWE<br />
                        DAK BETELE
                    </h1>
                    <p
                        class="mb-14 max-w-2xl text-base leading-relaxed font-medium text-sky-50 md:text-xl"
                    >
                        Ribuan lowongan dari perusahaan terverifikasi di
                        Bengkulu menunggumu. Ado loker, pela begawe sekarang!
                    </p>
                    <div class="flex flex-wrap gap-6">
                        <button
                            class="rounded-3xl bg-white px-12 py-6 text-sm font-black text-lokak-brand uppercase italic shadow-2xl transition-all hover:-translate-y-1 hover:bg-sky-50 active:scale-95"
                        >
                            Daftar Sekarang
                        </button>
                        <button
                            class="rounded-3xl border-2 border-white/30 px-12 py-6 text-sm font-black uppercase italic backdrop-blur-md transition-all hover:bg-white/10 active:scale-95"
                        >
                            Pelajari Lebih Lanjut
                        </button>
                    </div>
                </div>

                <div
                    class="relative min-h-[300px] w-full lg:min-h-full lg:w-2/5"
                >
                    <div
                        class="absolute inset-0 z-10 hidden bg-gradient-to-r from-lokak-brand via-transparent to-transparent lg:block"
                    ></div>
                    <div
                        class="absolute inset-0 z-10 bg-gradient-to-t from-lokak-brand via-transparent to-transparent lg:hidden"
                    ></div>

                    <img
                        src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&q=80"
                        alt="Loker Bengkulu"
                        class="absolute inset-0 h-full w-full object-cover brightness-75 grayscale-[20%] lg:brightness-100"
                    />
                </div>

                <div
                    class="absolute -top-20 -right-20 z-0 h-96 w-96 rounded-full bg-white/10 blur-[120px]"
                ></div>
                <div
                    class="absolute -bottom-20 left-20 z-0 h-96 w-96 rounded-full bg-sky-900/40 blur-[120px]"
                ></div>
            </section>

            <section class="mb-32">
                <div class="mb-14 flex items-end justify-between">
                    <div>
                        <h2 class="text-3xl font-black text-lokak-text uppercase italic">
                            LOWONGAN <span class="text-lokak-brand">TERBARU</span>
                        </h2>
                        <div class="mt-4 h-2 w-32 rounded-full bg-lokak-brand"></div>
                    </div>
                    <Link href="/lowongan" class="hidden font-black text-slate-400 uppercase italic hover:text-lokak-brand md:block">
                        Lihat Semua →
                    </Link>
                </div>

                <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4">
                    <div 
                        v-for="job in props.lowonganTerbaru" 
                        :key="job.id" 
                        @click="openJobDetail(job)"
                        class="group relative cursor-pointer rounded-[3rem] border border-slate-100 bg-white p-10 shadow-sm transition-all hover:-translate-y-3 hover:shadow-2xl"
                    >
                        <div class="mb-10 flex h-20 w-20 items-center justify-center rounded-3xl bg-slate-50 text-4xl">
                            💼
                        </div>
                        <h3 class="mb-3 text-lg font-black text-lokak-text uppercase italic group-hover:text-lokak-brand">
                            {{ job.judul_lowongan }}
                        </h3>
                        <p class="mb-8 text-xs font-bold text-slate-400 uppercase italic">
                            {{ job.mitra?.nama_mitra }}
                        </p>
                        <div class="flex items-center justify-between border-t border-slate-50 pt-8">
                            <span class="text-sm font-black text-lokak-brand italic">
                                {{ formatRupiah(job.gaji_min) }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase italic">
                                📍 {{ job.lokasi?.nama_lokasi || 'Bengkulu' }}
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-40">
                <div class="mb-14">
                    <h2 class="text-3xl font-black text-lokak-text uppercase italic">
                        MITRA <span class="text-lokak-brand">TERATAS</span>
                    </h2>
                    <div class="mt-4 h-2 w-32 rounded-full bg-lokak-brand"></div>
                </div>

                <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="mitra in props.mitraTeratas" :key="mitra.id" class="group rounded-[3rem] border border-slate-100 bg-white p-12 text-center transition-all hover:-translate-y-3 shadow-sm hover:shadow-2xl">
                        <div class="mx-auto mb-10 flex h-28 w-28 items-center justify-center rounded-full bg-slate-50 text-5xl">
                            🏢
                        </div>
                        <h3 class="mb-2 text-lg font-black text-lokak-text uppercase italic group-hover:text-lokak-brand">
                            {{ mitra.nama_mitra }}
                        </h3>
                        <div class="mb-10 flex items-center justify-center gap-4">
                            <span class="text-xs font-black text-amber-500 italic">⭐ 4.8</span>
                            <span class="rounded-2xl bg-lokak-brand px-4 py-2 text-[10px] font-black text-white uppercase italic">
                                {{ mitra.lowongan_count }} LOKER
                            </span>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <div v-if="isModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-6">
            <div class="absolute inset-0 bg-lokak-text/60 backdrop-blur-md" @click="closeModal"></div>
            <div class="relative z-10 w-full max-w-2xl rounded-[4rem] bg-white p-12 shadow-2xl animate-in">
                <div class="mb-12 flex justify-between">
                    <div class="text-5xl">💼</div>
                    <button @click="closeModal" class="h-12 w-12 rounded-full bg-slate-100 font-bold hover:bg-rose-500 hover:text-white">✕</button>
                </div>
                <h2 class="mb-4 text-4xl font-black text-lokak-text uppercase italic">{{ selectedJob?.judul_lowongan }}</h2>
                <p class="mb-12 text-xl font-black text-lokak-brand uppercase italic">{{ selectedJob?.mitra?.nama_mitra }}</p>
                
                <div class="mb-14 space-y-4 rounded-3xl bg-slate-50 p-8">
                    <div class="flex justify-between font-black italic">
                        <span class="text-slate-400">GAJI MINIMAL</span>
                        <span>{{ formatRupiah(selectedJob?.gaji_min) }}</span>
                    </div>
                    <div class="flex justify-between font-black italic">
                        <span class="text-slate-400">LOKASI</span>
                        <span>📍 {{ selectedJob?.lokasi?.nama_lokasi }}</span>
                    </div>
                </div>
                
                <button class="w-full rounded-full bg-lokak-brand py-6 text-xl font-black text-white uppercase italic shadow-lg hover:brightness-110">
                    Lamar Sekarang
                </button>
            </div>
        </div>

        <Footer />
    </div>
</template>

<style scoped>
.animate-in { animation: zoomIn 0.3s ease-out; }
@keyframes zoomIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
</style>