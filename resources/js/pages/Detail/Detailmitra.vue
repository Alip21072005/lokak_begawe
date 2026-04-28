<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { 
    MapPin, Briefcase, Globe, Users, Star, Mail, Phone,
    Building2, ArrowUpRight, CheckCircle2, Share2, Check, ChevronLeft, Clock
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

const props = defineProps<{
    partnerDetail: any;
}>();

const page = usePage();
const auth = computed(() => (page.props.auth as any));

const showSuccessPopup = ref(false);
const isCopied = ref(false);

const form = useForm({
    bintang: 0,
    ulasan: '',
});

const setRating = (n: number) => {
    form.bintang = n; 
};

const submitRating = () => {
    form.post(route('mitra.rating.store', props.partnerDetail.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showSuccessPopup.value = true;
            setTimeout(() => {
                showSuccessPopup.value = false; 
            }, 4000);
        },
    });
};

const copyProfileLink = async () => {
    try {
        await navigator.clipboard.writeText(window.location.href);
        isCopied.value = true;
        setTimeout(() => {
            isCopied.value = false; 
        }, 2000);
    } catch (err) {
        console.error(err); 
    }
};

const getInitial = (name: string) => {
    if (!name) return '??';
    const words = name.trim().split(' ');
    return words.length >= 2 ? (words[0][0] + words[1][0]).toUpperCase() : name.substring(0, 2).toUpperCase();
};
</script>

