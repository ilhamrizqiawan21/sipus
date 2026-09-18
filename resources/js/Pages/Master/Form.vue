<script setup lang="ts">
import AppLayout from '../../Layouts/AppLayout.vue'
import FormField from '../../components/FormField.vue'
import PageHeader from '../../components/PageHeader.vue'
import { Link, useForm } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

const props = defineProps<{ title: string; item: Record<string, any> | null; fields: Array<{ name: string; label: string; type?: string; required?: boolean; default?: string | number | boolean; options?: Array<{ value: string | number; label: string }> }>; action: string; method: 'post' | 'patch'; backUrl: string }>()
const formData = Object.fromEntries(props.fields.map((field) => [field.name, props.item?.[field.name] ?? field.default ?? (field.type === 'checkbox' ? false : '')]))
const form = useForm(formData)

function submit(): void {
    form.submit(props.method, props.action, { preserveScroll: true })
}
</script>

<template>
    <div class="mx-auto max-w-3xl space-y-6">
        <PageHeader :title="title" description="Lengkapi data dengan informasi yang valid." />
        <form class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <FormField v-for="field in fields" :key="field.name" :name="field.name" :label="field.label" :error="form.errors[field.name]" :required="field.required">
                <label v-if="field.type === 'checkbox'" class="flex items-center gap-3 text-sm text-slate-700"><input v-model="form[field.name]" type="checkbox" class="size-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"> {{ field.label }}</label>
                <select v-else-if="field.type === 'select'" :id="field.name" v-model="form[field.name]" :required="field.required" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"><option value="">Pilih {{ field.label.toLowerCase() }}</option><option v-for="option in field.options" :key="option.value" :value="option.value">{{ option.label }}</option></select>
                <textarea v-else-if="field.type === 'textarea'" :id="field.name" v-model="form[field.name]" :required="field.required" rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100" />
                <input v-else :id="field.name" v-model="form[field.name]" :type="field.type ?? 'text'" :required="field.required" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
            </FormField>
            <div class="flex justify-end gap-3 border-t border-slate-100 pt-5"><Link :href="backUrl" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</Link><button type="submit" :disabled="form.processing" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 disabled:opacity-60">{{ form.processing ? 'Menyimpan...' : 'Simpan' }}</button></div>
        </form>
    </div>
</template>
