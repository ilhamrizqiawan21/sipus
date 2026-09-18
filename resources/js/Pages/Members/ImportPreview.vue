<script setup lang="ts">
import AppLayout from '../../Layouts/AppLayout.vue'
import PageHeader from '../../components/PageHeader.vue'
import { Link, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'

defineOptions({ layout: AppLayout })
const props = defineProps<{ token: string; rows: Array<Record<string, any>>; backUrl: string }>()
const form = useForm({ token: props.token })
const validCount = computed(() => props.rows.filter((row) => row._valid).length)
function confirm(): void { form.post('/anggota/import/confirm') }
</script>

<template>
    <div class="space-y-6">
        <PageHeader title="Preview Import Anggota" description="Periksa baris valid dan baris yang perlu diperbaiki sebelum disimpan.">
            <template #actions><Link :href="backUrl" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Upload ulang</Link><button type="button" :disabled="validCount === 0 || form.processing" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 disabled:opacity-50" @click="confirm">Simpan {{ validCount }} baris valid</button></template>
        </PageHeader>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"><div class="overflow-x-auto"><table class="min-w-full text-left text-sm"><thead class="bg-slate-50 text-xs uppercase text-slate-500"><tr><th class="px-4 py-3">Baris</th><th class="px-4 py-3">Nomor</th><th class="px-4 py-3">Nama</th><th class="px-4 py-3">Jenis</th><th class="px-4 py-3">Status validasi</th></tr></thead><tbody class="divide-y divide-slate-100"><tr v-for="row in rows" :key="row._row"><td class="px-4 py-3">{{ row._row }}</td><td class="px-4 py-3">{{ row.nomor_anggota || '—' }}</td><td class="px-4 py-3">{{ row.nama || '—' }}</td><td class="px-4 py-3 capitalize">{{ row.jenis_anggota || '—' }}</td><td class="px-4 py-3"><span v-if="row._valid" class="font-semibold text-emerald-700">Valid</span><span v-else class="text-rose-700">{{ row._errors.join(', ') }}</span></td></tr></tbody></table></div></div>
    </div>
</template>
