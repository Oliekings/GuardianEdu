<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    fleet: any[];
}>();

const form = useForm({
    vehicle_number: '',
    driver_name: '',
    driver_phone: '',
    status: 'idle',
});

const submit = () => {
    form.post(route('admin.transport.fleet.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Bus Fleet Management" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Institutional <span class="g-gradient-text">Fleet</span></h2>
            <p class="text-gray-500 text-sm mt-1 italic">Manage and track the institution's transportation assets.</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Fleet Registration -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="g-card p-10 border-t-4 border-indigo-500">
                        <h3 class="text-lg font-black text-white mb-8 uppercase tracking-widest text-[10px]">Register <span class="text-indigo-400">Vehicle</span></h3>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="g-label">Vehicle Plate Number *</label>
                                <input v-model="form.vehicle_number" class="g-input font-bold" placeholder="e.g. TR-101-ABC" />
                                <p v-if="form.errors.vehicle_number" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.vehicle_number }}</p>
                            </div>
                            <div>
                                <label class="g-label">Driver Name</label>
                                <input v-model="form.driver_name" class="g-input" placeholder="e.g. John Doe" />
                            </div>
                            <div>
                                <label class="g-label">Driver Contact</label>
                                <input v-model="form.driver_phone" class="g-input" placeholder="+1..." />
                            </div>
                            <div>
                                <label class="g-label">Current Status</label>
                                <select v-model="form.status" class="g-input">
                                    <option value="idle">Idle (Stationary)</option>
                                    <option value="en_route">En Route (Active)</option>
                                    <option value="service" disabled>Under Maintenance</option>
                                </select>
                            </div>
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-5 bg-white text-black rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition shadow-xl active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Syncing Fleet...' : 'Add to Fleet' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Fleet List -->
                <div class="lg:col-span-2">
                    <div class="g-card overflow-hidden">
                        <div class="p-6 bg-white/[0.03] border-b border-white/5 flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest italic font-mono">Real-time Asset Ledger</span>
                            <span class="px-3 py-1 bg-indigo-500/10 text-indigo-400 rounded-full text-[10px] font-bold">{{ fleet.length }} Vehicles</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-0 border-b border-white/5">
                            <div v-for="vehicle in fleet" :key="vehicle.id" class="p-8 border-r border-b border-white/5 hover:bg-white/[0.02] transition group relative">
                                <div class="flex items-start justify-between">
                                    <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-xl grayscale group-hover:grayscale-0 transition">🚌</div>
                                    <span :class="vehicle.status === 'en_route' ? 'text-emerald-500 bg-emerald-500/10' : 'text-gray-500 bg-white/5'" class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest">{{ vehicle.status }}</span>
                                </div>
                                <div class="mt-6">
                                    <h4 class="text-xl font-black text-white uppercase tracking-tight">{{ vehicle.vehicle_number }}</h4>
                                    <div class="mt-4 space-y-1.5 border-l-2 border-indigo-500/30 pl-4">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Pilot: {{ vehicle.driver_name || 'Unassigned' }}</p>
                                        <p class="text-[10px] text-gray-500">{{ vehicle.driver_phone || 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="mt-8 flex justify-between items-center opacity-0 group-hover:opacity-100 transition">
                                    <div class="text-[9px] font-bold text-indigo-400 tracking-widest uppercase">GPS Online</div>
                                    <button class="p-2 bg-white/5 rounded-xl hover:bg-white/10 text-gray-500 hover:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-if="!fleet.length" class="p-20 text-center text-gray-600 italic text-sm">No vehicles registered. Begin building your institution's fleet.</div>
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
