<template>
  <div class="p-4">
    <h2 class="text-2xl font-semibold mb-4">Lista baz danych</h2>

    <div v-if="loading" class="text-gray-500">Ładowanie...</div>
    <div v-else-if="databases.length === 0" class="text-gray-500">Brak zapisanych baz danych.</div>

    <ul class="space-y-2" v-else>
      <li
          v-for="(db, index) in databases"
          :key="index"
          class="bg-white shadow rounded-xl p-4 hover:bg-gray-100"
      >
        <div class="flex justify-between">
          <div>
            <div class="font-bold text-lg">{{ db.database }}</div>
            <div class="text-sm text-gray-600">Host: {{ db.host }}</div>
            <div class="text-sm text-gray-600">Użytkownik: {{ db.username }}</div>
            <div class="text-sm text-gray-600">Port: {{ db.port }}</div>
          </div>
          <div class="flex items-center flex-col gap-[5px] justify-center">
            <button @click="setEditing(db)" class="hover:bg-[#357ABD] cursor-pointer bg-[#007acc] text-[white] [transition:background-color_0.3s_ease] px-[10px] py-[5px] rounded-[10px]">
              Edytuj
            </button>
            <button @click="connectToDatabase(db)" class="hover:bg-[#357ABD] cursor-pointer bg-[#007acc] text-[white] px-[10px] [transition:background-color_0.3s_ease] py-[5px] rounded-[10px]">
              Połącz
            </button>
          </div>
        </div>
      </li>
    </ul>
  </div>

  <div v-show="isEditing" class="fixed w-full h-full top-[0] bg-[#000000a6]">
    <form @submit.prevent="submitForm"
          class="translate-y-1/4 max-w-[400px] mx-[auto] my-[1em] p-[2em] bg-[#fff] rounded-[8px] [box-shadow:0_0_12px_rgba(0,_0,_0,_0.1)] font-['Segoe_UI',_Tahoma,_Geneva,_Verdana,_sans-serif] text-[#333]">
      <h2 class="mb-[1.5em] text-center text-[#222]">Edytujesz bazę: {{ edited.database + ':' + edited.host }}</h2>

      <label class="flex flex-col mb-[1em] font-semibold">
        Host
        <input
            class="p-[0.5em] mt-[0.3em] border-[1px] border-[solid] border-[#ccc] rounded-[4px] text-[1em] [transition:border-color_0.3s_ease] focus:outline-[none] focus:border-[#4A90E2] focus:[box-shadow:0_0_4px_#4A90E2]"
            type="text" v-model="edited.host" required placeholder="np. localhost"/>
      </label>

      <label class="flex flex-col mb-[1em] font-semibold">
        Nazwa bazy danych
        <input
            class="p-[0.5em] mt-[0.3em] border-[1px] border-[solid] border-[#ccc] rounded-[4px] text-[1em] [transition:border-color_0.3s_ease] focus:outline-[none] focus:border-[#4A90E2] focus:[box-shadow:0_0_4px_#4A90E2]"
            type="text" v-model="edited.database" required placeholder="np. my_database"/>
      </label>

      <label class="flex flex-col mb-[1em] font-semibold">
        Użytkownik
        <input
            class="p-[0.5em] mt-[0.3em] border-[1px] border-[solid] border-[#ccc] rounded-[4px] text-[1em] [transition:border-color_0.3s_ease] focus:outline-[none] focus:border-[#4A90E2] focus:[box-shadow:0_0_4px_#4A90E2]"
            type="text" v-model="edited.username" required placeholder="np. root"/>
      </label>

      <label class="flex flex-col mb-[1em] font-semibold">
        Hasło (opcjonalne)
        <input
            class="p-[0.5em] mt-[0.3em] border-[1px] border-[solid] border-[#ccc] rounded-[4px] text-[1em] [transition:border-color_0.3s_ease] focus:outline-[none] focus:border-[#4A90E2] focus:[box-shadow:0_0_4px_#4A90E2]"
            type="password" v-model="edited.password" placeholder="hasło"/>
      </label>

      <label class="flex flex-col mb-[1em] font-semibold">
        Port
        <input
            class="p-[0.5em] mt-[0.3em] border-[1px] border-[solid] border-[#ccc] rounded-[4px] text-[1em] [transition:border-color_0.3s_ease] focus:outline-[none] focus:border-[#4A90E2] focus:[box-shadow:0_0_4px_#4A90E2]"
            type="number" v-model.number="edited.port" required min="1" max="65535"/>
      </label>

      <button
          class="w-full p-[0.75em] [transition:background-color_0.3s_ease] text-[1.1em] rounded-[4px] font-bold border-[none] text-[white] hover:bg-[#357ABD] bg-[#4A90E2] cursor-pointer"
          type="submit"> Zmień
      </button>

      <p v-if="edited.error" class="text-[#d93025] mt-[1em] text-center">{{ edited.error }}</p>
      <p v-if="edited.success" class="text-[#188038] mt-[1em] text-center">Baza danych została dodana!</p>
    </form>
  </div>
</template>

<script>
import {data} from "autoprefixer";

export default {
  name: "DatabasesList",
  data() {
    return {
      databases: [],
      loading: true,
      edited: {
        host: '',
        id: '',
        database: '',
        username: '',
        password: '',
        port: 3306,
        error: '',
        success: false,
      },
      isEditing: false
    };
  },
  methods: {
    async submitForm(){

      const payload = {
        database: this.edited.database,
        host: this.edited.host,
        username: this.edited.username,
        port: this.edited.port,
        password: this.edited.password,
        id: this.edited.id
      };

      const response = await fetch("/eskuelmyadmin/database", {
        method: "PATCH",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
      });

      if (!response.ok) {
        const errorText = await response.text();
        throw new Error(errorText || "Błąd połączenia z bazą");
      }

      if (response.ok) {
        this.setEditing(null)

        const awaitedData = await response.json();

        if (awaitedData["database"]) {
          const data = awaitedData["database"]

          const id = data?.id

          if (id) {
            const index = this.databases.findIndex(element => element.id === id)

            console.log(index)

            this.databases[index] = data;
          }
        }
      }
    },

    setEditing(db){
      if (db) {
        this.edited.id = db.id;
        this.edited.host = db.host;
        this.edited.database = db.database;
        this.edited.username = db.username;
        this.edited.port = db.port;
      }
      this.isEditing = !this.isEditing
    },

    async fetchDatabases() {
      try {
        const response = await fetch("/eskuelmyadmin/databases-list");

        if (!response.ok) throw new Error("Błąd ładowania danych");

        const data = await response.json();

        this.databases = data?.databases ?? [];
      } catch (err) {
        console.error("Błąd:", err);
        this.databases = [];
      } finally {
        this.loading = false;
      }
    },

    async connectToDatabase(db) {
      let password = null;
      if (db.password && db.password.trim() !== "") {
        password = prompt(`Podaj hasło do bazy: ${db.database}`);
        if (password === null) {
          return;
        }
      }

      const payload = {
        database: db.database,
        host: db.host,
        username: db.username,
        port: db.port,
        password: password,
        id: db.id
      };

      try {
        const response = await fetch("/eskuelmyadmin/database-conn", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(payload),
        });

        if (!response.ok) {
          const errorText = await response.text();
          throw new Error(errorText || "Błąd połączenia z bazą");
        }

        const result = await response.json();
        alert("Połączenie nawiązane pomyślnie!");
      } catch (error) {
        alert(`Błąd: ${error.message}`);
      }
    },
  },
  mounted() {
    this.fetchDatabases();
  },
};
</script>