<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Novo Ticket</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg max-w-md">
                    <form @submit.prevent="submit">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Título do Problema</label>
                            <input v-model="form.title" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                            <div v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</div>
                        </div>

                        <div class="mt-4">
                            <label class="block font-medium text-sm text-gray-700">Projeto Relacionado</label>
                            <select v-model="form.project_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Selecione um projeto</option>
                                <option v-for="project in projects" :key="project.id" :value="project.id">
                                    {{ project.name }}
                                </option>
                            </select>
                            <div v-if="form.errors.project_id" class="text-red-500 text-xs mt-1">{{ form.errors.project_id }}</div>
                        </div>

                        <div class="mt-4">
                            <label class="block font-medium text-sm text-gray-700">Anexo (Opcional)</label>
                            <input
                                type="file"
                                @input="form.attachment = ($event.target as HTMLInputElement).files?.[0] || null"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                            />
                            <div v-if="form.errors.attachment" class="text-red-500 text-xs mt-1">{{ form.errors.attachment }}</div>
                            <progress v-if="form.progress" :value="form.progress.percentage" max="100" class="w-full h-2 mt-2">
                                {{ form.progress.percentage }}%
                            </progress>
                        </div>

                        <div class="mt-6">
                            <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded-md">
                                Abrir Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
