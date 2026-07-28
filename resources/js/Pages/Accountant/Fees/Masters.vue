<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    masters: any[];
    groups: any[];
    types: any[];
}>();

const form = useForm({
    fee_group_id: '',
    fee_type_id: '',
    amount: '',
    due_date: '',
});

const submit = () => {
    form.post(route('accountant.fees.masters.store'), {
        onSuccess: () => form.reset(),
    });
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
};
</script>

<template>
    <Head title="Fee Master Configuration" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Fee <span class="g-gradient-text">Masters</span></h2>
            <p class="text-gray-500 text-sm mt-1 italic">Assign amounts to fee types within specific groups (Core Logic).</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Config Panel -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="g-card p-10 border-t-4 border-indigo-500 shadow-2xl shadow-indigo-500/10">
                        <h3 class="text-xl font-black text-white mb-8">Master <span class="text-indigo-400">Rules</span></h3>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="g-label">Target Fee Group</label>
                                <select v-model="form.fee_group_id" class="g-input">
                                    <option value="">— Select Group —</option>
                                    <option v-for="g in groups" :key="g.id" :value="g.id">{{ g.name }}</option>
                                </select>
                                <p v-if="form.errors.fee_group_id" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.fee_group_id }}</p>
                            </div>

                            <div>
                                <label class="g-label">Fee Type</label>
                                <select v-model="form.fee_type_id" class="g-input">
                                    <option value="">— Select Type —</option>
                                    <option v-for="t in types" :key="t.id" :value="t.id">{{ t.name }} ({{ t.code }})</option>
                                </select>
                                <p v-if="form.errors.fee_type_id" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.fee_type_id }}</p>
                            </div>

                            <div>
                                <label class="g-label">Amount ($)</label>
                                <input v-model="form.amount" type="number" step="0.01" class="g-input" placeholder="0.00" />
                                <p v-if="form.errors.amount" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.amount }}</p>
                            </div>

                            <div>
                                <label class="g-label">Due Date</label>
                                <input v-model="form.due_date" type="date" class="g-input" />
                            </div>

                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-5 bg-white text-black rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition shadow-xl active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Syncing...' : 'Seal Master Rule' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Live Master List -->
                <div class="lg:col-span-2">
                    <div class="g-card overflow-hidden">
                        <div class="p-6 bg-white/[0.03] border-b border-white/5 flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Active Fiscal Rules</span>
                            <span class="px-3 py-1 bg-indigo-500/10 text-indigo-400 rounded-full text-[10px] font-bold">{{ masters.length }} Rules Active</span>
                        </div>
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-white/5 bg-white/[0.01]">
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500">Group</th>
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500">Fee Type</th>
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500">Amount</th>
                                    <th class="p-4 text-[10px] uppercase tracking-widest font-bold text-gray-500 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr v-for="master in masters" :key="master.id" class="hover:bg-white/[0.02] transition group">
                                    <td class="p-4">
                                        <div class="font-bold text-white text-xs uppercase">{{ master.group?.name }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="text-xs text-gray-300 font-semibold">{{ master.type?.name }}</div>
                                        <div class="text-[9px] text-gray-500 font-mono">{{ master.type?.code }}</div>
                                    </td>
                                    <td class="p-4">
                                        <span class="text-sm font-black text-emerald-400">{{ formatCurrency(master.amount) }}</span>
                                        <div v-if="master.due_date" class="text-[9px] text-rose-500 font-bold uppercase mt-1">Due: {{ master.due_date }}</div>
                                    </td>
                                    <td class="p-4 text-right">
                                        <button class="p-2 hover:bg-white/10 rounded-xl transition text-rose-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="!masters.length">
                                    <td colspan="4" class="p-20 text-center">
                                        <div class="text-gray-600 italic text-sm">No fiscal rules defined. Bridge your groups and types.</div>
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
    border-radius: 32px;
}
</style>
