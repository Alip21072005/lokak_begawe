<!-- eslint-disable @typescript-eslint/no-unused-vars -->
<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
// eslint-disable-next-line @typescript-eslint/no-unused-vars
import { AlertTriangle, Trash2, X, ShieldAlert } from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { useTemplateRef, ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const passwordInput = useTemplateRef('passwordInput');
const isDialogOpen = ref(false);

const form = useForm({
    password: '',
});

const deleteUser = () => {
    form.delete((window as any).route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            isDialogOpen.value = false;
            Swal.fire({
                title: 'AKUN DIHAPUS',
                text: 'Selamat tinggal, semoga kita bertemu lagi.',
                icon: 'success',
                confirmButtonColor: '#e11d48',
            });
        },
        onError: () =>
            (passwordInput.value as any)?.$el?.focus?.() ||
            (passwordInput.value as any)?.focus?.(),
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <div class="space-y-6">
        <div
            class="rounded-[2.5rem] border border-rose-100 bg-rose-50/30 p-8 transition-all hover:bg-rose-50/50"
        >
            <div
                class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between"
            >
                <div class="flex items-start gap-4">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-600 shadow-sm"
                    >
                        <AlertTriangle class="h-6 w-6" />
                    </div>
                    <div class="space-y-1">
                        <h3
                            class="text-sm font-black tracking-tight text-slate-900 uppercase italic"
                        >
                            HAPUS
                            <span class="text-rose-600">AKUN PERMANEN</span>
                        </h3>
                        <p
                            class="text-[10px] leading-relaxed font-bold text-slate-400 uppercase italic"
                        >
                            Setelah akun dihapus, semua data lowongan dan profil
                            mitra <br class="hidden md:block" />
                            akan hilang selamanya dan tidak bisa dipulihkan.
                        </p>
                    </div>
                </div>

                <Dialog v-model:open="isDialogOpen">
                    <DialogTrigger as-child>
                        <Button
                            variant="destructive"
                            class="h-14 rounded-2xl bg-rose-600 px-8 text-xs font-black tracking-widest uppercase italic shadow-xl shadow-rose-900/20 transition-all hover:scale-105 hover:bg-rose-700 active:scale-95"
                        >
                            <Trash2 class="mr-2 h-4 w-4" />
                            Hapus Akun Saya
                        </Button>
                    </DialogTrigger>

                    <DialogContent
                        class="max-w-md overflow-hidden rounded-[3rem] border-none bg-white p-0 shadow-2xl"
                    >
                        <div class="bg-rose-600 p-8 text-white">
                            <div
                                class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20"
                            >
                                <ShieldAlert class="h-6 w-6 text-white" />
                            </div>
                            <DialogTitle
                                class="text-2xl font-black tracking-tighter uppercase italic"
                            >
                                KONFIRMASI PENGHAPUSAN
                            </DialogTitle>
                            <DialogDescription
                                class="mt-2 text-xs leading-relaxed font-bold text-rose-100 uppercase italic"
                            >
                                Masukkan kata sandi Anda untuk memverifikasi
                                bahwa Anda ingin menghapus akun ini secara
                                permanen.
                            </DialogDescription>
                        </div>

                        <form
                            @submit.prevent="deleteUser"
                            class="space-y-6 p-8"
                        >
                            <div class="space-y-3">
                                <Label
                                    for="password"
                                    class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase"
                                    >Kata Sandi Anda</Label
                                >
                                <Input
                                    id="password"
                                    type="password"
                                    name="password"
                                    ref="passwordInput"
                                    v-model="form.password"
                                    placeholder="••••••••"
                                    class="h-14 rounded-2xl border-slate-200 bg-slate-50 px-6 font-bold focus:ring-2 focus:ring-rose-600/20"
                                />
                                <InputError :message="form.errors.password" />
                            </div>

                            <div
                                class="flex flex-col gap-3 sm:flex-row sm:justify-end"
                            >
                                <DialogClose as-child>
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        class="h-14 rounded-2xl text-xs font-black text-slate-400 uppercase italic hover:bg-slate-50"
                                        @click="form.reset()"
                                    >
                                        BATAL
                                    </Button>
                                </DialogClose>

                                <Button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="h-14 rounded-2xl bg-slate-900 px-8 text-xs font-black text-white uppercase italic transition-all hover:bg-black disabled:opacity-50"
                                >
                                    {{
                                        form.processing
                                            ? 'MEMPROSES...'
                                            : 'YA, HAPUS AKUN'
                                    }}
                                </Button>
                            </div>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Animasi getar dikit kalau ada error (opsional) */
.text-rose-600 {
    -webkit-font-smoothing: antialiased;
}
</style>
