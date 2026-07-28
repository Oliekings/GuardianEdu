<script setup lang="ts">
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { useTheme } from '@/composables/useTheme';

useTheme();

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen text-gray-100 selection:bg-[var(--color-g-primary-soft)]" style="background: var(--color-g-bg-base)">
            <!-- Background Decorative Elements -->
            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full blur-[120px] g-glow-pulse" style="background: var(--color-g-primary-soft)"></div>
                <div class="absolute top-[20%] -right-[10%] w-[30%] h-[30%] rounded-full blur-[120px]" style="background: var(--color-g-accent-soft)"></div>
            </div>

            <nav class="sticky top-0 z-50 border-b backdrop-blur-xl" style="border-color: var(--color-g-border); background: rgba(0,0,0,0.4)">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo class="block h-9 w-auto fill-current" style="color: var(--color-g-primary)" />
                                </Link>
                                <span class="ms-3 text-lg font-bold tracking-tight text-white">Guardian<span style="color: var(--color-g-primary)">Edu</span></span>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="text-sm font-medium transition-colors hover:text-white">
                                    Dashboard
                                </NavLink>

                                <!-- Super Admin Links -->
                                <template v-if="$page.props.auth.user.role === 'super_admin'">
                                    <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Branches</NavLink>
                                    <NavLink :href="route('admin.users.index')" :active="route().current('admin.users.*')">Users</NavLink>
                                </template>

                                <!-- Admin Links -->
                                <template v-if="$page.props.auth.user.role === 'admin' || $page.props.auth.user.role === 'super_admin'">
                                    <NavLink :href="route('admin.students.index')" :active="route().current('admin.students.*')">Students</NavLink>
                                    <NavLink :href="route('admin.users.index')" v-if="$page.props.auth.user.role === 'admin'" :active="route().current('admin.users.*')">Users</NavLink>
                                    <NavLink :href="route('security-cam.index')" :active="route().current('security-cam.index')">Security</NavLink>
                                    <NavLink :href="route('admin.theme.show')" :active="route().current('admin.theme.show')">🎨 Theme</NavLink>
                                </template>

                                <!-- Teacher/Staff Links -->
                                <template v-if="$page.props.auth.user.role === 'teacher' || $page.props.auth.user.role === 'staff'">
                                    <NavLink :href="route('staff.assignments.index')" :active="route().current('staff.assignments.*')">Assignments</NavLink>
                                    <NavLink :href="route('staff.gradebook.index')" :active="route().current('staff.gradebook.*')">Grade Book</NavLink>
                                    <NavLink :href="route('staff.attendance.index')" :active="route().current('staff.attendance.index')">Attendance</NavLink>
                                    <NavLink :href="route('staff.behavioral.index')" :active="route().current('staff.behavioral.index')">Behavioral</NavLink>
                                </template>

                                <!-- Accountant Links -->
                                <template v-if="$page.props.auth.user.role === 'accountant'">
                                    <NavLink :href="route('accountant.fees.collect.index')" :active="route().current('accountant.fees.collect.*')">Fee Collection</NavLink>
                                    <NavLink :href="route('accountant.fees.groups.index')" :active="route().current('accountant.fees.groups.*')">Fee Groups</NavLink>
                                    <NavLink :href="route('accountant.fees.inventory.items.index')" :active="route().current('accountant.fees.inventory.*')">Inventory</NavLink>
                                    <NavLink :href="route('accountant.fees.library.books.index')" :active="route().current('accountant.fees.library.*')">Library</NavLink>
                                </template>

                                <!-- Librarian Links -->
                                <template v-if="$page.props.auth.user.role === 'librarian'">
                                    <NavLink :href="route('accountant.fees.library.books.index')" :active="route().current('accountant.fees.library.books.*')">Books</NavLink>
                                    <NavLink :href="route('accountant.fees.library.issue.index')" :active="route().current('accountant.fees.library.issue.*')">Issuing</NavLink>
                                </template>

                                <!-- Receptionist Links -->
                                <template v-if="$page.props.auth.user.role === 'receptionist'">
                                    <NavLink :href="route('admin.enquiries.index')" :active="route().current('admin.enquiries.*')">Enquiries</NavLink>
                                </template>

                                <!-- Student Links -->
                                <template v-if="$page.props.auth.user.role === 'student'">
                                    <NavLink :href="route('student.assignments.index')" :active="route().current('student.assignments.*')">Assignments</NavLink>
                                    <NavLink :href="route('student.grades.index')" :active="route().current('student.grades.*')">Grades</NavLink>
                                </template>

                                <!-- Parent Links -->
                                <template v-if="$page.props.auth.user.role === 'parent'">
                                    <!-- Parent nav items are handled by dashboard child cards -->
                                </template>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Role Badge -->
                            <div class="mr-4 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest"
                                 :class="{
                                     'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20': $page.props.auth.user.role === 'admin' || $page.props.auth.user.role === 'super_admin',
                                     'bg-purple-500/10 text-purple-400 border border-purple-500/20': ['teacher', 'staff'].includes($page.props.auth.user.role),
                                     'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20': ['parent', 'accountant'].includes($page.props.auth.user.role),
                                     'bg-blue-500/10 text-blue-400 border border-blue-500/20': ['student', 'librarian'].includes($page.props.auth.user.role),
                                     'bg-amber-500/10 text-amber-400 border border-amber-500/20': $page.props.auth.user.role === 'receptionist',
                                 }">
                                {{ $page.props.auth.user.role.replace('_', ' ') }}
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-sm font-medium transition-all focus:outline-none"
                                                style="border: 1px solid var(--color-g-border); background: var(--color-g-bg-elevated); color: var(--color-g-text-muted)"
                                            >
                                                <div class="h-5 w-5 rounded-full" style="background: linear-gradient(to top right, var(--color-g-primary), var(--color-g-accent))"></div>
                                                {{ $page.props.auth.user.name }}
                                                <svg class="-me-0.5 ms-2 h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="backdrop-blur-xl rounded-lg overflow-hidden" style="background: rgba(0,0,0,0.9); border: 1px solid var(--color-g-border)">
                                            <DropdownLink :href="route('profile.edit')" class="hover:bg-white/5">Profile</DropdownLink>
                                            <DropdownLink :href="route('logout')" method="post" as="button" class="hover:bg-white/5">Log Out</DropdownLink>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center rounded-md p-2 transition-all focus:outline-none"
                                style="color: var(--color-g-text-muted)"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden backdrop-blur-xl"
                    style="border-top: 1px solid var(--color-g-border); background: rgba(0,0,0,0.6)"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</ResponsiveNavLink>

                        <!-- Admin Mobile -->
                        <template v-if="$page.props.auth.user.role === 'admin'">
                            <ResponsiveNavLink :href="route('admin.students.index')" :active="route().current('admin.students.*')">Students</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('admin.users.index')" :active="route().current('admin.users.*')">Users</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('security-cam.index')" :active="route().current('security-cam.index')">Security</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('admin.theme.show')" :active="route().current('admin.theme.show')">🎨 Theme</ResponsiveNavLink>
                        </template>

                        <!-- Teacher/Staff Mobile -->
                        <template v-if="$page.props.auth.user.role === 'staff' || $page.props.auth.user.role === 'teacher'">
                            <ResponsiveNavLink :href="route('staff.assignments.index')" :active="route().current('staff.assignments.*')">Assignments</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('staff.gradebook.index')" :active="route().current('staff.gradebook.*')">Grade Book</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('staff.attendance.index')" :active="route().current('staff.attendance.index')">Attendance</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('staff.behavioral.index')" :active="route().current('staff.behavioral.index')">Behavioral</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('security-cam.index')" :active="route().current('security-cam.index')">Security</ResponsiveNavLink>
                        </template>

                        <!-- Student Mobile -->
                        <template v-if="$page.props.auth.user.role === 'student'">
                            <ResponsiveNavLink :href="route('student.assignments.index')" :active="route().current('student.assignments.*')">Assignments</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('student.grades.index')" :active="route().current('student.grades.*')">Grades</ResponsiveNavLink>
                        </template>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div style="border-top: 1px solid var(--color-g-border)" class="pb-1 pt-4">
                        <div class="px-4">
                            <div class="text-base font-medium text-white">{{ $page.props.auth.user.name }}</div>
                            <div class="text-sm font-medium" style="color: var(--color-g-text-faint)">{{ $page.props.auth.user.email }}</div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-transparent" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="relative z-10">
                <slot />
            </main>
        </div>
    </div>
</template>
