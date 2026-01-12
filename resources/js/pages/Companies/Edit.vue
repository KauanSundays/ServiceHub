<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    company: {
        id: number;
        name: string;
    };
}>();

const form = useForm({
    name: props.company.name,
});

const submit = () => {
    form.put(`/companies/${props.company.id}`);
};
</script>

<template>
    <Head title="Editar Empresa" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Empresa</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg max-w-md">
                    <form @submit.prevent="submit">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Nome da Empresa</label>
                            <input
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                :class="{ 'border-red-500': form.errors.name }"
                            >
                            <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50"
                            >
                                Atualizar
                            </button>

                            <button
                                type="button"
                                @click="window.history.back()"
                                class="text-sm text-gray-600 hover:underline"
                            >
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
