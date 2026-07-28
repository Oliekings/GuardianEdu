<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    books: any[];
}>();

const form = useForm({
    title: '',
    author: '',
    isbn: '',
    publisher: '',
    rack_number: '',
    quantity: 1,
});

const submit = () => {
    form.post(route('accountant.library.books.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Library Catalog" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Library <span class="g-gradient-text">Catalog</span></h2>
            <p class="text-gray-500 text-sm mt-1 italic">Manage the institution's collection of literature and educational resources.</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Management Panel -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="g-card p-8 border-t-4 border-indigo-500/50">
                        <h3 class="text-lg font-bold text-white mb-6 uppercase tracking-widest text-[10px]">Add Book to <span class="text-indigo-400">Inventory</span></h3>
                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <label class="g-label text-[10px]">Title *</label>
                                <input v-model="form.title" class="g-input" placeholder="e.g. Clean Code" />
                                <p v-if="form.errors.title" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.title }}</p>
                            </div>
                            <div>
                                <label class="g-label text-[10px]">Author *</label>
                                <input v-model="form.author" class="g-input" placeholder="e.g. Robert C. Martin" />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="g-label text-[10px]">ISBN</label>
                                    <input v-model="form.isbn" class="g-input text-xs" />
                                </div>
                                <div>
                                    <label class="g-label text-[10px]">Quantity</label>
                                    <input v-model="form.quantity" type="number" class="g-input" />
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="g-label text-[10px]">Rack No</label>
                                    <input v-model="form.rack_number" class="g-input text-xs placeholder-gray-700" placeholder="e.g. A-12" />
                                </div>
                                <div>
                                    <label class="g-label text-[10px]">Publisher</label>
                                    <input v-model="form.publisher" class="g-input text-xs" />
                                </div>
                            </div>
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 transition shadow-xl shadow-indigo-500/10 active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Cataloging...' : 'Add Book' }}
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
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500">Book Details</th>
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500 text-center">Rack</th>
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500 text-center">Qty</th>
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="book in books" :key="book.id" class="border-b border-white/5 hover:bg-white/[0.02] transition group">
                                    <td class="p-4">
                                        <div class="font-bold text-white uppercase tracking-tight">{{ book.title }}</div>
                                        <div class="text-[9px] text-indigo-400 font-bold uppercase tracking-widest mt-0.5">By {{ book.author }}</div>
                                        <div class="text-[9px] text-gray-500 font-mono mt-1">ISBN: {{ book.isbn || 'N/A' }}</div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="px-3 py-1 bg-white/5 border border-white/10 rounded font-mono text-[10px] text-gray-400">{{ book.rack_number || '-' }}</span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span :class="book.quantity > 0 ? 'text-emerald-400' : 'text-rose-500'" class="font-black text-xs">{{ book.quantity }}</span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <button class="opacity-0 group-hover:opacity-100 transition text-[10px] font-bold uppercase tracking-widest text-indigo-400 hover:text-white">Edit</button>
                                    </td>
                                </tr>
                                <tr v-if="!books.length">
                                    <td colspan="4" class="p-12 text-center text-gray-600 text-sm italic">Catalog is currently empty. Start building your library.</td>
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
