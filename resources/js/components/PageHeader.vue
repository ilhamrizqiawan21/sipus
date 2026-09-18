<script setup lang="ts">
withDefaults(defineProps<{
    eyebrow?: string
    title: string
    description?: string
}>(), {
    eyebrow: 'SIPUS',
    description: '',
})
</script>

<template>
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-semibold text-emerald-700">{{ eyebrow }}</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">{{ title }}</h1>
            <p v-if="description" class="mt-2 text-sm text-slate-500">{{ description }}</p>
        </div>
        <div v-if="$slots.actions" class="flex items-center gap-3">
            <slot name="actions" />
        </div>
    </div>
</template>
