<script setup lang="ts">
import AppLayout from '../../Layouts/AppLayout.vue'
import AppPagination from '../../components/AppPagination.vue'
import ConfirmDialog from '../../components/ConfirmDialog.vue'
import DataTable from '../../components/DataTable.vue'
import EmptyState from '../../components/EmptyState.vue'
import PageHeader from '../../components/PageHeader.vue'
import StatusBadge from '../../components/StatusBadge.vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

defineOptions({ layout: AppLayout })

const props = defineProps<{ resource: string; title: string; description: string; items: any; columns: Array<{ key: string; label: string; type?: string }>; createUrl: string; search: string; importUrl?: string; importPreviewUrl?: string; templateUrl?: string; filters?: { status?: string } }>()
const search = ref(props.search)
const status = ref(props.filters?.status ?? '')
const importOpen = ref(false)
const importForm = useForm<{ file: File | null }>({ file: null })
const inlineFields = computed(() => ({
    'school-years': [{ name: 'nama', label: 'Tahun ajaran', type: 'text' }, { name: 'semester', label: 'Semester', type: 'select', options: [['1', 'Semester 1'], ['2', 'Semester 2']] }, { name: 'mulai', label: 'Mulai', type: 'date' }, { name: 'selesai', label: 'Selesai', type: 'date' }, { name: 'is_aktif', label: 'Aktif', type: 'checkbox' }],
    'book-types': [{ name: 'nama', label: 'Nama jenis', type: 'text' }, { name: 'kode', label: 'Kode', type: 'text' }, { name: 'deskripsi', label: 'Deskripsi', type: 'text' }, { name: 'aktif', label: 'Aktif', type: 'checkbox' }],
    publishers: [{ name: 'nama', label: 'Nama penerbit', type: 'text' }, { name: 'email', label: 'Email', type: 'email' }, { name: 'telepon', label: 'Telepon', type: 'text' }, { name: 'website', label: 'Website', type: 'url' }],
    authors: [{ name: 'nama', label: 'Nama pengarang', type: 'text' }, { name: 'bio', label: 'Biografi', type: 'text' }, { name: 'catatan', label: 'Catatan', type: 'text' }],
    inventaris: [{ name: 'kode_inventaris', label: 'Kode inventaris', type: 'text' }, { name: 'nama_barang', label: 'Nama barang', type: 'text' }, { name: 'jenis', label: 'Jenis', type: 'text' }, { name: 'jumlah', label: 'Jumlah', type: 'number' }, { name: 'satuan', label: 'Satuan', type: 'text' }, { name: 'lokasi', label: 'Lokasi', type: 'text' }],
}[props.resource] ?? []))
const inlineForm = useForm<Record<string, any>>({})
const hasInlineForm = computed(() => inlineFields.value.length > 0)
const deletingId = ref<number | null>(null)
const itemToDelete = computed(() => props.items.data.find((item: any) => item.id === deletingId.value))

function value(item: any, key: string): any {
    return key.split('.').reduce((current, part) => current?.[part], item)
}

function display(item: any, column: { key: string; type?: string }): string {
    const raw = value(item, column.key)
    if (column.type === 'status') return raw ? 'Aktif' : 'Nonaktif'
    if (column.type === 'semester') return raw === '1' ? 'Semester 1' : raw === '2' ? 'Semester 2' : String(raw ?? '—')
    if (raw === null || raw === undefined || raw === '') return '—'
    if (column.key === 'mulai' || column.key === 'selesai') return new Date(raw).toLocaleDateString('id-ID')
    return String(raw)
}

function applySearch(): void {
    router.get(window.location.pathname, { search: search.value, ...(props.resource === 'book-types' ? { status: status.value } : {}) }, { preserveState: true, replace: true })
}

function destroy(): void {
    if (!itemToDelete.value) return
    router.delete(`/${props.resource}/${itemToDelete.value.id}`, { preserveScroll: true, onFinish: () => { deletingId.value = null } })
}

function selectImportFile(event: Event): void {
    importForm.file = (event.target as HTMLInputElement).files?.[0] ?? null
}

function submitImport(): void {
    if (!props.importPreviewUrl) return
    importForm.post(props.importPreviewUrl, { preserveScroll: true })
}

function submitInline(): void {
    inlineForm.post(`/${props.resource}`, { preserveScroll: true, onSuccess: () => inlineForm.reset() })
}
</script>

