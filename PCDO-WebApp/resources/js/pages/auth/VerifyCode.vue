<script setup lang="ts">
import { useForm, Head } from '@inertiajs/vue3'

const form = useForm({
  code: '',
})

const submit = () => {
  form.post('/login/verify', {
    onError: (errors) => console.log(errors),
  })
}
</script>

<template>
  <Head title="Verify Login Code" />

  <div class="max-w-md mx-auto mt-12 p-6 border rounded">
    <h1 class="text-xl font-semibold mb-4">Enter Your Login Code</h1>

    <form @submit.prevent="submit" class="flex flex-col gap-4">
      <input
        type="text"
        v-model="form.code"
        name="code"
        placeholder="Enter code"
        class="border p-2 rounded"
      />
      <div v-if="form.errors.code" class="text-red-600">{{ form.errors.code }}</div>

      <button
        type="submit"
        :disabled="form.processing"
        class="bg-blue-600 text-white p-2 rounded disabled:opacity-50"
      >
        Verify
      </button>
    </form>
  </div>
</template>
