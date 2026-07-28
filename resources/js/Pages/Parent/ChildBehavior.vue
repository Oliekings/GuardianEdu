<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    student: { id: number; name: string; room_id: string };
    logs: any[];
    summary: {
        total_score: number;
        kudos_count: number;
        incident_count: number;
        kudos_points: number;
        incident_points: number;
    };
}>();
</script>

<template>
    <Head :title="student.name + ' — Behavior'" />
    <AuthenticatedLayout>
        <template #header>
            <Link :href="route('parent.dashboard')" class="text-gray-500 hover:text-white transition text-sm">← Back to Dashboard</Link>
            <div class="flex items-center gap-4 mt-2">
                <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white text-xl font-black">
                    {{ student.name.charAt(0) }}
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-white">{{ student.name }}'s <span class="g-gradient-text">Behavior</span></h2>
                    <p class="text-sm text-gray-500">{{ student.room_id }}</p>
                </div>
            </div>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">

                <!-- Summary Stats -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="g-card p-6 text-center md:col-span-1">
                        <div class="text-3xl font-black" :class="summary.total_score >= 0 ? 'text-emerald-400' : 'text-rose-400'">
                            {{ summary.total_score > 0 ? '+' : '' }}{{ summary.total_score }}
                        </div>
                        <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Total Score</div>
                    </div>
                    <div class="g-card p-6 text-center">
                        <div class="text-3xl font-black text-emerald-400">{{ summary.kudos_count }}</div>
                        <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Kudos</div>
                    </div>
                    <div class="g-card p-6 text-center">
                        <div class="text-3xl font-black text-rose-400">{{ summary.incident_count }}</div>
                        <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Incidents</div>
                    </div>
                    <div class="g-card p-6 text-center">
                        <div class="text-3xl font-black text-emerald-400">+{{ summary.kudos_points }}</div>
                        <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Kudos Pts</div>
                    </div>
                    <div class="g-card p-6 text-center">
                        <div class="text-3xl font-black text-rose-400">{{ summary.incident_points }}</div>
                        <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Incident Pts</div>
                    </div>
                </div>

                <!-- Balance Bar -->
                <div class="g-card p-6">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Kudos vs Incidents</h3>
                    <div class="flex h-4 rounded-full overflow-hidden bg-white/5">
                        <div v-if="summary.kudos_count + summary.incident_count > 0"
                             class="bg-gradient-to-r from-emerald-500 to-emerald-400 transition-all duration-700"
                             :style="{ width: (summary.kudos_count / (summary.kudos_count + summary.incident_count)) * 100 + '%' }"></div>
                        <div v-if="summary.kudos_count + summary.incident_count > 0"
                             class="bg-gradient-to-r from-rose-500 to-rose-400 transition-all duration-700"
                             :style="{ width: (summary.incident_count / (summary.kudos_count + summary.incident_count)) * 100 + '%' }"></div>
                    </div>
                    <div class="flex justify-between text-[10px] mt-2">
                        <span class="text-emerald-400 font-bold">Kudos ({{ summary.kudos_count }})</span>
                        <span class="text-rose-400 font-bold">Incidents ({{ summary.incident_count }})</span>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="g-card p-8">
                    <h3 class="text-lg font-bold text-white mb-6">Behavioral Timeline</h3>
                    <div v-if="logs.length" class="relative">
                        <div class="absolute left-5 top-0 bottom-0 w-px bg-white/5"></div>
                        <div v-for="log in logs" :key="log.id" class="relative pl-14 pb-8 last:pb-0">
                            <div :class="log.type === 'kudos' ? 'bg-emerald-500 shadow-emerald-500/30' : 'bg-rose-500 shadow-rose-500/30'"
                                 class="absolute left-3 top-1 h-5 w-5 rounded-full flex items-center justify-center text-white shadow-lg">
                                <svg v-if="log.type === 'kudos'" class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                <svg v-else class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                            </div>
                            <div class="p-4 rounded-xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.04] transition">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span :class="log.type === 'kudos' ? 'text-emerald-400' : 'text-rose-400'" class="text-sm font-bold">
                                            {{ log.type === 'kudos' ? '👏' : '⚠️' }} {{ log.category }}
                                        </span>
                                        <span :class="log.points > 0 ? 'text-emerald-400' : 'text-rose-400'" class="text-xs font-bold">
                                            {{ log.points > 0 ? '+' : '' }}{{ log.points }} pts
                                        </span>
                                    </div>
                                    <span class="text-[10px] text-gray-600">{{ log.date }}</span>
                                </div>
                                <p v-if="log.description" class="text-xs text-gray-400 mb-1">{{ log.description }}</p>
                                <p class="text-[10px] text-gray-600">Recorded by {{ log.teacher }}</p>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-12">
                        <div class="text-4xl mb-4">📋</div>
                        <p class="text-gray-500">No behavioral records yet.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
