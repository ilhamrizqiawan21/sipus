<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

defineProps<{ links: Array<{ url: string | null; label: string; active: boolean }> }>()
</script>

<template>
    <nav v-if="links.length > 3" class="flex flex-wrap items-center justify-end gap-1" aria-label="Pagination">
        <template v-for="link in links" :key="link.label">
            <Link v-if="link.url" :href="link.url" preserve-scroll class="rounded-lg px-3 py-2 text-sm transition" :class="link.active ? 'bg-emerald-600 font-semibold text-white' : 'text-slate-600 hover:bg-slate-100'" v-html="link.label" />
            <span v-else class="rounded-lg px-3 py-2 text-sm text-slate-300" v-html="link.label" />
        </template>
    </nav>
</template>
