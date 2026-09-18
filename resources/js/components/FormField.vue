<script setup lang="ts">
defineProps<{
    label: string
    name: string
    error?: string
    required?: boolean
}>()
</script>

<template>
    <div>
        <label :for="name" class="mb-2 block text-sm font-medium text-slate-700">{{ label }}<span v-if="required" class="ml-1 text-rose-500">*</span></label>
        <slot />
        <p v-if="error" class="mt-2 text-sm text-rose-600">{{ error }}</p>
    </div>
</template>
