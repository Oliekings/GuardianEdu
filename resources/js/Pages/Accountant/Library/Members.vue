<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    members: any[];
    availableUsers: any[];
}>();

const form = useForm({
    user_id: '',
    library_card_number: '',
});

const submit = () => {
    form.post(route('accountant.library.members.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Library Membership" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Library <span class="g-gradient-text">Membership</span></h2>
            <p class="text-gray-500 text-sm mt-1 italic">Register students and staff for library access and borrowing privileges.</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Registration Panel -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="g-card p-10 border-t-4 border-emerald-500/50">
                        <h3 class="text-xl font-black text-white mb-8 uppercase tracking-widest text-[10px]">Enroll <span class="text-emerald-400">Member</span></h3>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="g-label">System User *</label>
                                <select v-model="form.user_id" class="g-input">
                                    <option value="">— Select Student/Staff —</option>
                                    <option v-for="user in availableUsers" :key="user.id" :value="user.id">{{ user.name }} ({{ user.email }})</option>
                                </select>
                                <p v-if="form.errors.user_id" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.user_id }}</p>
                            </div>

                            <div>
                                <label class="g-label">Library Card Number *</label>
                                <input v-model="form.library_card_number" class="g-input font-bold" placeholder="LIB-XXXX" />
                                <p v-if="form.errors.library_card_number" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.library_card_number }}</p>
                            </div>

                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-5 bg-white text-black rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition shadow-xl active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Activating...' : 'Activate Membership' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- List Panel -->
                <div class="lg:col-span-2">
                    <div class="g-card overflow-hidden">
                        <div class="p-6 bg-white/[0.03] border-b border-white/5 flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest italic font-mono">Registry of Active Borrowers</span>
                            <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 rounded-full text-[10px] font-bold">{{ members.length }} Enrolled</span>
                        </div>
                        <table class="w-full text-left">
                            <thead class="bg-white/[0.01]">
                                <tr class="border-b border-white/5">
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500">Member Identity</th>
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500 text-center">Library ID</th>
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr v-for="member in members" :key="member.id" class="hover:bg-white/[0.02] transition group">
                                    <td class="p-4">
                                        <div class="font-black text-white uppercase tracking-tight">{{ member.user?.name }}</div>
                                        <div class="text-[9px] text-gray-500 font-mono mt-0.5">{{ member.user?.email }}</div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="px-4 py-1.5 bg-indigo-500/10 border border-indigo-500/20 rounded-xl font-black text-[10px] text-indigo-400 italic">{{ member.library_card_number }}</span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 rounded-lg text-[9px] font-bold uppercase">Active</span>
                                    </td>
                                </tr>
                                <tr v-if="!members.length">
                                    <td colspan="3" class="p-20 text-center text-gray-600 italic text-sm italic">No library memberships issued yet. Begin enrollment.</td>
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
