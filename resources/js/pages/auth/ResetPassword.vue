<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { update } from '@/routes/password';

defineOptions({
    layout: {
        title: 'Buat Sandi Baru',
        description: 'Silakan masukkan kata sandi baru Anda di bawah ini',
    },
});

const props = defineProps<{
    token: string;
    email: string;
}>();

const inputEmail = ref(props.email);
</script>

<template>
    <Head title="Buat Sandi Baru" />

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
                    Buat Sandi <span class="text-sky-700">Baru</span>
                </h1>
                <p
                    class="mt-2 text-xs font-medium tracking-wide text-slate-500 uppercase"
                >
                    Amankan kembali akun Lokak Begawe Anda
                </p>
            </div>

            <Form
                v-bind="update.form()"
                :transform="(data) => ({ ...data, token, email })"
                :reset-on-success="['password', 'password_confirmation']"
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
                            autocomplete="email"
                            v-model="inputEmail"
                            readonly
                            class="h-12 cursor-not-allowed rounded-xl border-slate-200 bg-slate-100 px-4 text-sm font-medium text-slate-500 shadow-none focus-visible:ring-0"
                        />
                        <InputError :message="errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <Label
                            for="password"
                            class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                        >
                            Kata Sandi Baru
                        </Label>
                        <PasswordInput
                            id="password"
                            name="password"
                            autocomplete="new-password"
                            autofocus
                            placeholder="••••••••"
                            class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 text-sm font-medium shadow-sm transition-all placeholder:text-slate-400 focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        />
                        <InputError :message="errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Label
                            for="password_confirmation"
                            class="text-xs font-bold tracking-wider text-slate-700 uppercase"
                        >
                            Konfirmasi Sandi Baru
                        </Label>
                        <PasswordInput
                            id="password_confirmation"
                            name="password_confirmation"
                            autocomplete="new-password"
                            placeholder="••••••••"
                            class="h-12 rounded-xl border-slate-200 bg-slate-50 px-4 text-sm font-medium shadow-sm transition-all placeholder:text-slate-400 focus:border-sky-600 focus:bg-white focus:ring-1 focus:ring-sky-600"
                        />
                        <InputError :message="errors.password_confirmation" />
                    </div>

                    <div class="pt-4">
                        <Button
                            type="submit"
                            class="h-14 w-full rounded-2xl bg-sky-700 font-bold tracking-[0.15em] text-white uppercase shadow-lg shadow-sky-700/20 transition-all hover:bg-sky-800 hover:shadow-sky-800/30 active:scale-[0.98]"
                            :disabled="processing"
                            data-test="reset-password-button"
                        >
                            <Spinner
                                v-if="processing"
                                class="mr-2 h-5 w-5 text-white"
                            />
                            <span v-if="!processing">SIMPAN SANDI BARU</span>
                            <span v-else>MEMPROSES...</span>
                        </Button>
                    </div>
                </div>
            </Form>
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
