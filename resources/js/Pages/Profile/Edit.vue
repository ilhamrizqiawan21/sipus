<script setup lang="ts">
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue'
import { useForm, usePage } from '@inertiajs/vue3'

defineOptions({ layout: AuthenticatedLayout })

const page = usePage()
const user = page.props.auth?.user

const form = useForm({
    nama: user?.nama ?? '',
    username: user?.username ?? '',
    password: '',
    password_confirmation: '',
    foto: null as File | null,
})

function submit() {
    form.patch('/profil', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => form.reset('password', 'password_confirmation', 'foto'),
    })
}

function selectPhoto(event: Event) {
    const target = event.target as HTMLInputElement
    form.foto = target.files?.[0] ?? null
}
</script>

<template>
    <div class="mx-auto max-w-2xl space-y-6">
        <div>
            <p class="text-sm font-medium text-emerald-700">Akun</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950">Profil saya</h1>
            <p class="mt-2 text-sm text-slate-500">Perbarui informasi akun dan password Anda.</p>
        </div>

        <form class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
            <div>
                <label for="nama" class="mb-2 block text-sm font-medium text-slate-700">Nama</label>
                <input id="nama" v-model="form.nama" type="text" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                <p v-if="form.errors.nama" class="mt-2 text-sm text-rose-600">{{ form.errors.nama }}</p>
            </div>

            <div>
                <label for="username" class="mb-2 block text-sm font-medium text-slate-700">Username</label>
                <input id="username" v-model="form.username" type="text" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                <p v-if="form.errors.username" class="mt-2 text-sm text-rose-600">{{ form.errors.username }}</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Password baru</label>
                    <input id="password" v-model="form.password" type="password" autocomplete="new-password" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    <p v-if="form.errors.password" class="mt-2 text-sm text-rose-600">{{ form.errors.password }}</p>
                </div>
                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">Konfirmasi password</label>
                    <input id="password_confirmation" v-model="form.password_confirmation" type="password" autocomplete="new-password" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>
            </div>

            <div>
                <label for="foto" class="mb-2 block text-sm font-medium text-slate-700">Foto profil</label>
                <input id="foto" type="file" accept="image/jpeg,image/png,image/webp" class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm" @input="selectPhoto">
                <p v-if="form.errors.foto" class="mt-2 text-sm text-rose-600">{{ form.errors.foto }}</p>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                <span v-if="form.recentlySuccessful" class="text-sm font-medium text-emerald-700">Profil tersimpan.</span>
                <button type="submit" :disabled="form.processing" class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-60">
                    {{ form.processing ? 'Menyimpan...' : 'Simpan perubahan' }}
                </button>
            </div>
        </form>
    </div>
</template>
