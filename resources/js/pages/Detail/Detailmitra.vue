<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { 
    MapPin, Briefcase, Globe, Users, Star, Mail, 
    Building2, Calendar, ArrowUpRight, MessageSquare,
    Send, CheckCircle2, X
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

// PROPS DARI MITRACONTROLLER
const props = defineProps<{
    partnerDetail: any;
}>();

// Mengambil data autentikasi dari Inertia Shared Props
const page = usePage();
const auth = computed(() => (page.props.auth as any));

// State untuk Pop-up Notifikasi Sukses
const showSuccessPopup = ref(false);

// Inisialisasi Form Rating dengan Inertia useForm
const form = useForm({
    bintang: 0,
    ulasan: '',
});

// Fungsi untuk memilih jumlah bintang
const setRating = (n: number) => {
    form.bintang = n;
};

// Fungsi untuk mengirim ulasan ke Backend
const submitRating = () => {
    form.post(route('mitra.rating.store', props.partnerDetail.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            // Munculkan pop-up sukses
            showSuccessPopup.value = true;
            // Hilangkan otomatis setelah 4 detik
            setTimeout(() => {
                showSuccessPopup.value = false;
            }, 4000);
        },
    });
};

// Helper untuk inisial nama pelamar (Contoh: "Alip Maulana" -> "AM")
const getInitial = (name: string) => {
    if (!name) {
return '??';
}

    const words = name.trim().split(' ');

    if (words.length >= 2) {
        return (words[0][0] + words[1][0]).toUpperCase();
    }

    return name.substring(0, 2).toUpperCase();
};
</script>

