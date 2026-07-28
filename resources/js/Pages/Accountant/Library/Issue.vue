<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';

const props = defineProps<{
    issues: any[];
    books: any[];
    members: any[];
}>();

const form = useForm({
    library_book_id: '',
    library_member_id: '',
    due_date: '',
});

const submit = () => {
    form.post(route('accountant.library.issue.store'), {
        onSuccess: () => form.reset(),
    });
};

const returnBook = (id: number) => {
    if (confirm('Verify book return?')) {
        router.post(route('library.return', id));
    }
};
</script>

<template>
    <Head title="Book Circulation" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Book <span class="g-gradient-text">Circulation</span></h2>
            <p class="text-gray-500 text-sm mt-1 italic">Manage the active flow of institutional literature and borrowing records.</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Issue Panel -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="g-card p-10 border-t-4 border-indigo-500 shadow-2xl shadow-indigo-500/10">
                        <h3 class="text-xl font-black text-white mb-8 uppercase tracking-widest text-[10px]">Issue <span class="text-indigo-400">Literature</span></h3>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="g-label">Select Book *</label>
                                <select v-model="form.library_book_id" class="g-input">
                                    <option value="">— Select Available Title —</option>
                                    <option v-for="book in books" :key="book.id" :value="book.id">{{ book.title }} (Qty: {{ book.quantity }})</option>
                                </select>
                                <p v-if="form.errors.library_book_id" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.library_book_id }}</p>
                            </div>

                            <div>
                                <label class="g-label">Borrower (Member) *</label>
                                <select v-model="form.library_member_id" class="g-input">
                                    <option value="">— Select Library Card —</option>
                                    <option v-for="m in members" :key="m.id" :value="m.id">{{ m.user?.name }} ({{ m.library_card_number }})</option>
                                </select>
                                <p v-if="form.errors.library_member_id" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.library_member_id }}</p>
                            </div>

                            <div>
                                <label class="g-label">Return Due Date</label>
                                <input v-model="form.due_date" type="date" class="g-input" />
                            </div>

                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-5 bg-white text-black rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition shadow-xl active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Syncing...' : 'Authorize Issue' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Active Issues List -->
                <div class="lg:col-span-2">
                    <div class="g-card overflow-hidden">
                        <div class="p-6 bg-white/[0.03] border-b border-white/5 flex justify-between items-center">
                            <h4 class="text-xs font-black text-white uppercase tracking-widest italic">Live Borrowing Registry</h4>
                            <span class="px-3 py-1 bg-indigo-500/10 text-indigo-400 rounded-full text-[10px] font-bold">{{ issues.length }} Active Loans</span>
                        </div>
                        <table class="w-full text-left">
                            <thead class="bg-white/[0.01]">
                                <tr class="border-b border-white/5">
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500">Book Title</th>
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500">Borrower</th>
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500 text-center">Due Date</th>
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr v-for="issue in issues" :key="issue.id" class="hover:bg-white/[0.02] transition">
                                    <td class="p-4">
                                        <div class="font-bold text-white uppercase tracking-tight text-xs">{{ issue.book?.title }}</div>
                                        <div class="text-[9px] text-gray-500 font-mono mt-0.5">By {{ issue.book?.author }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-gray-300 text-[11px] uppercase">{{ issue.member?.user?.name }}</div>
                                        <div class="text-[9px] text-indigo-400 font-bold uppercase tracking-widest mt-0.5">{{ issue.member?.library_card_number }}</div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="text-[10px] font-black uppercase" :class="new Date(issue.due_date) < new Date() ? 'text-rose-500' : 'text-gray-400'">{{ issue.due_date }}</span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <button @click="returnBook(issue.id)" class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-[9px] font-black uppercase text-emerald-400 hover:bg-emerald-500 hover:text-white transition">Mark Returned</button>
                                    </td>
                                </tr>
                                <tr v-if="!issues.length">
                                    <td colspan="4" class="p-20 text-center text-gray-600 italic text-sm italic">No books are currently in circulation.</td>
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
