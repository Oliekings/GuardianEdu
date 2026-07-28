<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    announcements: any[];
}>();

const form = useForm({
    title: '',
    content: '',
    target_role: 'all',
    expires_at: '',
});

const submit = () => {
    form.post(route('admin.announcements.store'), {
        onSuccess: () => form.reset(),
    });
};

const deleteAnnouncement = (id: number) => {
    if (confirm('Recall this announcement?')) {
        router.delete(route('admin.announcements.destroy', id));
    }
};

const getRoleBadgeClass = (role: string) => {
    switch (role) {
        case 'all': return 'bg-white/10 text-white';
        case 'teacher': return 'bg-amber-500/10 text-amber-500';
        case 'student': return 'bg-indigo-500/10 text-indigo-500';
        case 'parent': return 'bg-emerald-500/10 text-emerald-500';
        default: return 'bg-gray-500/10 text-gray-500';
    }
};
</script>

<template>
    <Head title="Institutional Announcements" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-black text-white">Public <span class="g-gradient-text">Relations</span></h2>
                    <p class="mt-1 text-sm text-gray-500 italic">Institutional broadcasting and community communication.</p>
                </div>
                <div class="px-4 py-2 bg-indigo-500/10 border border-indigo-500/20 rounded-full text-[10px] font-black text-indigo-400 uppercase tracking-widest">
                    Live Nodes Activated
                </div>
            </div>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8 font-primary">
            <div class="mx-auto max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Composer Panel -->
                <div class="lg:col-span-1 space-y-8">
                    <div class="g-card p-10 border-t-4 border-indigo-500 shadow-2xl shadow-indigo-500/10 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-8 opacity-5">
                            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" /></svg>
                        </div>
                        
                        <h3 class="text-xl font-black text-white mb-8 uppercase tracking-widest text-[10px]">Broadcast <span class="text-indigo-400">Composer</span></h3>
                        
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="g-label">Bulletin Heading *</label>
                                <input v-model="form.title" class="g-input" placeholder="e.g. Annual Sports Meet 2026" />
                                <p v-if="form.errors.title" class="text-rose-400 text-[10px] mt-1 uppercase font-bold">{{ form.errors.title }}</p>
                            </div>

                            <div>
                                <label class="g-label">Target Audience</label>
                                <select v-model="form.target_role" class="g-input">
                                    <option value="all">Entire Institution (Global)</option>
                                    <option value="teacher">Academic Staff Only</option>
                                    <option value="student">Student Body Only</option>
                                    <option value="parent">Parent Guild Only</option>
                                </select>
                            </div>

                            <div>
                                <label class="g-label">Bulletin Content *</label>
                                <textarea v-model="form.content" class="g-input min-h-[150px]" placeholder="Type your detailed announcement here..."></textarea>
                            </div>

                            <div>
                                <label class="g-label text-[10px]">Expiry Date (Optional)</label>
                                <input v-model="form.expires_at" type="date" class="g-input" />
                            </div>

                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full py-5 bg-white text-black rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition shadow-xl active:scale-95 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Transmitting...' : 'Broadcast Bulletin' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Feed Panel -->
                <div class="lg:col-span-2 space-y-8">
                    <div v-for="ann in announcements" :key="ann.id" class="g-card group p-8 hover:border-indigo-500/30 transition relative overflow-hidden">
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-lg shadow-inner border border-white/5">
                                    📣
                                </div>
                                <div>
                                    <h4 class="text-lg font-black text-white uppercase tracking-tight">{{ ann.title }}</h4>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-[9px] font-bold uppercase tracking-widest text-gray-500">By {{ ann.user?.name }}</span>
                                        <span class="w-1 h-1 bg-gray-700 rounded-full"></span>
                                        <span class="text-[9px] font-bold uppercase tracking-widest text-gray-500">{{ new Date(ann.created_at).toLocaleDateString() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span :class="getRoleBadgeClass(ann.target_role)" class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                    {{ ann.target_role === 'all' ? 'Institutional' : ann.target_role }}
                                </span>
                                <button @click="deleteAnnouncement(ann.id)" class="opacity-0 group-hover:opacity-100 transition p-2 bg-rose-500/5 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>

                        <div class="p-6 bg-white/[0.01] border border-white/5 rounded-3xl text-sm text-gray-400 italic leading-relaxed whitespace-pre-line">
                            {{ ann.content }}
                        </div>

                        <div v-if="ann.expires_at" class="mt-4 flex items-center gap-2 text-[9px] font-bold uppercase text-amber-500/60 tracking-widest italic">
                            <span>⏳ T-Minus Expiry:</span>
                            <span>{{ new Date(ann.expires_at).toLocaleDateString() }}</span>
                        </div>
                    </div>

                    <div v-if="!announcements.length" class="text-center py-32 g-card bg-transparent border-dashed border-2 border-white/5">
                        <div class="text-5xl mb-6 grayscale opacity-20">📡</div>
                        <h3 class="text-xl font-black text-gray-600 uppercase tracking-widest">Awaiting Transmission</h3>
                        <p class="text-gray-700 mt-2 italic">Broadcast your first institution-wide announcement.</p>
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

@font-face {
    font-family: 'primary';
    src: url('https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap');
}
.font-primary {
    font-family: 'Outfit', sans-serif;
}
</style>
