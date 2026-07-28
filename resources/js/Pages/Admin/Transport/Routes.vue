<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    routes: any[];
}>();

const form = useForm({
    name: '',
    start_point: '',
    end_point: '',
    monthly_fee: 0,
    description: '',
});

const submit = () => {
    form.post(route('admin.transport.routes.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Transport Routes" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Logistics <span class="g-gradient-text">Charting</span></h2>
            <p class="text-gray-500 text-sm mt-1 italic">Define the pathways and fiscal parameters for institutional transit.</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Route Creation -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="g-card p-10 border-t-4 border-amber-500 shadow-2xl shadow-amber-500/10">
                        <h3 class="text-xl font-black text-white mb-8 uppercase tracking-widest text-[10px]">Chart New <span class="text-amber-400">Route</span></h3>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="g-label">Route Designation *</label>
                                <input v-model="form.name" class="g-input" placeholder="e.g. Northern Suburban express" />
                                <p v-if="form.errors.name" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.name }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="g-label">Origin Point *</label>
                                    <input v-model="form.start_point" class="g-input text-xs" placeholder="Start..." />
                                </div>
                                <div>
                                    <label class="g-label">Destination *</label>
                                    <input v-model="form.end_point" class="g-input text-xs" placeholder="Terminal..." />
                                </div>
                            </div>
                            <div>
                                <label class="g-label font-black text-amber-400">Monthly Surcharge ($) *</label>
                                <input v-model="form.monthly_fee" type="number" class="g-input text-lg font-black" />
                            </div>
                            <div>
                                <label class="g-label">Navigator Notes</label>
                                <textarea v-model="form.description" class="g-input min-h-[80px]" placeholder="Key waypoints..."></textarea>
                            </div>
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-5 bg-white text-black rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-amber-600 hover:text-white transition shadow-xl active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Mapping...' : 'Finalize Path' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Routes Table -->
                <div class="lg:col-span-2">
                    <div class="g-card overflow-hidden">
                        <div class="p-6 bg-white/[0.03] border-b border-white/5 flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest italic font-mono">Institutional Route Matrix</span>
                            <span class="px-3 py-1 bg-amber-500/10 text-amber-400 rounded-full text-[10px] font-bold">{{ routes.length }} Active Routes</span>
                        </div>
                        <table class="w-full text-left font-mono">
                            <thead class="bg-white/[0.01]">
                                <tr class="border-b border-white/5">
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500">Route Identity</th>
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500">Terminal Points</th>
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500 text-center">Fee</th>
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr v-for="route in routes" :key="route.id" class="hover:bg-white/[0.02] transition">
                                    <td class="p-4">
                                        <div class="font-black text-white uppercase tracking-tight text-xs">{{ route.name }}</div>
                                        <div class="text-[9px] text-gray-600 uppercase mt-0.5">{{ route.description || 'Global institution pathway' }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                            <span>{{ route.start_point }}</span>
                                            <span class="text-amber-500">→</span>
                                            <span>{{ route.end_point }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="font-black text-xs text-amber-500">${{ route.monthly_fee }}</span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <button class="p-2 border border-white/5 rounded-xl text-gray-600 hover:text-white transition group/btn">
                                            <svg class="w-4 h-4 group-hover/btn:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="!routes.length">
                                    <td colspan="4" class="p-20 text-center text-gray-600 italic text-sm italic">Pathway registry is currently unmapped.</td>
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
