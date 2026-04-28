<script setup lang="ts">
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import { 
    MapPin, Building2, ChevronLeft, Briefcase, 
    User, Phone, Send, X, ChevronRight, 
    GraduationCap, Clock, Banknote, ShieldCheck, 
    Share2, Check, Globe, Star, CheckCircle, Mail
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

const props = defineProps<{
    lowongan: any;
    authPelamar: any; 
}>();

const page = usePage();
const user = computed(() => page.props.auth.user);

// --- LOGIC AUTH CHECK ---
const isPelamar = computed(() => user.value?.role === 'pelamar');
const isOwner = computed(() => user.value?.role === 'mitra' && props.authPelamar?.id === props.lowongan.id);

// --- LOGIC MODAL & FORM ---
const isConfirmModalOpen = ref(false);
const form = useForm({
    catatan: '', 
});

const openApplyModal = () => {
    isConfirmModalOpen.value = true;
};

const closeApplyModal = () => {
    isConfirmModalOpen.value = false;
    form.reset();
};

const submitApplication = () => {
    const routeFunc = (window as any).route;
    form.post(routeFunc('pelamar.lamar.store', props.lowongan.id), {
        onSuccess: () => {
            closeApplyModal();
            // Notifikasi bisa diganti SweetAlert jika diperlukan
            alert('Lamaran berhasil dikirim!'); 
        },
        preserveScroll: true
    });
};

// --- LOGIC COPY LINK ---
const isCopied = ref(false);
const copyToClipboard = async () => {
    try {
        await navigator.clipboard.writeText(window.location.href);
        isCopied.value = true;
        setTimeout(() => { isCopied.value = false; }, 2000);
    } catch (err) {
        console.error('Failed to copy text: ', err);
    }
};

// --- FORMAT GAJI ---
const formatRupiah = (value: number) => {
    if (!value) return 'Gaji Dirahasiakan';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);
};
</script>

