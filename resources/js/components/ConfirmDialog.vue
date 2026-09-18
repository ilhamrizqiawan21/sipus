<script setup lang="ts">
defineProps<{
    open: boolean
    title?: string
    message?: string
    confirmText?: string
    cancelText?: string
}>()

const emit = defineEmits<{ close: []; confirm: [] }>()
</script>

<template>
    <Teleport to="body">
        <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/40 p-4" role="dialog" aria-modal="true" @click.self="emit('close')">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                <h2 class="text-lg font-bold text-slate-950">{{ title ?? 'Konfirmasi tindakan' }}</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">{{ message ?? 'Apakah Anda yakin ingin melanjutkan tindakan ini?' }}</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50" @click="emit('close')">{{ cancelText ?? 'Batal' }}</button>
                    <button type="button" class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-700" @click="emit('confirm')">{{ confirmText ?? 'Ya, lanjutkan' }}</button>
                </div>
            </div>
        </div>
    </Teleport>
</template>
