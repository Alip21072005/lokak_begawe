<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import {
    ArrowLeft,
    MapPin,
    Mail,
    Phone,
    Globe,
    FileText,
    Download,
    MessageCircle,
    Briefcase,
    GraduationCap,
    Calendar,
    User,
    CheckCircle2,
    ExternalLink,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps<{
    pelamar: any;
}>();

const applicantName = () => props.pelamar?.nama_pelamar || props.pelamar?.user?.name || 'Pelamar';
const applicantEmail = () => props.pelamar?.email_pelamar || props.pelamar?.user?.email || '-';
const applicantPhone = () => props.pelamar?.nohp_pelamar || '-';
const applicantLocation = () => props.pelamar?.lokasi?.nama_lokasi || 'Lokasi belum diatur';
const applicantBio = () => props.pelamar?.bio || 'Belum ada ringkasan profil.';
const applicantPortfolio = () => props.pelamar?.website_portfolio || null;
const applicantPhoto = () => props.pelamar?.foto_pelamar || null;
const applicantCv = () => props.pelamar?.cv_pelamar || null;

const formatDate = (date?: string | null) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('id-ID', {
        month: 'short',
        year: 'numeric',
    });
};

const toWhatsappLink = (phone?: string) => {
    if (!phone) return null;
    const normalized = phone.replace(/\D/g, '').replace(/^0/, '62');
    return `https://wa.me/${normalized}`;
};
</script>

