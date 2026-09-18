<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{ status: string; label?: string }>()

const tone = computed(() => {
    const value = props.status.toLowerCase()

    if (['aktif', 'tersedia', 'dikembalikan', 'selesai'].includes(value)) return 'emerald'
    if (['terlambat', 'rusak', 'hilang', 'ditolak'].includes(value)) return 'rose'
    if (['dipinjam', 'diproses', 'menunggu'].includes(value)) return 'amber'

    return 'slate'
})

const toneClasses = computed(() => ({
    emerald: 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
    rose: 'bg-rose-50 text-rose-700 ring-rose-600/20',
    amber: 'bg-amber-50 text-amber-700 ring-amber-600/20',
    slate: 'bg-slate-100 text-slate-700 ring-slate-600/20',
}[tone.value]))
</script>

<template>
    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold capitalize ring-1 ring-inset" :class="toneClasses">
        {{ label ?? status }}
    </span>
</template>
