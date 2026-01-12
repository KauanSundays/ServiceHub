<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

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
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Novo Projeto</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl sm:rounded-3xl border border-gray-100 dark:border-gray-700">
                    <div class="p-8 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/20">
                        <div>
                            <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">Configurações do Projeto</h3>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Insira os detalhes fundamentais</p>
                        </div>
                        <Link href="/projects" class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-indigo-600 transition">Voltar</Link>
                    </div>

                    <form @submit.prevent="submit" class="p-8 space-y-6">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Nome do Projeto</label>
                                <input v-model="form.name" type="text" placeholder="Ex: Redesign E-commerce" :class="['w-full px-5 py-4 bg-gray-50 dark:bg-gray-900/50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all dark:text-white', form.errors.name ? 'ring-2 ring-red-500' : '']" />
                                <div v-if="form.errors.name" class="text-red-500 text-[10px] font-black uppercase mt-2 ml-1">{{ form.errors.name }}</div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Empresa Cliente</label>
                                <select v-model="form.company_id" :class="['w-full px-5 py-4 bg-gray-50 dark:bg-gray-900/50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all dark:text-white appearance-none', form.errors.company_id ? 'ring-2 ring-red-500' : '']">
                                    <option value="" disabled>Selecione uma empresa</option>
                                    <option v-for="company in companies" :key="company.id" :value="company.id">{{ company.name }}</option>
                                </select>
                                <div v-if="form.errors.company_id" class="text-red-500 text-[10px] font-black uppercase mt-2 ml-1">{{ form.errors.company_id }}</div>
                            </div>
                        </div>

                        <div class="pt-6 border-t dark:border-gray-700 flex justify-end">
                            <button type="submit" :disabled="form.processing" class="px-10 py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-500/20 disabled:opacity-50">
                                Criar Projeto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
