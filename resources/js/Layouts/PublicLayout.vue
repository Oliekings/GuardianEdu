<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useTheme } from '@/composables/useTheme';

useTheme();
</script>

<template>
    <div class="min-h-screen text-white selection:bg-indigo-500 selection:text-white" style="background: #050505">
        <!-- Premium Navigation -->
        <nav class="fixed top-0 w-full z-50 bg-black/50 backdrop-blur-2xl border-b border-white/5">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <Link href="/" class="flex items-center gap-2 group">
                    <div class="h-8 w-8 rounded-lg bg-indigo-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="text-xl font-black tracking-tighter">Guardian<span class="text-indigo-500">Edu</span></span>
                </Link>

                <div class="hidden md:flex items-center gap-10">
                    <a href="#services" class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-white transition">Ecosystem</a>
                    <a href="#contact" class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-white transition">Contact</a>
                </div>

                <div class="flex items-center gap-4">
                    <Link :href="route('login')" class="text-xs font-black uppercase tracking-widest px-6 py-2 hover:bg-white/5 rounded-full transition">Portal</Link>
                    <Link :href="route('register')" class="text-xs font-black uppercase tracking-widest px-6 py-2 bg-white text-black rounded-full hover:bg-indigo-600 hover:text-white transition">Apply</Link>
                </div>
            </div>
        </nav>

        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="py-20 border-t border-white/5 bg-black/50 overflow-hidden relative">
            <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-indigo-600/10 rounded-full blur-[100px] pointer-events-none"></div>
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12 relative z-10">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="h-6 w-6 rounded bg-indigo-600 flex items-center justify-center">
                            <span class="text-[10px] font-black">G</span>
                        </div>
                        <span class="text-lg font-black tracking-tighter">Guardian<span class="text-indigo-500">Edu</span></span>
                    </div>
                    <p class="text-gray-500 text-sm max-w-sm leading-loose italic">
                        The world's most advanced behavioral and academic orchestration platform for premium institutions.
                    </p>
                </div>
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-widest text-white mb-6">Navigation</h4>
                    <ul class="space-y-4 text-xs font-bold text-gray-500">
                        <li><a href="#" class="hover:text-indigo-400 transition">Institutional Governance</a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition">Academic Series</a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition">Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-widest text-white mb-6">Connect</h4>
                    <ul class="space-y-4 text-xs font-bold text-gray-500">
                        <li><a href="#" class="hover:text-indigo-400 transition">Email Hotline</a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition">Global LinkedIn</a></li>
                    </ul>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-6 mt-20 pt-10 border-t border-white/5 text-center">
                <p class="text-[10px] font-black text-gray-700 uppercase tracking-widest italic">&copy; 2026 GuardianEdu. All rights reserved. Professional Grade.</p>
            </div>
        </footer>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
</style>
