<script setup>
import { Head } from '@inertiajs/vue3'

defineProps({
  cooperatives: Array
})
</script>

<template>
  <Head title="Cooperatives" />

  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Cooperatives</h1>
    <table class="min-w-full border">
      <thead>
        <tr>
          <th class="border p-2">ID</th>
          <th class="border p-2">Name</th>
          <th class="border p-2">Type</th>
          <th class="border p-2">Holder</th>
          <th class="border p-2">Members</th>
          <th class="border p-2">Ongoing Program</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="coop in cooperatives" :key="coop.id">
          <td class="border p-2">{{ coop.id }}</td>
          <td class="border p-2">{{ coop.name }}</td>
          <td class="border p-2">{{ coop.type }}</td>
          <td class="border p-2">{{ coop.holder }}</td>
          <td class="border p-2">{{ coop.member_count }}</td>
          <td class="border p-2">
            {{ coop.has_ongoing_program ? '✅ Ongoing' : '—' }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>