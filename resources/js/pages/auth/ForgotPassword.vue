<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { Mail, ArrowLeft } from 'lucide-vue-next'; // Tambah ikon biar lebih interaktif
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { email as routeEmail } from '@/routes/password';

// --- DEFINISI RUTE (TS SAFE) ---
const routeLogin = login();
const routeEmailForm = routeEmail.form();

defineOptions({
    layout: {
        title: 'Lupa Kata Sandi',
        description:
            'Masukkan email Anda untuk menerima tautan pemulihan kata sandi',
    },
});

defineProps<{
    status?: string;
}>();
</script>

<template>
    <Head title="Pemulihan Akun - Lokak Begawe" />

    <div
        class="flex min-h-screen w-full items-center justify-center bg-slate-50 p-4 py-10 text-slate-900 sm:p-6"
    >
        <div
            class="w-full max-w-xl rounded-[2.5rem] border border-slate-100/50 bg-white p-8 shadow-[0_20px_50px_rgba(0,0,0,0.05)] sm:p-12"
        >
            <div class="mb-10 flex flex-col items-center text-center">
                <div
                    class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-50 text-sky-700 shadow-sm"
                >
                    <Mail class="h-6 w-6" />
                </div>
                <h2
                    class="mb-2 text-[10px] font-black tracking-[0.3em] text-slate-300 uppercase italic"
                >
                    PEMULIHAN AKSES
                </h2>
                <h1
                    class="text-3xl leading-tight font-black tracking-tighter text-slate-900 uppercase italic md:text-4xl"
                >
                    LUPA <span class="text-sky-700">SANDI?</span>
                </h1>
                <div class="mt-4 h-1.5 w-24 rounded-full bg-slate-100"></div>
                <p
                    class="mt-4 text-[10px] leading-relaxed font-bold tracking-widest text-slate-400 uppercase italic"
                >
                    Jangan khawatir, masukkan email Anda <br />
                    untuk menerima tautan pemulihan otomatis.
                </p>
            </div>

            <Transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="transform opacity-0 -translate-y-2"
                enter-to-class="transform opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="transform opacity-100 translate-y-0"
                leave-to-class="transform opacity-0 -translate-y-2"
            >
                <div
                    v-if="status"
                    class="mb-8 rounded-2xl border border-emerald-100 bg-emerald-50 px-6 py-4 text-center text-[11px] font-black tracking-widest text-emerald-700 uppercase italic"
                >
                    {{ status }}
                </div>
            </Transition>

            <Form
                v-bind="routeEmailForm"
                v-slot="{ errors, processing }"
                class="space-y-6"
            >
                <div class="space-y-6">
                    <div class="space-y-2">
                        <Label
                            for="email"
                            class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase italic"
                            >Alamat Email Terdaftar</Label
                        >
                        <Input
                            id="email"
                            type="email"
                            name="email"
                            autocomplete="off"
                            required
                            autofocus
                            placeholder="nama@email.com"
                            class="h-12 rounded-xl border-slate-200 bg-slate-50 px-5 font-bold shadow-sm transition-all focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        />
                        <InputError :message="errors.email" />
                    </div>

                    <div class="pt-4">
                        <Button
                            type="submit"
                            :disabled="processing"
                            class="h-14 w-full rounded-2xl bg-sky-700 text-[11px] font-black tracking-[0.2em] text-white uppercase italic shadow-xl shadow-sky-900/20 transition-all hover:scale-[1.02] hover:bg-sky-800 active:scale-[0.98] disabled:opacity-50"
                        >
                            <Spinner v-if="processing" class="mr-3 h-4 w-4" />
                            {{
                                processing
                                    ? 'MENGIRIM TAUTAN...'
                                    : 'KIRIM TAUTAN RESET'
                            }}
                        </Button>
                    </div>
                </div>
            </Form>

            <div
                class="mt-10 flex flex-col items-center space-y-6 border-t border-slate-50 pt-10 text-center"
            >
                <div class="relative w-full">
                    <div class="absolute inset-0 flex items-center">
                        <span class="w-full border-t border-slate-100"></span>
                    </div>
                    <div
                        class="relative flex justify-center text-[10px] uppercase"
                    >
                        <span
                            class="bg-white px-6 font-black tracking-[0.3em] text-slate-300 italic"
                        >
                            KEMBALI KE GERBANG
                        </span>
                    </div>
                </div>

                <Link
                    :href="routeLogin"
                    class="group flex items-center gap-2 text-[10px] font-black tracking-widest text-slate-500 uppercase italic transition-all hover:text-sky-700"
                >
                    <ArrowLeft
                        class="h-3 w-3 transition-transform group-hover:-translate-x-1"
                    />
                    Ingat Kata Sandi? Masuk Disini
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
input:focus {
    outline: none !important;
}

input::placeholder {
    font-weight: 700;
    font-style: italic;
    text-transform: uppercase;
    font-size: 10px;
    letter-spacing: 0.1em;
    opacity: 0.3;
}
</style>