<template>
    <Head :title="`${lowongan.judul} - Detail Lowongan`" />
    
    <div class="min-h-screen overflow-x-hidden bg-lokak-bg font-sans text-lokak-text">
        <Navbar />
        
        <main class="w-full px-6 py-12 md:px-16 lg:px-24 xl:px-32 mt-12">
            
            <Link :href="route('lowongan.index')" class="group mb-8 inline-flex items-center gap-2 text-[10px] font-black uppercase italic tracking-widest text-slate-400 transition-colors hover:text-lokak-brand">
                <ChevronLeft class="h-4 w-4 transition-transform group-hover:-translate-x-1" /> 
                Kembali ke Daftar
            </Link>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:gap-12">
                
                <div class="space-y-8 lg:col-span-8">
                    
                    <div class="relative overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm md:p-10">
                        <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-lokak-brand/5 blur-3xl"></div>
                        
                        <div class="relative z-10 flex flex-col-reverse gap-6 md:flex-row md:items-start md:justify-between">
                            <div class="space-y-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-xl bg-sky-50 px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-lokak-brand">
                                        {{ lowongan.tipe }}
                                    </span>
                                    <span v-if="lowongan.status === 'pending'" class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-amber-600">
                                        Menunggu Verifikasi
                                    </span>
                                </div>
                                
                                <h1 class="text-3xl font-black uppercase italic leading-tight text-slate-900 md:text-4xl lg:text-5xl">
                                    {{ lowongan.judul }}
                                </h1>
                                
                                <div class="flex flex-wrap items-center gap-4 text-xs font-bold text-slate-500 uppercase tracking-wide">
                                    <span class="flex items-center gap-1.5 hover:text-lokak-brand transition-colors cursor-pointer">
                                        <Building2 class="h-4 w-4 shrink-0 text-slate-400" /> {{ lowongan.perusahaan.nama }}
                                    </span>
                                    <span class="hidden h-1.5 w-1.5 rounded-full bg-slate-300 md:block"></span>
                                    <span class="flex items-center gap-1.5">
                                        <MapPin class="h-4 w-4 shrink-0 text-slate-400" /> {{ lowongan.lokasi_nama }}
                                    </span>
                                    <span class="hidden h-1.5 w-1.5 rounded-full bg-slate-300 md:block"></span>
                                    <span class="flex items-center gap-1.5 text-rose-500">
                                        <Clock class="h-4 w-4 shrink-0" /> Batas: {{ lowongan.deadline }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-3xl border border-slate-100 bg-slate-50 p-3 shadow-inner md:h-28 md:w-28">
                                <img v-if="lowongan.perusahaan.logo" :src="lowongan.perusahaan.logo" :alt="lowongan.perusahaan.nama" class="h-full w-full object-contain" />
                                <Building2 v-else class="h-10 w-10 text-slate-300" />
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm md:p-10">
                        <div class="space-y-12">
                            
                            <section>
                                <h3 class="mb-5 flex items-center gap-3 text-lg font-black uppercase italic tracking-tight text-slate-900">
                                    <div class="h-6 w-2 rounded-full bg-lokak-brand"></div> 
                                    Deskripsi Pekerjaan
                                </h3>
                                <div class="prose prose-slate max-w-none text-sm font-medium leading-loose text-slate-600 whitespace-pre-line md:text-base">
                                    {{ lowongan.deskripsi }}
                                </div>
                            </section>

                            <section v-if="lowongan.skills?.length">
                                <h3 class="mb-5 flex items-center gap-3 text-lg font-black uppercase italic tracking-tight text-slate-900">
                                    <div class="h-6 w-2 rounded-full bg-lokak-brand"></div> 
                                    Keahlian Spesifik
                                </h3>
                                <div class="flex flex-wrap gap-2.5">
                                    <span v-for="skill in lowongan.skills" :key="skill.id" class="rounded-xl bg-slate-100 px-4 py-2 text-xs font-bold text-slate-600 transition-colors hover:bg-slate-200 hover:text-lokak-brand cursor-default">
                                        {{ skill.nama_skill }}
                                    </span>
                                </div>
                            </section>
                            
                        </div>
                    </div>
                </div>

                <div class="space-y-6 lg:col-span-4">
                    
                    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-6 md:p-8 shadow-sm">
                        <h4 class="mb-6 text-sm font-black uppercase italic tracking-tight text-slate-900">Ringkasan Posisi</h4>
                        
                        <div class="space-y-4">
                            <div class="flex items-start gap-4 rounded-2xl bg-slate-50 p-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-lokak-brand shadow-sm">
                                    <GraduationCap class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Minimal Pendidikan</p>
                                    <p class="mt-0.5 text-sm font-black text-slate-800">{{ lowongan.minimal_pendidikan || 'Semua Jenjang' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 rounded-2xl bg-slate-50 p-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-lokak-brand shadow-sm">
                                    <Briefcase class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Pengalaman</p>
                                    <p class="mt-0.5 text-sm font-black text-slate-800">{{ lowongan.minimal_pengalaman }} Tahun</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 rounded-2xl bg-emerald-50 border border-emerald-100 p-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-emerald-600 shadow-sm">
                                    <Banknote class="h-5 w-5" />
                                </div>
                                <div class="flex flex-col">
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-600">Estimasi Gaji</p>
                                    <p class="mt-0.5 text-sm font-black text-emerald-700 italic">
                                        {{ formatRupiah(lowongan.gaji_min) }} 
                                        <span v-if="lowongan.gaji_max"> - {{ formatRupiah(lowongan.gaji_max) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <button 
                                v-if="isPelamar" 
                                @click="openApplyModal"
                                class="flex w-full items-center justify-center gap-2 rounded-2xl bg-lokak-brand py-4 text-xs font-black uppercase italic tracking-widest text-white shadow-xl shadow-sky-900/20 transition-all hover:scale-105 hover:bg-blue-600 active:scale-95"
                            >
                                Lamar Sekarang <ChevronRight class="h-4 w-4" />
                            </button>

                            <Link 
                                v-else-if="!user" 
                                :href="route('register.pelamar')"
                                class="flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-900 py-4 text-xs font-black uppercase italic tracking-widest text-white shadow-xl transition-all hover:scale-105 hover:bg-black active:scale-95"
                            >
                                Login Untuk Melamar <ChevronRight class="h-4 w-4" />
                            </Link>

                            <div v-else class="rounded-2xl border border-sky-100 bg-sky-50 p-5 text-center">
                                <ShieldCheck class="mx-auto mb-2 h-6 w-6 text-lokak-brand" />
                                <p class="text-xs font-black uppercase italic tracking-wide text-slate-700">Mode Tinjauan Mitra</p>
                                <p class="mt-1 text-[10px] font-bold uppercase tracking-widest text-slate-500">Hanya akun pelamar yang dapat mengirim lamaran.</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-6 md:p-8 shadow-sm">
                        <h4 class="mb-6 text-sm font-black uppercase italic tracking-tight text-slate-900">Tentang Perusahaan</h4>
                        
                        <div class="mb-4 flex items-center gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-slate-100 bg-slate-50 p-1.5">
                                <img v-if="lowongan.perusahaan.logo" :src="lowongan.perusahaan.logo" class="h-full w-full object-contain" />
                                <Building2 v-else class="h-6 w-6 text-slate-300" />
                            </div>
                            <div class="flex flex-col">
                                <p class="text-sm font-black uppercase italic text-lokak-brand leading-tight line-clamp-1" :title="lowongan.perusahaan.nama">{{ lowongan.perusahaan.nama }}</p>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ lowongan.perusahaan.industri || 'Industri Umum' }}</p>
                            </div>
                        </div>

                        <div class="mb-4 flex flex-wrap gap-2">
                            <span class="flex items-center gap-1 rounded-md bg-amber-50 px-2 py-1 text-[10px] font-bold text-amber-700">
                                <Star class="h-3 w-3 fill-amber-500 text-amber-500" /> {{ lowongan.perusahaan.rating || 'Baru' }}
                            </span>
                            <span v-if="lowongan.perusahaan.is_verified || true" class="flex items-center gap-1 rounded-md bg-emerald-50 px-2 py-1 text-[10px] font-bold text-emerald-700">
                                <CheckCircle class="h-3 w-3" /> Terverifikasi
                            </span>
                        </div>

                        <ul class="mb-5 space-y-2.5">
                            <li class="flex items-start gap-2 text-xs font-medium text-slate-600">
                                <MapPin class="mt-0.5 h-3.5 w-3.5 shrink-0 text-slate-400" />
                                <span>{{ lowongan.perusahaan.alamat || lowongan.lokasi_nama }}</span>
                            </li>
                            <li v-if="lowongan.perusahaan.website" class="flex items-center gap-2 text-xs font-medium text-slate-600">
                                <Globe class="h-3.5 w-3.5 shrink-0 text-slate-400" />
                                <a :href="lowongan.perusahaan.website" target="_blank" class="hover:text-lokak-brand hover:underline">{{ lowongan.perusahaan.website.replace(/^https?:\/\//, '') }}</a>
                            </li>
                            <li v-if="lowongan.perusahaan.email" class="flex items-center gap-2 text-xs font-medium text-slate-600">
                                <Mail class="h-3.5 w-3.5 shrink-0 text-slate-400" />
                                <a :href="'mailto:' + lowongan.perusahaan.email" class="hover:text-lokak-brand">{{ lowongan.perusahaan.email }}</a>
                            </li>
                        </ul>

                        <p class="text-xs font-medium leading-relaxed text-slate-600 line-clamp-3">{{ lowongan.perusahaan.deskripsi }}</p>
                        
                        <div class="mt-5 border-t border-slate-100 pt-5 text-center">
                            <Link :href="route('mitra.show', lowongan.perusahaan.id || 1)" class="inline-flex text-[10px] font-black uppercase italic tracking-widest text-lokak-brand hover:text-blue-800">
                                Lihat Profil Lengkap &rarr;
                            </Link>
                        </div>
                    </div>

                    <div class="rounded-4xl border border-slate-200 bg-white p-6 shadow-sm text-center">
                        <button 
                            @click="copyToClipboard"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-50 border border-slate-200 py-3 text-xs font-bold uppercase tracking-wider text-slate-600 transition-all hover:border-lokak-brand hover:bg-sky-50 hover:text-lokak-brand active:scale-95"
                        >
                            <Check v-if="isCopied" class="h-4 w-4 text-emerald-500" />
                            <Share2 v-else class="h-4 w-4" /> 
                            {{ isCopied ? 'Tautan Disalin!' : 'Bagikan Loker' }}
                        </button>
                    </div>

                </div>
            </div>
        </main>

        <Teleport to="body">
            <Transition name="fade">
                <div v-if="isConfirmModalOpen" class="fixed inset-0 z-100 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm">
                    <div class="relative w-full max-w-lg overflow-hidden rounded-[3rem] bg-white shadow-2xl">
                        
                        <div class="bg-lokak-brand border-b border-lokak-brand p-6 md:p-8 relative">
                            <button @click="closeApplyModal" class="absolute right-6 top-6 rounded-full bg-white/10 p-1.5 text-white transition-colors hover:bg-white/20">
                                <X class="h-5 w-5" />
                            </button>
                            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20">
                                <Send class="h-6 w-6 text-white" />
                            </div>
                            <h2 class="text-2xl font-black uppercase italic tracking-tighter text-white">
                                KIRIM LAMARAN
                            </h2>
                            <p class="mt-1 text-[10px] font-bold uppercase tracking-widest text-sky-200">
                                Pastikan profil & resume sudah diperbarui.
                            </p>
                        </div>

                        <div class="p-6 md:p-8 space-y-6">
                            
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5 space-y-3">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Melamar sebagai:</p>
                                <div class="flex items-center gap-3 text-sm font-bold text-slate-800">
                                    <User class="h-4 w-4 text-lokak-brand" /> {{ authPelamar?.nama_pelamar || 'Nama Pelamar' }}
                                </div>
                                <div class="flex items-center gap-3 text-sm font-bold text-slate-800">
                                    <Phone class="h-4 w-4 text-lokak-brand" /> {{ authPelamar?.nohp_pelamar || '-' }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500">Pesan Pengantar (Opsional)</label>
                                <textarea 
                                    v-model="form.catatan" 
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm font-medium text-slate-700 outline-none transition-all focus:border-lokak-brand focus:ring-2 focus:ring-lokak-brand/20" 
                                    rows="4" 
                                    placeholder="Yth. HRD, saya sangat tertarik dengan posisi ini karena..."
                                ></textarea>
                            </div>

                            <button 
                                @click="submitApplication" 
                                :disabled="form.processing" 
                                class="flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-900 py-4 text-xs font-black uppercase italic tracking-widest text-white shadow-xl transition-all hover:scale-105 hover:bg-black active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed"
                            >
                                <Send class="h-4 w-4" /> 
                                {{ form.processing ? 'MEMPROSES...' : 'KIRIM LAMARAN SEKARANG' }}
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
    
    <Footer />
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>