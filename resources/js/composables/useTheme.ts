import { usePage } from '@inertiajs/vue3';
import { onMounted, watch, computed } from 'vue';

/**
 * Default theme values — matches what's defined in app.css @theme.
 * When an admin customizes the school theme, only the overridden
 * values need to be stored; everything else falls back here.
 */
const DEFAULTS: Record<string, string> = {
    primary: '#6366f1',
    primaryHover: '#818cf8',
    accent: '#a855f7',
    bgBase: '#050505',
    success: '#10b981',
    danger: '#f43f5e',
    warning: '#f59e0b',
};

/**
 * Maps theme setting keys to CSS custom property names.
 */
function applyThemeToRoot(theme: Record<string, string>) {
    const root = document.documentElement;
    const map: Record<string, string> = {
        primary:      '--color-g-primary',
        primaryHover: '--color-g-primary-hover',
        accent:       '--color-g-accent',
        bgBase:       '--color-g-bg-base',
        success:      '--color-g-success',
        danger:       '--color-g-danger',
        warning:      '--color-g-warning',
    };

    for (const [key, cssVar] of Object.entries(map)) {
        const value = theme[key] || DEFAULTS[key];
        if (value) {
            root.style.setProperty(cssVar, value);

            // Derived values — generate soft/glow variants automatically
            if (key === 'primary') {
                root.style.setProperty('--color-g-primary-soft', hexToRgba(value, 0.1));
                root.style.setProperty('--color-g-primary-glow', hexToRgba(value, 0.2));
            }
            if (key === 'accent') {
                root.style.setProperty('--color-g-accent-soft', hexToRgba(value, 0.1));
            }
        }
    }
}

/**
 * Convert hex color to rgba string.
 */
function hexToRgba(hex: string, alpha: number): string {
    const r = parseInt(hex.slice(1, 3), 16);
    const g = parseInt(hex.slice(3, 5), 16);
    const b = parseInt(hex.slice(5, 7), 16);
    return `rgba(${r}, ${g}, ${b}, ${alpha})`;
}

/**
 * Composable: useTheme
 *
 * Reads theme settings from Inertia shared props and applies
 * CSS custom property overrides to :root. Should be called once
 * in App.vue or in any layout that needs theming.
 *
 * Usage:
 *   const { themeColors } = useTheme();
 */
export function useTheme() {
    const page = usePage();

    const themeColors = computed<Record<string, string>>(() => {
        const schoolTheme = (page.props as any).theme || {};
        return { ...DEFAULTS, ...schoolTheme };
    });

    function apply() {
        applyThemeToRoot(themeColors.value);
    }

    onMounted(apply);
    watch(themeColors, apply, { deep: true });

    return {
        themeColors,
        DEFAULTS,
        applyThemeToRoot,
    };
}
