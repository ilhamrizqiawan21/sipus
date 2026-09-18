<script setup lang="ts">
withDefaults(defineProps<{ title?: string; description?: string }>(), {
    title: 'Data gagal dimuat',
    description: 'Silakan coba lagi atau hubungi administrator.',
})
</script>

<template>
    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-6 py-10 text-center text-rose-800" role="alert">
        <h3 class="text-sm font-semibold">{{ title }}</h3>
        <p class="mt-1 text-sm text-rose-700">{{ description }}</p>
    </div>
</template>
