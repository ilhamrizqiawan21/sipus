<script setup lang="ts">
withDefaults(defineProps<{ title?: string; description?: string }>(), {
    title: 'Belum ada data',
    description: 'Data akan tampil di sini setelah tersedia.',
})
</script>

<template>
    <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
        <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">—</div>
        <h3 class="mt-4 text-sm font-semibold text-slate-900">{{ title }}</h3>
        <p class="mt-1 text-sm text-slate-500">{{ description }}</p>
    </div>
</template>
