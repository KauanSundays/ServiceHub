<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Company {
    id: number;
    name: string;
}

defineProps<{
    companies: Company[];
}>();

const companyBeingDeleted = ref<number | null>(null);

const confirmDelete = (id: number) => {
    companyBeingDeleted.value = id;
};

const closeModal = () => {
    companyBeingDeleted.value = null;
};

const deleteCompany = () => {
    if (companyBeingDeleted.value) {
        router.delete(`/companies/${companyBeingDeleted.value}`, {
            onSuccess: () => closeModal(),
        });
    }
};
</script>

<template>
    <Head title="Empresas" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Empresas</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Gerenciar Empresas</h3>
                    <Link href="/companies/create" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Nova Empresa
                    </Link>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="company in companies" :key="company.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ company.name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link :href="`/companies/${company.id}/edit`" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Editar
                                    </Link>
                                    <button
                                        @click="confirmDelete(company.id)"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        Excluir
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="companyBeingDeleted" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-lg shadow-xl max-w-sm w-full p-6">
                <h3 class="text-lg font-bold text-gray-900">Confirmar Exclusão</h3>
                <p class="text-gray-600 mt-2">Tem certeza que deseja excluir esta empresa? Esta ação não pode ser desfeita.</p>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        @click="closeModal"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="deleteCompany"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700"
                    >
                        Confirmar Exclusão
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
