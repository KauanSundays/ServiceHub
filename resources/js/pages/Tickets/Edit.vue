<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';

const props = defineProps<{
    ticket: { id: number; title: string; status: string; project_id: number };
    projects: { id: number; name: string }[];
}>();

const form = useForm({
    title: props.ticket.title,
    project_id: props.ticket.project_id,
    status: props.ticket.status,
});

const submit = () => {
    form.put(`/tickets/${props.ticket.id}`);
};
</script>

<template>
    <Head title="Editar Ticket" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Editar Chamado
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl shadow-gray-200/50 dark:shadow-none sm:rounded-3xl border border-gray-100 dark:border-gray-700">
                    <div class="p-8">
                        <div class="mb-8 flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">Edição do Ticket #{{ ticket.id }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Atualize as informações necessárias deste registro.</p>
                            </div>
                            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 text-[10px] font-black text-gray-500 rounded-lg uppercase tracking-tighter">
                                Revisão Pendente
                            </span>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Título do Chamado</label>
                                <input
                                    v-model="form.title"
                                    type="text"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900/50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 dark:text-white transition-all"
                                />
                                <div v-if="form.errors.title" class="text-red-500 text-[11px] mt-2 font-bold uppercase">{{ form.errors.title }}</div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Vínculo de Projeto</label>
                                    <select
                                        v-model="form.project_id"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900/50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 dark:text-white transition-all"
                                    >
                                        <option v-for="project in projects" :key="project.id" :value="project.id">
                                            {{ project.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.project_id" class="text-red-500 text-[11px] mt-2 font-bold uppercase">{{ form.errors.project_id }}</div>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Status Atual</label>
                                    <select
                                        v-model="form.status"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900/50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 dark:text-white transition-all"
                                    >
                                        <option value="pending">Pendente</option>
                                        <option value="processing">Em Processamento</option>
                                        <option value="completed">Concluído</option>
                                    </select>
                                </div>
                            </div>

                            <div class="pt-6 border-t dark:border-gray-700 flex items-center justify-between">
                                <Link href="/tickets" class="text-xs font-bold text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors uppercase tracking-widest">
                                    Descartar Alterações
                                </Link>

                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-3 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700 transition-all font-bold text-xs uppercase tracking-[0.2em] shadow-xl shadow-indigo-500/20 hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50"
                                >
                                    Salvar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
