<!-- eslint-disable @typescript-eslint/no-unused-vars -->
<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3'; // Tambahkan usePage
import {
    Briefcase,
    Users,
    UserCheck,
    ChevronRight,
    ArrowUpRight,
    AlertTriangle,
    ShieldCheck,
    XCircle,
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { onMounted, watch } from 'vue'; // Tambahkan onMounted & watch
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    auth: any;
    stats: any;
    recentApplicants: any[];
}>();

defineOptions({ layout: AppLayout });

// Ambil flash message dari shared data Inertia
const page = usePage();

// Fungsi untuk menampilkan pop-up jika ada error dari middleware
const checkFlashMessage = () => {
    const errorMsg = (page.props.flash as any)?.error;

    if (errorMsg) {
        Swal.fire({
            title: 'Akses Terbatas',
            text: errorMsg,
            icon: 'warning',
            confirmButtonColor: '#0369a1',
            customClass: { popup: 'rounded-[2.5rem]' },
        });
    }
};

onMounted(() => checkFlashMessage());
// Pantau jika ada perubahan flash message (saat redirect ulang)
watch(
    () => (page.props.flash as any)?.error,
    () => checkFlashMessage(),
);
</script>

<template>
    <Head title="Mitra Dashboard" />

    <div class="space-y-8 p-6 lg:p-10">
        <div
            v-if="auth.mitra.status_mitra !== 'verified'"
            class="flex items-center gap-4 rounded-4xl border p-6 shadow-sm transition-all"
            :class="
                auth.mitra.status_mitra === 'pending'
                    ? 'border-amber-100 bg-amber-50/50'
                    : 'border-rose-100 bg-rose-50/50'
            "
        >
            <div
                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl shadow-lg"
                :class="
                    auth.mitra.status_mitra === 'pending'
                        ? 'bg-amber-500 text-white'
                        : 'bg-rose-500 text-white'
                "
            >
                <AlertTriangle
                    v-if="auth.mitra.status_mitra === 'pending'"
                    class="h-7 w-7"
                />
                <XCircle v-else class="h-7 w-7" />
            </div>

            <div>
                <h4
                    class="text-xs font-black tracking-widest uppercase italic"
                    :class="
                        auth.mitra.status_mitra === 'pending'
                            ? 'text-amber-800'
                            : 'text-rose-800'
                    "
                >
                    STATUS AKUN:
                    <span class="underline">{{
                        auth.mitra.status_mitra === 'pending'
                            ? 'MENUNGGU VERIFIKASI'
                            : 'PENDAFTARAN DITOLAK'
                    }}</span>
                </h4>
                <p
                    class="mt-1 text-[10px] leading-tight font-bold uppercase italic opacity-70"
                    :class="
                        auth.mitra.status_mitra === 'pending'
                            ? 'text-amber-700'
                            : 'text-rose-700'
                    "
                >
                    {{
                        auth.mitra.status_mitra === 'pending'
                            ? 'Tim kami sedang meninjau profil perusahaan Anda. Menu Pasang Lowongan & Kelola Pelamar akan terbuka otomatis setelah disetujui.'
                            : 'Mohon maaf, profil perusahaan Anda tidak memenuhi kriteria kami. Silakan perbarui dokumen Anda atau hubungi dukungan.'
                    }}
                </p>
            </div>
        </div>

        <div
            v-if="auth.mitra.status_mitra === 'verified'"
            class="flex items-center gap-4 rounded-4xl border border-emerald-100 bg-emerald-50/50 p-6"
        >
            <div
                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-900/20"
            >
                <ShieldCheck class="h-6 w-6" />
            </div>
            <div>
                <h4
                    class="text-xs font-black tracking-tight text-emerald-800 uppercase italic"
                >
                    Akun Terverifikasi
                </h4>
                <p
                    class="mt-1 text-[10px] leading-tight font-bold text-emerald-700/80 uppercase italic"
                >
                    Selamat! Perusahaan Anda telah diverifikasi. Anda sekarang
                    dapat memasang lowongan kerja.
                </p>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic"
                >
                    HELLO,
                    <span class="text-sky-700">{{
                        auth.mitra.nama_mitra
                    }}</span>
                </h1>
                <p
                    class="mt-2 text-[11px] font-bold tracking-widest text-slate-400 uppercase italic"
                >
                    Dashboard Manajemen Rekrutmen Lokak Begawe.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
            <div
                v-for="s in [
                    {
                        n: 'Total Lowongan',
                        v: stats.total_lowongan,
                        i: Briefcase,
                        c: 'text-sky-600',
                        b: 'bg-sky-50',
                    },
                    {
                        n: 'Total Pelamar',
                        v: stats.total_pelamar,
                        i: Users,
                        c: 'text-indigo-600',
                        b: 'bg-indigo-50',
                    },
                    {
                        n: 'Perlu Review',
                        v: stats.perlu_review,
                        i: UserCheck,
                        c: 'text-emerald-600',
                        b: 'bg-emerald-50',
                    },
                ]"
                :key="s.n"
                class="rounded-4xl border border-slate-200 bg-white p-6 shadow-sm"
            >
                <div :class="[s.b, 'mb-4 w-fit rounded-2xl p-3']">
                    <component :is="s.i" :class="[s.c, 'h-5 w-5']" />
                </div>
                <p
                    class="text-[9px] font-black tracking-widest text-slate-400 uppercase"
                >
                    {{ s.n }}
                </p>
                <h2 class="mt-1 text-3xl font-black text-slate-900">
                    {{ s.v }}
                </h2>
            </div>
        </div>

        <div
            class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm"
        >
            <h2 class="mb-8 text-sm font-black text-slate-900 uppercase italic">
                Pelamar Terbaru
            </h2>
            <div class="space-y-4">
                <div
                    v-if="recentApplicants.length === 0"
                    class="py-10 text-center text-[10px] font-black text-slate-300 uppercase italic"
                >
                    Belum ada pelamar masuk
                </div>
                <div
                    v-for="app in recentApplicants"
                    :key="app.id"
                    class="group flex items-center justify-between rounded-3xl border border-transparent bg-slate-50 p-5 transition-all hover:border-slate-200"
                >
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-white font-black text-sky-700 uppercase shadow-sm"
                        >
                            {{ app.avatar }}
                        </div>
                        <div>
                            <h4
                                class="text-xs font-black text-slate-900 uppercase"
                            >
                                {{ app.nama_pelamar }}
                            </h4>
                            <p
                                class="text-[9px] font-bold text-slate-400 italic"
                            >
                                {{ app.posisi_dilamar }} • {{ app.tanggal }}
                            </p>
                        </div>
                    </div>
                    <span
                        class="rounded-lg border border-slate-100 bg-white px-3 py-1 text-[9px] font-black text-slate-400 uppercase italic transition-all group-hover:bg-sky-700 group-hover:text-white"
                        >{{ app.status }}</span
                    >
                </div>
            </div>
        </div>
    </div>
</template>
