<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const props = defineProps({
    status: Number,
});

const titleMap: Record<number, string> = {
    503: 'Service Unavailable',
    500: 'Server Error',
    404: 'Area Restricted',
    403: 'Forbidden Access',
};

const descriptionMap: Record<number, string> = {
    503: 'The ecosystem is currently undergoing scheduled maintenance. Services will resume shortly.',
    500: 'A critical error occurred within the GuardianEdu infrastructure. Our engineers have been logged.',
    404: 'The endpoint you requested does not exist or has been moved from this sector.',
    403: 'Your current security clearance does not allow access to this module.',
};

const title = computed(() => titleMap[props.status || 500] || 'System Anomaly');
const description = computed(() => descriptionMap[props.status || 500] || 'An unexpected anomaly was detected in the matrix.');
</script>

<template>
    <Head :title="title + ' — GuardianEdu'" />

    <div class="relative min-h-screen bg-[#050505] text-white selection:bg-indigo-500/30 overflow-hidden font-sans flex items-center justify-center p-6">
        
        <!-- Animated Background Glitches (Subtle) -->
        <div class="absolute inset-0 z-0 pointer-events-none opacity-40 mix-blend-screen">
            <div class="absolute top-[10%] left-[20%] w-[40%] h-[1px] bg-indigo-500/50 shadow-[0_0_8px_rgba(99,102,241,0.8)] opacity-0 animate-[glitch_3s_infinite]"></div>
            <div class="absolute top-[40%] right-[10%] w-[20%] h-[1px] bg-rose-500/50 shadow-[0_0_8px_rgba(244,63,94,0.8)] opacity-0 animate-[glitch_2s_infinite_1s]"></div>
            <div class="absolute bottom-[20%] left-[30%] w-[30%] h-[1px] bg-indigo-500/50 shadow-[0_0_8px_rgba(99,102,241,0.8)] opacity-0 animate-[glitch_4s_infinite_2s]"></div>
            
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80%] h-[80%] rounded-full bg-rose-600/5 blur-[200px] animate-pulse"></div>
        </div>

        <div class="relative z-10 w-full max-w-2xl text-center">
            
            <div class="flex justify-center mb-12">
                <div class="relative flex items-center justify-center h-24 w-24 bg-black/60 border border-white/10 rounded-3xl backdrop-blur-3xl shadow-2xl overflow-hidden group">
                    <div class="absolute inset-0 opacity-20 bg-gradient-to-tr from-rose-500 to-indigo-500 group-hover:opacity-100 transition duration-500"></div>
                    <ApplicationLogo class="relative z-10 h-10 w-10 fill-current text-white" />
                </div>
            </div>

            <div class="bg-white/5 border border-white/10 p-12 rounded-[40px] backdrop-blur-3xl shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                
                <h1 class="text-7xl md:text-9xl font-black text-transparent bg-clip-text bg-gradient-to-br from-white to-gray-600 tracking-tighter mb-4 opacity-90 drop-shadow-[0_0_10px_rgba(255,255,255,0.1)]">
                    {{ status }}
                </h1>
                
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-[10px] font-black uppercase tracking-widest mb-8">
                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                    {{ title }}
                </div>

                <p class="text-gray-400 text-sm md:text-base leading-relaxed max-w-lg mx-auto font-medium">
                    {{ description }}
                </p>

                <div class="mt-12 flex justify-center">
                    <Link 
                        href="/dashboard"
                        class="px-8 py-4 bg-white text-black text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-gray-200 transition transform hover:scale-[1.02] active:scale-[0.98]"
                    >
                        Return to Dashboard
                    </Link>
                </div>
            </div>
            
            <p class="mt-12 text-xs text-gray-700 font-bold uppercase tracking-widest">
                GuardianEdu &copy; Ecosystem Monitoring
            </p>
        </div>

    </div>
</template>

<style scoped>
@keyframes glitch {
    0% { opacity: 0; transform: translateX(-10%); }
    2% { opacity: 1; transform: translateX(10%); }
    4% { opacity: 0; transform: translateX(0); }
    100% { opacity: 0; }
}
</style>
