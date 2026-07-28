<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    enquiries: any[];
}>();

const getStatusColor = (status: string) => {
    switch(status) {
        case 'resolved': return 'text-emerald-400 bg-emerald-500/10';
        case 'contacted': return 'text-indigo-400 bg-indigo-500/10';
        default: return 'text-amber-400 bg-amber-500/10';
    }
};
</script>

<template>
    <Head title="Lead Intelligence" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-black text-white">Lead <span class="g-gradient-text">Intelligence</span></h2>
            <p class="text-gray-500 text-sm mt-1 uppercase tracking-widest font-black text-[10px]">Prospective visitor enquiries and conversion pipeline.</p>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="g-card overflow-hidden">
                    <div class="p-6 bg-white/[0.03] border-b border-white/5 flex justify-between items-center">
                        <span class="text-xs font-black text-white uppercase tracking-widest italic font-mono">Enquiry Manifest</span>
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 bg-white/5 text-gray-400 rounded-full text-[10px] font-bold">{{ enquiries.length }} Prospects Captured</span>
                        </div>
                    </div>

                    <div class="p-8 space-y-6">
                        <div v-for="lead in enquiries" :key="lead.id" class="p-8 bg-white/5 rounded-[40px] border border-white/5 hover:border-indigo-500/30 transition group flex flex-col md:flex-row justify-between items-start md:items-center gap-8 relative overflow-hidden">
                            <div class="absolute -top-10 -right-10 opacity-5 group-hover:scale-110 transition duration-700 pointer-events-none">
                                <span class="text-[120px] font-black italic">{{ lead.id }}</span>
                            </div>
                            
                            <div class="flex-1 space-y-4">
                                <div class="flex items-center gap-4">
                                    <h4 class="text-xl font-black text-white uppercase tracking-tight">{{ lead.name }}</h4>
                                    <span :class="getStatusColor(lead.status)" class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                        {{ lead.status }}
                                    </span>
                                </div>
                                <div class="flex flex-wrap items-center gap-6 text-[10px] font-black text-gray-500 uppercase tracking-widest italic">
                                    <div class="flex items-center gap-2">
                                        <span class="text-indigo-500">EMAIL:</span>
                                        <span class="text-white">{{ lead.email }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-indigo-500">PHONE:</span>
                                        <span class="text-white">{{ lead.phone || 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-indigo-500">TIMESTAMP:</span>
                                        <span class="text-white">{{ new Date(lead.created_at).toLocaleString() }}</span>
                                    </div>
                                </div>
                                <div class="p-6 bg-black/40 rounded-3xl border border-white/5 text-sm text-gray-400 leading-loose italic">
                                    "{{ lead.message }}"
                                </div>
                            </div>

                            <div class="flex md:flex-col gap-3">
                                <button class="px-8 py-3 bg-white text-black rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition shadow-xl active:scale-95">
                                    Mark Contacted
                                </button>
                                <button class="px-8 py-3 bg-white/5 border border-white/10 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition active:scale-95">
                                    Resolve
                                </button>
                            </div>
                        </div>

                        <div v-if="!enquiries.length" class="py-40 text-center">
                            <h3 class="text-2xl font-black text-gray-700 uppercase italic tracking-widest">No Lead Capture Recorded</h3>
                            <p class="text-gray-800 mt-2 font-medium">Prospective enquiries from the landing page will manifest here.</p>
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
