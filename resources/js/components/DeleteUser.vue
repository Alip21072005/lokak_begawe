<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { AlertTriangle, Trash2, ShieldAlert, Eye, EyeOff } from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { useTemplateRef, ref, computed, watch } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

// Mengambil data user yang sedang login
const page = usePage();
const user = computed(() => page.props.auth.user as any);

const passwordInput = useTemplateRef('passwordInput');
const isDialogOpen = ref(false);
const showPassword = ref(false); // State untuk toggle mata password

// State untuk Konfirmasi GitHub-style
const confirmationInput = ref('');
const expectedConfirmation = computed(() => `hapus/${user.value?.email}`);
const isMatch = computed(() => confirmationInput.value === expectedConfirmation.value);

const form = useForm({
    password: '',
});

// Reset form dan input saat dialog ditutup (batal)
watch(isDialogOpen, (isOpen) => {
    if (!isOpen) {
        form.reset();
        form.clearErrors();
        confirmationInput.value = '';
        showPassword.value = false; // Kembalikan mata ke tertutup
    }
});

const deleteUser = () => {
    // Mencegah eksekusi jika ketikan belum cocok
    if (!isMatch.value) return;

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
        <div class="flex flex-col rounded-[2.5rem] border border-rose-100 bg-rose-50/30 p-6 md:p-8 transition-all hover:bg-rose-50/50">
            
            <div class="mb-4 flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-600 shadow-sm">
                    <AlertTriangle class="h-6 w-6" />
                </div>
                <div class="space-y-1">
                    <h3 class="text-sm font-black tracking-tight text-slate-900 uppercase italic">
                        HAPUS <span class="text-rose-600">AKUN</span>
                    </h3>
                    <p class="text-[10px] font-bold text-rose-500 uppercase italic tracking-widest">
                        Tindakan Permanen
                    </p>
                </div>
            </div>

            <p class="mb-6 text-[10px] leading-relaxed font-bold text-slate-400 uppercase italic">
                Setelah akun dihapus, semua data lowongan, profil, dan pengaturan Anda akan hilang selamanya dan tidak bisa dipulihkan kembali.
            </p>

            <Dialog v-model:open="isDialogOpen">
                <DialogTrigger as-child>
                    <Button
                        variant="destructive"
                        class="h-14 w-full rounded-2xl bg-rose-600 px-8 text-xs font-black tracking-widest uppercase italic shadow-xl shadow-rose-900/20 transition-all hover:scale-105 hover:bg-rose-700 active:scale-95"
                    >
                        <Trash2 class="mr-2 h-4 w-4" />
                        Hapus Akun Saya
                    </Button>
                </DialogTrigger>

                <DialogContent class="max-w-md overflow-hidden rounded-[3rem] border-none bg-white p-0 shadow-2xl">
                    <div class="bg-rose-600 p-8 text-white">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20">
                            <ShieldAlert class="h-6 w-6 text-white" />
                        </div>
                        <DialogTitle class="text-2xl font-black tracking-tighter uppercase italic">
                            KONFIRMASI HAPUS
                        </DialogTitle>
                        <DialogDescription class="mt-2 text-xs leading-relaxed font-bold text-rose-100 uppercase italic">
                            Ketik konfirmasi persis seperti yang diminta dan masukkan kata sandi Anda.
                        </DialogDescription>
                    </div>

                    <form @submit.prevent="deleteUser" class="space-y-6 p-8">
                        
                        <div class="space-y-3">
                            <Label class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                Ketik <span class="select-all rounded bg-rose-100 px-1.5 py-0.5 font-mono text-rose-700 tracking-normal normal-case">{{ expectedConfirmation }}</span>
                            </Label>
                            <Input
                                type="text"
                                v-model="confirmationInput"
                                placeholder="Ketik konfirmasi di sini..."
                                class="h-14 rounded-2xl border-slate-200 bg-slate-50 px-6 font-mono text-sm font-bold focus:ring-2 focus:ring-rose-600/20"
                                autocomplete="off"
                            />
                        </div>

                        <div class="space-y-3 relative">
                            <Label for="password" class="ml-2 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                Kata Sandi Anda
                            </Label>
                            <div class="relative">
                                <Input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    name="password"
                                    ref="passwordInput"
                                    v-model="form.password"
                                    placeholder="••••••••"
                                    class="h-14 w-full rounded-2xl border-slate-200 bg-slate-50 pl-6 pr-14 font-bold focus:ring-2 focus:ring-rose-600/20"
                                />
                                <button 
                                    type="button" 
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-xl text-slate-400 transition-colors hover:bg-slate-200/50 hover:text-slate-600"
                                >
                                    <EyeOff v-if="!showPassword" class="h-4 w-4" />
                                    <Eye v-else class="h-4 w-4" />
                                </button>
                            </div>
                            <InputError :message="form.errors.password" />
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:justify-end pt-2">
                            <DialogClose as-child>
                                <Button
                                    type="button"
                                    variant="ghost"
                                    class="h-14 rounded-2xl text-xs font-black text-slate-400 uppercase italic hover:bg-slate-50"
                                >
                                    BATAL
                                </Button>
                            </DialogClose>

                            <Button
                                type="submit"
                                :disabled="form.processing || !isMatch"
                                :class="[
                                    'h-14 rounded-2xl px-8 text-xs font-black text-white uppercase italic transition-all',
                                    isMatch && !form.processing 
                                        ? 'bg-slate-900 hover:bg-black' 
                                        : 'bg-slate-300 cursor-not-allowed opacity-70'
                                ]"
                            >
                                {{ form.processing ? 'MEMPROSES...' : 'YA, HAPUS AKUN' }}
                            </Button>
                        </div>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    </div>
</template>

<style scoped>
.text-rose-600 {
    -webkit-font-smoothing: antialiased;
}
</style>