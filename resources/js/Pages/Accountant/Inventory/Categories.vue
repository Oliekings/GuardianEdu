<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    categories: any[];
}>();

const form = useForm({
    name: '',
    description: '',
});

const submit = () => {
    form.post(route('accountant.inventory.categories.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Inventory Categories" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Stock <span class="g-gradient-text">Categories</span></h2>
            <p class="text-gray-500 text-sm mt-1 italic">Organize your institution's physical assets and consumables.</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Management Panel -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="g-card p-8 border-t-4 border-amber-500/50">
                        <h3 class="text-lg font-bold text-white mb-6 uppercase tracking-widest text-[10px]">Create Category</h3>
                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <label class="g-label text-[10px]">Category Name *</label>
                                <input v-model="form.name" class="g-input" placeholder="e.g. Stationery" />
                                <p v-if="form.errors.name" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label class="g-label text-[10px]">Description</label>
                                <textarea v-model="form.description" class="g-input min-h-[100px]" placeholder="Brief notes on this category..."></textarea>
                            </div>
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-4 bg-amber-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-amber-700 transition shadow-xl shadow-amber-500/10 active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Saving...' : 'Create Category' }}
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
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500">Description</th>
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="cat in categories" :key="cat.id" class="border-b border-white/5 hover:bg-white/[0.02] transition group">
                                    <td class="p-4">
                                        <div class="font-bold text-white uppercase tracking-tight">{{ cat.name }}</div>
                                    </td>
                                    <td class="p-4 text-xs text-gray-500 italic">{{ cat.description || 'Generic stock category.' }}</td>
                                    <td class="p-4 text-right">
                                        <button class="opacity-0 group-hover:opacity-100 transition text-[10px] font-bold uppercase tracking-widest text-amber-500 hover:text-white">Delete</button>
                                    </td>
                                </tr>
                                <tr v-if="!categories.length">
                                    <td colspan="3" class="p-12 text-center text-gray-600 text-sm italic">Define your stock categories to begin tracking inventory.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.g-card {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border-radius: 40px;
}
</style>
