<script setup lang="ts">
import { Form } from '@inertiajs/vue3'
</script>

<template>
    <main class="flex min-h-screen items-center justify-center bg-slate-50 px-4 py-12">
        <section class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="mb-8 text-center">
                <div class="mx-auto mb-4 flex size-14 items-center justify-center rounded-2xl bg-emerald-600 text-xl font-bold text-white">S</div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950">Masuk ke SIPUS</h1>
                <p class="mt-2 text-sm text-slate-500">Kelola aktivitas perpustakaan dengan mudah.</p>
            </div>

            <Form action="/login" method="post" #default="{ errors, processing }" class="space-y-5">
                <div>
                    <label for="username" class="mb-2 block text-sm font-medium text-slate-700">Username</label>
                    <input id="username" name="username" type="text" autocomplete="username" required autofocus class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    <p v-if="errors.username" class="mt-2 text-sm text-rose-600">{{ errors.username }}</p>
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    <p v-if="errors.password" class="mt-2 text-sm text-rose-600">{{ errors.password }}</p>
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input name="remember" type="checkbox" value="1" class="size-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    Ingat saya
                </label>

                <button type="submit" :disabled="processing" class="w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-60">
                    {{ processing ? 'Memproses...' : 'Masuk' }}
                </button>
            </Form>
        </section>
    </main>
</template>
