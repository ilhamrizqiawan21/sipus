<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import AppNotice from '../components/AppNotice.vue'

const page = usePage()
const sidebarOpen = ref(false)

const user = computed(() => page.props.auth?.user)
const flashSuccess = computed(() => page.props.flash?.success)

const navItems = computed(() => {
    const common = [
        { label: 'Dashboard', href: '/dashboard', icon: 'grid' },
        { label: 'Cari Buku', href: '/buku', icon: 'book' },
        { label: 'Profil Saya', href: '/profil', icon: 'user' },
    ]

    if (user.value?.role !== 'admin') {
        return [...common, { label: 'Profil Saya', href: '/profil', icon: 'user' }]
    }

    return [
        { label: 'Dashboard', href: '/dashboard', icon: 'grid' },
        { label: 'Tahun Ajaran', href: '/school-years', icon: 'archive' },
        { label: 'Kelas', href: '/classrooms', icon: 'users' },
        { label: 'Jenis Buku', href: '/book-types', icon: 'book' },
        { label: 'Penerbit', href: '/publishers', icon: 'book' },
        { label: 'Pengarang', href: '/authors', icon: 'user' },
        { label: 'Buku dan Eksemplar', href: '/buku', icon: 'book' },
        { label: 'Inventaris', href: '/inventaris', icon: 'archive' },
        { label: 'Anggota', href: '/anggota', icon: 'users' },
        { label: 'Profil Saya', href: '/profil', icon: 'user' },
    ]
})

function isActive(href: string): boolean {
    return page.url === href || page.url.startsWith(`${href}/`)
}

function closeSidebar(): void {
    sidebarOpen.value = false
}

watch(() => page.url, closeSidebar)
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <div v-if="sidebarOpen" class="fixed inset-0 z-40 bg-slate-950/40 lg:hidden" @click="closeSidebar" />

        <aside :class="['fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-200 bg-white transition-transform duration-200 lg:translate-x-0', sidebarOpen ? 'translate-x-0' : '-translate-x-full']">
            <div class="flex h-20 items-center justify-between border-b border-slate-100 px-6">
                <Link href="/dashboard" class="flex items-center gap-3" @click="closeSidebar">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-emerald-600 font-bold text-white">S</span>
                    <span>
                        <span class="block text-sm font-bold text-slate-950">SIPUS</span>
                        <span class="block text-xs text-slate-500">Perpustakaan Sekolah</span>
                    </span>
                </Link>
                <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden" aria-label="Tutup menu" @click="closeSidebar">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" /></svg>
                </button>
            </div>

            <div class="border-b border-slate-100 px-5 py-5">
                <p class="truncate text-sm font-semibold text-slate-900">{{ user?.nama }}</p>
                <p class="mt-1 text-xs font-medium uppercase tracking-wide text-emerald-700">{{ user?.role }}</p>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-5">
                <Link v-for="item in navItems" :key="item.href" :href="item.href" :class="['group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition', isActive(item.href) ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900']" @click="closeSidebar">
                    <svg v-if="item.icon === 'grid'" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="4" width="6" height="6" rx="1" /><rect x="14" y="4" width="6" height="6" rx="1" /><rect x="4" y="14" width="6" height="6" rx="1" /><rect x="14" y="14" width="6" height="6" rx="1" /></svg>
                    <svg v-else-if="item.icon === 'book'" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21.5v-16Z" /><path d="M4 5.5v16M8 7h8M8 11h8" /></svg>
                    <svg v-else-if="item.icon === 'users'" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 20v-1.5a3.5 3.5 0 0 0-3.5-3.5h-5A3.5 3.5 0 0 0 4 18.5V20" /><circle cx="10" cy="7" r="3" /><path d="M16 11a3 3 0 1 0 0-6M16 15h1.5a3.5 3.5 0 0 1 3.5 3.5V20" /></svg>
                    <svg v-else-if="item.icon === 'repeat'" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 2l4 4-4 4M3 11V9a3 3 0 0 1 3-3h15M7 22l-4-4 4-4M21 13v2a3 3 0 0 1-3 3H3" /></svg>
                    <svg v-else-if="item.icon === 'archive'" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16v13H4zM3 4h18v3H3zM9 11h6" /></svg>
                    <svg v-else-if="item.icon === 'chart'" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 19V5M4 19h16M8 16v-5M12 16V7M16 16v-3" /></svg>
                    <svg v-else-if="item.icon === 'settings'" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3" /><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-1.4 1.4-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6v.2h-2v-.2a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1L9 17l.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.6-1H7v-2h.8a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.9L9 9l1.4-1.4.1.1a1.7 1.7 0 0 0 1.9.3 1.7 1.7 0 0 0 1-1.6v-.2h2v.2a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1L19.8 9l-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1h.2v2H21a1.7 1.7 0 0 0-1.6 1Z" /></svg>
                    <svg v-else class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3" /><path d="M5 20a7 7 0 0 1 14 0" /></svg>
                    <span>{{ item.label }}</span>
                </Link>
            </nav>

            <div class="border-t border-slate-100 p-4">
                <Link href="/logout" method="post" as="button" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 17l5-5-5-5M15 12H3M21 19V5a2 2 0 0 0-2-2h-5" /></svg>
                    Keluar
                </Link>
            </div>
        </aside>

        <div class="lg:pl-72">
            <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">
                <div class="flex h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3">
                        <button type="button" class="rounded-xl border border-slate-200 p-2.5 text-slate-600 hover:bg-slate-50 lg:hidden" aria-label="Buka menu" @click="sidebarOpen = true">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>
                        <div class="hidden text-sm text-slate-500 sm:block">
                            SIPUS <span class="mx-1 text-slate-300">/</span> <span class="font-medium text-slate-800">{{ page.component }}</span>
                        </div>
                    </div>
                    <Link href="/profil" class="flex items-center gap-3 rounded-xl px-2 py-1.5 transition hover:bg-slate-50">
                        <span class="hidden text-right sm:block"><span class="block text-sm font-semibold text-slate-900">{{ user?.nama }}</span><span class="block text-xs text-slate-500">{{ user?.username }}</span></span>
                        <span class="flex size-10 items-center justify-center rounded-full bg-emerald-100 font-semibold text-emerald-700">{{ user?.nama?.charAt(0)?.toUpperCase() }}</span>
                    </Link>
                </div>
            </header>

            <div class="px-4 pt-4 sm:px-6 lg:px-8">
                <AppNotice v-if="flashSuccess" :message="flashSuccess" />
            </div>
            <main class="px-4 py-8 sm:px-6 lg:px-8">
                <slot />
            </main>
        </div>
    </div>
</template>
