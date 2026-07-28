<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    schedule: any[];
    pendingAssignments: any[];
    recentGrades: any[];
    announcements: any[];
    stats: {
        gpa: number;
        behavior_score: number;
        assignments_pending: number;
        attendance_rate: string;
    };
}>();

const gradeColor = (pct: number) => {
    if (pct >= 90) return 'text-emerald-400';
    if (pct >= 80) return 'text-blue-400';
    if (pct >= 70) return 'text-amber-400';
    return 'text-rose-400';
};

const typeIcon = (type: string) => {
    switch (type) {
        case 'quiz': return '⚡';
        case 'test': return '📝';
        default: return '📄';
    }
};
</script>

<template>
    <Head title="Student Portal" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-extrabold tracking-tight text-white">
                Explorer <span class="g-gradient-text">Dashboard</span>
            </h2>
            <p class="mt-1 text-sm text-gray-500">Your academic journey — schedule, assignments, and achievements.</p>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
                <!-- Stats Row -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <StatCard title="GPA" :value="stats.gpa.toFixed(2)" />
                    <StatCard title="Behavior" :value="(stats.behavior_score > 0 ? '+' : '') + stats.behavior_score" :colorClass="stats.behavior_score >= 0 ? 'text-emerald-400' : 'text-rose-400'" />
                    <StatCard title="Pending" :value="stats.assignments_pending" colorClass="text-amber-400" />
                    <StatCard title="Attendance" :value="stats.attendance_rate" colorClass="text-blue-400" />
                </div>

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                    <!-- Main Content -->
                    <div class="lg:col-span-8 space-y-8">

                        <!-- Today's Schedule -->
                        <div class="g-card p-8">
                            <h3 class="text-xl font-bold text-white mb-6">Today's Roadmap</h3>
                            <div v-if="schedule.length" class="space-y-3">
                                <div v-for="item in schedule" :key="item.id" :class="[
                                    'p-5 rounded-2xl border transition-all duration-300',
                                    item.active
                                        ? 'bg-indigo-600/10 border-indigo-500/30 ring-1 ring-indigo-500/20'
                                        : item.completed
                                            ? 'bg-white/[0.01] border-white/5 opacity-50'
                                            : 'bg-white/[0.02] border-white/5 hover:bg-white/[0.05]'
                                ]">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="text-xs font-bold text-gray-400 font-mono w-24">{{ item.time }}</div>
                                            <div>
                                                <h4 class="text-sm font-bold text-white">{{ item.name }}</h4>
                                                <p class="text-[10px] text-gray-500 uppercase tracking-widest">{{ item.room }} • {{ item.teacher }}</p>
                                            </div>
                                        </div>
                                        <div v-if="item.active" class="g-badge bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 animate-pulse">
                                            NOW
                                        </div>
                                        <div v-else-if="item.completed" class="text-[10px] text-emerald-500/50 font-bold">✓ DONE</div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-12">
                                <div class="text-4xl mb-4">📅</div>
                                <p class="text-gray-500 font-medium">No classes scheduled for today.</p>
                            </div>
                        </div>

                        <!-- Pending Assignments -->
                        <div class="g-card p-8">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-white">Pending Quests</h3>
                                <Link :href="route('student.assignments.index')" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition">View All →</Link>
                            </div>
                            <div v-if="pendingAssignments.length" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <Link v-for="task in pendingAssignments" :key="task.id"
                                    :href="route('student.assignments.show', task.id)"
                                    class="p-5 rounded-2xl bg-white/[0.02] border border-white/5 hover:border-indigo-500/30 transition-all group block">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">{{ typeIcon(task.type) }} {{ task.subject }}</span>
                                        <span :class="task.is_past_due ? 'text-rose-500' : 'text-amber-500'" class="text-[10px] font-bold">{{ task.due_label }}</span>
                                    </div>
                                    <h4 class="text-sm font-bold text-white mb-3 group-hover:text-indigo-400 transition">{{ task.title }}</h4>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">{{ task.total_points }} XP</span>
                                        <span v-if="task.time_limit" class="text-[10px] text-gray-600 font-mono">⏱ {{ task.time_limit }}m</span>
                                    </div>
                                </Link>
                            </div>
                            <div v-else class="text-center py-12">
                                <div class="text-4xl mb-4">🎉</div>
                                <p class="text-gray-500 font-medium">All caught up! No pending assignments.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Sidebar -->
                    <div class="lg:col-span-4 space-y-8">
                        <!-- Achievement Hub -->
                        <div class="bg-gradient-to-br from-indigo-900/40 to-purple-900/40 border border-white/10 rounded-3xl p-8 backdrop-blur-sm shadow-xl relative overflow-hidden">
                            <div class="absolute -right-8 -top-8 w-24 h-24 bg-indigo-500/20 blur-3xl"></div>
                            <h3 class="text-lg font-bold text-white mb-4">Achievement Hub</h3>
                            <div class="text-center py-4">
                                <div class="text-5xl font-black text-white mb-1">{{ stats.gpa.toFixed(2) }}</div>
                                <div class="text-[10px] text-indigo-300 font-bold uppercase tracking-[0.2em]">Cumulative GPA</div>
                            </div>
                            <div class="mt-6">
                                <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-500"
                                         :style="{ width: Math.min((stats.gpa / 4.0) * 100, 100) + '%' }"></div>
                                </div>
                                <div class="flex justify-between text-[11px] font-medium mt-2">
                                    <span class="text-gray-400">Progress to Honor Roll</span>
                                    <span class="text-white">{{ stats.gpa >= 3.5 ? '🏆 Honor Roll!' : (3.5 - stats.gpa).toFixed(2) + ' to go' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Grades -->
                        <div class="g-card p-8">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-white">Recent Grades</h3>
                                <Link :href="route('student.grades.index')" class="text-xs font-bold text-indigo-400 hover:text-indigo-300">All →</Link>
                            </div>
                            <div v-if="recentGrades.length" class="space-y-4">
                                <div v-for="grade in recentGrades" :key="grade.id" class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-semibold text-white">{{ grade.subject }}</h4>
                                        <p class="text-[10px] text-gray-500">{{ grade.term }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span :class="gradeColor(grade.percentage)" class="text-lg font-bold">{{ grade.letter_grade }}</span>
                                        <p class="text-[10px] text-gray-500">{{ grade.score }}/{{ grade.max_score }}</p>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-8">
                                <p class="text-gray-500 text-sm">No grades yet.</p>
                            </div>
                        </div>

                        <!-- Announcements -->
                        <div class="g-card p-8">
                            <h3 class="text-lg font-bold text-white mb-6">School Bulletin</h3>
                            <div v-if="announcements.length" class="space-y-5">
                                <div v-for="a in announcements" :key="a.id" class="flex gap-4 group">
                                    <div class="h-10 w-10 shrink-0 rounded-xl bg-orange-500/10 border border-orange-500/20 flex items-center justify-center text-orange-500 group-hover:bg-orange-500 group-hover:text-white transition-all duration-300">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-white">{{ a.title }}</h4>
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ a.content }}</p>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-8">
                                <p class="text-gray-500 text-sm">No announcements.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
