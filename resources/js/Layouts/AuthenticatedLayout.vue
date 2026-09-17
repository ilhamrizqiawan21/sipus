<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth?.user
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-xl bg-emerald-600 font-bold text-white">S</div>
                    <div>
                        <p class="text-sm font-semibold text-slate-950">SIPUS</p>
                        <p class="text-xs text-slate-500">Perpustakaan Sekolah</p>
                    </div>
                </div>

                <nav class="hidden items-center gap-2 sm:flex">
                    <Link href="/dashboard" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-emerald-50 hover:text-emerald-700">Dashboard</Link>
                    <Link href="/profil" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-emerald-50 hover:text-emerald-700">Profil</Link>
                    <Link href="/logout" method="post" as="button" class="rounded-lg px-3 py-2 text-sm font-medium text-rose-600 transition hover:bg-rose-50">Keluar</Link>
                </nav>

                <div class="text-right sm:hidden">
                    <p class="text-sm font-semibold text-slate-900">{{ user?.nama }}</p>
                    <Link href="/logout" method="post" as="button" class="text-xs font-medium text-rose-600">Keluar</Link>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>
