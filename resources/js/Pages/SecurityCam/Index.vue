<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ClassroomPlayer from '@/Components/ClassroomPlayer.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const feeds = ref<any[]>([]);
const isLoading = ref(true);

onMounted(async () => {
    try {
        const response = await axios.get(route('api.camera.index'));
        feeds.value = response.data;
    } catch (e) {
        console.error('Failed to fetch security feeds', e);
    } finally {
        isLoading.value = false;
    }
});
</script>

<template>
    <Head title="Security Monitoring — GuardianEdu" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-white">
                        Security <span class="bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent">Monitoring</span>
                    </h2>
                    <p class="mt-1 text-sm text-gray-400">Authorized real-time oversight of educational spaces.</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="px-4 py-2 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        System Operational
                    </div>
                </div>
            </div>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div v-if="isLoading" class="h-96 flex flex-col items-center justify-center gap-6">
                    <div class="h-12 w-12 border-4 border-indigo-500/20 border-t-indigo-500 rounded-full animate-spin"></div>
                    <p class="text-gray-500 font-medium">Initializing secure streams...</p>
                </div>

                <div v-else-if="feeds.length === 0" class="bg-white/5 border border-white/10 rounded-3xl p-20 text-center">
                    <div class="h-20 w-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="h-10 w-10 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">No Authorized Feeds</h3>
                    <p class="text-gray-500 max-w-sm mx-auto">You do not have permission to view any active classroom streams at this time.</p>
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-8 animate-fade-in">
                    <div v-for="feed in feeds" :key="feed.id" class="group relative bg-white/5 border border-white/10 rounded-3xl overflow-hidden shadow-2xl transition hover:border-indigo-500/30">
                        <div class="p-6 border-b border-white/5 flex items-center justify-between bg-black/40">
                            <div>
                                <h4 class="text-sm font-bold text-white uppercase tracking-tight">{{ feed.display_name }}</h4>
                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-0.5">{{ feed.room_id }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="h-2 w-2 rounded-full bg-rose-500 animate-pulse"></span>
                                <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest">Live</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <ClassroomPlayer 
                                :stream-url="feed.playback_url"
                                :room-name="feed.room_id"
                                :is-active="true"
                            />
                        </div>
                        <div class="px-6 py-4 bg-black/20 flex items-center justify-between">
                             <div class="flex -space-x-2">
                                <div v-for="i in 3" :key="i" class="h-6 w-6 rounded-full border-2 border-zinc-900 bg-gray-700"></div>
                             </div>
                             <button class="text-[10px] font-bold text-indigo-400 hover:text-indigo-300 transition">Detailed Protocol</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.5s ease-out forwards;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
