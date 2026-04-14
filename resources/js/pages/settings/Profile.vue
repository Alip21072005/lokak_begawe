<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import DeleteUser from '@/components/DeleteUser.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';

type Props = {
    mustVerifyEmail: boolean;
    status?: string;
};

defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Pengaturan Profil',
                href: edit(),
            },
        ],
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Pengaturan Profil" />

    <div class="space-y-8 p-6 lg:p-10">
        <div class="flex flex-col items-start">
            <h1
                class="text-3xl font-black tracking-tighter text-lokak-text uppercase italic md:text-4xl"
            >
                PENGATURAN <span class="text-lokak-brand">PROFIL</span>
            </h1>
            <div class="mt-2 h-1.5 w-24 rounded-full bg-slate-200"></div>
            <p
                class="mt-4 text-[11px] font-bold tracking-widest text-lokak-text-muted uppercase italic"
            >
                Perbarui informasi akun dan alamat email Anda di sini.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="lg:col-span-8">
                <div
                    class="rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm"
                >
                    <div class="mb-8">
                        <h2
                            class="text-lg font-black tracking-tight text-lokak-text uppercase italic"
                        >
                            Informasi
                            <span class="text-lokak-brand">Pribadi</span>
                        </h2>
                    </div>

                    <Form
                        v-bind="ProfileController.update.form()"
                        class="space-y-6"
                        v-slot="{ errors, processing }"
                    >
                        <div class="grid gap-2">
                            <Label
                                for="name"
                                class="text-[10px] font-black tracking-widest text-lokak-text-muted uppercase"
                                >Nama Lengkap</Label
                            >
                            <Input
                                id="name"
                                class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 text-sm font-medium transition-all focus:border-lokak-brand focus:bg-white focus:ring-1 focus:ring-lokak-brand"
                                name="name"
                                :default-value="user.name"
                                required
                                autocomplete="name"
                                placeholder="Masukkan nama lengkap"
                            />
                            <InputError :message="errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label
                                for="email"
                                class="text-[10px] font-black tracking-widest text-lokak-text-muted uppercase"
                                >Alamat Email</Label
                            >
                            <Input
                                id="email"
                                type="email"
                                class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 text-sm font-medium transition-all focus:border-lokak-brand focus:bg-white focus:ring-1 focus:ring-lokak-brand"
                                name="email"
                                :default-value="user.email"
                                required
                                autocomplete="username"
                                placeholder="nama@email.com"
                            />
                            <InputError :message="errors.email" />
                        </div>

                        <div
                            v-if="mustVerifyEmail && !user.email_verified_at"
                            class="rounded-xl border border-amber-100 bg-amber-50 p-4"
                        >
                            <p class="text-xs font-bold text-amber-700">
                                Alamat email Anda belum terverifikasi.
                                <Link
                                    :href="send()"
                                    as="button"
                                    class="ml-1 text-lokak-brand underline decoration-sky-200 underline-offset-4 transition-colors hover:text-lokak-brand-dark"
                                >
                                    Klik di sini untuk mengirim ulang email
                                    verifikasi.
                                </Link>
                            </p>

                            <div
                                v-if="status === 'verification-link-sent'"
                                class="mt-2 text-xs font-black text-emerald-600 uppercase italic"
                            >
                                Link verifikasi baru telah dikirim ke email
                                Anda.
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <Button
                                :disabled="processing"
                                class="rounded-xl bg-lokak-brand px-8 py-6 text-xs font-black tracking-widest text-white uppercase italic shadow-lg shadow-sky-900/20 transition-all hover:bg-lokak-brand-dark active:scale-95"
                            >
                                Simpan Perubahan
                            </Button>
                        </div>
                    </Form>
                </div>
            </div>

            <div class="space-y-6 lg:col-span-4">
                <div
                    class="rounded-[2.5rem] bg-slate-900 p-8 text-white shadow-xl"
                >
                    <div
                        class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-white/10"
                    >
                        <span class="text-xl">🔒</span>
                    </div>
                    <h3
                        class="text-sm font-black tracking-tighter uppercase italic"
                    >
                        Keamanan Akun
                    </h3>
                    <p
                        class="mt-2 text-[10px] leading-relaxed font-medium uppercase italic opacity-60"
                    >
                        Pastikan email Anda aktif untuk menerima notifikasi
                        lowongan kerja dan panggilan interview terbaru.
                    </p>
                </div>

                <div
                    class="rounded-[2.5rem] border border-rose-100 bg-rose-50/30 p-2"
                >
                    <DeleteUser />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
input:focus {
    outline: none !important;
}
</style>
