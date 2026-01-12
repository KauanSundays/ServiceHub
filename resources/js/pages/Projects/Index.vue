<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Project {
    id: number;
    name: string;
    company: {
        name: string;
    };
}

defineProps<{
    projects: Project[];
}>();

const projectBeingDeleted = ref<number | null>(null);

const confirmDelete = (id: number) => {
    projectBeingDeleted.value = id;
};

const deleteProject = () => {
    if (projectBeingDeleted.value) {
        router.delete(`/projects/${projectBeingDeleted.value}`, {
            onSuccess: () => projectBeingDeleted.value = null,
        });
    }
};
</script>

<template>
    <Head title="Projetos" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Projetos</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Gerenciar Projetos</h3>
                    <Link href="/projects/create" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Novo Projeto
                    </Link>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Projeto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Empresa</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="project in projects" :key="project.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ project.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ project.company?.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link :href="`/projects/${project.id}/edit`" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</Link>
                                    <button @click="confirmDelete(project.id)" class="text-red-600 hover:text-red-900">Excluir</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="projectBeingDeleted" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-lg shadow-xl max-w-sm w-full p-6">
                <h3 class="text-lg font-bold text-gray-900">Excluir Projeto</h3>
                <p class="text-gray-600 mt-2">Deseja realmente excluir este projeto?</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button @click="projectBeingDeleted = null" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md">Cancelar</button>
                    <button @click="deleteProject" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md">Confirmar</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
