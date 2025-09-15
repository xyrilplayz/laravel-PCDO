<script setup>
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';

defineProps({
  cooperatives: Array,
  breadcrumbs: Array,
  urls: Object,
})
</script>

<template>
  <Head :title="`| ${$page.component}`" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <h1 class="text-xl font-bold mb-4">Cooperatives</h1>
      <h2 class="mb-4">List of all cooperatives</h2>
      <Link :href="urls.create">
        <Button>Add New Cooperative</Button>
      </Link>
      <Link :href="urls.import">
        <Button>Import Data</Button>
      </Link>
      <Link :href="urls.export">
        <Button>Export Data</Button>
      </Link>
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
              <Link
                :href="`/cooperatives/${coop.id}`"
                class="text-blue-500 hover:underline"
                >View</Link
              >
              <Link
                :href="`/cooperatives/${coop.id}/edit`"
                class="text-green-500 hover:underline"
                >Edit</Link
              >
              <Link
                :href="`/cooperatives/${coop.id}/delete`"
                class="text-red-500 hover:underline"
                >Delete</Link
              >
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>