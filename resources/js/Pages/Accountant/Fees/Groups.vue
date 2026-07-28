<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    groups: any[];
}>();

const form = useForm({
    name: '',
    description: '',
});

const submit = () => {
    form.post(route('accountant.fees.groups.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Fee Groups" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Fee <span class="g-gradient-text">Groups</span></h2>
            <p class="text-gray-500 text-sm mt-1 italic">Categorize fees by classes, streams, or administrative groups.</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Management Panel -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="g-card p-8">
                        <h3 class="text-lg font-bold text-white mb-6">Create New Group</h3>
                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <label class="g-label">Group Name *</label>
                                <input v-model="form.name" class="g-input" placeholder="e.g. Class 10 Fees" />
                                <p v-if="form.errors.name" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label class="g-label">Description</label>
                                <textarea v-model="form.description" class="g-input min-h-[100px]" placeholder="Brief context..."></textarea>
                            </div>
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 transition shadow-xl shadow-indigo-500/10 active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Saving...' : 'Create Group' }}
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
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500">Group Name</th>
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500">Description</th>
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="group in groups" :key="group.id" class="border-b border-white/5 hover:bg-white/[0.02] transition">
                                    <td class="p-4">
                                        <div class="font-bold text-white uppercase tracking-tight">{{ group.name }}</div>
                                    </td>
                                    <td class="p-4 text-xs text-gray-500">{{ group.description || 'No description provided.' }}</td>
                                    <td class="p-4 text-right">
                                        <button class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 hover:text-white transition">Edit</button>
                                    </td>
                                </tr>
                                <tr v-if="!groups.length">
                                    <td colspan="3" class="p-12 text-center text-gray-600 text-sm">No fee groups defined yet.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
