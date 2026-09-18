<script setup lang="ts">
import AppLayout from '../../Layouts/AppLayout.vue'
import ConfirmDialog from '../../components/ConfirmDialog.vue'
import PageHeader from '../../components/PageHeader.vue'
import StatusBadge from '../../components/StatusBadge.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({ layout: AppLayout })
const props = defineProps<{ book: any; editUrl: string; backUrl: string; copyCreateUrl: string }>()
const deletingCopy = ref<number | null>(null)
function removeCopy(): void { if (deletingCopy.value) router.delete(`/eksemplar/${deletingCopy.value}`, { onFinish: () => { deletingCopy.value = null } }) }
function authorNames(): string { return props.book.authors?.map((author: { nama: string }) => author.nama).join(', ') || '—' }
</script>

<template>
    <div class="space-y-6">
        <PageHeader :title="book.judul" :description="book.kode_buku || 'Buku tanpa kode katalog'">
            <template #actions><Link :href="backUrl" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Kembali</Link><Link :href="editUrl" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">Edit buku</Link></template>
        </PageHeader>
        <section class="grid gap-6 lg:grid-cols-[180px_1fr]">
            <div class="flex min-h-56 items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"><img v-if="book.cover_path" :src="`/storage/${book.cover_path}`" :alt="book.judul" class="h-full w-full object-cover"><span v-else class="text-sm text-slate-400">Belum ada cover</span></div>
            <div class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:grid-cols-2"><div><p class="text-xs font-semibold uppercase text-slate-400">Jenis</p><p class="mt-1 text-sm text-slate-800">{{ book.book_type?.nama }}</p></div><div><p class="text-xs font-semibold uppercase text-slate-400">Penerbit</p><p class="mt-1 text-sm text-slate-800">{{ book.publisher?.nama || '—' }}</p></div><div><p class="text-xs font-semibold uppercase text-slate-400">Pengarang</p><p class="mt-1 text-sm text-slate-800">{{ authorNames() }}</p></div><div><p class="text-xs font-semibold uppercase text-slate-400">Tahun terbit</p><p class="mt-1 text-sm text-slate-800">{{ book.tahun_terbit || '—' }}</p></div><div class="sm:col-span-2"><p class="text-xs font-semibold uppercase text-slate-400">Deskripsi</p><p class="mt-1 whitespace-pre-line text-sm leading-6 text-slate-600">{{ book.deskripsi || '—' }}</p></div></div>
        </section>
        <section class="space-y-4"><div class="flex items-center justify-between"><div><h2 class="text-lg font-bold text-slate-950">Eksemplar</h2><p class="text-sm text-slate-500">{{ book.available_copies }} tersedia dari {{ book.total_copies }} eksemplar.</p></div><Link :href="copyCreateUrl" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">Tambah eksemplar</Link></div><div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"><div class="overflow-x-auto"><table class="min-w-full text-left text-sm"><thead class="bg-slate-50 text-xs uppercase text-slate-500"><tr><th class="px-5 py-3">Kode inventaris</th><th class="px-5 py-3">Lokasi</th><th class="px-5 py-3">Kondisi</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Aksi</th></tr></thead><tbody class="divide-y divide-slate-100"><tr v-for="copy in book.copies" :key="copy.id"><td class="px-5 py-4 font-medium text-slate-800">{{ copy.kode_inventaris }}</td><td class="px-5 py-4 text-slate-600">{{ copy.lokasi_rak || '—' }}</td><td class="px-5 py-4"><StatusBadge :status="copy.kondisi.replace('_', ' ')" /></td><td class="px-5 py-4"><StatusBadge :status="copy.status" /></td><td class="px-5 py-4 text-right"><Link :href="`/eksemplar/${copy.id}/edit`" class="mr-2 text-xs font-semibold text-emerald-700">Edit</Link><button type="button" class="text-xs font-semibold text-rose-600" @click="deletingCopy = copy.id">Hapus</button></td></tr><tr v-if="!book.copies.length"><td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">Belum ada eksemplar.</td></tr></tbody></table></div></div></section>
        <ConfirmDialog :open="deletingCopy !== null" title="Hapus eksemplar?" message="Eksemplar akan dihapus dari katalog." @close="deletingCopy = null" @confirm="removeCopy" />
    </div>
</template>
