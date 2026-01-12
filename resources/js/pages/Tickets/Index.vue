<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Ticket {
    id: number;
    title: string;
    status: string;
    project?: {
        name: string;
        company?: { name: string }
    };
    user?: { name: string };
}

defineProps<{ tickets: Ticket[] }>();

const statusMap: Record<string, { label: string; color: string }> = {
    pending: { label: 'Pendente', color: 'bg-yellow-100 text-yellow-800' },
    processing: { label: 'Em Processamento', color: 'bg-blue-100 text-blue-800' },
    completed: { label: 'Concluído', color: 'bg-green-100 text-green-800' },
};

const deleteTicket = (id: number) => {
    if (confirm('Deseja realmente excluir este ticket?')) {
        router.delete(`/tickets/${id}`);
    }
};
</script>

<template>
    <Head title="Tickets" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tickets</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Gerenciar Tickets</h3>
                    <Link href="/tickets/create" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Novo Ticket
                    </Link>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Projeto/Empresa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="ticket in tickets" :key="ticket.id">
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                    {{ ticket.title }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ ticket.project?.name || 'Sem Projeto' }}
                                    <span v-if="ticket.project?.company">
                                        ({{ ticket.project.company.name }})
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span v-if="statusMap[ticket.status]"
                                          :class="['px-2.5 py-0.5 rounded-full text-xs font-medium', statusMap[ticket.status].color]">
                                        {{ statusMap[ticket.status].label }}
                                    </span>
                                    <span v-else class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ticket.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link :href="`/tickets/${ticket.id}/edit`" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Editar
                                    </Link>
                                    <button @click="deleteTicket(ticket.id)" class="text-red-600 hover:text-red-900">
                                        Excluir
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
