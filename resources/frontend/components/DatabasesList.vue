<template>
  <div class="p-4">
    <h2 class="text-2xl font-semibold mb-4">Lista baz danych</h2>

    <div v-if="loading" class="text-gray-500">Ładowanie...</div>
    <div v-else-if="databases.length === 0" class="text-gray-500">Brak zapisanych baz danych.</div>

    <ul class="space-y-2" v-else>
      <li v-for="(db, index) in databases" :key="index" class="bg-white shadow rounded-xl p-4">
        <div class="font-bold text-lg">{{ db.database }}</div>
        <div class="text-sm text-gray-600">Host: {{ db.host }}</div>
        <div class="text-sm text-gray-600">Użytkownik: {{ db.username }}</div>
        <div class="text-sm text-gray-600">Port: {{ db.port }}</div>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: "DatabasesList",
  data() {
    return {
      databases: [],
      loading: true
    };
  },
  methods: {
    async fetchDatabases() {
      try {
        const response = await fetch("/eskuelmyadmin/databases-list");

        if (!response.ok) throw new Error("Błąd ładowania danych");

        const data = await response.json();

        this.databases = data?.databases;
      } catch (err) {

        console.error("Błąd:", err);
        this.databases = [];
      } finally {

        this.loading = false;
      }
    }
  },
  mounted() {
    this.fetchDatabases();
  }
};
</script>