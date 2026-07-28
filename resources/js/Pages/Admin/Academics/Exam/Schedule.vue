<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps<{
    exam: any;
}>();

const form = useForm({
    subject_name: '',
    room_name: '',
    date: '',
    start_time: '',
    end_time: '',
    max_marks: 100,
    passing_marks: 40,
});

const submit = () => {
    form.post(route('admin.exams.schedule.store', props.exam.id), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Exam Scheduling" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('admin.exams.index')" class="p-2 bg-white/5 rounded-xl text-gray-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </Link>
                <div>
                    <h2 class="text-3xl font-black text-white italic capitalize">{{ exam.name }} <span class="g-gradient-text">Matrix</span></h2>
                    <p class="text-gray-500 text-sm mt-1 uppercase tracking-widest font-black text-[10px]">Session {{ exam.session }} | Time-Table Synchronization</p>
                </div>
            </div>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Schedule Form -->
                <div class="lg:col-span-1">
                    <div class="g-card p-10 border-t-4 border-indigo-500 shadow-2xl shadow-indigo-500/10">
                        <h3 class="text-xl font-black text-white mb-8 uppercase tracking-widest text-[10px]">Add <span class="text-indigo-400">Subject</span></h3>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="g-label">Subject Label *</label>
                                <input v-model="form.subject_name" class="g-input" placeholder="e.g. Physics - I" />
                                <p v-if="form.errors.subject_name" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.subject_name }}</p>
                            </div>
                            <div>
                                <label class="g-label">Venue / Room</label>
                                <input v-model="form.room_name" class="g-input" placeholder="Hall B-102" />
                            </div>
                            <div>
                                <label class="g-label">Evaluation Date *</label>
                                <input v-model="form.date" type="date" class="g-input" />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="g-label">H-Start</label>
                                    <input v-model="form.start_time" type="time" class="g-input text-xs" />
                                </div>
                                <div>
                                    <label class="g-label">H-End</label>
                                    <input v-model="form.end_time" type="time" class="g-input text-xs" />
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="g-label">Max Marks</label>
                                    <input v-model="form.max_marks" type="number" class="g-input font-black" />
                                </div>
                                <div>
                                    <label class="g-label">Pass Marks</label>
                                    <input v-model="form.passing_marks" type="number" class="g-input font-black text-indigo-400" />
                                </div>
                            </div>
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-5 bg-white text-black rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition shadow-xl active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Syncing...' : 'Commit Schedule' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Schedule Table -->
                <div class="lg:col-span-2">
                    <div class="g-card overflow-hidden">
                        <div class="p-6 bg-white/[0.03] border-b border-white/5 flex justify-between items-center">
                            <span class="text-xs font-black text-white uppercase tracking-widest italic font-mono">Series Temporal Manifest</span>
                            <span class="px-3 py-1 bg-indigo-500/10 text-indigo-400 rounded-full text-[10px] font-bold">{{ exam.schedules?.length || 0 }} Papers Scheduled</span>
                        </div>
                        <table class="w-full text-left font-mono">
                            <thead class="bg-white/[0.01]">
                                <tr class="border-b border-white/5">
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500">Subject / Venue</th>
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500">Temporal Slot</th>
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500 text-center">Marks Matrix</th>
                                    <th class="p-4 text-[10px] uppercase font-bold text-gray-500 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr v-for="sch in exam.schedules" :key="sch.id" class="hover:bg-white/[0.02] transition">
                                    <td class="p-4">
                                        <div class="font-black text-white uppercase tracking-tight text-xs">{{ sch.subject_name }}</div>
                                        <div class="text-[9px] text-gray-600 uppercase mt-0.5">@ {{ sch.room_name || 'Standard Hall' }}</div>
                                    </td>
                                    <td class="p-4 text-xs font-bold text-gray-400">
                                        <div class="flex items-center gap-2">
                                            <span>{{ sch.date }}</span>
                                            <span class="text-indigo-500">|</span>
                                            <span class="text-[10px]">{{ sch.start_time }} - {{ sch.end_time }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="text-[11px] font-black text-white shadow-inner bg-white/5 rounded-lg py-1">
                                            {{ sch.passing_marks }} / <span class="text-indigo-500">{{ sch.max_marks }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-right">
                                        <Link 
                                            :href="route('staff.marks.index', sch.id)"
                                            class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-[9px] font-black uppercase text-gray-400 hover:bg-emerald-500 hover:text-white transition"
                                        >
                                            Enter Marks
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="!exam.schedules?.length">
                                    <td colspan="4" class="p-20 text-center text-gray-600 italic text-sm italic">Chronology is currently unmapped for this series.</td>
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
