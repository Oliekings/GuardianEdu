<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    assignments: any[];
    students: any[];
    routes: any[];
    fleet: any[];
}>();

const form = useForm({
    student_id: '',
    transport_route_id: '',
    bus_fleet_id: '',
});

const submit = () => {
    form.post(route('admin.transport.assign.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Transport Enrollment" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Transit <span class="g-gradient-text">Enrollment</span></h2>
            <p class="text-gray-500 text-sm mt-1 italic">Assign students to specific transport units and routes.</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8 font-primary">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Enrollment Panel -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="g-card p-10 border-t-4 border-emerald-500 shadow-2xl shadow-emerald-500/10 relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 opacity-5 pointer-events-none">
                            <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 20 20"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" /></svg>
                        </div>
                        
                        <h3 class="text-xl font-black text-white mb-8 uppercase tracking-widest text-[10px]">Enroll <span class="text-emerald-400">Commuter</span></h3>
                        
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="g-label">Student Identity *</label>
                                <select v-model="form.student_id" class="g-input">
                                    <option value="">— Select Official Enrollee —</option>
                                    <option v-for="s in students" :key="s.id" :value="s.id">{{ s.user?.name }} (UID: {{ s.admission_id }})</option>
                                </select>
                                <p v-if="form.errors.student_id" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.student_id }}</p>
                            </div>

                            <div>
                                <label class="g-label">Service Route *</label>
                                <select v-model="form.transport_route_id" class="g-input">
                                    <option value="">— Choose Path —</option>
                                    <option v-for="r in routes" :key="r.id" :value="r.id">{{ r.name }} (${{ r.monthly_fee }}/mo)</option>
                                </select>
                            </div>

                            <div>
                                <label class="g-label">Assigned Vehicle *</label>
                                <select v-model="form.bus_fleet_id" class="g-input">
                                    <option value="">— Assign Unit —</option>
                                    <option v-for="v in fleet" :key="v.id" :value="v.id">{{ v.vehicle_number }} ({{ v.status }})</option>
                                </select>
                            </div>

                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-5 bg-white text-black rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition shadow-xl active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Syncing Nodes...' : 'Finalize Enrollment' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Assignment Ledger -->
                <div class="lg:col-span-2">
                    <div class="g-card overflow-hidden">
                        <div class="p-6 bg-white/[0.03] border-b border-white/5 flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest italic font-mono space-x-2">
                                <span>Transit Node Manifest</span>
                            </span>
                            <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 rounded-full text-[10px] font-bold uppercase">{{ assignments.length }} Managed Commuters</span>
                        </div>
                        <table class="w-full text-left font-mono">
                            <thead class="bg-white/[0.01]">
                                <tr class="border-b border-white/5">
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500">Commuter</th>
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500">Route & Unit</th>
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500 text-center">Status</th>
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr v-for="asn in assignments" :key="asn.id" class="hover:bg-white/[0.02] transition group">
                                    <td class="p-4">
                                        <div class="font-black text-white uppercase tracking-tight text-xs">{{ asn.student?.user?.name }}</div>
                                        <div class="text-[9px] text-gray-500 uppercase mt-0.5">UID: {{ asn.student?.admission_id }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-indigo-400 text-[10px] uppercase">{{ asn.route?.name }}</div>
                                        <div class="text-[9px] text-gray-500 mt-1 flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 bg-gray-700 rounded-sm"></span>
                                            Vehicle: {{ asn.vehicle?.vehicle_number }}
                                        </div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-500 rounded text-[9px] font-bold uppercase border border-emerald-500/20 shadow-sm">Verified</span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <button class="opacity-0 group-hover:opacity-100 transition p-2 bg-rose-500/5 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="!assignments.length">
                                    <td colspan="4" class="p-20 text-center text-gray-600 italic text-sm italic">Transit manifest is currently zeroed.</td>
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
