<!-- eslint-disable @typescript-eslint/no-unused-vars -->
<script setup lang="ts">
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import { 
    MapPin, Building2, ChevronLeft, Briefcase, 
    User, Phone, Send, X, ChevronRight, 
    Wrench, GraduationCap, Clock, Banknote, ShieldCheck
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
const isOwner = computed(() => user.value?.role === 'mitra' && props.authPelamar?.id === props.lowongan.id); // Opsional jika butuh check owner

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
            // Gunakan notifikasi yang lebih cantik jika ada
            alert('Lamaran berhasil dikirim!');
        },
        preserveScroll: true
    });
};

// --- FORMAT GAJI ---
const formatRupiah = (value: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);
};

</script>

<template>
    <Head :title="`${lowongan.judul} - Detail Lowongan`" />
    <Navbar />
    
    <div class="min-h-screen bg-slate-50 font-sans text-slate-900">
        <main class="w-full px-6 pt-32 pb-20 md:px-16 lg:px-32">
            <Link :href="route('lowongan.index')" class="mb-8 flex items-center gap-2 text-[10px] font-black uppercase italic text-slate-400 hover:text-sky-700 transition-all">
                <ChevronLeft class="h-4 w-4" /> Kembali ke Daftar Lowongan
            </Link>

            <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
                <div class="lg:col-span-8 space-y-8">
                    <div class="rounded-[3rem] bg-white p-10 shadow-sm border border-slate-100 relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-sky-500/5 blur-3xl"></div>
                        
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <span class="rounded-full bg-sky-100 px-4 py-1 text-[9px] font-black uppercase italic text-sky-700 border border-sky-200">
                                        {{ lowongan.tipe }}
                                    </span>
                                    <span v-if="lowongan.status === 'pending'" class="rounded-full bg-amber-100 px-4 py-1 text-[9px] font-black uppercase italic text-amber-700 border border-amber-200">
                                        Menunggu Verifikasi
                                    </span>
                                </div>
                                <h1 class="text-3xl font-black italic tracking-tighter uppercase text-slate-900 md:text-5xl leading-[0.9]">
                                    {{ lowongan.judul }}
                                </h1>
                                <div class="flex flex-wrap gap-6 text-[11px] font-bold uppercase italic text-slate-400">
                                    <span class="flex items-center gap-2">
                                        <Building2 class="h-4 w-4 text-sky-700" /> {{ lowongan.perusahaan.nama }}
                                    </span>
                                    <span class="flex items-center gap-2">
                                        <MapPin class="h-4 w-4 text-sky-700" /> {{ lowongan.lokasi_nama }}
                                    </span>
                                    <span class="flex items-center gap-2">
                                        <Clock class="h-4 w-4 text-sky-700" /> Batas: {{ lowongan.deadline }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="h-24 w-24 shrink-0 rounded-4xl bg-slate-50 border border-slate-100 p-4 flex items-center justify-center">
                                <img v-if="lowongan.perusahaan.logo" :src="lowongan.perusahaan.logo" class="max-h-full object-contain" />
                                <Building2 v-else class="h-10 w-10 text-slate-200" />
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[3rem] bg-white p-10 shadow-sm border border-slate-100">
                        <div class="space-y-12">
                            <section>
                                <h3 class="mb-6 flex items-center gap-3 text-sm font-black italic uppercase text-slate-900">
                                    <div class="h-2 w-8 rounded-full bg-sky-700"></div> Deskripsi Pekerjaan
                                </h3>
                                <div class="text-sm leading-relaxed text-slate-500 whitespace-pre-line italic font-medium">
                                    {{ lowongan.deskripsi }}
                                </div>
                            </section>

                            <section v-if="lowongan.skills?.length">
                                <h3 class="mb-6 flex items-center gap-3 text-sm font-black italic uppercase text-slate-900">
                                    <div class="h-2 w-8 rounded-full bg-sky-700"></div> Keahlian Dibutuhkan
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    <span v-for="skill in lowongan.skills" :key="skill.id" class="rounded-xl bg-slate-900 px-5 py-2.5 text-[10px] font-black text-white uppercase italic">
                                        # {{ skill.nama_skill }}
                                    </span>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 space-y-6">
                    <div class="rounded-[3rem] bg-white p-8 shadow-sm border border-slate-100 space-y-6">
                        <h4 class="text-[11px] font-black uppercase italic text-slate-400 tracking-widest">Ringkasan Kualifikasi</h4>
                        
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 p-4 rounded-3xl bg-slate-50">
                                <div class="h-10 w-10 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-sky-700">
                                    <GraduationCap class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase italic">Pendidikan Min.</p>
                                    <p class="text-xs font-black text-slate-900 uppercase italic">{{ lowongan.minimal_pendidikan || 'Semua Jenjang' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 p-4 rounded-3xl bg-slate-50">
                                <div class="h-10 w-10 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-sky-700">
                                    <Briefcase class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase italic">Pengalaman Min.</p>
                                    <p class="text-xs font-black text-slate-900 uppercase italic">{{ lowongan.minimal_pengalaman }} Tahun</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 p-4 rounded-3xl bg-emerald-50 border border-emerald-100">
                                <div class="h-10 w-10 rounded-2xl bg-white border border-emerald-100 flex items-center justify-center text-emerald-600">
                                    <Banknote class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-emerald-400 uppercase italic">Estimasi Gaji</p>
                                    <p class="text-xs font-black text-emerald-700 italic">
                                        {{ formatRupiah(lowongan.gaji_min) }} - {{ formatRupiah(lowongan.gaji_max) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6">
                            <button 
                                v-if="isPelamar" 
                                @click="openApplyModal"
                                class="flex w-full items-center justify-center gap-3 rounded-3xl bg-sky-700 py-6 text-xs font-black uppercase italic text-white shadow-xl shadow-sky-900/20 transition-all hover:-translate-y-1 hover:bg-sky-800 active:scale-95"
                            >
                                LAMAR SEKARANG <ChevronRight class="h-4 w-4" />
                            </button>

                            <Link 
                                v-else-if="!user" 
                                :href="route('register.pelamar')"
                                class="flex w-full items-center justify-center gap-3 rounded-3xl bg-slate-900 py-6 text-xs font-black uppercase italic text-white shadow-xl transition-all hover:-translate-y-1 hover:bg-slate-800 active:scale-95"
                            >
                                LOGIN UNTUK MELAMAR <ChevronRight class="h-4 w-4" />
                            </Link>

                            <div v-else class="rounded-3xl bg-slate-900 p-6 text-center">
                                <ShieldCheck class="h-8 w-8 text-sky-500 mx-auto mb-2" />
                                <p class="text-[10px] font-black text-white uppercase italic">Mode Pratinjau Manajemen</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase italic mt-1">Hanya Pelamar yang dapat mengirim lamaran.</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[3rem] bg-white p-8 shadow-sm border border-slate-100">
                        <h4 class="mb-6 text-[11px] font-black uppercase italic text-slate-400 tracking-widest">Tentang Perusahaan</h4>
                        <div class="space-y-4">
                            <p class="text-lg font-black text-sky-700 uppercase italic leading-none">{{ lowongan.perusahaan.nama }}</p>
                            <p class="text-[10px] font-black text-slate-400 uppercase italic">{{ lowongan.perusahaan.industri }}</p>
                            <p class="text-xs font-medium text-slate-500 italic leading-relaxed line-clamp-4">{{ lowongan.perusahaan.deskripsi }}</p>
                            <div class="pt-4">
                                <Link :href="route('mitra.show', lowongan.perusahaan.id || 1)" class="text-[10px] font-black text-sky-700 uppercase italic hover:underline">Lihat Profil Lengkap Perusahaan →</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <Teleport to="body">
            <Transition name="fade">
                <div v-if="isConfirmModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
                    <div class="w-full max-w-lg rounded-[3.5rem] bg-white p-10 shadow-2xl relative animate-in zoom-in-95 duration-300">
                        <button @click="closeApplyModal" class="absolute top-8 right-8 text-slate-400 hover:text-rose-500 transition-colors">
                            <X class="h-6 w-6" />
                        </button>

                        <div class="mb-8">
                            <h2 class="text-2xl font-black italic tracking-tighter text-slate-900 uppercase">
                                Kirim <span class="text-sky-700">Lamaran</span>
                            </h2>
                            <p class="text-[10px] font-bold text-slate-400 uppercase italic mt-1">Pastikan data profil Anda sudah benar.</p>
                        </div>

                        <div class="space-y-6">
                            <div class="rounded-3xl bg-slate-50 p-6 space-y-3">
                                <div class="flex items-center gap-3 text-xs font-black text-slate-700 uppercase italic">
                                    <User class="h-4 w-4 text-sky-700" /> {{ authPelamar?.nama_pelamar }}
                                </div>
                                <div class="flex items-center gap-3 text-xs font-black text-slate-700 uppercase italic">
                                    <Phone class="h-4 w-4 text-sky-700" /> {{ authPelamar?.nohp_pelamar }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="ml-4 text-[10px] font-black text-slate-400 uppercase italic">Catatan Tambahan (Opsional)</label>
                                <textarea v-model="form.catatan" class="w-full rounded-3xl border-slate-100 bg-slate-50 p-5 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-sky-700/20 italic" rows="4" placeholder="Tuliskan pesan untuk HRD..."></textarea>
                            </div>

                            <button @click="submitApplication" :disabled="form.processing" class="flex w-full items-center justify-center gap-3 rounded-2xl bg-sky-700 py-5 text-xs font-black uppercase italic text-white shadow-xl transition-all hover:bg-sky-800 disabled:opacity-50">
                                <Send class="h-4 w-4" /> KIRIM LAMARAN SEKARANG
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
.fade-enter-active, .fade-leave-active { transition: opacity 0.4s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
.line-clamp-4 { display: -webkit-box; -webkit-line-clamp: 4; line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; }
</style>