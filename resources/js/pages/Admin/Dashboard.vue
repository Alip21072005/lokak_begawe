<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import {
    Users,
    Briefcase,
    ShieldCheck,
    AlertCircle,
    Check,
    X,
    Eye,
    Clock,
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { computed, ref, onMounted, onUnmounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    auth: any;
    counts: {
        pelamar: number;
        mitra: number;
        lowongan: number;
    };
    mitraPending: any[];
    mitraVerified: any[];
}>();
defineOptions({
    layout: AppLayout,
});

// --- STATE MANAGEMENT ---
const selectedMitra = ref<any>(null);
const isModalOpen = ref(false);
const currentTime = ref(new Date().toLocaleTimeString('id-ID'));
let timer: any;

onMounted(() => {
    timer = setInterval(() => {
        currentTime.value = new Date().toLocaleTimeString('id-ID');
    }, 1000);
});
onUnmounted(() => clearInterval(timer));

// --- FUNCTIONS ---
const openDetail = (mitra: any) => {
    selectedMitra.value = mitra;
    isModalOpen.value = true;
};

const updateStatus = (id: string, status: string, name: string) => {
    const isVerify = status === 'verified';

    Swal.fire({
        title: isVerify ? 'Setujui Mitra?' : 'Tolak Mitra?',
        text: `Apakah kamu yakin ingin memproses ${name}?`,
        icon: isVerify ? 'question' : 'warning',
        showCancelButton: true,
        confirmButtonColor: isVerify ? '#0369a1' : '#e11d48',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: isVerify ? 'Ya, Setujui!' : 'Ya, Tolak',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        background: '#ffffff',
        customClass: {
            popup: 'rounded-[2rem]',
            confirmButton:
                'rounded-xl font-bold uppercase tracking-widest text-xs px-6 py-3',
            cancelButton:
                'rounded-xl font-bold uppercase tracking-widest text-xs px-6 py-3',
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // URL langsung diarahkan tanpa Ziggy route()
            router.patch(
                `/dashboard/admin/mitra/${id}/status`,
                {
                    status: status,
                },
                {
                    preserveScroll: true,
                    onSuccess: () => {
                        isModalOpen.value = false;
                        Swal.fire({
                            title: 'Berhasil!',
                            text: `Status ${name} telah diperbarui.`,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                            background: '#ffffff',
                            customClass: {
                                popup: 'rounded-[2rem]',
                            },
                        });
                    },
                },
            );
        }
    });
};

const stats = computed(() => [
    {
        name: 'Total Pelamar',
        value: props.counts?.pelamar ?? 0,
        icon: Users,
        color: 'text-lokak-brand',
    },
    {
        name: 'Mitra Aktif',
        value: props.counts?.mitra ?? 0,
        icon: ShieldCheck,
        color: 'text-emerald-500',
    },
    {
        name: 'Total Lowongan',
        value: props.counts?.lowongan ?? 0,
        icon: Briefcase,
        color: 'text-indigo-500',
    },
    {
        name: 'Antrean Verifikasi',
        value: props.mitraPending?.length ?? 0,
        icon: AlertCircle,
        color: 'text-amber-500',
    },
]);
</script>

<template>
    <Head title="Dashboard Admin" />

    <div class="space-y-8 p-6 lg:p-10">
        <div
            class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <h1
                    class="text-3xl font-black tracking-tighter text-lokak-text uppercase italic md:text-4xl"
                >
                    ADMIN <span class="text-lokak-brand">CONTROL</span>
                </h1>
                <div
                    class="mt-2 flex items-center gap-3 text-[10px] font-bold text-slate-400 uppercase italic"
                >
                    <div class="h-1.5 w-24 rounded-full bg-slate-200"></div>
                    <Clock class="h-3.5 w-3.5" /> {{ currentTime }} WIB
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div
                v-for="stat in stats"
                :key="stat.name"
                class="group rounded-4xl border border-slate-200 bg-white p-7 shadow-sm transition-all hover:border-sky-200 hover:shadow-xl"
            >
                <p
                    class="text-[9px] font-black tracking-widest text-slate-400 uppercase italic"
                >
                    {{ stat.name }}
                </p>
                <p class="mt-1 text-3xl font-black text-lokak-text">
                    {{ stat.value }}
                </p>
                <div class="mt-4 flex items-center gap-2">
                    <div
                        :class="[
                            stat.color,
                            'flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 transition-colors group-hover:bg-lokak-brand group-hover:text-white',
                        ]"
                    >
                        <component :is="stat.icon" class="h-4 w-4" />
                    </div>
                    <span
                        class="text-[9px] font-bold text-slate-300 uppercase italic"
                        >Update Real-time</span
                    >
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="lg:col-span-8">
                <div
                    class="min-h-112.5 rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm"
                >
                    <div class="mb-8 flex items-center justify-between">
                        <h2
                            class="text-lg font-black tracking-tight text-lokak-text uppercase italic"
                        >
                            Daftar <span class="text-lokak-brand">Antrean</span>
                        </h2>
                    </div>

                    <div class="space-y-4">
                        <div
                            v-if="mitraPending.length === 0"
                            class="flex flex-col items-center justify-center py-20"
                        >
                            <ShieldCheck
                                class="mb-4 h-16 w-16 text-slate-100"
                            />
                            <p
                                class="text-xs font-black tracking-widest text-slate-300 uppercase italic"
                            >
                                Belum ada mitra baru
                            </p>
                        </div>

                        <div
                            v-for="mitra in mitraPending"
                            :key="mitra.id"
                            class="flex items-center justify-between rounded-3xl border border-slate-50 bg-slate-50 p-5 transition-all hover:border-slate-200 hover:bg-white hover:shadow-md"
                        >
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white font-black text-lokak-brand uppercase italic shadow-sm"
                                >
                                    {{ mitra.nama_mitra?.charAt(0) }}
                                </div>
                                <div>
                                    <h3
                                        class="text-xs font-black text-lokak-text uppercase"
                                    >
                                        {{ mitra.nama_mitra }}
                                    </h3>
                                    <p
                                        class="text-[9px] font-bold text-slate-400 uppercase italic"
                                    >
                                        {{ mitra.kategori?.nama_kategori }} •
                                        {{ mitra.lokasi?.nama_lokasi }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button
                                    @click="openDetail(mitra)"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-400 transition-colors hover:text-lokak-brand"
                                >
                                    <Eye class="h-4 w-4" />
                                </button>
                                <button
                                    @click="
                                        updateStatus(
                                            mitra.id,
                                            'verified',
                                            mitra.nama_mitra,
                                        )
                                    "
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/20 transition-all hover:bg-emerald-600"
                                >
                                    <Check class="h-4 w-4" />
                                </button>
                                <button
                                    @click="
                                        updateStatus(
                                            mitra.id,
                                            'rejected',
                                            mitra.nama_mitra,
                                        )
                                    "
                                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-rose-100 bg-white text-rose-500 transition-all hover:bg-rose-50"
                                >
                                    <X class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6 lg:col-span-4">
                <div
                    class="rounded-[2.5rem] bg-slate-900 p-8 text-white shadow-xl"
                >
                    <h4
                        class="mb-6 text-[10px] font-black uppercase italic opacity-50"
                    >
                        Akses Cepat
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div
        v-if="isModalOpen && selectedMitra"
        class="fixed inset-0 z-100 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
    >
        <div
            class="w-full max-w-lg animate-in overflow-hidden rounded-[3rem] bg-white shadow-2xl duration-300 zoom-in"
        >
            <div class="flex h-32 justify-end bg-lokak-brand p-6">
                <button
                    @click="isModalOpen = false"
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/40"
                >
                    <X class="h-5 w-5" />
                </button>
            </div>
            <div class="px-10 pb-10">
                <div
                    class="-mt-12 mb-6 flex h-24 w-24 items-center justify-center rounded-3xl border-4 border-white bg-white text-3xl font-black text-lokak-brand uppercase italic shadow-xl"
                >
                    {{ selectedMitra.nama_mitra?.charAt(0) }}
                </div>
                <h2
                    class="text-2xl font-black text-lokak-text uppercase italic"
                >
                    {{ selectedMitra.nama_mitra }}
                </h2>
                <div
                    class="mt-6 space-y-4 rounded-3xl border border-slate-100 bg-slate-50 p-6"
                >
                    <div
                        class="flex justify-between text-[10px] font-black uppercase italic"
                    >
                        <span class="text-slate-400">Email Mitra</span>
                        <span class="text-lokak-text">{{
                            selectedMitra.email_mitra
                        }}</span>
                    </div>
                    <div
                        class="flex justify-between text-[10px] font-black uppercase italic"
                    >
                        <span class="text-slate-400">WhatsApp</span>
                        <span class="text-lokak-text">{{
                            selectedMitra.nohp_mitra
                        }}</span>
                    </div>
                    <p
                        class="border-t border-slate-200 pt-4 text-[10px] leading-relaxed font-bold text-slate-500 italic"
                    >
                        {{
                            selectedMitra.deksipsi_mitra ??
                            'Tidak ada deskripsi.'
                        }}
                    </p>
                </div>
                <div class="mt-8 flex gap-4">
                    <button
                        @click="
                            updateStatus(
                                selectedMitra.id,
                                'verified',
                                selectedMitra.nama_mitra,
                            )
                        "
                        class="flex-1 rounded-2xl bg-emerald-500 py-4 text-[10px] font-black tracking-widest text-white uppercase shadow-lg shadow-emerald-500/20 transition-all hover:bg-emerald-600"
                    >
                        Setujui
                    </button>
                    <button
                        @click="
                            updateStatus(
                                selectedMitra.id,
                                'rejected',
                                selectedMitra.nama_mitra,
                            )
                        "
                        class="flex-1 rounded-2xl bg-rose-500 py-4 text-[10px] font-black tracking-widest text-white uppercase shadow-lg shadow-rose-500/20 transition-all hover:bg-rose-600"
                    >
                        Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
