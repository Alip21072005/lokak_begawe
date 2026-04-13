<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { email } from '@/routes/password';

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
    <Head title="Lupa Kata Sandi" />

    <div
        class="flex min-h-screen w-full items-center justify-center bg-slate-50 p-4 sm:p-6"
    >
        <div
            class="w-full max-w-xl rounded-[2.5rem] bg-white p-8 shadow-[0_20px_50px_rgba(0,0,0,0.05)] sm:p-12"
        >
            <div class="mb-10 flex flex-col items-center text-center">
                <h2
                    class="mb-2 text-sm font-bold tracking-[0.2em] text-slate-400 uppercase"
                >
                    Pemulihan Akun
                </h2>
                <h1
                    class="text-3xl leading-tight font-black tracking-tight text-slate-900 uppercase italic"
                >
                    Lupa <span class="text-sky-700">Sandi?</span>
                </h1>
                <p
                    class="mt-2 text-xs font-medium tracking-wide text-slate-500 uppercase"
                >
                    Jangan khawatir, masukkan email Anda untuk menerima tautan
                    pemulihan
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
                    class="mb-6 rounded-xl border border-green-100 bg-green-50 p-4 text-center text-sm font-semibold text-green-700"
                >
                    {{ status }}
                </div>
            </Transition>

            <Form
                v-bind="email.form()"
                v-slot="{ errors, processing }"
                class="space-y-5"
            >
                <div class="grid gap-5">
                    <div class="grid gap-2">
                        <Label
                            for="email"
                            class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                        >
                            Alamat Email
                        </Label>
                        <Input
                            id="email"
                            type="email"
                            name="email"
                            autocomplete="off"
                            autofocus
                            placeholder="contoh@email.com"
                            class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 text-sm font-medium shadow-sm transition-all placeholder:text-slate-400 focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        />
                        <InputError :message="errors.email" />
                    </div>

                    <div class="pt-4">
                        <Button
                            type="submit"
                            class="h-14 w-full rounded-2xl bg-sky-700 font-bold tracking-[0.15em] text-white uppercase shadow-lg shadow-sky-700/20 transition-all hover:bg-sky-800 hover:shadow-sky-800/30 active:scale-[0.98]"
                            :disabled="processing"
                            data-test="email-password-reset-link-button"
                        >
                            <Spinner
                                v-if="processing"
                                class="mr-2 h-5 w-5 text-white"
                            />
                            <span v-if="!processing">Kirim Tautan Reset</span>
                            <span v-else>Memproses...</span>
                        </Button>
                    </div>
                </div>
            </Form>

            <div class="space-y-5 pt-8 text-center">
                <div class="relative py-2">
                    <div class="absolute inset-0 flex items-center">
                        <span class="w-full border-t border-slate-200"></span>
                    </div>
                    <div
                        class="relative flex justify-center text-[10px] uppercase"
                    >
                        <span
                            class="bg-white px-3 font-bold tracking-widest text-slate-400"
                            >Atau</span
                        >
                    </div>
                </div>

                <p
                    class="text-xs font-semibold tracking-wide text-slate-500 uppercase"
                >
                    Kembali ke halaman
                    <TextLink
                        :href="login()"
                        class="ml-1 text-sky-700 transition-all hover:text-sky-800 hover:underline hover:underline-offset-4"
                    >
                        Masuk
                    </TextLink>
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Menghilangkan ring fokus default browser */
input:focus {
    outline: none !important;
}

/* Memastikan placeholder memiliki warna yang konsisten */
input::placeholder {
    font-weight: 500;
    opacity: 0.6;
}
</style>
