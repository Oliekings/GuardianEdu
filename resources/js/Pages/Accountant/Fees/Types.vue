<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    types: any[];
}>();

const form = useForm({
    name: '',
    code: '',
    description: '',
});

const submit = () => {
    form.post(route('accountant.fees.types.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Fee Types" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Fee <span class="g-gradient-text">Types</span></h2>
            <p class="text-gray-500 text-sm mt-1 italic">Define types of fees (Tuition, Transport, Admission, Laboratory, etc.)</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Management Panel -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="g-card p-8 border-t-4 border-emerald-500/50">
                        <h3 class="text-lg font-bold text-white mb-6 uppercase tracking-widest text-[10px]">Create Fee Entity</h3>
                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <label class="g-label text-[10px]">Entity Name *</label>
                                <input v-model="form.name" class="g-input" placeholder="e.g. Tuition Fee" />
                                <p v-if="form.errors.name" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label class="g-label text-[10px]">Fee Code *</label>
                                <input v-model="form.code" class="g-input" placeholder="e.g. TF-101" />
                                <p v-if="form.errors.code" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.code }}</p>
                            </div>
                            <div>
                                <label class="g-label text-[10px]">Context / Notes</label>
                                <textarea v-model="form.description" class="g-input min-h-[100px]" placeholder="Explain why this fee exists..."></textarea>
                            </div>
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-700 transition shadow-xl shadow-emerald-500/10 active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Registering...' : 'Register Fee Type' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- List Panel -->
                <div class="lg:col-span-2">
                    <div class="g-card overflow-hidden">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-white/5 bg-white/[0.02]">
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500">Name</th>
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500 text-center">Code</th>
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500">Description</th>
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="type in types" :key="type.id" class="border-b border-white/5 hover:bg-white/[0.02] transition group">
                                    <td class="p-4">
                                        <div class="font-bold text-white uppercase tracking-tight">{{ type.name }}</div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="px-3 py-1 bg-white/5 border border-white/10 rounded text-[10px] font-mono text-emerald-400">{{ type.code }}</span>
                                    </td>
                                    <td class="p-4 text-xs text-gray-500 italic">{{ type.description || 'Generic fee component.' }}</td>
                                    <td class="p-4 text-right">
                                        <button class="opacity-0 group-hover:opacity-100 transition text-[10px] font-bold uppercase tracking-widest text-emerald-400 hover:text-white">Delete</button>
                                    </td>
                                </tr>
                                <tr v-if="!types.length">
                                    <td colspan="4" class="p-12 text-center text-gray-600 text-sm">Define your fiscal entities to begin billing.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
