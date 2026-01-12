<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

defineProps<{
    projects: { id: number; name: string }[];
}>();

const form = useForm({
    title: '',
    project_id: '',
    attachment: null as File | null,
});

const submit = () => {
    form.post('/tickets', {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Novo Ticket" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Novo Ticket</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl shadow-gray-200/50 dark:shadow-none sm:rounded-3xl border border-gray-100 dark:border-gray-700">
                    <div class="p-8">
                        <div class="mb-8">
                            <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">Abertura de Chamado</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Preencha os detalhes abaixo para registrar seu ticket.</p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Título do Problema</label>
                                <input
                                    v-model="form.title"
                                    type="text"
                                    placeholder="Ex: Erro ao processar pagamento"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900/50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 dark:text-white transition-all"
                                />
                                <div v-if="form.errors.title" class="text-red-500 text-[11px] mt-2 font-bold uppercase">{{ form.errors.title }}</div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Projeto Relacionado</label>
                                <select
                                    v-model="form.project_id"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900/50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 dark:text-white transition-all"
                                >
                                    <option value="">Selecione um projeto</option>
                                    <option v-for="project in projects" :key="project.id" :value="project.id">
                                        {{ project.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.project_id" class="text-red-500 text-[11px] mt-2 font-bold uppercase">{{ form.errors.project_id }}</div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Anexo (Opcional)</label>
                                <div class="relative">
                                    <input
                                        type="file"
                                        @input="form.attachment = ($event.target as HTMLInputElement).files?.[0] || null"
                                        class="w-full text-xs text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition-all cursor-pointer bg-gray-50 dark:bg-gray-900/50 rounded-xl pr-4"
                                    />
                                </div>
                                <div v-if="form.errors.attachment" class="text-red-500 text-[11px] mt-2 font-bold uppercase">{{ form.errors.attachment }}</div>
                            </div>

                            <div class="pt-6 border-t dark:border-gray-700 flex items-center justify-between">
                                <Link href="/tickets" class="text-xs font-bold text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors uppercase tracking-widest">
                                    Cancelar
                                </Link>

                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-3 bg-gray-900 dark:bg-indigo-600 text-white rounded-2xl hover:bg-black dark:hover:bg-indigo-700 transition-all font-bold text-xs uppercase tracking-[0.2em] shadow-xl hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50"
                                >
                                    Abrir Ticket
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
