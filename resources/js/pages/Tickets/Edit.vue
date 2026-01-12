<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Ticket #{{ ticket.id }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg max-w-md">
                    <form @submit.prevent="submit">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Título</label>
                            <input v-model="form.title" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                            <div v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</div>
                        </div>

                        <div class="mt-4">
                            <label class="block font-medium text-sm text-gray-700">Projeto</label>
                            <select v-model="form.project_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option v-for="project in projects" :key="project.id" :value="project.id">
                                    {{ project.name }}
                                </option>
                            </select>
                            <div v-if="form.errors.project_id" class="text-red-500 text-xs mt-1">{{ form.errors.project_id }}</div>
                        </div>

                        <div class="mt-4">
                            <label class="block font-medium text-sm text-gray-700">Status</label>
                            <select v-model="form.status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="pending">Pendente</option>
                                <option value="processing">Em Processamento</option>
                                <option value="completed">Concluído</option>
                            </select>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded-md">
                                Salvar Alterações
                            </button>
                            <button type="button" @click="window.history.back()" class="text-sm text-gray-600">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
