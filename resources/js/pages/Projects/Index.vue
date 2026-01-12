<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface Project {
    id: number;
    name: string;
    company: { name: string };
}

const props = defineProps<{
    projects: { data: Project[]; links: any[] };
    companies: { id: number; name: string }[];
    filters: { search?: string; companies?: number[] };
}>();

const showFilters = ref(false);
const showDeleteModal = ref(false);
const projectToDelete = ref<number | null>(null);
const showSuccessToast = ref(false);

const search = ref(props.filters?.search || '');
const selectedCompanies = ref<number[]>(props.filters?.companies ? props.filters.companies.map(Number) : []);

const activeFiltersCount = computed(() => {
    return (search.value ? 1 : 0) + selectedCompanies.value.length;
});

const submitFilters = () => {
    router.get('/projects', {
        search: search.value,
        companies: selectedCompanies.value
    }, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
        onSuccess: () => showFilters.value = false
    });
};

const toggleItem = (list: any[], id: any) => {
    const index = list.indexOf(id);
    if (index > -1) list.splice(index, 1);
    else list.push(id);
};

const resetFilters = () => {
    search.value = '';
    selectedCompanies.value = [];
    submitFilters();
};

const confirmDelete = (id: number) => {
    projectToDelete.value = id;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    if (projectToDelete.value) {
        router.delete(`/projects/${projectToDelete.value}`, {
            onSuccess: () => {
                showDeleteModal.value = false;
                projectToDelete.value = null;
                showSuccessToast.value = true;
                setTimeout(() => showSuccessToast.value = false, 3000);
            },
            onFinish: () => showDeleteModal.value = false
        });
    }
};
</script>

