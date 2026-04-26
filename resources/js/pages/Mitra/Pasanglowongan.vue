<!-- eslint-disable @typescript-eslint/no-unused-vars -->
<script setup lang="ts">
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import {
    Eye, MapPin, X, Wrench, Save, Info, Search, 
    FileText, Edit3, Trash2, Clock, AlertCircle,
    CheckCircle2, Banknote, GraduationCap, Briefcase
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { computed, ref } from 'vue';
import { route } from 'ziggy-js';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    auth: any;
    lokasis: any[];
    myJobs: any[];
    masterSkills: any[];
}>();

defineOptions({ layout: AppLayout });

// --- STATE MANAGEMENT ---
const isEditing = ref(false);
const editId = ref<string | null>(null);
const skillSearch = ref('');
const isPreviewModalOpen = ref(false);
const selectedJob = ref<any>(null);

const form = useForm({
    judul_lowongan: '',
    tipe_pekerjaan: 'Full-time',
    gaji_min: '',
    gaji_max: '',
    lokasi_id: '',
    deskripsi_lowongan: '',
    minimal_pendidikan: '',
    minimal_pengalaman: 0,
    required_skills: [] as string[],
});

// --- LOGIKA SKILL ---
const filteredSkills = computed(() => {
    return props.masterSkills.filter(skill => 
        skill.nama_skill.toLowerCase().includes(skillSearch.value.toLowerCase()) &&
        !form.required_skills.includes(skill.id)
    );
});

const getSkillName = (id: string) => {
    return props.masterSkills.find(s => s.id === id)?.nama_skill || 'Unknown';
};

const toggleSkill = (skillId: string) => {
    const index = form.required_skills.indexOf(skillId);

    if (index === -1) {
form.required_skills.push(skillId);
} else {
form.required_skills.splice(index, 1);
}
};

// --- LOGIKA DETAIL / PREVIEW ---
const handleViewDetail = (job: any) => {
    if (job.status_lowongan === 'verified') {
        // Jika sudah verified, arahkan ke halaman detail publik
        router.visit(route('detail.lowongan', job.id));
    } else {
        // Jika pending/rejected, tampilkan modal pratinjau internal
        selectedJob.value = job;
        isPreviewModalOpen.value = true;
    }
};

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);
};

// --- CRUD ACTIONS ---
const submit = () => {
    const routeFunc = (window as any).route;

    if (isEditing.value && editId.value) {
        form.put(routeFunc('mitra.pasanglowongan.update', editId.value), {
            onSuccess: () => {
                resetForm();
                Swal.fire({ 
                    title: 'Berhasil!', 
                    text: 'Lowongan telah diperbarui dan sedang dalam antrean verifikasi.', 
                    icon: 'success', 
                    timer: 2000, 
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-[2rem]' }
                });
            },
        });
    } else {
        form.post(routeFunc('mitra.pasanglowongan.store'), {
            onSuccess: () => {
                resetForm();
                Swal.fire({ 
                    title: 'Sukses Terbit!', 
                    text: 'Lowongan Anda berhasil dibuat. Mohon tunggu verifikasi admin.', 
                    icon: 'success', 
                    timer: 2000, 
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-[2rem]' }
                });
            },
        });
    }
};

const editJob = (job: any) => {
    isEditing.value = true;
    editId.value = job.id;
    form.judul_lowongan = job.judul_lowongan;
    form.tipe_pekerjaan = job.tipe_pekerjaan;
    form.gaji_min = job.gaji_min;
    form.gaji_max = job.gaji_max;
    form.lokasi_id = job.lokasi_id;
    form.deskripsi_lowongan = job.deskripsi_lowongan;
    form.minimal_pendidikan = job.minimal_pendidikan || '';
    form.minimal_pengalaman = job.minimal_pengalaman || 0;
    form.required_skills = job.skills ? job.skills.map((s: any) => s.id) : [];
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const deleteJob = (id: string) => {
    Swal.fire({
        title: 'Hapus Lowongan?',
        text: "Tindakan ini tidak dapat dibatalkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0369a1',
        cancelButtonColor: '#f43f5e',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-[2rem]' }
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete((window as any).route('mitra.pasanglowongan.destroy', id), {
                onSuccess: () => Swal.fire({ title: 'Terhapus!', icon: 'success', timer: 1000, showConfirmButton: false })
            });
        }
    });
};

