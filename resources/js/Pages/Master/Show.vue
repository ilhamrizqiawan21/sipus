<script setup lang="ts">
import AppLayout from '../../Layouts/AppLayout.vue'
import PageHeader from '../../components/PageHeader.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })
const props = defineProps<{ title: string; item: Record<string, any>; editUrl: string; backUrl: string; resource?: string; books?: Array<{ id: number; kode_buku: string | null; judul: string }> }>()
const excluded = new Set(['id', 'created_at', 'updated_at', 'school_year', 'members'])
function label(key: string): string { return key.replace(/_count$/, ' (jumlah)').replaceAll('_', ' ').replace(/\b\w/g, (letter) => letter.toUpperCase()) }
function display(value: any): string { if (typeof value === 'boolean') return value ? 'Aktif' : 'Nonaktif'; if (value === null || value === '') return '—'; return String(value) }
</script>

<template>
    <div class="mx-auto max-w-3xl space-y-6">
        <PageHeader :title="title">
            <template #actions><Link :href="backUrl" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Kembali</Link><Link :href="editUrl" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">Edit</Link></template>
        </PageHeader>
        <div class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:grid-cols-2">
            <div v-for="(value, key) in item" v-show="!excluded.has(key)" :key="key" class="border-b border-slate-100 pb-4"><dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ label(key) }}</dt><dd class="mt-1 text-sm text-slate-800">{{ display(value) }}</dd></div>
        </div>
        <div v-if="resource === 'book-types'" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><h2 class="font-bold text-slate-950">Buku dalam jenis ini</h2><div v-if="books?.length" class="mt-4 divide-y divide-slate-100"><Link v-for="book in books" :key="book.id" :href="`/buku/${book.id}`" class="flex items-center justify-between gap-4 py-3 hover:text-emerald-700"><span><span class="block text-sm font-semibold">{{ book.judul }}</span><span class="text-xs text-slate-500">{{ book.kode_buku || 'Tanpa kode' }}</span></span><span class="text-xs font-semibold text-emerald-700">Lihat buku</span></Link></div><p v-else class="mt-4 rounded-xl bg-slate-50 p-4 text-sm text-slate-500">Belum ada buku dalam jenis ini.</p></div>
    </div>
</template>
