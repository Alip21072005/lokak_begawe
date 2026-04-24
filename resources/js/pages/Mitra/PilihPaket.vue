<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { 
    CheckCircle2, 
    UploadCloud, 
    ArrowLeft, 
    Clock, 
    CreditCard, 
    Info,
    Check
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { ref } from 'vue';
import { route } from 'ziggy-js';

const props = defineProps<{ 
    lowongan: any 
}>();

// Konfigurasi Paket Sederhana (Hanya fokus pada durasi)
const packages = [
    { 
        name: 'Hemat', 
        price: 50000, 
        duration: '7 Hari',
        desc: 'Cocok untuk kebutuhan mendesak.' 
    },
    { 
        name: 'Pro', 
        price: 150000, 
        duration: '30 Hari',
        desc: 'Pilihan terbaik untuk perusahaan.' 
    },
    { 
        name: 'Gold', 
        price: 300000, 
        duration: '60 Hari',
        desc: 'Durasi maksimal untuk pencarian talenta.' 
    },
];

const selectedPackage = ref(packages[1]); // Default ke Pro

const form = useForm({
    nama_paket: selectedPackage.value.name,
    harga: selectedPackage.value.price,
    bukti_transfer: null as any,
});

const handleFileChange = (e: any) => {
    form.bukti_transfer = e.target.files[0];
};

const submit = () => {
    const routeFunc = (window as any).route || (window as any).Ziggy?.route;
    
    form.post(routeFunc('mitra.pembayaran.bayar', props.lowongan.id), {
        onBefore: () => {
            Swal.fire({
                title: 'Sedang Mengirim...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                didOpen: () => {
 Swal.showLoading(); 
}
            });
        },
        onSuccess: () => {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Bukti transfer telah dikirim. Admin akan segera memverifikasi lowongan Anda.',
                icon: 'success',
                confirmButtonColor: '#0369a1',
                customClass: { popup: 'rounded-[2.5rem]' }
            });
        },
        onError: () => {
            Swal.fire({
                title: 'Gagal!',
                text: 'Pastikan file bukti transfer sudah dipilih dan ukuran tidak terlalu besar.',
                icon: 'error',
                confirmButtonColor: '#e11d48',
                customClass: { popup: 'rounded-[2.5rem]' }
            });
        }
    });
};
</script>