<template>
    <Head title="Projetos" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Projetos</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between mb-8 relative items-center">
                    <div class="flex gap-4 items-center">
                        <button
                            @click="showFilters = !showFilters"
                            :class="[
                                'group flex items-center gap-2.5 px-5 py-2.5 rounded-xl border transition-all duration-300 text-sm font-bold shadow-sm',
                                showFilters
                                    ? 'bg-indigo-600 border-indigo-600 text-white shadow-indigo-200 dark:shadow-none'
                                    : 'bg-white border-gray-200 text-gray-700 hover:border-indigo-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300'
                            ]"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Filtros Avançados
                            <span v-if="activeFiltersCount > 0" :class="['ml-1 px-2 py-0.5 text-[10px] rounded-full', showFilters ? 'bg-white text-indigo-600' : 'bg-indigo-600 text-white']">
                                {{ activeFiltersCount }}
                            </span>
                        </button>

                        <Transition
                            enter-active-class="transition duration-300 ease-out"
                            enter-from-class="translate-y-4 opacity-0 scale-95"
                            enter-to-class="translate-y-0 opacity-100 scale-100"
                            leave-active-class="transition duration-200 ease-in"
                            leave-from-class="opacity-100 scale-100"
                            leave-to-class="opacity-0 scale-95"
                        >
                            <div v-if="showFilters" class="absolute top-16 left-0 z-50 w-[400px] bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
                                <div class="flex justify-between items-center mb-6">
                                    <h4 class="text-sm font-black uppercase tracking-widest text-gray-400">Filtrar Projetos</h4>
                                    <button @click="resetFilters" class="text-xs font-bold text-red-500 hover:text-red-600 transition">Limpar</button>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-tighter">Nome do Projeto</label>
                                        <input v-model="search" type="text" placeholder="Pesquise..." class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 dark:text-white transition-all"/>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-tighter">Empresas</label>
                                        <div class="max-h-48 overflow-y-auto space-y-1 pr-2 custom-scrollbar border border-gray-100 dark:border-gray-800 rounded-xl p-1">
                                            <div
                                                v-for="c in companies" :key="c.id"
                                                @click="toggleItem(selectedCompanies, c.id)"
                                                class="flex items-center justify-between px-3 py-2 rounded-lg cursor-pointer transition-colors"
                                                :class="selectedCompanies.includes(c.id) ? 'bg-indigo-50 dark:bg-indigo-900/30' : 'hover:bg-gray-100 dark:hover:bg-gray-800'"
                                            >
                                                <span class="text-sm" :class="selectedCompanies.includes(c.id) ? 'font-bold text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400'">{{ c.name }}</span>
                                                <svg v-if="selectedCompanies.includes(c.id)" class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <button
                                        @click="submitFilters"
                                        class="w-full py-3 bg-indigo-600 text-white rounded-xl font-black uppercase tracking-widest text-xs hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-500/20"
                                    >
                                        Aplicar Filtros
                                    </button>
                                </div>
                            </div>
                        </Transition>
                    </div>

                    <Link href="/projects/create" class="flex items-center gap-2 px-6 py-3 bg-gray-900 dark:bg-indigo-600 text-white rounded-2xl hover:bg-black dark:hover:bg-indigo-700 transition-all font-bold text-sm shadow-xl hover:-translate-y-0.5 active:translate-y-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Novo Projeto
                    </Link>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl shadow-gray-200/50 dark:shadow-none sm:rounded-3xl border border-gray-100 dark:border-gray-700">
                    <table class="min-w-full">
                        <thead class="bg-gray-50/50 dark:bg-gray-900/40 border-b border-gray-100 dark:border-gray-700">
                            <tr>
                                <th class="px-8 py-5 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Projeto</th>
                                <th class="px-8 py-5 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Empresa Responsável</th>
                                <th class="px-8 py-5 text-right text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                            <tr v-for="project in projects.data" :key="project.id" class="group hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition-all">
                                <td class="px-8 py-6">
                                    <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ project.name }}</div>
                                    <div class="text-[10px] text-gray-400 font-medium tracking-tighter">ID #{{ project.id }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ project.company?.name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end gap-2">
                                        <Link
                                            :href="`/projects/${project.id}/edit`"
                                            class="p-2 text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </Link>
                                        <button
                                            @click="confirmDelete(project.id)"
                                            class="p-2 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="projects.links.length > 3" class="px-8 py-6 bg-gray-50/30 dark:bg-gray-900/20 border-t dark:border-gray-700 flex justify-center gap-2">
                        <template v-for="(link, k) in projects.links" :key="k">
                            <Link
                                v-if="link.url" :href="link.url" v-html="link.label"
                                :class="['px-4 py-2 text-xs font-bold rounded-xl transition-all', link.active ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200 dark:shadow-none' : 'bg-white dark:bg-gray-800 text-gray-500 border border-gray-100 dark:border-gray-700 hover:border-indigo-300']"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showDeleteModal" class="relative z-[100]">
            <Transition
                enter-active-class="ease-out duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100"
                leave-active-class="ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0"
            >
                <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"></div>
            </Transition>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <Transition
                        enter-active-class="ease-out duration-300" enter-from-class="opacity-0 translate-y-4 sm:scale-95" enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                        leave-active-class="ease-in duration-200" leave-from-class="opacity-100 translate-y-0 sm:scale-100" leave-to-class="opacity-0 translate-y-4 sm:scale-95"
                    >
                        <div class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-gray-900 text-left shadow-2xl transition-all sm:w-full sm:max-w-lg border border-gray-100 dark:border-gray-800">
                            <div class="p-8">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.268 15.19c-.77 1.333.192 3 1.732 3z" /></svg>
                                    </div>
                                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                        <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">Excluir Projeto</h3>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Esta ação removerá o projeto permanentemente. Deseja continuar?</p>
                                    </div>
                                </div>
                                <div class="mt-8 sm:flex sm:flex-row-reverse gap-3">
                                    <button @click="executeDelete" type="button" class="w-full inline-flex justify-center rounded-2xl bg-red-600 px-6 py-3 text-xs font-black uppercase tracking-widest text-white shadow-lg shadow-red-500/20 hover:bg-red-700 transition-all sm:w-auto">Sim, Excluir</button>
                                    <button @click="showDeleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-6 py-3 text-xs font-black uppercase tracking-widest text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all sm:mt-0 sm:w-auto">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </div>

        <Transition
            enter-active-class="transform ease-out duration-300 transition" enter-from-class="translate-y-2 opacity-0 sm:translate-x-2" enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0"
        >
            <div v-if="showSuccessToast" class="fixed bottom-5 right-5 z-[110]">
                <div class="bg-gray-900 dark:bg-indigo-600 text-white px-6 py-3 rounded-2xl shadow-2xl flex items-center gap-3 border border-white/10">
                    <div class="bg-white/20 rounded-full p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg></div>
                    <span class="text-sm font-bold">Projeto removido com sucesso!</span>
                </div>
            </div>
        </Transition>
    </AppLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
</style>
