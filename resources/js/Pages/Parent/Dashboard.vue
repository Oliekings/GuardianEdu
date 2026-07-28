<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    children: any[];
    announcements: any[];
}>();

const gradeColor = (pct: number) => {
    if (pct >= 90) return 'text-emerald-400';
    if (pct >= 80) return 'text-blue-400';
    if (pct >= 70) return 'text-amber-400';
    return 'text-rose-400';
};
</script>

<template>
    <Head title="Parent Portal" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-extrabold tracking-tight text-white">
                Guardian <span class="g-gradient-text">Dashboard</span>
            </h2>
            <p class="mt-1 text-sm text-gray-500">Stay connected with your children's academic journey and school life.</p>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">

                <!-- Children Cards -->
                <div v-if="children.length" class="space-y-8">
                    <div v-for="child in children" :key="child.id" class="g-card overflow-hidden">
                        <!-- Child Header -->
                        <div class="p-8 bg-gradient-to-r from-indigo-900/30 to-purple-900/30 border-b border-white/5">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-2xl font-black shadow-xl shadow-indigo-500/20">
                                        {{ child.name.charAt(0) }}
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">{{ child.name }}</h3>
                                        <p class="text-sm text-gray-400">{{ child.admission_number }} • {{ child.room_id }} • {{ child.status }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <Link :href="route('parent.child.grades', child.id)"
                                        class="px-5 py-2.5 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-xl text-xs font-bold hover:bg-indigo-500/20 transition">
                                        📊 Full Grades
                                    </Link>
                                    <Link :href="route('parent.child.behavior', child.id)"
                                        class="px-5 py-2.5 bg-purple-500/10 text-purple-400 border border-purple-500/20 rounded-xl text-xs font-bold hover:bg-purple-500/20 transition">
                                        📋 Behavior Log
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Child Stats -->
                        <div class="p-8">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                                <div class="text-center p-4 rounded-xl bg-white/[0.02] border border-white/5">
                                    <div class="text-2xl font-black text-white">{{ child.gpa.toFixed(2) }}</div>
                                    <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">GPA</div>
                                </div>
                                <div class="text-center p-4 rounded-xl bg-white/[0.02] border border-white/5">
                                    <div class="text-2xl font-black" :class="child.behavior_score >= 0 ? 'text-emerald-400' : 'text-rose-400'">
                                        {{ child.behavior_score > 0 ? '+' : '' }}{{ child.behavior_score }}
                                    </div>
                                    <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Behavior</div>
                                </div>
                                <div class="text-center p-4 rounded-xl bg-white/[0.02] border border-white/5">
                                    <div class="text-2xl font-black text-amber-400">{{ child.pending_assignments }}</div>
                                    <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Pending Work</div>
                                </div>
                                <div class="text-center p-4 rounded-xl bg-white/[0.02] border border-white/5">
                                    <div class="text-2xl font-black text-blue-400">{{ child.attendance_rate }}</div>
                                    <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Attendance</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Latest Grades -->
                                <div>
                                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Latest Grades</h4>
                                    <div v-if="child.latest_grades.length" class="space-y-3">
                                        <div v-for="g in child.latest_grades" :key="g.subject" class="flex items-center justify-between p-3 rounded-xl bg-white/[0.02] border border-white/5">
                                            <span class="text-sm font-medium text-white">{{ g.subject }}</span>
                                            <div class="text-right">
                                                <span :class="gradeColor(g.percentage)" class="text-lg font-black">{{ g.letter_grade }}</span>
                                                <span class="text-[10px] text-gray-500 ml-2">{{ g.score }}/{{ g.max_score }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <p v-else class="text-sm text-gray-500">No grades yet.</p>
                                </div>

                                <!-- Recent Behavior -->
                                <div>
                                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Recent Behavior</h4>
                                    <div v-if="child.recent_behavior.length" class="space-y-3">
                                        <div v-for="b in child.recent_behavior" :key="b.category + b.date" class="flex items-center gap-3 p-3 rounded-xl bg-white/[0.02] border border-white/5">
                                            <div :class="b.type === 'kudos' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400'"
                                                 class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0">
                                                {{ b.type === 'kudos' ? '+' : '' }}{{ b.points }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-white truncate">{{ b.category }}</p>
                                                <p class="text-[10px] text-gray-500">{{ b.teacher }} • {{ b.date }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <p v-else class="text-sm text-gray-500">No behavioral records.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="g-card p-16 text-center">
                    <div class="text-5xl mb-6">👨‍👩‍👧</div>
                    <h3 class="text-xl font-bold text-white mb-2">No Children Linked</h3>
                    <p class="text-gray-500">Contact the school administrator to link your children to your account.</p>
                </div>

                <!-- Announcements -->
                <div v-if="announcements.length" class="g-card p-8">
                    <h3 class="text-lg font-bold text-white mb-6">School Announcements</h3>
                    <div class="space-y-5">
                        <div v-for="a in announcements" :key="a.id" class="pb-5 border-b border-white/5 last:border-0 last:pb-0">
                            <div class="flex items-start gap-4">
                                <div class="h-10 w-10 shrink-0 rounded-xl bg-orange-500/10 border border-orange-500/20 flex items-center justify-center text-orange-500">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white">{{ a.title }}</h4>
                                    <p class="text-xs text-gray-400 mt-1 leading-relaxed">{{ a.content }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
