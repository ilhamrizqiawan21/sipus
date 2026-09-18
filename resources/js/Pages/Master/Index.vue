<script setup lang="ts">
import AppLayout from '../../Layouts/AppLayout.vue'
import AppPagination from '../../components/AppPagination.vue'
import ConfirmDialog from '../../components/ConfirmDialog.vue'
import DataTable from '../../components/DataTable.vue'
import EmptyState from '../../components/EmptyState.vue'
import PageHeader from '../../components/PageHeader.vue'
import StatusBadge from '../../components/StatusBadge.vue'
import { Link, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

defineOptions({ layout: AppLayout })

const props = defineProps<{ resource: string; title: string; description: string; items: any; columns: Array<{ key: string; label: string; type?: string }>; createUrl: string; search: string; importUrl?: string }>()
const search = ref(props.search)
const deletingId = ref<number | null>(null)
const itemToDelete = computed(() => props.items.data.find((item: any) => item.id === deletingId.value))

function value(item: any, key: string): any {
    return key.split('.').reduce((current, part) => current?.[part], item)
}

function display(item: any, column: { key: string; type?: string }): string {
    const raw = value(item, column.key)
    if (column.type === 'status') return raw ? 'Aktif' : 'Nonaktif'
    if (raw === null || raw === undefined || raw === '') return '—'
    if (column.key === 'mulai' || column.key === 'selesai') return new Date(raw).toLocaleDateString('id-ID')
    return String(raw)
}

function applySearch(): void {
    router.get(window.location.pathname, { search: search.value }, { preserveState: true, replace: true })
}

function destroy(): void {
    if (!itemToDelete.value) return
    router.delete(`/${props.resource}/${itemToDelete.value.id}`, { preserveScroll: true, onFinish: () => { deletingId.value = null } })
}
</script>

<template>
    <div class="space-y-6">
        <PageHeader :title="title" :description="description">
            <template #actions><Link v-if="importUrl" :href="importUrl" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Import XLSX</Link><Link :href="createUrl" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Tambah data</Link></template>
        </PageHeader>

        <form class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row" @submit.prevent="applySearch">
            <input v-model="search" type="search" placeholder="Cari data..." class="min-w-0 flex-1 rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
            <button type="submit" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cari</button>
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