const resetForm = () => {
    form.reset();
    isEditing.value = false;
    editId.value = null;
};

const formattedSalaryPreview = computed(() => {
    if (!form.gaji_min && !form.gaji_max) {
return 'Rp -';
}

    const min = form.gaji_min ? new Intl.NumberFormat('id-ID').format(Number(form.gaji_min)) : '0';
    const max = form.gaji_max ? new Intl.NumberFormat('id-ID').format(Number(form.gaji_max)) : '0';

    return `Rp ${min} - ${max}`;
});
</script>

<template>
    <Head title="Pasang Lowongan - Lokak Begawe" />

    <div class="space-y-16 p-6 lg:p-10">
        <div class="flex flex-col gap-2">
            <h1 class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl">
                {{ isEditing ? 'REVISI' : 'PASANG' }} <span class="text-sky-700">LOWONGAN</span>
            </h1>
            <div class="h-1.5 w-24 rounded-full bg-slate-200"></div>
        </div>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
            <div class="space-y-10 lg:col-span-8">
                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <h3 class="mb-8 flex items-center gap-3 text-[11px] font-black uppercase italic text-slate-400 tracking-widest">
                        <Info class="h-4 w-4 text-sky-700" /> Informasi Utama
                    </h3>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Judul Lowongan</label>
                            <input v-model="form.judul_lowongan" type="text" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20" placeholder="Senior Backend Developer" />
                        </div>
                        <div class="space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Tipe Kontrak</label>
                            <select v-model="form.tipe_pekerjaan" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none">
                                <option>Full-time</option>
                                <option>Part-time</option>
                                <option>Internship</option>
                                <option>Freelance</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Gaji Minimum</label>
                            <input v-model="form.gaji_min" type="number" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none" placeholder="3000000" />
                        </div>
                        <div class="space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Gaji Maksimum</label>
                            <input v-model="form.gaji_max" type="number" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none" placeholder="7000000" />
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Wilayah Bengkulu</label>
                            <select v-model="form.lokasi_id" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none">
                                <option value="" disabled>Pilih Kabupaten/Kota...</option>
                                <option v-for="loc in lokasis" :key="loc.id" :value="loc.id">{{ loc.nama_lokasi }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <h3 class="mb-8 flex items-center gap-3 text-[11px] font-black uppercase italic text-slate-400 tracking-widest">
                        <Wrench class="h-4 w-4 text-sky-700" /> Persyaratan Kualifikasi
                    </h3>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mb-8">
                        <div class="space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Pendidikan Minimal</label>
                            <select v-model="form.minimal_pendidikan" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none">
                                <option value="">Semua Jenjang</option>
                                <option value="SMA/SMK">SMA/SMK Sederajat</option>
                                <option value="D3">Diploma (D3)</option>
                                <option value="S1">Sarjana (S1)</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Pengalaman (Tahun)</label>
                            <input v-model="form.minimal_pengalaman" type="number" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none" placeholder="0" />
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="relative">
                            <Search class="absolute left-4 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" />
                            <input v-model="skillSearch" type="text" placeholder="Cari keahlian spesifik..." class="h-10 w-full rounded-xl border-none bg-slate-100 pl-10 pr-4 text-[11px] font-bold outline-none italic" />
                        </div>
                        <div class="flex flex-wrap gap-2 min-h-10">
                            <span v-for="sId in form.required_skills" :key="sId" class="flex items-center gap-2 rounded-lg bg-slate-900 px-3 py-1.5 text-[9px] font-black text-white uppercase italic">
                                {{ getSkillName(sId) }}
                                <button @click="toggleSkill(sId)" class="hover:text-rose-400 transition-colors"><X class="h-3 w-3" /></button>
                            </span>
                        </div>
                        <div class="relative">
                            <div class="max-h-32 overflow-y-auto rounded-2xl border border-slate-100 p-4 scrollbar-hide flex flex-wrap gap-2">
                                <button v-for="skill in filteredSkills" :key="skill.id" @click="toggleSkill(skill.id)" type="button" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[9px] font-black uppercase text-slate-500 hover:border-sky-600 hover:text-sky-700 transition-all">
                                    + {{ skill.nama_skill }}
                                </button>
                            </div>
                            <div class="pointer-events-none absolute bottom-0 left-0 h-10 w-full rounded-b-2xl bg-linear-to-t from-white to-transparent"></div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <h3 class="mb-6 flex items-center gap-3 text-[11px] font-black uppercase italic text-slate-400 tracking-widest">
                        <FileText class="h-4 w-4 text-sky-700" /> Deskripsi Pekerjaan
                    </h3>
                    <textarea v-model="form.deskripsi_lowongan" rows="6" class="w-full rounded-3xl border-slate-200 bg-slate-50 p-6 text-xs font-bold outline-none italic focus:ring-2 focus:ring-sky-700/20" placeholder="Jelaskan detail pekerjaan, benefit, dan budaya kerja..."></textarea>
                    
                    <div class="mt-8 flex justify-end gap-4">
                        <button v-if="isEditing" @click="resetForm" class="rounded-2xl bg-slate-50 px-8 py-4 text-xs font-black uppercase text-slate-400 hover:bg-slate-100 italic">Batalkan</button>
                        <button @click="submit" :disabled="form.processing" class="flex items-center gap-3 rounded-2xl bg-sky-700 px-10 py-5 text-[11px] font-black text-white uppercase italic shadow-xl shadow-sky-900/20 transition-all hover:bg-sky-800 disabled:opacity-50">
                            <Save class="h-4 w-4" /> {{ isEditing ? 'Simpan Perubahan' : 'Terbitkan Sekarang' }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="sticky top-10 space-y-6">
                    <div class="rounded-[3rem] bg-slate-900 p-8 text-white shadow-2xl relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-sky-500/10 blur-2xl"></div>
                        <div class="mb-8 flex items-center gap-2 text-[10px] font-black uppercase italic text-sky-400 tracking-widest">
                            <Eye class="h-4 w-4" /> LIVE PREVIEW
                        </div>
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-xl font-black uppercase italic leading-tight tracking-tighter">{{ form.judul_lowongan || 'JUDUL POSISI' }}</h4>
                                <p class="mt-1 text-[10px] font-bold text-slate-400 uppercase italic">
                                    <MapPin class="inline h-3 w-3 mr-1" /> {{ props.lokasis.find(l => l.id === form.lokasi_id)?.nama_lokasi || 'Bengkulu' }}
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-sky-700 px-3 py-1 text-[8px] font-black uppercase italic">{{ form.tipe_pekerjaan }}</span>
                                <span class="bg-slate-800 px-3 py-1 text-[8px] font-black uppercase italic text-slate-400 border border-slate-700">{{ form.minimal_pendidikan || 'Pendidikan Bebas' }}</span>
                            </div>
                            <div class="mt-6 border-t border-dashed border-slate-700 pt-6 text-center">
                                <p class="text-[13px] font-black text-emerald-400 italic tracking-tighter">{{ formattedSalaryPreview }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-10 pt-10 border-t border-slate-100">
            <h2 class="text-2xl font-black tracking-tighter text-slate-900 uppercase italic">RIWAYAT <span class="text-sky-700">PUBLIKASI</span></h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div v-for="job in myJobs" :key="job.id" 
                    class="group flex flex-col justify-between rounded-[2.5rem] border border-slate-200 bg-white p-8 transition-all hover:border-sky-300 hover:shadow-xl">
                    <div class="space-y-4">
                        <div class="flex items-start justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 text-xl font-black text-sky-700 italic border border-slate-100">
                                {{ job.judul_lowongan.charAt(0) }}
                            </div>
                            <span :class="{
                                'bg-emerald-50 text-emerald-600 border-emerald-100': job.status_lowongan === 'verified',
                                'bg-amber-50 text-amber-600 border-amber-100': job.status_lowongan === 'pending',
                                'bg-rose-50 text-rose-600 border-rose-100': job.status_lowongan === 'rejected'
                            }" class="rounded-full border px-3 py-1 text-[8px] font-black uppercase italic">
                                {{ job.status_lowongan }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-lg font-black uppercase italic leading-tight text-slate-900 group-hover:text-sky-700">{{ job.judul_lowongan }}</h4>
                            <div class="mt-2 flex items-center gap-3 text-[9px] font-bold text-slate-400 uppercase italic">
                                <span><MapPin class="inline h-2.5 w-2.5" /> {{ job.lokasi?.nama_lokasi }}</span>
                                <span><Clock class="inline h-2.5 w-2.5" /> {{ job.tipe_pekerjaan }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center gap-2 border-t border-slate-50 pt-6">
                        <button 
                            @click="handleViewDetail(job)"
                            class="flex-1 rounded-xl bg-slate-900 py-3 text-[10px] font-black text-white uppercase italic transition-all hover:bg-sky-700 flex items-center justify-center gap-2"
                        >
                            <Eye class="h-3.5 w-3.5" /> Lihat Detail
                        </button>
                        <div class="flex gap-2">
                            <button @click="editJob(job)" class="rounded-xl border border-slate-100 bg-white p-3 text-slate-400 transition-all hover:border-amber-200 hover:text-amber-600">
                                <Edit3 class="h-4 w-4" />
                            </button>
                            <button @click="deleteJob(job.id)" class="rounded-xl border border-slate-100 bg-white p-3 text-slate-400 transition-all hover:border-rose-200 hover:text-rose-600">
                                <Trash2 class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Teleport to="body">
            <Transition name="fade">
                <div v-if="isPreviewModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
                    <div class="w-full max-w-2xl rounded-[3.5rem] bg-white overflow-hidden shadow-2xl relative animate-in zoom-in-95 duration-300">
                        <div class="bg-slate-900 p-10 text-white relative">
                            <button @click="isPreviewModalOpen = false" class="absolute top-8 right-8 text-slate-500 hover:text-white"><X class="h-6 w-6" /></button>
                            <div class="flex items-center gap-4 mb-4">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-700 text-2xl font-black italic">
                                    {{ selectedJob?.judul_lowongan.charAt(0) }}
                                </div>
                                <div class="px-3 py-1 rounded-full border border-amber-500/50 bg-amber-500/10 text-[9px] font-black text-amber-500 uppercase italic">
                                    Pratinjau Internal
                                </div>
                            </div>
                            <h3 class="text-2xl font-black uppercase italic leading-none tracking-tighter">{{ selectedJob?.judul_lowongan }}</h3>
                            <p class="mt-2 text-[10px] font-bold text-slate-400 uppercase italic">{{ selectedJob?.tipe_pekerjaan }} • {{ selectedJob?.lokasi?.nama_lokasi }}</p>
                        </div>

                        <div class="p-10 space-y-8 max-h-[50vh] overflow-y-auto scrollbar-hide">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-5 rounded-3xl bg-slate-50 border border-slate-100">
                                    <p class="text-[9px] font-black text-slate-400 uppercase italic mb-1">Persyaratan</p>
                                    <div class="flex items-center gap-2 text-xs font-black text-slate-700 uppercase italic">
                                        <GraduationCap class="h-4 w-4 text-sky-700" /> {{ selectedJob?.minimal_pendidikan || 'Umum' }}
                                    </div>
                                </div>
                                <div class="p-5 rounded-3xl bg-slate-50 border border-slate-100">
                                    <p class="text-[9px] font-black text-slate-400 uppercase italic mb-1">Pengalaman</p>
                                    <div class="flex items-center gap-2 text-xs font-black text-slate-700 uppercase italic">
                                        <Briefcase class="h-4 w-4 text-sky-700" /> {{ selectedJob?.minimal_pengalaman }} Tahun
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h5 class="text-[11px] font-black text-slate-900 uppercase italic">Keahlian Yang Dibutuhkan</h5>
                                <div class="flex flex-wrap gap-2">
                                    <span v-for="skill in selectedJob?.skills" :key="skill.id" class="px-4 py-2 rounded-xl bg-slate-900 text-[9px] font-black text-white uppercase italic">
                                        # {{ skill.nama_skill }}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h5 class="text-[11px] font-black text-slate-900 uppercase italic">Deskripsi Tugas</h5>
                                <p class="text-xs font-medium text-slate-500 italic leading-relaxed whitespace-pre-line">{{ selectedJob?.deskripsi_lowongan }}</p>
                            </div>
                        </div>

                        <div class="p-8 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <Banknote class="h-5 w-5 text-emerald-500" />
                                <span class="text-sm font-black text-emerald-600 italic">{{ formatCurrency(selectedJob?.gaji_min) }} - {{ formatCurrency(selectedJob?.gaji_max) }}</span>
                            </div>
                            <button @click="isPreviewModalOpen = false" class="px-8 py-3 rounded-xl bg-slate-900 text-white text-[10px] font-black uppercase italic">Tutup Pratinjau</button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<style scoped>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

.fade-enter-active, .fade-leave-active { transition: opacity 0.4s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>