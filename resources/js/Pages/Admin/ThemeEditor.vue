<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const props = defineProps<{
    currentTheme: Record<string, string>;
    defaults: Record<string, string>;
}>();

const form = useForm({
    primary:      props.currentTheme.primary ?? props.defaults.primary,
    primaryHover: props.currentTheme.primaryHover ?? props.defaults.primaryHover,
    accent:       props.currentTheme.accent ?? props.defaults.accent,
    bgBase:       props.currentTheme.bgBase ?? props.defaults.bgBase,
    success:      props.currentTheme.success ?? props.defaults.success,
    danger:       props.currentTheme.danger ?? props.defaults.danger,
    warning:      props.currentTheme.warning ?? props.defaults.warning,
});

const colorFields = [
    { key: 'primary', label: 'Primary Brand', desc: 'Buttons, links, active states' },
    { key: 'primaryHover', label: 'Primary Hover', desc: 'Hovered interactive elements' },
    { key: 'accent', label: 'Accent Gradient', desc: 'Gradient endpoints, decorative highlights' },
    { key: 'bgBase', label: 'Background Base', desc: 'Main dark background color' },
    { key: 'success', label: 'Success / Active', desc: 'Online status, confirmations' },
    { key: 'danger', label: 'Danger / Alert', desc: 'Errors, destructive actions, live feeds' },
    { key: 'warning', label: 'Warning', desc: 'Caution notices, pending states' },
];

// Live preview: apply changes to :root as user edits
watch(() => ({ ...form }), (newValues: any) => {
    const root = document.documentElement;
    if (newValues.primary) root.style.setProperty('--color-g-primary', newValues.primary);
    if (newValues.primaryHover) root.style.setProperty('--color-g-primary-hover', newValues.primaryHover);
    if (newValues.accent) root.style.setProperty('--color-g-accent', newValues.accent);
    if (newValues.bgBase) root.style.setProperty('--color-g-bg-base', newValues.bgBase);
    if (newValues.success) root.style.setProperty('--color-g-success', newValues.success);
    if (newValues.danger) root.style.setProperty('--color-g-danger', newValues.danger);
    if (newValues.warning) root.style.setProperty('--color-g-warning', newValues.warning);
}, { deep: true });

const save = () => {
    form.put(route('admin.theme.update'));
};

const resetDefaults = () => {
    form.primary = props.defaults.primary;
    form.primaryHover = props.defaults.primaryHover;
    form.accent = props.defaults.accent;
    form.bgBase = props.defaults.bgBase;
    form.success = props.defaults.success;
    form.danger = props.defaults.danger;
    form.warning = props.defaults.warning;
};
</script>

<template>
    <Head title="Theme Editor — GuardianEdu" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-extrabold tracking-tight text-white">
                Brand <span class="g-gradient-text">Customization</span>
            </h2>
            <p class="mt-1 text-sm" style="color: var(--color-g-text-muted)">Configure your institution's visual identity across the entire ecosystem.</p>
        </template>

        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Color Pickers -->
                    <div class="lg:col-span-2 g-card p-10">
                        <h3 class="g-section-title mb-8">Color Palette</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div 
                                v-for="field in colorFields" 
                                :key="field.key" 
                                class="g-card-inset p-5 flex items-center gap-5 group transition hover:border-[var(--color-g-border-active)]"
                            >
                                <div class="relative shrink-0">
                                    <input
                                        type="color"
                                        v-model="(form as any)[field.key]"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    />
                                    <div 
                                        class="h-12 w-12 rounded-2xl border-2 border-white/10 shadow-lg transition group-hover:scale-110 group-hover:shadow-xl"
                                        :style="{ background: (form as any)[field.key] }"
                                    ></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold" style="color: var(--color-g-text)">{{ field.label }}</h4>
                                    <p class="text-[10px] mt-0.5" style="color: var(--color-g-text-faint)">{{ field.desc }}</p>
                                    <code class="text-[10px] font-mono mt-1 block" style="color: var(--color-g-text-muted)">{{ (form as any)[field.key] }}</code>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4 mt-10">
                            <button @click="save" :disabled="form.processing" class="g-btn-primary flex-1">
                                {{ form.processing ? 'Applying...' : 'Save Theme' }}
                            </button>
                            <button @click="resetDefaults" class="g-btn-secondary flex-1">
                                Reset to Defaults
                            </button>
                        </div>

                        <p v-if="($page.props as any).flash?.success" class="mt-4 text-sm font-bold" style="color: var(--color-g-success)">
                            {{ ($page.props as any).flash.success }}
                        </p>
                    </div>

                    <!-- Live Preview -->
                    <div class="space-y-6">
                        <div class="g-card p-6">
                            <h3 class="g-section-title mb-6">Live Preview</h3>
                            
                            <!-- Button Preview -->
                            <div class="space-y-4">
                                <button class="g-btn-primary text-xs">Primary Action</button>
                                <button class="g-btn-secondary text-xs">Secondary Action</button>
                                <button class="g-btn-danger w-full text-xs">Danger Action</button>
                            </div>
                        </div>

                        <div class="g-card p-6">
                            <h3 class="g-section-title mb-4">Input Preview</h3>
                            <label class="g-label">Field Label</label>
                            <input class="g-input" placeholder="Type something..." />
                        </div>

                        <div class="g-card p-6">
                            <h3 class="g-section-title mb-4">Gradient Preview</h3>
                            <h2 class="text-3xl font-black tracking-tighter">
                                This is <span class="g-gradient-text">branded text</span>
                            </h2>
                        </div>

                        <div class="g-card p-6">
                            <h3 class="g-section-title mb-4">Status Badges</h3>
                            <div class="flex flex-wrap gap-3">
                                <span class="g-badge" style="background: var(--color-g-primary-soft); color: var(--color-g-primary)">
                                    <span class="h-1.5 w-1.5 rounded-full" style="background: var(--color-g-primary)"></span>
                                    Active
                                </span>
                                <span class="g-badge" style="background: rgba(16,185,129,0.1); color: var(--color-g-success)">
                                    <span class="h-1.5 w-1.5 rounded-full animate-pulse" style="background: var(--color-g-success)"></span>
                                    Online
                                </span>
                                <span class="g-badge" style="background: rgba(244,63,94,0.1); color: var(--color-g-danger)">
                                    <span class="h-1.5 w-1.5 rounded-full animate-pulse" style="background: var(--color-g-danger)"></span>
                                    Live
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
