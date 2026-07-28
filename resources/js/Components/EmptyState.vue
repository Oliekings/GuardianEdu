<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    icon?: string;
    title: string;
    description?: string;
    actionLabel?: string;
    actionHref?: string;
}>();
</script>

<template>
    <div class="g-card p-16 text-center">
        <div class="text-5xl mb-6">{{ icon || '🍃' }}</div>
        <h3 class="text-xl font-bold text-white mb-2">{{ title }}</h3>
        <p v-if="description" class="text-gray-500 mb-6">{{ description }}</p>
        <Link v-if="actionLabel && actionHref" :href="actionHref" class="g-btn-primary !w-auto !inline-block !px-8">
            {{ actionLabel }}
        </Link>
    </div>
</template>