<template>
    <div class="space-y-6">
        <PageHeader :title="title" :description="description">
            <template #actions><button v-if="importPreviewUrl" type="button" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50" @click="importOpen = !importOpen">{{ importOpen ? 'Tutup import' : 'Import XLSX' }}</button><Link v-else-if="importUrl" :href="importUrl" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Import XLSX</Link><Link :href="createUrl" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Tambah data</Link></template>
        </PageHeader>

        <section v-if="importOpen && importPreviewUrl" class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5"><div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end"><div><h2 class="font-bold text-emerald-950">Upload data buku</h2><p class="mt-1 text-sm text-emerald-800">Gunakan template dan isi <strong>jenis_buku</strong> dengan kode atau nama Jenis Buku yang aktif.</p></div><a v-if="templateUrl" :href="templateUrl" class="w-fit rounded-xl border border-emerald-300 bg-white px-4 py-2.5 text-sm font-semibold text-emerald-800 hover:bg-emerald-100">Download template</a></div><form class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-end" @submit.prevent="submitImport"><div class="min-w-0 flex-1"><label for="book-import-file" class="mb-2 block text-sm font-semibold text-emerald-950">File XLSX</label><input id="book-import-file" type="file" accept=".xlsx" required class="block w-full rounded-xl border border-emerald-300 bg-white px-4 py-2.5 text-sm" @input="selectImportFile"><p v-if="importForm.errors.file" class="mt-1 text-sm text-rose-600">{{ importForm.errors.file }}</p></div><button type="submit" :disabled="importForm.processing" class="rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800 disabled:opacity-60">{{ importForm.processing ? 'Memproses...' : 'Upload dan preview' }}</button></form></section>

        <section v-if="hasInlineForm" class="rounded-2xl border border-emerald-200 bg-white p-5 shadow-sm"><div class="mb-4"><h2 class="font-bold text-slate-950">Tambah data</h2><p class="mt-1 text-sm text-slate-500">Isi form berikut untuk menambahkan data baru.</p></div><form class="grid gap-4 md:grid-cols-2 xl:grid-cols-3" @submit.prevent="submitInline"><div v-for="field in inlineFields" :key="field.name" :class="field.type === 'checkbox' ? 'flex items-center gap-2 self-end pb-3' : ''"><label v-if="field.type !== 'checkbox'" :for="`inline-${field.name}`" class="mb-1.5 block text-xs font-semibold text-slate-600">{{ field.label }}</label><select v-if="field.type === 'select'" v-model="inlineForm[field.name]" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm"><option v-for="option in field.options" :key="option[0]" :value="option[0]">{{ option[1] }}</option></select><input v-else-if="field.type !== 'checkbox'" :id="`inline-${field.name}`" v-model="inlineForm[field.name]" :type="field.type" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"><label v-else class="text-sm text-slate-700"><input v-model="inlineForm[field.name]" type="checkbox" class="mr-2 size-4 rounded border-slate-300 text-emerald-600">{{ field.label }}</label><p v-if="inlineForm.errors[field.name]" class="mt-1 text-xs text-rose-600">{{ inlineForm.errors[field.name] }}</p></div><div class="flex items-end"><button type="submit" :disabled="inlineForm.processing" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 disabled:opacity-60">{{ inlineForm.processing ? 'Menyimpan...' : 'Simpan data' }}</button></div></form></section>

        <form class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row" @submit.prevent="applySearch">
            <input v-model="search" type="search" placeholder="Cari data..." class="min-w-0 flex-1 rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
            <select v-if="resource === 'book-types'" v-model="status" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-emerald-500"><option value="">Semua status</option><option value="active">Aktif</option><option value="inactive">Nonaktif</option></select><button type="submit" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cari</button>
        </form>

        <DataTable v-if="items.data.length" :headers="[...columns.map((column) => column.label), 'Aksi']">
            <tr v-for="item in items.data" :key="item.id" class="hover:bg-slate-50/70">
                <td v-for="column in columns" :key="column.key" class="whitespace-nowrap px-5 py-4 text-slate-700">
                    <StatusBadge v-if="column.type === 'status'" :status="display(item, column)" />
                    <span v-else>{{ display(item, column) }}</span>
                </td>
                <td class="whitespace-nowrap px-5 py-4 text-right">
                    <div class="flex justify-end gap-2"><Link :href="`/${resource}/${item.id}`" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-100">Detail</Link><Link :href="`/${resource}/${item.id}/edit`" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50">Edit</Link><button type="button" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50" @click="deletingId = item.id">Hapus</button></div>
                </td>
            </tr>
        </DataTable>
        <EmptyState v-else title="Belum ada data master" description="Tambahkan data pertama untuk mulai menggunakan modul ini." />
        <AppPagination :links="items.links" />

        <ConfirmDialog :open="deletingId !== null" title="Hapus data?" :message="`Data ${itemToDelete?.nama ?? ''} akan dihapus dan tidak dapat dikembalikan.`" @close="deletingId = null" @confirm="destroy" />
    </div>
</template>
