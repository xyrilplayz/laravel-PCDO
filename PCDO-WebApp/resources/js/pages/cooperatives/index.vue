<script setup>
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';

defineProps({
  cooperatives: Array,
  breadcrumbs: Array
})
</script>

<template>
  <Head :title="`| ${$page.component}`" />
  <AppLayout :breadcrumbs="`${$page.props.breadcrumbs || []}`">
    <div class="p-6">
      <h1 class="text-xl font-bold mb-4">Cooperatives</h1>
      <h2 class="mb-4">List of all cooperatives</h2>
      <inertia-link
        href="/cooperatives/create"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-4 inline-block"
        >Add New Cooperative</inertia-link
      >
      <inertia-link
        href="/cooperatives/import"
        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mb-4 inline-block ml-2"
        >Import Data</inertia-link
      >
      <inertia-link
        href="/cooperatives/export"
        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mb-4 inline-block ml-2"
        >Export Data</inertia-link
      >
      <input
        type="text"
        placeholder="Search cooperatives..."
        class="border p-2 mb-4 w-full"
      />
      <table class="min-w-full border">
        <thead>
          <tr>
            <th class="border p-2">ID</th>
            <th class="border p-2">Name</th>
            <th class="border p-2">Type</th>
            <th class="border p-2">Holder</th>
            <th class="border p-2">Members</th>
            <th class="border p-2">Ongoing Program</th>
            <th class="border p-2">Actions</th>
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
            <td>
              <inertia-link
                :href="`/cooperatives/${coop.id}`"
                class="text-blue-500 hover:underline"
                >View</inertia-link
              >
              <inertia-link
                :href="`/cooperatives/${coop.id}/edit`"
                class="text-green-500 hover:underline"
                >Edit</inertia-link
              >
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>