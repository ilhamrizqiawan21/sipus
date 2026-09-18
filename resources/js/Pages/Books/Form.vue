<script setup lang="ts">
import AppLayout from '../../Layouts/AppLayout.vue'
import FormField from '../../components/FormField.vue'
import PageHeader from '../../components/PageHeader.vue'
import { Link, useForm } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })
const props = defineProps<{ title: string; item: Record<string, any> | null; bookTypes: Array<{ id: number; nama: string }>; publishers: Array<{ id: number; nama: string }>; authors: Array<{ id: number; nama: string }>; action: string; method: 'post' | 'patch'; backUrl: string }>()
const form = useForm({ kode_buku: props.item?.kode_buku ?? '', judul: props.item?.judul ?? '', jenis_buku_id: props.item?.jenis_buku_id ?? '', penerbit_id: props.item?.penerbit_id ?? '', tahun_terbit: props.item?.tahun_terbit ?? '', deskripsi: props.item?.deskripsi ?? '', authors: props.item?.authors ?? [], cover: null as File | null })

function submit(): void { form.submit(props.method, props.action, { forceFormData: true, preserveScroll: true }) }
function selectCover(event: Event): void { form.cover = (event.target as HTMLInputElement).files?.[0] ?? null }
</script>

<template>
    <div class="mx-auto max-w-3xl space-y-6">
        <PageHeader :title="title" description="Lengkapi metadata judul buku dan hubungan katalog." />
        <form class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <div class="grid gap-5 sm:grid-cols-2">
                <FormField name="kode_buku" label="Kode buku" :error="form.errors.kode_buku"><input id="kode_buku" v-model="form.kode_buku" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"></FormField>
                <FormField name="tahun_terbit" label="Tahun terbit" :error="form.errors.tahun_terbit"><input id="tahun_terbit" v-model="form.tahun_terbit" type="number" min="1000" max="2100" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"></FormField>
            </div>
            <FormField name="judul" label="Judul buku" :error="form.errors.judul" required><input id="judul" v-model="form.judul" type="text" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"></FormField>
            <div class="grid gap-5 sm:grid-cols-2">
                <FormField name="jenis_buku_id" label="Jenis buku" :error="form.errors.jenis_buku_id" required><select id="jenis_buku_id" v-model="form.jenis_buku_id" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm"><option value="">Pilih jenis buku</option><option v-for="type in bookTypes" :key="type.id" :value="type.id">{{ type.nama }}</option></select></FormField>
                <FormField name="penerbit_id" label="Penerbit" :error="form.errors.penerbit_id"><select id="penerbit_id" v-model="form.penerbit_id" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm"><option value="">Tanpa penerbit</option><option v-for="publisher in publishers" :key="publisher.id" :value="publisher.id">{{ publisher.nama }}</option></select></FormField>
            </div>
            <FormField name="authors" label="Pengarang" :error="form.errors.authors"><select id="authors" v-model="form.authors" multiple class="min-h-32 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm"><option v-for="author in authors" :key="author.id" :value="author.id">{{ author.nama }}</option></select><p class="mt-1 text-xs text-slate-400">Gunakan Ctrl/Cmd untuk memilih lebih dari satu pengarang.</p></FormField>
            <FormField name="deskripsi" label="Deskripsi" :error="form.errors.deskripsi"><textarea id="deskripsi" v-model="form.deskripsi" rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" /></FormField>
            <FormField name="cover" label="Cover buku" :error="form.errors.cover"><input id="cover" type="file" accept="image/jpeg,image/png,image/webp" class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm" @input="selectCover"><p class="mt-1 text-xs text-slate-400">JPG, PNG, atau WebP maksimal 2 MB.</p></FormField>
            <div class="flex justify-end gap-3 border-t border-slate-100 pt-5"><Link :href="backUrl" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</Link><button type="submit" :disabled="form.processing" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 disabled:opacity-60">{{ form.processing ? 'Menyimpan...' : 'Simpan' }}</button></div>
        </form>
    </div>
</template>
