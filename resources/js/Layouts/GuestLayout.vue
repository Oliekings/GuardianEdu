<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { useTheme } from '@/composables/useTheme';

useTheme();
</script>

<template>
    <div class="relative min-h-screen text-white selection:bg-[var(--color-g-primary-soft)] overflow-hidden font-sans flex" style="background: var(--color-g-bg-base)">
        
        <!-- Left Side: Interactive Branding -->
        <div class="hidden lg:flex w-1/2 relative flex-col justify-between p-12 overflow-hidden" style="border-right: 1px solid var(--color-g-border)">
            <div class="absolute -top-[10%] -left-[10%] w-[80%] h-[80%] rounded-full blur-[150px] animate-pulse pointer-events-none" style="background: var(--color-g-primary-soft)"></div>
            <div class="absolute bottom-[10%] -right-[10%] w-[60%] h-[60%] rounded-full blur-[150px] pointer-events-none" style="background: var(--color-g-accent-soft)"></div>
            
            <div class="relative z-10 flex items-center gap-3">
                <Link href="/" class="flex items-center gap-3 group">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition duration-300" style="background: var(--color-g-primary); box-shadow: 0 10px 25px -5px var(--color-g-primary-glow)">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-black tracking-tighter">Guardian<span style="color: var(--color-g-primary)">Edu</span></span>
                </Link>
            </div>
            
            <div class="relative z-10 max-w-md">
                <h2 class="text-5xl font-black tracking-tighter leading-[1.1] mb-6">
                    Enter the <br>
                    <span class="g-gradient-text">Ecosystem</span>
                </h2>
                <p class="text-lg leading-relaxed font-medium" style="color: var(--color-g-text-muted)">
                    Authenticate to access real-time behavioral insights, live telemetry, and total academic oversight.
                </p>
                <div class="mt-8 flex gap-3">
                    <div class="h-1 w-12 rounded-full" style="background: var(--color-g-primary)"></div>
                    <div class="h-1 w-4 rounded-full" style="background: var(--color-g-border-active)"></div>
                    <div class="h-1 w-4 rounded-full" style="background: var(--color-g-border-active)"></div>
                </div>
            </div>
        </div>

        <!-- Right Side: Form Content -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 relative z-10">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex items-center justify-center gap-3 mb-12">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center shadow-lg" style="background: var(--color-g-primary); box-shadow: 0 10px 25px -5px var(--color-g-primary-glow)">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-black tracking-tighter">Guardian<span style="color: var(--color-g-primary)">Edu</span></span>
                </div>

                <div class="p-8 sm:p-10 relative overflow-hidden" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(40px); border: 1px solid var(--color-g-border); border-radius: var(--radius-g-2xl); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5)">
                    <div class="absolute top-0 inset-x-0 h-px" style="background: linear-gradient(to right, transparent, var(--color-g-border-active), transparent)"></div>
                    <slot />
                </div>
                
                <p class="mt-8 text-center text-xs font-bold uppercase tracking-widest" style="color: var(--color-g-text-faint)">
                    &copy; 2026 Premium Education Software
                </p>
            </div>
        </div>
    </div>
</template>