<template>
    <Head :title="`${partnerDetail?.name || 'Profil Mitra'} - Lokak Begawe`" />

    <div class="relative min-h-screen bg-lokak-bg font-sans text-slate-900">
        <Navbar />

        <Transition
            enter-active-class="transform transition duration-500 ease-out"
            enter-from-class="-translate-y-full opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="showSuccessPopup" class="fixed top-10 left-1/2 z-100 -translate-x-1/2 w-[90%] max-w-md">
                <div class="flex items-center justify-between rounded-4xl bg-slate-900 p-2 pl-6 shadow-2xl border border-white/10 backdrop-blur-md">
                    <div class="flex items-center gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/40">
                            <CheckCircle2 class="h-6 w-6 text-white" />
                        </div>
                        <div>
                            <p class="text-[10px] font-black italic uppercase text-emerald-400 tracking-widest leading-none">Berhasil!</p>
                            <p class="text-xs font-bold text-white italic mt-0.5">Ulasan Anda telah dipublikasikan.</p>
                        </div>
                    </div>
                    <button @click="showSuccessPopup = false" class="p-4 text-slate-500 hover:text-white transition-colors">
                        <X class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </Transition>

        <main class="w-full px-6 pt-32 pb-20 md:px-16 lg:px-24 xl:px-32">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                
                <div class="lg:col-span-8 space-y-8">
                    
                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="flex items-center gap-6">
                                <div class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl bg-slate-50 flex items-center justify-center border border-slate-100 shadow-inner">
                                    <img v-if="partnerDetail?.logo" :src="partnerDetail.logo" class="h-full w-full object-cover" />
                                    <Building2 v-else class="h-10 w-10 text-slate-300" />
                                </div>
                                <div>
                                    <h1 class="text-3xl font-black italic tracking-tighter text-slate-800 uppercase leading-none">{{ partnerDetail?.name }}</h1>
                                    <p class="mt-2 text-sm font-black text-lokak-brand italic uppercase tracking-tight">{{ partnerDetail?.industry }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 grid grid-cols-2 gap-6 sm:grid-cols-4 border-t border-slate-50 pt-8">
                            <div class="flex flex-col gap-1">
                                <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic"><Globe class="h-3 w-3" /> Website</span>
                                <a :href="partnerDetail?.website?.startsWith('http') ? partnerDetail.website : 'https://' + partnerDetail?.website" target="_blank" class="text-xs font-black italic text-blue-600 uppercase underline truncate">{{ partnerDetail?.website || 'N/A' }}</a>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic"><Users class="h-3 w-3" /> Ukuran</span>
                                <span class="text-xs font-black italic text-slate-800 uppercase">{{ partnerDetail?.size }}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic"><Calendar class="h-3 w-3" /> Berdiri</span>
                                <span class="text-xs font-black italic text-slate-800 uppercase">{{ partnerDetail?.founded }}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase italic"><Star class="h-3 w-3" /> Rating</span>
                                <div class="flex items-center gap-1">
                                    <span class="text-xs font-black italic text-slate-800 uppercase">{{ partnerDetail?.rating || '0' }} / 5.0</span>
                                    <Star class="h-3 w-3 text-amber-500 fill-amber-500" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-10 shadow-sm">
                        <h3 class="mb-6 text-xl font-black italic text-slate-800 uppercase flex items-center gap-2">
                            <Building2 class="h-5 w-5 text-lokak-brand" /> Tentang Kami
                        </h3>
                        <p class="text-sm font-medium leading-relaxed text-slate-600 mb-8 whitespace-pre-line">{{ partnerDetail?.description }}</p>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-10 shadow-sm">
                        <h3 class="mb-10 text-xl font-black italic text-slate-800 uppercase flex items-center gap-2">
                            <MessageSquare class="h-5 w-5 text-lokak-brand" /> 
                            Ulasan ({{ partnerDetail?.reviewCount || 0 }})
                        </h3>

                        <div v-if="auth?.user" class="mb-12 overflow-hidden rounded-[2.5rem] bg-slate-900 p-8 text-white shadow-2xl">
                            <div class="mb-8">
                                <h4 class="text-sm font-black italic uppercase tracking-widest text-emerald-400">Penilaian Anda</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase italic mt-1">Bantu pelamar lain mengetahui kualitas perusahaan ini</p>
                            </div>

                            <div class="mb-8 flex items-center gap-4">
                                <div class="flex gap-2">
                                    <button 
                                        v-for="i in 5" :key="i" 
                                        @click="setRating(i)"
                                        type="button"
                                        class="transition-transform hover:scale-125 active:scale-90"
                                    >
                                        <Star 
                                            :class="['h-9 w-9', i <= form.bintang ? 'text-amber-400 fill-amber-400' : 'text-slate-700']" 
                                        />
                                    </button>
                                </div>
                                <span v-if="form.bintang > 0" class="text-[10px] font-black italic uppercase text-amber-400 tracking-tighter">
                                    ({{ form.bintang }} Bintang)
                                </span>
                            </div>

                            <div class="space-y-4">
                                <textarea 
                                    v-model="form.ulasan"
                                    rows="3"
                                    placeholder="Bagikan pengalaman Anda bekerja atau melamar di sini..."
                                    class="w-full rounded-2xl border-none bg-white/5 p-5 text-sm font-medium text-white placeholder-slate-500 focus:bg-white/10 focus:ring-2 focus:ring-lokak-brand transition-all outline-none"
                                ></textarea>
                                
                                <button 
                                    @click="submitRating"
                                    :disabled="form.processing || form.bintang === 0 || form.ulasan.length < 5"
                                    class="flex w-full items-center justify-center gap-3 rounded-2xl bg-lokak-brand py-5 text-xs font-black uppercase italic shadow-lg transition-all hover:-translate-y-1 hover:brightness-110 disabled:opacity-50 disabled:hover:translate-y-0"
                                >
                                    <Send class="h-4 w-4" />
                                    {{ form.processing ? 'Mengirim...' : 'Kirim Ulasan Sekarang' }}
                                </button>
                            </div>
                        </div>

                        <div v-else class="mb-12 rounded-[2.5rem] border-2 border-dashed border-slate-100 p-10 text-center">
                            <p class="text-xs font-black text-slate-400 uppercase italic">
                                Punya pengalaman di sini? <Link :href="route('login')" class="text-lokak-brand underline underline-offset-4 decoration-2 hover:text-sky-600 transition-colors">Login Pelamar</Link> untuk memberi rating.
                            </p>
                        </div>

                        <div class="space-y-8">
                            <template v-if="partnerDetail?.reviews && partnerDetail.reviews.length > 0">
                                <div v-for="(rev, index) in partnerDetail.reviews" :key="index" class="border-b border-slate-50 pb-8 last:border-0">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-lokak-brand/10 flex items-center justify-center text-[10px] font-black text-lokak-brand uppercase italic border border-lokak-brand/5">
                                                {{ getInitial(rev.user_name) }}
                                            </div>
                                            <div>
                                                <p class="text-xs font-black italic text-slate-800 uppercase leading-none">{{ rev.user_name }}</p>
                                                <p class="text-[9px] font-bold text-slate-300 uppercase italic tracking-wider mt-1">{{ rev.date }}</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-0.5">
                                            <Star v-for="i in 5" :key="i" 
                                                :class="['h-3.5 w-3.5', i <= rev.bintang ? 'text-amber-500 fill-amber-500' : 'text-slate-200']" />
                                        </div>
                                    </div>
                                    <div class="rounded-2xl bg-slate-50 p-5 border border-slate-100/50 shadow-sm">
                                        <p class="text-sm font-medium text-slate-600 leading-relaxed italic">"{{ rev.ulasan }}"</p>
                                    </div>
                                </div>
                            </template>

                            <div v-else class="text-center py-12">
                                <div class="bg-slate-50 h-16 w-16 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                    <Star class="h-8 w-8 text-slate-200" />
                                </div>
                                <p class="text-xs font-black text-slate-400 italic uppercase">Belum ada ulasan untuk perusahaan ini</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-xl font-black italic text-slate-800 uppercase tracking-tight px-2">Lowongan Aktif ({{ partnerDetail?.activeJobs?.length || 0 }})</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div v-for="job in partnerDetail?.activeJobs" :key="job.id" class="group rounded-2xl border border-slate-200 bg-white p-6 transition-all hover:border-lokak-brand hover:shadow-md">
                                <div class="flex items-center justify-between">
                                    <div class="space-y-1">
                                        <h4 class="font-black italic text-slate-800 uppercase group-hover:text-lokak-brand transition-colors">{{ job.title }}</h4>
                                        <div class="flex items-center gap-4 text-[10px] font-bold text-slate-400 uppercase italic">
                                            <span class="flex items-center gap-1"><Briefcase class="h-3 w-3" /> {{ job.type }}</span>
                                            <span class="flex items-center gap-1"><MapPin class="h-3 w-3" /> {{ partnerDetail?.location }}</span>
                                        </div>
                                    </div>
                                    <Link :href="'/detail/lowongan/' + job.id" class="flex items-center gap-2 rounded-lg bg-slate-50 p-3 text-slate-400 transition-all group-hover:bg-lokak-brand group-hover:text-white">
                                        <span class="text-[10px] font-black italic">LIHAT</span>
                                        <ArrowUpRight class="h-4 w-4" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="sticky top-32 space-y-6">
                        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                            <h3 class="text-lg font-black italic text-slate-800 uppercase mb-6 border-b border-slate-50 pb-4">Kontak</h3>
                            <div class="space-y-6">
                                <div class="flex items-start gap-4">
                                    <div class="rounded-xl bg-sky-50 p-3 text-lokak-brand shadow-sm"><MapPin class="h-5 w-5" /></div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase italic">Alamat</p>
                                        <p class="text-xs font-black italic text-slate-700 uppercase leading-relaxed">{{ partnerDetail?.address }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="rounded-xl bg-blue-50 p-3 text-blue-500 shadow-sm"><Mail class="h-5 w-5" /></div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase italic">Email</p>
                                        <p class="text-xs font-black italic text-slate-700">{{ partnerDetail?.email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-8 rounded-2xl bg-slate-900 p-6 text-center shadow-lg">
                                <p class="text-[10px] font-bold text-slate-400 uppercase italic mb-1">Lowongan Aktif</p>
                                <p class="text-2xl font-black italic text-white uppercase tracking-tighter">{{ partnerDetail?.jobCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
        <Footer />
    </div>
</template>

<style scoped>
/* Transisi gambar */
img { animation: fadeIn 0.4s ease-in-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
</style>