<script setup lang="ts">
defineProps<{ message: string }>()
</script>

<template>
    <div class="flex items-start justify-between gap-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">
        <div class="flex items-start gap-3">
            <svg class="mt-0.5 size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5" /></svg>
            <span>{{ message }}</span>
        </div>
    </div>
</template>
