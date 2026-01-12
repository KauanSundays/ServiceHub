<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps<{
    companies: { id: number; name: string }[];
}>();

const form = useForm({
    name: '',
    company_id: '',
});

const submit = () => {
    form.post('/projects');
};
</script>

<template>
    <Head title="Novo Projeto" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Novo Projeto</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg max-w-md">
                    <form @submit.prevent="submit">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Nome do Projeto</label>
                            <input v-model="form.name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                            <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                        </div>

                        <div class="mt-4">
                            <label class="block font-medium text-sm text-gray-700">Empresa</label>
                            <select v-model="form.company_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Selecione uma empresa</option>
                                <option v-for="company in companies" :key="company.id" :value="company.id">
                                    {{ company.name }}
                                </option>
                            </select>
                            <div v-if="form.errors.company_id" class="text-red-500 text-xs mt-1">{{ form.errors.company_id }}</div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded-md">
                                Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
