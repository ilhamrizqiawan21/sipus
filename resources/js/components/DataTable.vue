<script setup lang="ts">
defineProps<{ headers: string[] }>()
</script>

<template>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr><th v-for="header in headers" :key="header" class="whitespace-nowrap px-5 py-3 font-semibold">{{ header }}</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100"><slot /></tbody>
            </table>
        </div>
    </div>
</template>
