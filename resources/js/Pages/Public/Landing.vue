<script setup lang="ts">
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    school: any;
    sectors: any;
}>();

const form = useForm({
    name: '',
    email: '',
    phone: '',
    message: '',
    school_id: props.school?.id,
});

const submitEnquiry = () => {
    form.post(route('public.enquiry'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Premium Educational Ecosystem" />
    <PublicLayout>
        <!-- Hero Section -->
        <section class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden">
            <!-- Background Elements -->
            <div class="absolute inset-0 z-0">
                <img src="/images/hero.png" alt="Hero Background" class="absolute inset-0 w-full h-full object-cover opacity-40" loading="lazy" />
                <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-[#050505]/40 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 to-transparent"></div>
                <div class="absolute inset-0 bg-[url('/images/noise.svg')] opacity-[0.05] pointer-events-none"></div>
            </div>

            <div class="max-w-7xl mx-auto px-6 relative z-10 text-left w-full mt-20">
                <div class="inline-flex items-center gap-2 px-6 py-2 rounded-full border border-white/10 bg-black/30 backdrop-blur-md mb-8 g-slide-up">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-300">Institutional Innovation Live</span>
                </div>
                
                <h1 class="text-7xl md:text-9xl font-black tracking-[ -0.02em] leading-[0.9] mb-8 drop-shadow-2xl g-slide-up" style="animation-delay: 0.1s;">
                    Defining the <br>
                    <span class="g-gradient-text">Academic Era</span>
                </h1>
                
                <p class="text-xl md:text-2xl text-gray-400 max-w-2xl leading-relaxed mb-12 italic drop-shadow-lg g-slide-up" style="animation-delay: 0.2s;">
                    The ultra-modern behavioral monitoring and academic orchestration ecosystem designed for high-performance institutions. Experience total clarity.
                </p>

                <div class="flex flex-col sm:flex-row items-center gap-6 g-slide-up" style="animation-delay: 0.3s;">
                    <a href="#contact" class="px-10 py-5 bg-indigo-600 text-white rounded-full font-black text-xs uppercase tracking-widest hover:bg-indigo-500 transition shadow-2xl shadow-indigo-500/20 text-center">
                        Schedule Visitation
                    </a>
                    <a href="#security" class="px-10 py-5 border border-white/20 text-white rounded-full font-black text-xs uppercase tracking-widest hover:bg-white/10 transition backdrop-blur-xl text-center">
                        Explore Methodology
                    </a>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="relative md:absolute md:bottom-20 left-0 w-full mt-20 md:mt-0 z-20">
                <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-12">
                    <div class="space-y-1">
                        <div class="text-4xl font-black italic tracking-tighter">99.8%</div>
                        <div class="text-[9px] font-black uppercase tracking-widest text-gray-500">Telemetry Accuracy</div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-4xl font-black italic tracking-tighter">12k+</div>
                        <div class="text-[9px] font-black uppercase tracking-widest text-gray-500">Annual Evaluations</div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-4xl font-black italic tracking-tighter">0.5s</div>
                        <div class="text-[9px] font-black uppercase tracking-widest text-gray-500">Tap-In Latency</div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-4xl font-black italic tracking-tighter">24/7</div>
                        <div class="text-[9px] font-black uppercase tracking-widest text-gray-500">Portal Availability</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- The Guardian Gate (Security) -->
        <section id="security" class="py-32 relative">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                    <div class="order-2 lg:order-1 relative group g-card overflow-hidden h-[500px] border border-white/5">
                        <img src="/images/security_scene.png" alt="Guardian Gate" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:scale-105 transition duration-700" loading="lazy" />
                        <div class="absolute inset-x-0 bottom-0 p-8 bg-gradient-to-t from-black via-black/80 to-transparent">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-[2px] bg-indigo-500 shadow-[0_0_15px_rgba(99,102,241,0.8)]"></div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-indigo-400">Identity Scan Active</span>
                            </div>
                        </div>
                    </div>
                    <div class="order-1 lg:order-2 space-y-6">
                        <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-500 mb-2">Phase 1: Entry</h2>
                        <h3 class="text-4xl md:text-6xl font-black tracking-tighter leading-tight">
                            Unrivaled <br><span class="g-gradient-text">Safety Protocol</span>
                        </h3>
                        <p class="text-gray-400 text-lg leading-relaxed font-medium">
                            Premium architecture meets elite security technology. Our discreet, glowing RFID and biometric scanners ensure that only authorized individuals traverse your halls, providing absolute environmental control.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Digital Telemetry Hub -->
        <section id="services" class="py-32 bg-[#080808] relative border-y border-white/5 shadow-[inset_0_0_100px_rgba(0,0,0,0.5)]">
            <div class="absolute inset-0 bg-[url('/images/noise.svg')] opacity-[0.03] pointer-events-none"></div>
            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                    <div class="space-y-6 lg:pr-10">
                        <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-purple-500 mb-2">Phase 2: Oversight</h2>
                        <h3 class="text-4xl md:text-5xl font-black tracking-tighter leading-tight">
                            Complete <span class="g-gradient-text">Biometric</span> & Academic Integration
                        </h3>
                        <div class="space-y-8 mt-10">
                            <div class="flex gap-6 group">
                                <div class="h-12 w-12 rounded-2xl bg-indigo-600/10 border border-indigo-500/30 flex items-center justify-center text-xl grayscale group-hover:grayscale-0 transition shadow-[0_0_20px_rgba(99,102,241,0.1)] group-hover:shadow-[0_0_20px_rgba(99,102,241,0.3)]">📡</div>
                                <div>
                                    <h4 class="text-lg font-black uppercase tracking-tight text-white">Cloud RFID Telemetry</h4>
                                    <p class="text-gray-500 text-sm mt-1 leading-relaxed italic">Real-time attendance syncing across institutions via edge protocols.</p>
                                </div>
                            </div>
                            <div class="flex gap-6 group">
                                <div class="h-12 w-12 rounded-2xl bg-purple-600/10 border border-purple-500/30 flex items-center justify-center text-xl grayscale group-hover:grayscale-0 transition shadow-[0_0_20px_rgba(168,85,247,0.1)] group-hover:shadow-[0_0_20px_rgba(168,85,247,0.3)]">🔒</div>
                                <div>
                                    <h4 class="text-lg font-black uppercase tracking-tight text-white">Absolute Privacy</h4>
                                    <p class="text-gray-500 text-sm mt-1 leading-relaxed italic">Encrypted 1-to-1 communication channels and multi-branch isolation.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative group h-[600px] g-card overflow-hidden border border-white/5">
                        <img src="/images/telemetry_scene.png" alt="Digital Telemetry" class="absolute inset-0 w-full h-full object-cover opacity-70 group-hover:opacity-100 transition duration-1000" loading="lazy" />
                        <!-- HUD Elements -->
                        <div class="absolute top-8 left-8 py-2 px-4 bg-black/60 backdrop-blur-xl border border-purple-500/50 rounded-full text-[10px] font-black uppercase tracking-widest text-purple-400 shadow-xl shadow-purple-500/10">
                            <span class="inline-block w-2 h-2 rounded-full bg-purple-500 animate-pulse mr-2"></span> Active Node: 4.2
                        </div>
                        <div class="absolute bottom-8 right-8 py-2 px-4 bg-black/60 backdrop-blur-xl border border-indigo-500/50 rounded-full text-[10px] font-black uppercase tracking-widest text-indigo-400 shadow-xl shadow-indigo-500/10">
                            Live Feed: 99.8% Sync
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Elite Evaluation (Academic Lab) -->
        <section id="academics" class="py-32 relative">
            <div class="absolute top-[10%] left-[20%] w-[40%] h-[40%] rounded-full bg-blue-600/10 blur-[150px] pointer-events-none"></div>
            <div class="max-w-7xl mx-auto px-6 text-center mb-16 relative z-10">
                <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-500 mb-4">Phase 3: Achievement</h2>
                <h3 class="text-4xl md:text-6xl font-black tracking-tighter leading-tight">
                    Advanced <span class="g-gradient-text">Evaluation Hub</span>
                </h3>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto mt-6 leading-relaxed">
                    Futuristic laboratories integrated with high-fidelity marksheet orchestration. Where an advanced digital curriculum meets real-time learning analytics.
                </p>
            </div>
            <div class="relative max-w-7xl mx-auto rounded-[3rem] overflow-hidden h-[600px] border border-white/5 bg-black z-10 shadow-2xl">
                <img src="/images/academic_lab.png" alt="Academic Lab" class="w-full h-full object-cover opacity-80" style="mask-image: linear-gradient(to bottom, black 70%, transparent 100%); -webkit-mask-image: linear-gradient(to bottom, black 70%, transparent 100%);" loading="lazy" />
                
                <div class="absolute inset-0 flex flex-col items-center justify-center gap-6 pointer-events-none backdrop-blur-[2px]">
                    <div class="g-badge bg-black/60 text-blue-400 border border-blue-500/50 backdrop-blur-md shadow-lg shadow-blue-500/20 translate-x-32 -translate-y-12">STEM Ready Framework</div>
                    <div class="g-badge bg-black/60 text-purple-400 border border-purple-500/50 backdrop-blur-md shadow-lg shadow-purple-500/20 -translate-x-24 translate-y-4">Data Driven Profiling</div>
                    <div class="g-badge bg-black/60 text-indigo-400 border border-indigo-500/50 backdrop-blur-md shadow-lg shadow-indigo-500/20 translate-x-12 translate-y-20">Automated Grading Engine</div>
                </div>
            </div>
        </section>

        <!-- Campus Life Full Width -->
        <section id="campus" class="relative h-[600px] flex items-center justify-center overflow-hidden border-y border-white/5">
            <div class="absolute inset-0 z-0">
                <img src="/images/campus.png" alt="Campus Life" class="w-full h-full object-cover opacity-50 block" loading="lazy" />
                <div class="absolute inset-0 bg-black/40"></div>
                <!-- Gradients to blend edges into the black ecosystem -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-transparent to-[#050505]"></div>
            </div>
            <div class="relative z-10 text-center px-6">
                <h2 class="text-5xl md:text-7xl font-black tracking-tighter text-white drop-shadow-2xl mb-8">Discover the Environment</h2>
                <a href="#contact" class="inline-block px-12 py-5 bg-white text-black font-black text-xs uppercase tracking-widest rounded-full hover:bg-indigo-600 hover:text-white transition duration-300 shadow-2xl">
                    Begin Journey
                </a>
            </div>
        </section>

        <!-- Enquiry Section -->
        <section id="contact" class="py-40 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="g-card p-12 md:p-20 grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                    <div>
                        <h2 class="text-4xl font-black tracking-tighter mb-6 uppercase italic">Join the <span class="g-gradient-text">Waitlist</span></h2>
                        <p class="text-gray-400 text-lg leading-loose font-medium mb-10">
                            Ready to transform your institution? Connect with our implementation engineering team to start your digital transition.
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                <span class="w-6 h-6 rounded bg-indigo-600/20 flex items-center justify-center text-[10px] text-indigo-400">✓</span>
                                Custom deployment timelines.
                            </div>
                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                <span class="w-6 h-6 rounded bg-indigo-600/20 flex items-center justify-center text-[10px] text-indigo-400">✓</span>
                                On-site hardware configuration.
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="submitEnquiry" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-600 mb-2 block">Full Name</label>
                                <input v-model="form.name" class="g-input" placeholder="Executor..." />
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-600 mb-2 block">Email Protocol</label>
                                <input v-model="form.email" type="email" class="g-input" placeholder="secure@..." />
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-600 mb-2 block">Inquiry Narrative</label>
                            <textarea v-model="form.message" class="g-input h-32" placeholder="Tell us about your institution's goals..."></textarea>
                        </div>
                        
                        <div v-if="$page.props.flash?.success" class="p-4 bg-emerald-500 border border-emerald-600 text-black font-black text-[10px] uppercase tracking-widest rounded-2xl">
                            {{ $page.props.flash.success }}
                        </div>

                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="w-full py-6 bg-white text-black rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition shadow-2xl active:scale-95 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Transmitting Data...' : 'Transmit Inquiry' }}
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<style scoped>
.g-card {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border-radius: 60px;
}
</style>