<template>
    <Head :title="`${applicantName()} - Detail Pelamar`" />

    <div class="min-h-screen bg-slate-50 pb-10">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <!-- Top navigation -->
            <div class="mb-6">
                <Link
                    :href="route('admin.kelolapelamar')"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-100"
                >
                    <ArrowLeft class="h-4 w-4" />
                    Kembali ke Kelola Pelamar
                </Link>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
                <!-- Left column -->
                <aside class="space-y-6 lg:col-span-4">
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="mb-5 flex items-start gap-4">
                            <div class="h-20 w-20 overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                                <img
                                    v-if="applicantPhoto()"
                                    :src="`/storage/${applicantPhoto()}`"
                                    alt="Foto pelamar"
                                    class="h-full w-full object-cover"
                                />
                                <div v-else class="flex h-full w-full items-center justify-center text-slate-400">
                                    <User class="h-8 w-8" />
                                </div>
                            </div>

                            <div class="min-w-0">
                                <h1 class="truncate text-lg font-semibold text-slate-900">
                                    {{ applicantName() }}
                                </h1>
                                <p class="mt-1 flex items-center gap-1 text-sm text-slate-600">
                                    <MapPin class="h-4 w-4 text-slate-400" />
                                    {{ applicantLocation() }}
                                </p>
                                <div class="mt-2 inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-medium text-emerald-700">
                                    <CheckCircle2 class="h-3.5 w-3.5" />
                                    Profil Aktif
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3 border-t border-slate-100 pt-4">
                            <div class="flex items-start gap-2 text-sm text-slate-700">
                                <Mail class="mt-0.5 h-4 w-4 text-slate-400" />
                                <span class="break-all">{{ applicantEmail() }}</span>
                            </div>
                            <div class="flex items-start gap-2 text-sm text-slate-700">
                                <Phone class="mt-0.5 h-4 w-4 text-slate-400" />
                                <span>{{ applicantPhone() }}</span>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h2 class="mb-4 text-sm font-semibold text-slate-900">Dokumen & Tautan</h2>

                        <div class="space-y-3">
                            <a
                                v-if="applicantCv()"
                                :href="`/storage/${applicantCv()}`"
                                target="_blank"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-black"
                            >
                                <Download class="h-4 w-4" />
                                Lihat CV
                            </a>
                            <div
                                v-else
                                class="rounded-lg border border-dashed border-slate-300 px-4 py-3 text-center text-xs text-slate-500"
                            >
                                CV belum diunggah
                            </div>

                            <a
                                v-if="applicantPortfolio()"
                                :href="applicantPortfolio()"
                                target="_blank"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-sky-200 bg-sky-50 px-4 py-2.5 text-sm font-medium text-sky-700 transition hover:bg-sky-100"
                            >
                                <Globe class="h-4 w-4" />
                                Buka Portofolio
                                <ExternalLink class="h-4 w-4" />
                            </a>
                            <div
                                v-else
                                class="rounded-lg border border-dashed border-slate-300 px-4 py-3 text-center text-xs text-slate-500"
                            >
                                Portofolio belum tersedia
                            </div>

                            <a
                                v-if="toWhatsappLink(props.pelamar?.nohp_pelamar)"
                                :href="toWhatsappLink(props.pelamar?.nohp_pelamar)!"
                                target="_blank"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-emerald-600"
                            >
                                <MessageCircle class="h-4 w-4" />
                                Hubungi via WhatsApp
                            </a>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h2 class="mb-4 text-sm font-semibold text-slate-900">Keahlian</h2>
                        <div class="flex flex-wrap gap-2">
                            <template v-if="props.pelamar?.skills?.length">
                                <span
                                    v-for="skill in props.pelamar.skills"
                                    :key="skill.id"
                                    class="rounded-md border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-700"
                                >
                                    {{ skill.master_skill?.nama_skill || skill.nama_skill || '-' }}
                                </span>
                            </template>
                            <p v-else class="text-xs text-slate-500">Belum ada data keahlian.</p>
                        </div>
                    </section>
                </aside>

                <!-- Right column -->
                <section class="space-y-6 lg:col-span-8">
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <h2 class="mb-3 text-base font-semibold text-slate-900">Ringkasan Profil</h2>
                        <p class="whitespace-pre-line text-sm leading-relaxed text-slate-700">
                            {{ applicantBio() }}
                        </p>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="mb-4 flex items-center gap-2">
                            <Briefcase class="h-4 w-4 text-slate-500" />
                            <h2 class="text-base font-semibold text-slate-900">Pengalaman Kerja</h2>
                        </div>

                        <div v-if="props.pelamar?.pengalamans?.length" class="space-y-4">
                            <article
                                v-for="exp in props.pelamar.pengalamans"
                                :key="exp.id"
                                class="rounded-xl border border-slate-200 p-4 transition hover:border-slate-300"
                            >
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-900">
                                            {{ exp.posisi || 'Posisi belum diisi' }}
                                        </h3>
                                        <p class="text-sm text-slate-600">
                                            {{ exp.master_perusahaan?.nama_perusahaan || exp.nama_perusahaan || '-' }}
                                        </p>
                                    </div>
                                    <div class="inline-flex items-center gap-1 text-xs text-slate-500">
                                        <Calendar class="h-3.5 w-3.5" />
                                        {{ formatDate(exp.tgl_mulai) }} - {{ exp.is_current ? 'Sekarang' : formatDate(exp.tgl_selesai) }}
                                    </div>
                                </div>
                                <p class="mt-3 text-sm leading-relaxed text-slate-700">
                                    {{ exp.deskripsi || 'Tidak ada deskripsi pengalaman.' }}
                                </p>
                            </article>
                        </div>

                        <div
                            v-else
                            class="rounded-lg border border-dashed border-slate-300 px-4 py-6 text-center text-sm text-slate-500"
                        >
                            Belum ada data pengalaman kerja.
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="mb-4 flex items-center gap-2">
                            <GraduationCap class="h-4 w-4 text-slate-500" />
                            <h2 class="text-base font-semibold text-slate-900">Riwayat Pendidikan</h2>
                        </div>

                        <div v-if="props.pelamar?.pendidikans?.length" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <article
                                v-for="edu in props.pelamar.pendidikans"
                                :key="edu.id"
                                class="rounded-xl border border-slate-200 p-4 transition hover:border-slate-300"
                            >
                                <h3 class="text-sm font-semibold text-slate-900">
                                    {{ edu.master_instansi?.nama_instansi || edu.nama_instansi || '-' }}
                                </h3>
                                <p class="mt-1 text-sm text-slate-600">
                                    {{ edu.gelar || '-' }}
                                </p>
                                <p class="mt-3 inline-flex items-center gap-1 text-xs text-slate-500">
                                    <Calendar class="h-3.5 w-3.5" />
                                    Lulus: {{ formatDate(edu.tgl_lulus) }}
                                </p>
                            </article>
                        </div>

                        <div
                            v-else
                            class="rounded-lg border border-dashed border-slate-300 px-4 py-6 text-center text-sm text-slate-500"
                        >
                            Belum ada data pendidikan.
                        </div>
                    </section>
                </section>
            </div>
        </div>
    </div>
</template>