<script setup lang="ts">
import AppLayout from '../../Layouts/AppLayout.vue'
import PageHeader from '../../components/PageHeader.vue'
import { Link, useForm } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })
defineProps<{ templateUrl: string; backUrl: string }>()
const form = useForm<{ file: File | null }>({ file: null })
function submit(): void { form.post('/buku/import/preview') }
function selectFile(event: Event): void { form.file = (event.target as HTMLInputElement).files?.[0] ?? null }
</script>

<template>
    <div class="mx-auto max-w-3xl space-y-6">
        <PageHeader title="Import Buku XLSX" description="Upload banyak judul buku sekaligus, periksa validasi, lalu konfirmasi penyimpanan.">
            <template #actions><Link :href="backUrl" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Kembali</Link></template>
        </PageHeader>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="rounded-xl bg-emerald-50 p-4 text-sm leading-6 text-emerald-900">Gunakan template. Kolom <strong>jenis_buku</strong> harus diisi dengan kode atau nama Jenis Buku yang aktif. Penerbit dan pengarang harus sudah tersedia di menu masing-masing.</div>
            <div class="mt-6 flex flex-wrap gap-3"><a :href="templateUrl" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Download template</a></div>
            <form class="mt-6 space-y-4" @submit.prevent="submit"><div><label for="file" class="mb-2 block text-sm font-semibold text-slate-700">File XLSX</label><input id="file" type="file" accept=".xlsx" required class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm" @input="selectFile"><p v-if="form.errors.file" class="mt-2 text-sm text-rose-600">{{ form.errors.file }}</p></div><button type="submit" :disabled="form.processing" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 disabled:opacity-60">{{ form.processing ? 'Memproses...' : 'Upload dan preview' }}</button></form>
        </div>
    </div>
</template>
