<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

withDefaults(defineProps<{
    title: string;
    value: string | number;
    icon?: string;
    trendLabel?: string;
    trendType?: 'positive' | 'negative' | 'neutral';
    colorClass?: string;
    href?: string;
}>(), {
    trendType: 'neutral',
});
</script>

<template>
    <component :is="href ? Link : 'div'" :href="href"
        class="g-card p-6 text-center transition group relative block"
        :class="{ 'hover:border-indigo-500/20 hover:bg-white/[0.04] cursor-pointer': href }">
        
        <div v-if="icon" class="text-3xl mb-3">{{ icon }}</div>
        
        <div class="text-3xl font-black text-white transition" :class="colorClass ? colorClass : (href ? 'group-hover:text-indigo-400' : '')">
            {{ value }}
        </div>
        
        <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">
            {{ title }}
        </div>
        
        <div v-if="trendLabel" class="mt-3 text-xs font-bold"
             :class="{
                 'text-emerald-400': trendType === 'positive',
                 'text-rose-400': trendType === 'negative',
                 'text-gray-400': trendType === 'neutral'
             }">
            {{ trendLabel }}
        </div>
    </component>
</template>
