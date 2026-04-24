<script setup lang="ts">
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';
import { 
    MapPin, Building2, ChevronLeft, Briefcase, 
    User, Phone, Send, X 
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

const props = defineProps<{
    lowongan: any;
    authPelamar: any; // Data pelamar dari Controller
}>();

const page = usePage();
const user = computed(() => page.props.auth.user);

// --- LOGIC MODAL & FORM ---
const isConfirmModalOpen = ref(false);

const form = useForm({
    catatan: '', // Pesan tambahan opsional
});

const openApplyModal = () => {
    isConfirmModalOpen.value = true;
};

const closeApplyModal = () => {
    isConfirmModalOpen.value = false;
    form.reset();
};

const submitApplication = () => {
    // Arahkan ke rute POST lamaran.store yang sudah kamu buat
    form.post(route('pelamar.lamar.store', props.lowongan.id), {
        onSuccess: () => {
            closeApplyModal();
            alert('Lamaran berhasil dikirim! Silahkan cek dashboard untuk memantau status.');
        },
        preserveScroll: true
    });
};

</script>

<template>

    <Head :title="`${lowongan.judul} - Detail Lowongan`" />
    <Navbar />
    <div class="min-h-screen bg-lokak-bg font-sans text-lokak-text">
        
        
        <main class="w-full px-6 pt-32 pb-20 md:px-16 lg:px-32">
            <Link href="/lowongan" class="mb-8 flex items-center gap-2 text-xs font-black uppercase italic text-slate-400 hover:text-lokak-brand transition-colors">
                <ChevronLeft class="h-4 w-4" /> Kembali ke Daftar
            </Link>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                <div class="lg:col-span-8 space-y-6">
                    <div class="rounded-[2.5rem] bg-white p-8 shadow-sm border border-slate-100 relative overflow-hidden">
                        <div class="absolute -top-6 -right-6 h-24 w-24 rounded-full bg-lokak-brand/5"></div>
                        <h1 class="text-3xl font-black italic tracking-tighter uppercase mb-4 text-slate-900 md:text-4xl">{{ lowongan.judul }}</h1>

                        <div class="flex flex-wrap gap-4 text-[10px] font-bold uppercase italic text-slate-400">
                            <span class="flex items-center gap-1.5 rounded-lg bg-slate-50 px-3 py-1.5">
                                <Building2 class="h-3 w-3 text-lokak-brand" /> {{ lowongan.perusahaan.nama }}
                            </span>
                            <span class="flex items-center gap-1.5 rounded-lg bg-slate-50 px-3 py-1.5">
                                <MapPin class="h-3 w-3 text-lokak-brand" /> {{ lowongan.lokasi_nama }}
                            </span>
                        </div>
                        
                        <div class="mt-10 border-t border-slate-50 pt-8">
                            <h3 class="mb-6 flex items-center gap-2 text-sm font-black italic uppercase text-slate-800">
                                <span class="h-1.5 w-6 rounded-full bg-lokak-brand"></span> Deskripsi Pekerjaan
                            </h3>
                            <div class="text-sm leading-relaxed text-slate-600 whitespace-pre-line" v-html="lowongan.deskripsi"></div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="sticky">
                        <div class="rounded-[2.5rem] text-white  shadow-sky-900/20 border border-white/5">
                          
                            
                            <button 
                                v-if="user" 
                                @click="openApplyModal"
                                class="flex w-full items-center justify-center  rounded-2xl bg-lokak-brand py-5 text-xs font-black uppercase italic text-white shadow-xl shadow-sky-900/40 transition-all hover:-translate-y-1 hover:bg-sky-600 active:scale-95"
                            >
                                LAMAR SEKARANG <ChevronRight class="h-3 w-3" />
                            </button>

                            <Link 
                                v-else 
                                :href="route('lowongan.daftar', lowongan.id)"
                                class="flex w-full items-center justify-center gap-2 rounded-2xl bg-lokak-brand py-5 text-xs font-black uppercase italic text-white shadow-xl shadow-sky-900/40 transition-all hover:-translate-y-1 hover:bg-sky-600 active:scale-95"
                            >
                                LAMAR SEKARANG <ChevronRight class="h-3 w-3" />
                            </Link>

                            
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <Teleport to="body">
            <Transition name="fade">
                <div v-if="isConfirmModalOpen" class="fixed inset-0 z-100 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm">
                    <div class="w-full max-w-lg rounded-[2.5rem] bg-white p-8 shadow-2xl relative overflow-hidden animate-in fade-in zoom-in duration-300">
                        
                        <div class="mb-8 flex items-center justify-between">
                            <h2 class="text-xl font-black italic tracking-tighter text-slate-900 uppercase">
                                Konfirmasi <span class="text-lokak-brand">Lamaran</span>
                            </h2>
                            <button @click="closeApplyModal" class="rounded-full p-2 hover:bg-slate-100 transition-colors">
                                <X class="h-5 w-5 text-slate-400" />
                            </button>
                        </div>

                        <div class="mb-8 space-y-4 rounded-3xl bg-slate-50 p-6">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 shrink-0 rounded-xl bg-lokak-brand/10 flex items-center justify-center text-lokak-brand">
                                    <Briefcase class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase italic">Posisi Dilamar</p>
                                    <p class="text-xs font-black italic text-slate-800 uppercase">{{ lowongan.judul }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-200/50 pt-4">
                                <div class="flex items-center gap-3">
                                    <User class="h-3.5 w-3.5 text-slate-400" />
                                    <span class="text-[11px] font-bold text-slate-600 italic">{{ authPelamar?.nama_pelamar }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <Phone class="h-3.5 w-3.5 text-slate-400" />
                                    <span class="text-[11px] font-bold text-slate-600 italic">{{ authPelamar?.nohp_pelamar }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8 space-y-3">
                            <label class="ml-4 text-[10px] font-black tracking-widest text-slate-400 uppercase italic">Pesan Tambahan (Opsional)</label>
                            <textarea 
                                v-model="form.catatan"
                                placeholder="Kenapa Anda tertarik dengan posisi ini?..."
                                class="w-full rounded-3xl border border-slate-100 bg-slate-50 p-5 text-xs font-bold text-slate-700 outline-none focus:border-lokak-brand focus:bg-white transition-all min-h-30"
                            ></textarea>
                            <p class="text-[9px] font-bold text-slate-400 italic px-4">
                                * Pastikan CV Anda di dashboard sudah dalam versi terbaru.
                            </p>
                        </div>

                        <div class="flex flex-col gap-3">
                            <button 
                                @click="submitApplication"
                                :disabled="form.processing"
                                class="flex w-full items-center justify-center gap-3 rounded-2xl bg-lokak-brand py-5 text-xs font-black uppercase italic text-white shadow-xl shadow-sky-900/20 transition-all hover:bg-sky-600 active:scale-95 disabled:opacity-50"
                            >
                                <span v-if="form.processing">MENGIRIM LAMARAN...</span>
                                <template v-else>
                                    KIRIM LAMARAN <Send class="h-4 w-4" />
                                </template>
                            </button>
                            <button 
                                @click="closeApplyModal"
                                class="w-full py-4 text-[10px] font-black uppercase italic text-slate-400 hover:text-rose-500 transition-colors"
                            >
                                Batalkan
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
.transition-all { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
</style>