<template>
    <Head :title="`${partnerDetail?.name} - Profil Perusahaan`" />

    <div class="mt-12 min-h-screen overflow-x-hidden bg-lokak-bg font-sans text-lokak-text">
        <Navbar />

        <main class="w-full px-6 py-12 md:px-16 lg:px-24 xl:px-32">
            
            <Link :href="route('mitra.index')" class="group mb-8 inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-lokak-brand transition-colors italic">
                <ChevronLeft class="h-4 w-4 transition-transform group-hover:-translate-x-1" /> Kembali ke Daftar Mitra
            </Link>

            <div class="mb-12 overflow-hidden rounded-[2.5rem] bg-white shadow-sm border border-slate-200">
                <div class="relative h-48 w-full overflow-hidden md:h-80">
                    <img v-if="partnerDetail.banner" :src="partnerDetail.banner" class="h-full w-full object-cover" alt="Banner Perusahaan" />
                    <div v-else class="h-full w-full bg-slate-900"></div>
                </div>

                <div class="relative px-6 pb-10 md:px-12 -mt-16 md:-mt-24 z-10 flex flex-col items-center md:flex-row md:items-end md:gap-8 text-center md:text-left">
                    <div class="h-32 w-32 shrink-0 overflow-hidden rounded-4xl border-8 border-white bg-white shadow-xl md:h-48 md:w-48 flex items-center justify-center p-4">
                        <img v-if="partnerDetail.logo" :src="partnerDetail.logo" class="max-h-full max-w-full object-contain" />
                        <Building2 v-else class="h-16 w-16 text-slate-200" />
                    </div>

                    <div class="mt-4 flex-1 pb-2">
                        <h1 class="text-3xl font-black uppercase italic tracking-tight text-slate-900 md:text-5xl">
                            {{ partnerDetail.name }}
                        </h1>
                        <p class="mt-3 inline-flex items-center rounded-full bg-sky-50 px-4 py-1.5 text-[10px] font-black uppercase tracking-widest text-lokak-brand">
                            {{ partnerDetail.industry }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:gap-10">
                
                <div class="space-y-8 lg:col-span-8">
                    
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                        <div class="flex flex-col items-center justify-center rounded-4xl border border-slate-200 bg-white p-6 shadow-sm">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Berdiri</p>
                            <p class="mt-1 text-lg font-black text-slate-800 italic">{{ partnerDetail.founded || '-' }}</p>
                        </div>
                        <div class="flex flex-col items-center justify-center rounded-4xl border border-slate-200 bg-white p-6 shadow-sm">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Ukuran</p>
                            <p class="mt-1 text-lg font-black text-slate-800 italic">{{ partnerDetail.size || '-' }}</p>
                        </div>
                        <div class="flex flex-col items-center justify-center rounded-4xl border border-slate-200 bg-white p-6 shadow-sm">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Loker Aktif</p>
                            <p class="mt-1 text-lg font-black text-lokak-brand italic">{{ partnerDetail.jobCount }}</p>
                        </div>
                        <div class="flex flex-col items-center justify-center rounded-4xl border border-slate-200 bg-white p-6 shadow-sm">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Rating</p>
                            <p class="mt-1 text-lg font-black text-amber-500 flex items-center gap-1 italic"><Star class="h-4 w-4 fill-amber-500" /> {{ partnerDetail.rating }}</p>
                        </div>
                    </div>

                    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 md:p-10 shadow-sm">
                        <h3 class="mb-6 flex items-center gap-3 text-2xl font-black uppercase italic text-slate-900">
                            <div class="h-8 w-2 rounded-full bg-lokak-brand"></div> Tentang Perusahaan
                        </h3>
                        <p class="text-sm font-medium leading-relaxed text-slate-600 whitespace-pre-line">{{ partnerDetail.description }}</p>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-2xl font-black uppercase italic text-slate-900 flex items-center gap-3">
                            <div class="h-8 w-2 rounded-full bg-lokak-brand"></div> 
                            Lowongan Aktif ({{ partnerDetail.jobCount }})
                        </h3>
                        
                        <div v-if="partnerDetail.activeJobs && partnerDetail.activeJobs.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <Link v-for="job in partnerDetail.activeJobs" :key="job.id" :href="'/detail/lowongan/' + job.id" 
                                class="group flex flex-col justify-between rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-lokak-brand/30 hover:shadow-xl">
                                <div>
                                    <div class="mb-4 flex items-start justify-between">
                                        <div class="rounded-full bg-sky-50 px-3 py-1.5 text-[10px] font-black uppercase text-lokak-brand tracking-wider">
                                            {{ job.type }}
                                        </div>
                                        <ArrowUpRight class="h-5 w-5 text-slate-300 transition-colors group-hover:text-lokak-brand" />
                                    </div>
                                    <h4 class="mb-4 line-clamp-2 text-lg font-black leading-snug text-slate-900 uppercase italic transition-colors group-hover:text-lokak-brand">{{ job.title }}</h4>
                                    <div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-500 uppercase tracking-tight">
                                        <MapPin class="h-3.5 w-3.5 shrink-0 text-slate-400" /> <span class="truncate">{{ partnerDetail.location }}</span>
                                    </div>
                                </div>
                                <div class="mt-6 flex items-center justify-end border-t border-slate-100 pt-4">
                                    <span class="inline-flex items-center justify-center rounded-full bg-slate-900 px-5 py-2 text-[10px] font-black uppercase tracking-wider text-white transition-all group-hover:bg-lokak-brand italic shadow-md active:scale-95"> 
                                        Detail &rarr;
                                    </span>
                                </div>
                            </Link>
                        </div>
                        <div v-else class="rounded-[2.5rem] border-2 border-dashed border-slate-200 bg-white py-20 text-center text-slate-400">
                            <Briefcase class="mx-auto h-12 w-12 opacity-30 mb-4" />
                            <h4 class="text-lg font-bold text-slate-800">Belum Ada Lowongan</h4>
                            <p class="mt-2 text-sm font-medium text-slate-500">Saat ini perusahaan belum membuka rekrutmen baru.</p>
                        </div>
                    </div>

                    <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 md:p-10 shadow-sm mb-10">
                        <h3 class="mb-10 flex items-center gap-3 text-2xl font-black uppercase italic text-slate-900">
                            <div class="h-8 w-2 rounded-full bg-lokak-brand"></div> Ulasan Pelamar
                        </h3>

                        <div v-if="auth?.user?.role === 'pelamar'" class="mb-12 rounded-4xl bg-slate-900 p-8 text-white shadow-xl relative overflow-hidden">
                            <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-lokak-brand/20 blur-3xl"></div>
                            
                            <h4 class="text-xs font-black text-emerald-400 uppercase tracking-widest mb-4">Bagikan Pengalaman Anda</h4>
                            <div class="mb-6 flex gap-2">
                                <button v-for="i in 5" :key="i" @click="setRating(i)" type="button" class="hover:scale-110 transition-transform active:scale-95">
                                    <Star :class="['h-8 w-8', i <= form.bintang ? 'text-amber-400 fill-amber-400 shadow-amber-400' : 'text-slate-700']" />
                                </button>
                            </div>
                            <textarea v-model="form.ulasan" rows="3" placeholder="Ceritakan proses rekrutmen atau lingkungan kerja di sini..." class="w-full rounded-2xl border-none bg-white/10 p-5 text-sm font-medium text-white placeholder-slate-400 focus:ring-2 focus:ring-lokak-brand outline-none transition-all"></textarea>
                            <div class="mt-6 flex justify-end">
                                <button @click="submitRating" :disabled="form.processing || form.bintang === 0" class="rounded-xl bg-lokak-brand px-8 py-3 text-xs font-black uppercase tracking-widest transition-all hover:bg-blue-600 active:scale-95 disabled:opacity-50 italic">
                                    Kirim Ulasan
                                </button>
                            </div>
                        </div>

                        <div v-if="partnerDetail.reviews.length" class="space-y-8">
                            <div v-for="(rev, index) in partnerDetail.reviews" :key="index" class="border-b border-slate-100 pb-8 last:border-0">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-2xl bg-sky-50 flex items-center justify-center text-sm font-black text-lokak-brand shadow-sm">
                                            {{ getInitial(rev.user_name) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-800 uppercase italic">{{ rev.user_name }}</p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ rev.date }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-1 bg-slate-50 px-3 py-1.5 rounded-full">
                                        <Star v-for="i in 5" :key="i" :class="['h-3.5 w-3.5', i <= rev.bintang ? 'text-amber-500 fill-amber-500' : 'text-slate-300']" />
                                    </div>
                                </div>
                                <p class="text-sm font-medium text-slate-600 bg-slate-50 p-5 rounded-2xl leading-relaxed">"{{ rev.ulasan }}"</p>
                            </div>
                        </div>
                        <div v-else class="py-8 text-center">
                            <Star class="mx-auto h-10 w-10 text-slate-200 mb-3" />
                            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Belum ada ulasan</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 space-y-6">
                    
                    <div class="rounded-[2.5rem] bg-slate-900 p-8 shadow-xl text-white relative overflow-hidden">
                        <div class="absolute -right-20 -bottom-20 h-64 w-64 rounded-full bg-lokak-brand/10 blur-3xl"></div>
                        
                        <h4 class="mb-8 text-lg font-black uppercase italic tracking-widest text-white border-b border-white/10 pb-4">
                            Info Kontak
                        </h4>
                        
                        <div class="space-y-6 relative z-10">
                            <div v-if="partnerDetail.website" class="flex gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-lokak-brand">
                                    <Globe class="h-5 w-5" />
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Website Resmi</p>
                                    <a :href="partnerDetail.website" target="_blank" class="text-sm font-bold text-sky-400 hover:text-white transition-colors truncate block mt-1">{{ partnerDetail.website }}</a>
                                </div>
                            </div>
                            
                            <div class="flex gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-lokak-brand">
                                    <Mail class="h-5 w-5" />
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email</p>
                                    <p class="text-sm font-bold text-slate-200 mt-1 truncate">{{ partnerDetail.email }}</p>
                                </div>
                            </div>
                            
                            <div v-if="partnerDetail.phone" class="flex gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-lokak-brand">
                                    <Phone class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Telepon / WA</p>
                                    <p class="text-sm font-bold text-slate-200 mt-1">{{ partnerDetail.phone }}</p>
                                </div>
                            </div>
                            
                            <div class="flex gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-lokak-brand">
                                    <MapPin class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Alamat Kantor</p>
                                    <p class="text-sm font-medium text-slate-300 leading-relaxed mt-1">{{ partnerDetail.address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-4xl border border-slate-200 bg-white p-6 text-center shadow-sm">
                        <button @click="copyProfileLink" class="flex w-full items-center justify-center gap-2 rounded-xl border-2 border-slate-100 bg-slate-50 py-4 text-xs font-black uppercase tracking-widest text-slate-600 transition-all hover:border-lokak-brand hover:bg-white hover:text-lokak-brand active:scale-95 italic">
                            <Check v-if="isCopied" class="h-4 w-4 text-emerald-500" />
                            <Share2 v-else class="h-4 w-4" /> {{ isCopied ? 'Tautan Disalin!' : 'Bagikan Profil' }}
                        </button>
                    </div>
                </div>
            </div>
        </main>
        
        <Footer />
        
        <Teleport to="body">
            <Transition name="fade">
                <div v-if="showSuccessPopup" class="fixed bottom-10 left-1/2 -translate-x-1/2 z-100 bg-slate-900 text-white px-8 py-4 rounded-full shadow-2xl flex items-center gap-3">
                    <CheckCircle2 class="h-5 w-5 text-emerald-400" />
                    <span class="text-xs font-black italic uppercase tracking-widest">Ulasan berhasil dikirim!</span>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translate(-50%, 20px) scale(0.95); }
</style>