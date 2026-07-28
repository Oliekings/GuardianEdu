<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps<{
    exams: any[];
}>();

const form = useForm({
    name: '',
    session: new Date().getFullYear() + '-' + (new Date().getFullYear() + 1),
    term: '',
    status: 'upcoming',
});

const submit = () => {
    form.post(route('admin.exams.store'), {
        onSuccess: () => form.reset(),
    });
};

const getStatusColor = (status: string) => {
    switch(status) {
        case 'ongoing': return 'text-emerald-400 bg-emerald-500/10';
        case 'completed': return 'text-indigo-400 bg-indigo-500/10';
        case 'cancelled': return 'text-rose-400 bg-rose-500/10';
        default: return 'text-gray-400 bg-white/5';
    }
};
</script>

<template>
    <Head title="Examination Control" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Academic <span class="g-gradient-text">Evaluation</span></h2>
            <p class="text-gray-500 text-sm mt-1 italic">Formalize institutional testing, term sessions, and examination series.</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Composer Panel -->
                <div class="lg:col-span-1 space-y-8">
                    <div class="g-card p-10 border-t-4 border-indigo-500 group">
                        <h3 class="text-xl font-black text-white mb-8 uppercase tracking-widest text-[10px]">Initialize <span class="text-indigo-400">Series</span></h3>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="g-label">Exam Title *</label>
                                <input v-model="form.name" class="g-input" placeholder="e.g. Quarterly Finals" />
                                <p v-if="form.errors.name" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.name }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="g-label">Academic Session</label>
                                    <input v-model="form.session" class="g-input text-xs" />
                                </div>
                                <div>
                                    <label class="g-label">Series Term</label>
                                    <input v-model="form.term" class="g-input text-xs" placeholder="e.g. Term 1" />
                                </div>
                            </div>
                            <div>
                                <label class="g-label">Initial Status</label>
                                <select v-model="form.status" class="g-input">
                                    <option value="upcoming">Upcoming (Planning)</option>
                                    <option value="ongoing">Ongoing (Active)</option>
                                </select>
                            </div>
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-5 bg-white text-black rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition shadow-xl active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Syncing...' : 'Formalize Exam' }}
                            </button>
                        </form>
                    </div>

                    <Link 
                        :href="route('admin.exams.grading.index')"
                        class="block p-8 g-card border-l-4 border-amber-500 hover:bg-white/5 transition group"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-black text-white uppercase tracking-widest">Grading Matrix</h4>
                                <p class="text-[10px] text-gray-500 mt-1">Configure institutional grade scales.</p>
                            </div>
                            <span class="text-2xl group-hover:translate-x-2 transition">📊</span>
                        </div>
                    </Link>
                </div>

                <!-- Series Ledger -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div v-for="exam in exams" :key="exam.id" class="g-card group p-8 hover:border-indigo-500/30 transition relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-8 opacity-[0.03] group-hover:opacity-[0.07] transition pointer-events-none">
                                <span class="text-9xl font-black italic">{{ exam.session.split('-')[0] }}</span>
                            </div>
                            
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <div :class="getStatusColor(exam.status)" class="inline-block px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest mb-3">
                                        {{ exam.status }}
                                    </div>
                                    <h4 class="text-xl font-black text-white uppercase tracking-tight">{{ exam.name }}</h4>
                                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">{{ exam.term }} | Session {{ exam.session }}</p>
                                </div>
                            </div>

                            <div class="mt-8 flex items-center gap-3">
                                <Link 
                                    :href="route('admin.exams.schedule.index', exam.id)"
                                    class="flex-1 py-3 bg-white/5 border border-white/10 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center hover:bg-indigo-600 hover:border-indigo-600 hover:text-white transition shadow-lg"
                                >
                                    Manage Schedule
                                </Link>
                                <button class="p-3 bg-white/5 border border-white/10 rounded-2xl text-gray-500 hover:text-white transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                                </button>
                            </div>
                        </div>

                        <div v-if="!exams.length" class="md:col-span-2 p-20 text-center g-card bg-transparent border-dashed border-2 border-white/5">
                            <h3 class="text-xl font-black text-gray-600 uppercase tracking-widest">No Series Formalized</h3>
                            <p class="text-gray-700 mt-2 italic">Begin by initializing an academic examination series.</p>
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
