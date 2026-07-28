<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';

const props = defineProps<{
    scales: any[];
}>();

const form = useForm({
    grade_name: '',
    min_score: '',
    max_score: '',
    remarks: '',
});

const submit = () => {
    form.post(route('admin.exams.grading.store'), {
        onSuccess: () => form.reset(),
    });
};

const deleteScale = (id: number) => {
    if (confirm('Delete this grading scale?')) {
        router.delete(route('admin.exams.grading.destroy', id)); // Need to ensure destroy exists or just hide it
    }
};
</script>

<template>
    <Head title="Grading Registry" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Grading <span class="g-gradient-text">Matrix</span></h2>
            <p class="text-gray-500 text-sm mt-1 italic">Define institutional grade boundaries and evaluation metrics.</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8 font-primary">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Composer -->
                <div class="lg:col-span-1">
                    <div class="g-card p-10 border-t-4 border-amber-500 relative overflow-hidden group">
                        <div class="absolute -top-10 -right-10 opacity-5 group-hover:scale-110 transition duration-1000">
                            <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" /><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" /></svg>
                        </div>

                        <h3 class="text-xl font-black text-white mb-8 uppercase tracking-widest text-[10px]">Define <span class="text-amber-400">Boundary</span></h3>
                        
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="g-label">Grade Identity (e.g. A+, Pass) *</label>
                                <input v-model="form.grade_name" class="g-input font-black text-lg text-amber-500" placeholder="A+" />
                                <p v-if="form.errors.grade_name" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.grade_name }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="g-label">Min % *</label>
                                    <input v-model="form.min_score" type="number" class="g-input" placeholder="0" />
                                </div>
                                <div>
                                    <label class="g-label">Max % *</label>
                                    <input v-model="form.max_score" type="number" class="g-input" placeholder="100" />
                                </div>
                            </div>

                            <div>
                                <label class="g-label">Performance Remark</label>
                                <textarea v-model="form.remarks" class="g-input h-24" placeholder="e.g. Outstanding performance..."></textarea>
                            </div>

                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-5 bg-white text-black rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-amber-600 hover:text-white transition shadow-xl active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Syncing Matrix...' : 'Commit Grade' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Registry -->
                <div class="lg:col-span-2">
                    <div class="g-card overflow-hidden">
                        <div class="p-6 bg-white/[0.03] border-b border-white/5 flex justify-between items-center">
                            <h4 class="text-xs font-black text-white uppercase tracking-widest italic font-mono">Institutional Evaluation Ledger</h4>
                            <span class="px-3 py-1 bg-amber-500/10 text-amber-400 rounded-full text-[10px] font-bold">{{ scales.length }} Defined Tiers</span>
                        </div>

                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div v-for="scale in scales" :key="scale.id" class="p-6 bg-white/5 rounded-[32px] border border-white/5 hover:border-amber-500/30 transition group flex items-center justify-between">
                                <div class="flex items-center gap-6">
                                    <div class="w-16 h-16 bg-white/[0.03] rounded-2xl flex items-center justify-center text-2xl font-black text-amber-500 border border-white/5 shadow-inner">
                                        {{ scale.grade_name }}
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-black text-white uppercase tracking-widest">Range: {{ scale.min_score }}% - {{ scale.max_score }}%</h5>
                                        <p class="text-[10px] text-gray-500 italic mt-1">{{ scale.remarks || 'Standard evaluation tier' }}</p>
                                    </div>
                                </div>
                                <button class="opacity-0 group-hover:opacity-100 transition p-3 hover:bg-rose-500/10 text-rose-500 rounded-2xl">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                            
                            <div v-if="!scales.length" class="col-span-2 py-20 text-center text-gray-600 italic text-sm">Grading scales are zeroed. Initialize your evaluation metrics.</div>
                        </div>
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
