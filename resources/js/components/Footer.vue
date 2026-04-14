<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const suggestionForm = useForm({
    email: '',
    message: '',
});

const submitSuggestion = () => {
    suggestionForm.reset();
    alert('Saran berhasil dikirim!');
};
</script>
<template>
    <footer class="bg-lokak-footer px-11 py-16 text-white">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 lg:grid-cols-12">
            <div class="lg:col-span-3">
                <div class="mb-6 flex items-center gap-3">
                    <div
                        class="flex h-12 w-12 items-center justify-center bg-slate-800 font-black text-white italic shadow-inner"
                    >
                        <img src="/Logo.png" alt="" class="rounded-xl" />
                    </div>
                    <span class="text-xl font-black tracking-tighter uppercase"
                        >LOKAKBEGAWE</span
                    >
                </div>
                <p class="text-xs font-semibold uppercase italic opacity-60">
                    Ado Lokak, Pela Begawe - Portal Lowongan Kerja Bengkulu
                </p>
            </div>

            <div
                class="flex flex-col gap-4 text-xs font-semibold uppercase italic lg:col-span-3"
            >
                <span class="text-[10px] font-black text-white opacity-40"
                    >TENTANG KAMI</span
                >
                <a
                    href="#"
                    class="transition hover:text-sky-400 hover:underline"
                    >Tentang Kami</a
                >
                <a
                    href="#"
                    class="transition hover:text-sky-400 hover:underline"
                    >Logo & Brand</a
                >
            </div>

            <div
                class="flex flex-col gap-4 text-xs font-semibold uppercase italic lg:col-span-3"
            >
                <span class="text-[10px] font-black text-white opacity-40"
                    >HUBUNGI KAMI</span
                >
                <a
                    href="#"
                    class="transition hover:text-sky-400 hover:underline"
                    >Instagram</a
                >
                <a
                    href="#"
                    class="transition hover:text-sky-400 hover:underline"
                    >WhatsApp</a
                >
                <a
                    href="#"
                    class="transition hover:text-sky-400 hover:underline"
                    >Email Support</a
                >
            </div>

            <div
                class="rounded-2rem border border-slate-700 bg-slate-800 p-8 shadow-inner lg:col-span-3"
            >
                <h4 class="mb-5 text-sm font-black text-white uppercase italic">
                    BERIKAN SARAN
                </h4>
                <form @submit.prevent="submitSuggestion" class="space-y-3">
                    <input
                        v-model="suggestionForm.email"
                        type="email"
                        name="email"
                        required
                        placeholder="Email kamu (wajib)"
                        class="w-full rounded-xl border border-slate-700 bg-slate-700 p-3.5 text-xs text-white transition outline-none placeholder:text-slate-400 focus:border-lokak-brand focus:bg-slate-600"
                    />
                    <textarea
                        v-model="suggestionForm.message"
                        name="message"
                        required
                        placeholder="Pesan Kamu (wajib)"
                        rows="2"
                        class="w-full resize-none rounded-xl border border-slate-700 bg-slate-700 p-3.5 text-xs text-white transition outline-none placeholder:text-slate-400 focus:border-lokak-brand focus:bg-slate-600"
                    ></textarea>
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-white py-3 text-xs font-black text-lokak-text uppercase italic shadow-lg shadow-white/10 transition-all hover:bg-slate-100 hover:text-lokak-brand active:scale-[0.98]"
                    >
                        Kirim Saran
                    </button>
                </form>
            </div>
        </div>

        <div
            class="mx-auto mt-20 max-w-7xl border-t border-white/10 pt-8 text-center text-[9px] font-black tracking-[0.3em] uppercase opacity-40"
        >
            © 2026 LOKAK BEGAWE - HAK CIPTA DILINDUNGI UNDANG-UNDANG
        </div>
    </footer>
</template>
