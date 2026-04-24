<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import {
    Eye, MapPin, X, 
    Wrench, 
    Save, Info, Search, FileText
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
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
const skillSearch = ref(''); // State untuk pencarian skill

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

const submit = () => {
    const routeFunc = (window as any).route;

    if (isEditing.value && editId.value) {
        form.put(routeFunc('mitra.pasanglowongan.update', editId.value), {
            onSuccess: () => resetForm(),
        });
    } else {
        form.post(routeFunc('mitra.pasanglowongan.store'), {
            onSuccess: () => resetForm(),
        });
    }
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

    <div class="space-y-12 p-6 lg:p-10">
        <div class="flex flex-col gap-2 text-center md:text-left">
            <h1 class="text-3xl font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl">
                {{ isEditing ? 'EDIT' : 'PASANG' }} <span class="text-sky-700">LOWONGAN</span>
            </h1>
            <div class="mx-auto h-1.5 w-24 rounded-full bg-slate-200 md:mx-0"></div>
        </div>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
            <div class="space-y-10 lg:col-span-8">
                
                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <h3 class="mb-8 flex items-center gap-3 text-[11px] font-black uppercase italic text-slate-400 tracking-widest">
                        <Info class="h-4 w-4 text-sky-700" /> Informasi Pekerjaan
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Judul Posisi</label>
                            <input v-model="form.judul_lowongan" type="text" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-sky-700/20" placeholder="Contoh: Senior UI Designer" />
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
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Gaji Minimum (Bulan)</label>
                            <input v-model="form.gaji_min" type="number" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none" placeholder="3000000" />
                        </div>
                        <div class="space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Gaji Maksimum (Bulan)</label>
                            <input v-model="form.gaji_max" type="number" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none" placeholder="7000000" />
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Wilayah Penempatan</label>
                            <select v-model="form.lokasi_id" class="h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-xs font-bold outline-none">
                                <option value="" disabled>Pilih Kabupaten/Kota...</option>
                                <option v-for="loc in lokasis" :key="loc.id" :value="loc.id">{{ loc.nama_lokasi }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <h3 class="mb-8 flex items-center gap-3 text-[11px] font-black uppercase italic text-slate-400 tracking-widest">
                        <Wrench class="h-4 w-4 text-sky-700" /> Kualifikasi Pelamar
                    </h3>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mb-10">
                        <div class="space-y-2">
                            <label class="ml-2 text-[10px] font-black uppercase italic text-slate-400">Minimal Pendidikan</label>
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
                        <div class="flex items-center justify-between px-2">
                            <label class="text-[10px] font-black uppercase italic text-slate-400">Pilih Keahlian Spesifik</label>
                            <span class="text-[9px] font-bold text-sky-700 bg-sky-50 px-2 py-0.5 rounded-lg uppercase italic">{{ form.required_skills.length }} Terpilih</span>
                        </div>

                        <div class="relative">
                            <Search class="absolute left-4 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" />
                            <input v-model="skillSearch" type="text" placeholder="Cari skill (contoh: Photoshop)..." class="h-10 w-full rounded-xl border-none bg-slate-100 pl-10 pr-4 text-[11px] font-bold outline-none focus:ring-2 focus:ring-sky-700/20 italic" />
                        </div>
                        
                        <div class="flex flex-wrap gap-2 min-h-11 p-2">
                            <TransitionGroup name="list">
                                <span v-for="sId in form.required_skills" :key="sId" class="flex items-center gap-2 rounded-lg bg-slate-900 px-3 py-1.5 text-[10px] font-black text-white uppercase italic">
                                    {{ getSkillName(sId) }}
                                    <button @click="toggleSkill(sId)" class="hover:text-rose-400"><X class="h-3 w-3" /></button>
                                </span>
                            </TransitionGroup>
                            <p v-if="form.required_skills.length === 0" class="text-[10px] font-bold text-slate-300 italic py-2 px-2">Belum ada skill yang dipilih.</p>
                        </div>

                        <div class="relative">
                            <div class="max-h-40 overflow-y-auto rounded-2xl border border-slate-100 p-4 scrollbar-hide">
                                <div class="flex flex-wrap gap-2">
                                    <button 
                                        v-for="skill in filteredSkills" :key="skill.id"
                                        @click="toggleSkill(skill.id)"
                                        type="button"
                                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[9px] font-black uppercase italic text-slate-500 transition-all hover:border-sky-600 hover:text-sky-700"
                                    >
                                        + {{ skill.nama_skill }}
                                    </button>
                                    <p v-if="filteredSkills.length === 0" class="w-full text-center text-[10px] font-bold text-slate-300 italic py-4">Skill tidak ditemukan...</p>
                                </div>
                                <div class="h-8 w-full"></div>
                            </div>
                            <div class="pointer-events-none absolute bottom-0 left-0 h-12 w-full rounded-b-2xl bg-linear-to-t from-white to-transparent"></div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm">
                    <h3 class="mb-6 flex items-center gap-3 text-[11px] font-black uppercase italic text-slate-400 tracking-widest">
                        <FileText class="h-4 w-4 text-sky-700" /> Deskripsi Tambahan
                    </h3>
                    <textarea v-model="form.deskripsi_lowongan" rows="6" class="w-full rounded-3xl border-slate-200 bg-slate-50 p-6 text-xs font-bold outline-none italic focus:ring-2 focus:ring-sky-700/20" placeholder="Tuliskan detail pekerjaan..."></textarea>
                    
                    <div class="mt-8 flex justify-end gap-4">
                        <button v-if="isEditing" @click="resetForm" class="rounded-2xl bg-slate-50 px-8 py-4 text-xs font-black uppercase italic text-slate-400 transition-all hover:bg-slate-100">Batal</button>
                        <button @click="submit" :disabled="form.processing" class="flex items-center gap-3 rounded-2xl bg-sky-700 px-10 py-5 text-[11px] font-black text-white uppercase italic shadow-xl shadow-sky-900/20 transition-all hover:bg-sky-800 active:scale-95">
                            <Save class="h-4 w-4" /> {{ isEditing ? 'Simpan Perubahan' : 'Publish Lowongan' }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="sticky top-10 space-y-6">
                    <div class="rounded-[3rem] bg-slate-900 p-8 text-white shadow-2xl overflow-hidden relative">
                        <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-sky-500/10 blur-2xl"></div>
                        
                        <div class="mb-8 flex items-center gap-2 text-[10px] font-black uppercase italic text-sky-400 tracking-[0.2em]">
                            <Eye class="h-4 w-4" /> LIVE PREVIEW
                        </div>
                        
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-xl font-black uppercase italic leading-tight tracking-tighter">{{ form.judul_lowongan || 'JUDUL POSISI' }}</h4>
                                <p class="mt-1 text-[10px] font-bold text-slate-400 uppercase italic">
                                    <MapPin class="inline h-3 w-3 mr-1" /> {{ props.lokasis.find(l => l.id === form.lokasi_id)?.nama_lokasi || 'Wilayah' }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <span class="bg-sky-700 px-3 py-1 text-[8px] font-black uppercase italic">{{ form.tipe_pekerjaan }}</span>
                                <span class="bg-slate-800 border border-slate-700 px-3 py-1 text-[8px] font-black uppercase italic text-slate-400">{{ form.minimal_pendidikan || 'Pendidikan Bebas' }}</span>
                            </div>
                            
                            <div class="space-y-2 border-t border-slate-800 pt-6">
                                <div class="flex flex-wrap gap-1">
                                    <span v-for="sId in form.required_skills" :key="sId" class="text-[9px] font-black uppercase italic text-sky-400 mr-2">
                                        #{{ getSkillName(sId) }}
                                    </span>
                                    <p v-if="form.required_skills.length === 0" class="text-[9px] font-bold text-slate-600 italic">Belum ada syarat skill...</p>
                                </div>
                            </div>

                            <div class="mt-6 border-t border-dashed border-slate-800 pt-6 text-center">
                                <p class="text-[13px] font-black text-emerald-400 italic tracking-tighter">{{ formattedSalaryPreview }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

.list-enter-active, .list-leave-active { transition: all 0.3s ease; }
.list-enter-from, .list-leave-to { opacity: 0; transform: translateY(10px); }
</style>