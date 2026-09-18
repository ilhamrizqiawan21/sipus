<script setup lang="ts">
import AppLayout from '../Layouts/AppLayout.vue'
import { Link, usePage } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

defineProps<{
    stats: {
        activeLoans: number
        completedLoans: number
        overdueLoans: number
        role: string
    }
}>()

const page = usePage()
</script>

<template>
    <div class="space-y-8">
        <section class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-medium text-emerald-700">Dashboard</p>
                <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">Selamat datang, {{ page.props.auth?.user?.nama }}</h1>
                <p class="mt-2 text-sm text-slate-500">Berikut ringkasan aktivitas perpustakaan Anda.</p>
            </div>
            <Link href="/profil" class="inline-flex w-fit items-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-emerald-300 hover:text-emerald-700">Kelola profil</Link>
        </section>

        <section class="grid gap-4 sm:grid-cols-3">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Peminjaman aktif</p>
                <p class="mt-3 text-3xl font-bold text-slate-950">{{ stats.activeLoans }}</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Peminjaman selesai</p>
                <p class="mt-3 text-3xl font-bold text-slate-950">{{ stats.completedLoans }}</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Terlambat</p>
                <p class="mt-3 text-3xl font-bold text-rose-600">{{ stats.overdueLoans }}</p>
            </article>
        </section>

        <section class="rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
            <p class="text-sm font-semibold text-emerald-900">Akses Anda</p>
            <p class="mt-2 text-sm text-emerald-800">Role aktif: <span class="font-bold uppercase">{{ stats.role }}</span>. Modul perpustakaan akan tersedia sesuai hak akses role ini.</p>
        </section>
    </div>
</template>