<template>
    <Head title="Pilih Paket Penayangan - Lokak Begawe" />

    <div class="min-h-screen bg-[#F8FAFC] font-sans text-slate-900 selection:bg-sky-100">
        <nav class="flex items-center justify-between px-6 py-6 md:px-16 lg:px-24">
            <Link :href="route('mitra.pasanglowongan')" class="group flex items-center gap-2 text-xs font-black uppercase italic text-slate-400 transition-colors hover:text-sky-700">
                <ArrowLeft class="h-4 w-4 transition-transform group-hover:-translate-x-1" />
                Kembali
            </Link>
            <div class="text-[10px] font-black uppercase tracking-widest text-slate-300 italic">Langkah 2 dari 2: Pembayaran</div>
        </nav>

        <main class="mx-auto max-w-5xl px-6 pb-24 md:px-16">
            <header class="mb-16 text-center">
                <h1 class="text-4xl font-black italic uppercase tracking-tighter md:text-5xl">
                    PILIH <span class="text-sky-700">PAKET</span> PENAYANGAN
                </h1>
                <p class="mt-4 text-xs font-bold uppercase italic text-slate-400">
                    Aktifkan lowongan profesional untuk posisi: 
                    <span class="text-slate-900 underline underline-offset-4 decoration-sky-500">"{{ lowongan.judul_lowongan }}"</span>
                </p>
            </header>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div 
                    v-for="pkg in packages" 
                    :key="pkg.name" 
                    @click="selectedPackage = pkg; form.nama_paket = pkg.name; form.harga = pkg.price"
                    :class="[
                        'relative cursor-pointer rounded-[2.5rem] border-4 p-8 transition-all duration-500',
                        selectedPackage.name === pkg.name 
                        ? 'border-sky-600 bg-white shadow-2xl shadow-sky-900/10 scale-105 z-10' 
                        : 'border-transparent bg-white/50 opacity-60 grayscale-[0.5] hover:opacity-100 hover:grayscale-0'
                    ]"
                >
                    <div v-if="selectedPackage.name === pkg.name" class="absolute -top-4 -right-4 flex h-10 w-10 items-center justify-center rounded-full bg-sky-600 text-white shadow-lg">
                        <Check class="h-6 w-6" />
                    </div>

                    <h3 class="text-lg font-black italic uppercase tracking-tight">{{ pkg.name }}</h3>
                    <div class="my-6 flex flex-col">
                        <span class="text-[10px] font-bold uppercase text-slate-400">Harga</span>
                        <span class="text-3xl font-black italic text-sky-700">Rp {{ pkg.price.toLocaleString() }}</span>
                    </div>

                    <div class="space-y-3 border-t border-slate-100 pt-6">
                        <div class="flex items-center gap-2 text-[11px] font-black uppercase italic text-slate-600">
                            <Clock class="h-4 w-4 text-sky-600" />
                            Tayang {{ pkg.duration }}
                        </div>
                        <p class="text-[10px] font-medium leading-relaxed text-slate-400 italic">
                            {{ pkg.desc }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-12 overflow-hidden rounded-[3rem] border border-slate-200 bg-white shadow-2xl shadow-slate-200/60">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    
                    <div class="bg-slate-900 p-10 text-white lg:p-16">
                        <div class="mb-8 flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-600 shadow-lg">
                            <CreditCard class="h-6 w-6" />
                        </div>
                        <h2 class="text-2xl font-black italic uppercase tracking-tighter">Instruksi Transfer</h2>
                        <p class="mt-4 text-[11px] font-medium leading-relaxed text-slate-400 italic">
                            Silakan lakukan transfer sesuai harga paket yang dipilih ke rekening resmi Lokak Begawe di bawah ini:
                        </p>

                        <div class="mt-10 space-y-6">
                            <div class="rounded-3xl bg-white/5 p-6 backdrop-blur-md">
                                <span class="text-[9px] font-black uppercase tracking-widest text-sky-400 italic">Bank Bengkulu (ATM)</span>
                                <div class="mt-2 text-xl font-black italic tracking-wider">123 - 456 - 7890</div>
                                <div class="mt-1 text-[10px] font-bold uppercase text-slate-300">A/N Lokak Begawe (Alip Maulana)</div>
                            </div>

                            <div class="flex items-start gap-3 rounded-2xl bg-amber-500/10 p-4 text-amber-500">
                                <Info class="mt-0.5 h-4 w-4 shrink-0" />
                                <p class="text-[10px] font-bold italic leading-tight uppercase">
                                    Simpan screenshot atau foto struk bukti transfer untuk diunggah di kolom sebelah kanan.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-10 lg:p-16">
                        <form @submit.prevent="submit" class="space-y-8">
                            <div class="space-y-2 text-center">
                                <h3 class="text-lg font-black italic uppercase">Upload Bukti</h3>
                                <p class="text-[10px] font-bold text-slate-400 italic uppercase">Format Gambar: JPG, PNG, atau JPEG (Max 2MB)</p>
                            </div>

                            <div class="relative group">
                                <input 
                                    type="file" 
                                    @change="handleFileChange" 
                                    accept="image/*" 
                                    class="absolute inset-0 z-10 cursor-pointer opacity-0"
                                />
                                <div class="flex flex-col items-center justify-center rounded-[2.5rem] border-4 border-dashed border-slate-100 bg-slate-50 py-16 transition-all group-hover:border-sky-600 group-hover:bg-white">
                                    <div v-if="!form.bukti_transfer" class="text-center">
                                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-white shadow-sm transition-transform group-hover:scale-110">
                                            <UploadCloud class="h-8 w-8 text-sky-600" />
                                        </div>
                                        <span class="text-[10px] font-black uppercase italic tracking-widest text-slate-400">Pilih File Bukti</span>
                                    </div>
                                    <div v-else class="flex flex-col items-center">
                                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500 text-white shadow-lg">
                                            <CheckCircle2 class="h-8 w-8" />
                                        </div>
                                        <span class="px-6 text-center text-xs font-black uppercase italic text-emerald-600 line-clamp-1">
                                            {{ form.bukti_transfer.name }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <button 
                                type="submit" 
                                :disabled="form.processing || !form.bukti_transfer"
                                class="flex h-16 w-full items-center justify-center gap-3 rounded-2xl bg-sky-700 text-xs font-black italic text-white uppercase shadow-xl shadow-sky-900/20 transition-all hover:bg-sky-800 disabled:opacity-50 active:scale-95"
                            >
                                Selesaikan & Pasang Lowongan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
.transition-all {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>