<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    student: any;
    feeMasters: any[];
    deposits: any[];
}>();

const form = useForm({
    fee_master_id: '',
    amount_paid: '',
    payment_mode: 'Cash',
    notes: '',
});

const submit = () => {
    form.post(route('accountant.fees.collect.store', props.student.id), {
        onSuccess: () => form.reset(),
    });
};

const getPaidAmount = (masterId: number) => {
    return props.deposits
        .filter(d => d.fee_master_id === masterId)
        .reduce((sum, d) => sum + parseFloat(d.amount_paid), 0);
};

const getBalance = (master: any) => {
    return parseFloat(master.amount) - getPaidAmount(master.id);
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
};

const totalPayable = computed(() => props.feeMasters.reduce((sum, m) => sum + parseFloat(m.amount), 0));
const totalPaid = computed(() => props.deposits.reduce((sum, d) => sum + parseFloat(d.amount_paid), 0));
const totalBalance = computed(() => totalPayable.value - totalPaid.value);
</script>

<template>
    <Head :title="`Collect: ${student.full_name}`" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <Link :href="route('accountant.fees.collect.index')" class="p-2 bg-white/5 border border-white/10 rounded-full hover:bg-white/10 transition">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    </Link>
                    <div>
                        <h2 class="text-3xl font-black text-white">Student <span class="g-gradient-text">Billing</span></h2>
                        <p class="text-xs font-mono text-gray-500 tracking-tighter uppercase mt-1">Transaction Ledger for {{ student.admission_number }}</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="g-card p-3 px-6 flex items-center gap-4 bg-emerald-500/5 border-emerald-500/10">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Paid</span>
                        <span class="text-xl font-black text-emerald-400">{{ formatCurrency(totalPaid) }}</span>
                    </div>
                    <div class="g-card p-3 px-6 flex items-center gap-4 bg-rose-500/5 border-rose-500/10">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Balance</span>
                        <span class="text-xl font-black text-rose-500">{{ formatCurrency(totalBalance) }}</span>
                    </div>
                </div>
            </div>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Student Summary & Payment Form -->
                <div class="space-y-6">
                    <div class="g-card p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-indigo-600 rounded-3xl flex items-center justify-center text-xl font-black text-white">
                                {{ student.first_name[0] }}{{ student.last_name[0] }}
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-white uppercase">{{ student.full_name }}</h3>
                                <p class="text-[10px] text-gray-500 font-mono">{{ student.email || 'no-email@guardian.edu' }}</p>
                            </div>
                        </div>
                        <div class="space-y-4 pt-6 border-t border-white/5">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500 uppercase font-bold tracking-widest">Classroom</span>
                                <span class="text-white font-bold">{{ student.room?.name || 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500 uppercase font-bold tracking-widest">Fee Group</span>
                                <span class="text-indigo-400 font-black uppercase tracking-tight">{{ student.fee_group?.name || 'Unassigned' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <div class="g-card p-8 border-t-4 border-indigo-500">
                        <h4 class="text-sm font-black text-white uppercase tracking-widest mb-8">Record New Deposit</h4>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="g-label">Select Fee Component</label>
                                <select v-model="form.fee_master_id" class="g-input">
                                    <option value="">— Choose Component —</option>
                                    <option v-for="m in feeMasters" :key="m.id" :value="m.id">
                                        {{ m.type.name }} (Bal: {{ formatCurrency(getBalance(m)) }})
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="g-label">Amount Paid ($)</label>
                                <input v-model="form.amount_paid" type="number" step="0.01" class="g-input" placeholder="0.00" />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="g-label">Mode</label>
                                    <select v-model="form.payment_mode" class="g-input">
                                        <option value="Cash">Cash</option>
                                        <option value="Card">Card</option>
                                        <option value="Transfer">Transfer</option>
                                        <option value="Cheque">Cheque</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="g-label">Reference</label>
                                    <input v-model="form.notes" class="g-input" placeholder="TXN-ID..." />
                                </div>
                            </div>
                            <button 
                                type="submit" 
                                :disabled="form.processing || !form.fee_master_id"
                                class="w-full py-5 bg-white text-black rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition shadow-xl active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Syncing...' : 'Record Payment' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right: Fee Breakdown & History -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Fee Breakdown -->
                    <div class="g-card overflow-hidden">
                        <div class="p-6 bg-white/[0.03] border-b border-white/5 flex justify-between items-center">
                            <h4 class="text-xs font-black text-white uppercase tracking-widest pointer-events-none">Applicable Fees Breakdown</h4>
                            <span class="text-[10px] text-gray-500 font-bold uppercase italic">Billing Cycle 2026-27</span>
                        </div>
                        <table class="w-full text-left">
                            <thead class="bg-white/[0.01]">
                                <tr class="border-b border-white/5">
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500">Component</th>
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500 text-right">Payable</th>
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500 text-right">Paid</th>
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500 text-right">Balance</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr v-for="m in feeMasters" :key="m.id" class="hover:bg-white/[0.02] transition">
                                    <td class="p-4">
                                        <div class="text-xs font-bold text-white uppercase">{{ m.type.name }}</div>
                                        <div v-if="m.due_date" class="text-[9px] text-rose-500 font-bold uppercase mt-0.5 italic">Due: {{ m.due_date }}</div>
                                    </td>
                                    <td class="p-4 text-right text-xs text-gray-300 font-mono">{{ formatCurrency(m.amount) }}</td>
                                    <td class="p-4 text-right text-xs text-emerald-400 font-mono">{{ formatCurrency(getPaidAmount(m.id)) }}</td>
                                    <td class="p-4 text-right">
                                        <span class="text-xs font-black" :class="getBalance(m) > 0 ? 'text-rose-500' : 'text-emerald-500'">
                                            {{ formatCurrency(getBalance(m)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr v-if="!feeMasters.length">
                                    <td colspan="4" class="p-12 text-center text-gray-600 italic text-sm">No fee structure assigned to this student.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Transaction History -->
                    <div class="g-card overflow-hidden">
                        <div class="p-6 bg-white/[0.03] border-b border-white/5">
                            <h4 class="text-xs font-black text-white uppercase tracking-widest pointer-events-none">Recent Transaction History</h4>
                        </div>
                        <div v-if="deposits.length" class="p-0">
                            <div v-for="d in deposits" :key="d.id" class="p-4 flex items-center justify-between border-b border-white/5 hover:bg-white/[0.01] transition">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-emerald-500/10 text-emerald-400 rounded-2xl">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <div>
                                        <div class="text-[11px] font-black text-white uppercase tracking-tight">{{ d.master?.type?.name }}</div>
                                        <div class="text-[9px] text-gray-500 font-mono flex gap-2">
                                            <span>{{ new Date(d.deposit_date).toLocaleDateString() }}</span>
                                            <span>•</span>
                                            <span class="uppercase tracking-widest text-indigo-400">{{ d.payment_mode }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-black text-white">{{ formatCurrency(d.amount_paid) }}</div>
                                    <button class="text-[9px] font-black text-gray-600 uppercase tracking-widest hover:text-indigo-400 transition">Print Receipt</button>
                                </div>
                            </div>
                        </div>
                        <div v-else class="p-12 text-center text-gray-600 italic text-sm">No payments recorded for this account.</div>
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
