<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();

const isLoaded = ref(false);
onMounted(() => {
    isLoaded.value = true;
});
</script>

<template>
    <Head title="Guardian Academy | Excellence in Education" />

    <div class="relative min-h-screen bg-[#050505] text-white selection:bg-indigo-500/30 overflow-hidden font-sans">
        
        <!-- Premium Navigation -->
        <nav class="absolute top-0 left-0 w-full z-50 px-6 py-8">
            <div class="mx-auto max-w-7xl flex items-center justify-between">
                <div class="flex items-center gap-4 group">
                    <div class="h-12 w-12 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/20 shadow-2xl transition duration-500 group-hover:bg-white/20">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-2xl font-black tracking-widest uppercase font-serif">Guardian</span>
                        <span class="block text-[10px] uppercase tracking-[0.2em] text-gray-400">Academy</span>
                    </div>
                </div>

                <div class="hidden lg:flex items-center gap-10">
                    <a href="#about" class="text-sm font-semibold tracking-widest uppercase text-gray-300 hover:text-white transition">About</a>
                    <a href="#academics" class="text-sm font-semibold tracking-widest uppercase text-gray-300 hover:text-white transition">Academics</a>
                    <a href="#campus" class="text-sm font-semibold tracking-widest uppercase text-gray-300 hover:text-white transition">Campus Life</a>
                </div>

                <div v-if="canLogin" class="flex items-center gap-6">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-xs font-bold hover:text-indigo-400 transition tracking-widest uppercase">Dashboard</Link>
                    <template v-else>
                        <Link :href="route('login')" class="px-8 py-3 bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-bold uppercase tracking-widest rounded-full hover:bg-white/20 transition shadow-xl">
                            Portal Login
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative h-screen min-h-[800px] flex items-center justify-center">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <img src="/images/hero.png" alt="Guardian Academy Campus" class="w-full h-full object-cover opacity-50 transition duration-[2s]" :class="isLoaded ? 'scale-100' : 'scale-110 blur-sm'" />
                <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-[#050505]/40 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-[#050505]/80 to-transparent"></div>
            </div>

            <div class="relative z-10 text-center px-6 max-w-5xl mx-auto mt-20">
                <div class="inline-flex items-center gap-3 px-6 py-2 rounded-full border border-white/20 bg-black/30 backdrop-blur-md text-xs font-bold uppercase tracking-widest mb-8 animate-fade-in text-gray-300">
                    <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    Applications Open for Fall 2026
                </div>
                
                <h1 class="text-6xl md:text-8xl lg:text-9xl font-serif text-white mb-8 leading-[1.0] drop-shadow-2xl">
                    Tradition Meets <br/>
                    <em class="font-light italic text-indigo-400">Innovation</em>
                </h1>
                
                <p class="text-lg md:text-xl text-gray-300 max-w-2xl mx-auto mb-12 leading-relaxed font-light drop-shadow-md">
                    A premier independent preparatory academy dedicated to nurturing intellect, character, and global leadership in a world-class environment.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center gap-6 justify-center">
                    <a href="#admissions" class="px-10 py-4 bg-indigo-600 backdrop-blur-md rounded-full text-sm uppercase tracking-widest font-bold hover:bg-indigo-500 transition shadow-2xl shadow-indigo-500/20 border border-indigo-400/50">
                        Inquire Now
                    </a>
                </div>
            </div>
            
            <!-- Scroll Indicator -->
            <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex flex-col items-center animate-bounce-slow text-white/50">
                <span class="text-[10px] uppercase tracking-[0.3em] font-bold mb-3">Scroll</span>
                <div class="w-[1px] h-12 bg-gradient-to-b from-white/50 to-transparent"></div>
            </div>
        </div>

        <!-- Headmaster / Philosophy Section -->
        <section id="about" class="relative z-10 py-32 px-6 max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div class="order-2 lg:order-1 relative group">
                    <div class="absolute -inset-2 bg-gradient-to-tr from-indigo-500/20 to-purple-500/20 rounded-3xl blur-xl transition duration-500 group-hover:blur-2xl"></div>
                    <img src="/images/academics.png" alt="Library" class="relative rounded-3xl object-cover h-[600px] w-full border border-white/10 shadow-2xl" />
                </div>
                <div class="order-1 lg:order-2 space-y-8">
                    <h2 class="text-sm uppercase tracking-[0.3em] font-bold text-indigo-400">Headmaster's Welcome</h2>
                    <h3 class="text-4xl md:text-6xl font-serif leading-tight">
                        Shaping the <br><em class="italic font-light text-gray-400">Leaders of Tomorrow</em>
                    </h3>
                    <div class="w-20 h-[1px] bg-indigo-500/50"></div>
                    <p class="text-gray-400 text-lg leading-relaxed font-light">
                        At Guardian Academy, we believe education extends far beyond the classroom. For over a century, our prestigious institution has cultivated a community driven by intellectual curiosity, emotional intelligence, and physical excellence.
                    </p>
                    <p class="text-gray-400 text-lg leading-relaxed font-light">
                        We surround our students with state-of-the-art facilities, world-renowned faculty, and the GuardianEdu technological ecosystem, ensuring unparalleled academic oversight and parental connection.
                    </p>
                    <div>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Signature_of_John_Hancock.svg" alt="Signature" class="h-16 opacity-50 invert" />
                        <p class="mt-4 text-xs tracking-widest uppercase font-bold text-gray-500">Dr. Alistair Sterling, Headmaster</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pillars / Features -->
        <section id="academics" class="relative z-10 py-32 bg-[#0a0a0a] border-y border-white/5">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-20">
                    <h2 class="text-sm uppercase tracking-[0.3em] font-bold text-indigo-400 mb-4">Our Core Pillars</h2>
                    <h3 class="text-4xl md:text-5xl font-serif">A Foundation for Excellence</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Card 1 -->
                    <div class="p-10 bg-white/[0.02] border border-white/5 rounded-3xl hover:bg-white/[0.04] transition duration-500 group">
                        <div class="h-16 w-16 bg-gradient-to-br from-indigo-500/20 to-purple-500/20 rounded-2xl flex items-center justify-center text-indigo-400 mb-8 border border-indigo-500/20 group-hover:scale-110 transition duration-500">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <h4 class="text-2xl font-serif mb-4">Rigorous Academics</h4>
                        <p class="text-gray-400 leading-relaxed font-light text-sm">
                            Offering an advanced, globally recognized curriculum. Our AP and college-level programs prepare students for the world's most competitive universities.
                        </p>
                    </div>

                    <!-- Card 2 -->
                    <div class="p-10 bg-white/[0.02] border border-white/5 rounded-3xl hover:bg-white/[0.04] transition duration-500 group">
                        <div class="h-16 w-16 bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-2xl flex items-center justify-center text-purple-400 mb-8 border border-purple-500/20 group-hover:scale-110 transition duration-500">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h4 class="text-2xl font-serif mb-4">Character & Arts</h4>
                        <p class="text-gray-400 leading-relaxed font-light text-sm">
                            We balance rigorous academics with a vibrant fine arts program and athletics, building well-rounded individuals grounded in integrity.
                        </p>
                    </div>

                    <!-- Card 3 -->
                    <div class="p-10 bg-white/[0.02] border border-white/5 rounded-3xl hover:bg-white/[0.04] transition duration-500 group">
                        <div class="h-16 w-16 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center text-blue-400 mb-8 border border-blue-500/20 group-hover:scale-110 transition duration-500">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        </div>
                        <h4 class="text-2xl font-serif mb-4">GuardianEdu Security</h4>
                        <p class="text-gray-400 leading-relaxed font-light text-sm">
                            Our proprietary Guardian platform integrates secure parent portal access, AWS live classroom feeds, and real-time attendance telemetry.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Campus Life / Full Width Image -->
        <section id="campus" class="relative h-[600px] flex items-center justify-center">
            <div class="absolute inset-0 z-0">
                <img src="/images/campus.png" alt="Campus Life" class="w-full h-full object-cover opacity-60 fixed-bg" />
                <div class="absolute inset-0 bg-[#050505]/50"></div>
            </div>
            <div class="relative z-10 text-center px-6">
                <h2 class="text-5xl md:text-7xl font-serif text-white drop-shadow-lg mb-6">Discover Campus Life</h2>
                <a href="#" class="inline-block px-10 py-4 bg-white/10 backdrop-blur-md border border-white/30 text-white text-sm font-bold uppercase tracking-widest rounded-full hover:bg-white/20 transition shadow-xl">
                    View Gallery
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="relative z-10 bg-[#020202] pt-24 pb-12 px-6 border-t border-white/10">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-8 w-8 bg-white/10 rounded-lg flex items-center justify-center border border-white/20">
                            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold tracking-widest uppercase font-serif">Guardian Academy</span>
                    </div>
                    <p class="text-gray-500 font-light max-w-sm mb-6 text-sm leading-relaxed">
                        Excellence in Education.<br>
                        1000 Prestige Avenue, Suite A<br>
                        Boston, MA 02116
                    </p>
                    <p class="text-indigo-400 font-bold tracking-widest text-sm">Admissions: (800) 555-0199</p>
                </div>
                
                <div>
                    <h4 class="text-white font-bold uppercase tracking-widest mb-6 text-xs">Quick Links</h4>
                    <ul class="space-y-4 text-gray-500 text-sm font-light">
                        <li><a href="#" class="hover:text-white transition">Admissions Process</a></li>
                        <li><a href="#" class="hover:text-white transition">Tuition & Financial Aid</a></li>
                        <li><a href="#" class="hover:text-white transition">Athletic Programs</a></li>
                        <li><a href="#" class="hover:text-white transition">Alumni Network</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold uppercase tracking-widest mb-6 text-xs">Community</h4>
                    <ul class="space-y-4 text-gray-500 text-sm font-light">
                       <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="block hover:text-white transition">Portal Dashboard</Link>
                        <template v-else>
                            <li><Link :href="route('login')" class="hover:text-white transition">Parent/Student Login</Link></li>
                            <li><Link :href="route('login')" class="hover:text-white transition">Staff Login</Link></li>
                        </template>
                        <li><a href="#" class="hover:text-white transition">Careers</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="max-w-7xl mx-auto pt-8 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-gray-600 text-xs font-mono uppercase tracking-widest">
                    &copy; 2026 Guardian Academy. Powered by GuardianEdu Ecosystem.
                </p>
                <div class="flex gap-6 text-xs text-gray-600 font-bold uppercase tracking-widest">
                    <a href="#" class="hover:text-white transition">Privacy</a>
                    <a href="#" class="hover:text-white transition">Terms</a>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
@keyframes bounce-slow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
.animate-bounce-slow {
    animation: bounce-slow 4s ease-in-out infinite;
}
.animate-fade-in {
    animation: fadeIn 1s ease-out forwards;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.fixed-bg {
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
}
</style>
