<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    items: any[];
    categories: any[];
}>();

const form = useForm({
    inventory_category_id: '',
    name: '',
    code: '',
    unit: 'Pieces',
    description: '',
});

const submit = () => {
    form.post(route('accountant.inventory.items.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Inventory Items" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Inventory <span class="g-gradient-text">Items</span></h2>
            <p class="text-gray-500 text-sm mt-1 italic">Register and track specific stock items within your categories.</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Management Panel -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="g-card p-10 border-t-4 border-indigo-500 shadow-2xl shadow-indigo-500/10">
                        <h3 class="text-xl font-black text-white mb-8 uppercase tracking-widest text-[10px]">Add Item <span class="text-indigo-400">Registry</span></h3>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="g-label">Parent Category *</label>
                                <select v-model="form.inventory_category_id" class="g-input">
                                    <option value="">— Select Category —</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                                <p v-if="form.errors.inventory_category_id" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.inventory_category_id }}</p>
                            </div>

                            <div>
                                <label class="g-label">Item Identity (Name) *</label>
                                <input v-model="form.name" class="g-input" placeholder="e.g. Laser Printer Paper" />
                                <p v-if="form.errors.name" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.name }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="g-label">Item Code *</label>
                                    <input v-model="form.code" class="g-input font-mono" placeholder="SKU-100" />
                                </div>
                                <div>
                                    <label class="g-label">Unit</label>
                                    <input v-model="form.unit" class="g-input" placeholder="e.g. Boxes, Pkt" />
                                </div>
                            </div>

                            <div>
                                <label class="g-label">Technical Specs / Description</label>
                                <textarea v-model="form.description" class="g-input min-h-[80px]" placeholder="Dimensions, brand, color..."></textarea>
                            </div>

                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-5 bg-white text-black rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition shadow-xl shadow-indigo-500/10 active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Syncing...' : 'Register Item' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- List Panel -->
                <div class="lg:col-span-2">
                    <div class="g-card overflow-hidden">
                        <div class="p-6 bg-white/[0.03] border-b border-white/5 flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest italic">Institutional Stock Ledger</span>
                            <span class="px-3 py-1 bg-indigo-500/10 text-indigo-400 rounded-full text-[10px] font-bold">{{ items.length }} Items Tracked</span>
                        </div>
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-white/5 bg-white/[0.01]">
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500">Item Details</th>
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500">Category</th>
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500">Unit</th>
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr v-for="item in items" :key="item.id" class="hover:bg-white/[0.02] transition group">
                                    <td class="p-4">
                                        <div class="font-bold text-white uppercase tracking-tight">{{ item.name }}</div>
                                        <div class="text-[10px] text-gray-500 font-mono tracking-widest uppercase mt-0.5">{{ item.code }}</div>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-[9px] font-bold text-gray-400 uppercase tracking-widest shadow-inner">{{ item.category?.name || 'Uncategorized' }}</span>
                                    </td>
                                    <td class="p-4 text-xs text-gray-300 font-medium">{{ item.unit }}</td>
                                    <td class="p-4 text-right">
                                        <button class="opacity-0 group-hover:opacity-100 transition p-2 bg-indigo-500/10 text-indigo-400 rounded-xl hover:bg-indigo-500 hover:text-white">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="!items.length">
                                    <td colspan="4" class="p-20 text-center">
                                        <div class="text-gray-600 italic text-sm">Stock ledger is currently empty. Initialize your first asset.</div>
                                    </td>
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
