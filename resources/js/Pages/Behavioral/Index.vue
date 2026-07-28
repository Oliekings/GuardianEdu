<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Student {
    id: number;
    name: string;
}

const props = defineProps<{
    students: Student[];
}>();

const categories = [
    { id: 'academic', name: 'Academic' },
    { id: 'behavior', name: 'Behavior' },
    { id: 'attendance', name: 'Attendance' },
];

const form = useForm({
    student_id: null as number | null,
    category: 'academic',
    type: 'kudos' as 'kudos' | 'incident',
    points: 5,
    description: '',
});

const submitLog = () => {
    if (!form.student_id) return alert('Select a student first');
    
    form.post(route('staff.behavioral.store'), {
        onSuccess: () => {
            alert('Behavioral log recorded successfully!');
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Behavioral Log — GuardianEdu" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-extrabold tracking-tight text-white focus:outline-none">
                Behavioral <span class="bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent">Log Hub</span>
            </h2>
            <p class="mt-1 text-sm text-gray-500">Reward excellence or track incidents in real-time.</p>
        </template>

        <div class="pb-12 text-white">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white/5 border border-white/10 rounded-[32px] backdrop-blur-sm p-10 max-w-3xl border-l-4 border-l-indigo-500 shadow-2xl">
                    <div class="space-y-8">
                        <!-- Student Selection -->
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-4">Choose Target Student</label>
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                                <button 
                                    v-for="student in students" 
                                    :key="student.id"
                                    type="button"
                                    @click="form.student_id = student.id"
                                    :class="[
                                        'px-4 py-3 rounded-2xl border text-xs font-bold transition-all transform active:scale-95',
                                        form.student_id === student.id 
                                            ? 'bg-indigo-600 border-indigo-500 text-white shadow-lg shadow-indigo-500/30' 
                                            : 'bg-black/40 border-white/5 text-gray-500 hover:bg-white/5 hover:text-white'
                                    ]"
                                >
                                    {{ student.name }}
                                </button>
                            </div>
                        </div>

                        <!-- Type Toggle -->
                        <div class="flex p-1 bg-black/40 border border-white/5 rounded-2xl w-fit">
                            <button 
                                type="button"
                                @click="form.type = 'kudos'"
                                :class="[
                                    'px-8 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all',
                                    form.type === 'kudos' ? 'bg-emerald-500 text-white shadow-lg' : 'text-gray-600 hover:text-gray-400'
                                ]"
                            >
                                Kudo (+)
                            </button>
                            <button 
                                type="button"
                                @click="form.type = 'incident'"
                                :class="[
                                    'px-8 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all',
                                    form.type === 'incident' ? 'bg-rose-500 text-white shadow-lg' : 'text-gray-600 hover:text-gray-400'
                                ]"
                            >
                                Incident (-)
                            </button>
                        </div>

                        <!-- Category & Points -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Category Focus</label>
                                <select 
                                    v-model="form.category"
                                    class="w-full bg-black/40 border-white/5 rounded-2xl text-white text-sm focus:border-indigo-500 focus:ring-indigo-500/10 py-4 px-6 appearance-none transition"
                                >
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Point Weight</label>
                                <div class="flex items-center gap-4 bg-black/40 border border-white/5 rounded-2xl px-6 py-2.5 justify-between">
                                    <button type="button" @click="form.points--" class="text-indigo-400 hover:text-indigo-300 font-bold text-2xl p-1 transition">-</button>
                                    <span class="text-lg font-black text-white w-8 text-center">{{ form.points }}</span>
                                    <button type="button" @click="form.points++" class="text-indigo-400 hover:text-indigo-300 font-bold text-2xl p-1 transition">+</button>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Contextual Observation</label>
                            <textarea 
                                v-model="form.description"
                                rows="4"
                                class="w-full bg-black/40 border-white/5 rounded-2xl text-white text-sm focus:border-indigo-500 focus:ring-indigo-500/10 p-6 transition"
                                placeholder="Describe the behavior or achievement..."
                            ></textarea>
                        </div>

                        <div class="pt-6">
                            <button 
                                @click="submitLog"
                                :disabled="form.processing"
                                class="w-full py-5 bg-indigo-600 rounded-[20px] text-sm font-black uppercase tracking-widest text-white shadow-2xl shadow-indigo-500/30 transition transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50"
                            >
                                {{ form.processing ? 'Syncing Record...' : 'Log Observation to Cloud' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
