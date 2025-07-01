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

  <transition name="fade">
    <div
        v-if="isEditing"
        class="fixed inset-0 bg-[#000000ab] bg-opacity-60 flex items-center justify-center z-50"
    >
      <form
          @submit.prevent="submitForm"
          class="relative w-full max-w-md mx-auto p-8 bg-white rounded-lg shadow-lg font-['Segoe_UI',_Tahoma,_Geneva,_Verdana,_sans-serif] text-[#333]"
      >
        <!-- Przycisk zamykania -->
        <button
            type="button"
            @click="closeEditing"
            aria-label="Zamknij"
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl font-bold leading-none cursor-pointer"
        >
          &times;
        </button>

        <h2 class="mb-6 text-center text-2xl font-semibold text-[#222]">
          Edytujesz bazę: {{ edited.database }} : {{ edited.host }}
        </h2>

        <label class="flex flex-col mb-4 font-semibold">
          Host
          <input
              type="text"
              v-model="edited.host"
              required
              placeholder="np. localhost"
              class="p-2 mt-1 border border-gray-300 rounded focus:outline-none focus:border-blue-500 focus:shadow-outline"
          />
        </label>

        <label class="flex flex-col mb-4 font-semibold">
          Nazwa bazy danych
          <input
              type="text"
              v-model="edited.database"
              required
              placeholder="np. my_database"
              class="p-2 mt-1 border border-gray-300 rounded focus:outline-none focus:border-blue-500 focus:shadow-outline"
          />
        </label>

        <label class="flex flex-col mb-4 font-semibold">
          Użytkownik
          <input
              type="text"
              v-model="edited.username"
              required
              placeholder="np. root"
              class="p-2 mt-1 border border-gray-300 rounded focus:outline-none focus:border-blue-500 focus:shadow-outline"
          />
        </label>

        <label class="flex flex-col mb-4 font-semibold">
          Hasło (opcjonalne)
          <input
              type="password"
              v-model="edited.password"
              placeholder="hasło"
              class="p-2 mt-1 border border-gray-300 rounded focus:outline-none focus:border-blue-500 focus:shadow-outline"
          />
        </label>

        <label class="flex flex-col mb-6 font-semibold">
          Port
          <input
              type="number"
              v-model.number="edited.port"
              required
              min="1"
              max="65535"
              class="p-2 mt-1 border border-gray-300 rounded focus:outline-none focus:border-blue-500 focus:shadow-outline"
          />
        </label>

        <button
            type="submit"
            class="w-full bg-blue-600 text-white font-bold py-3 rounded hover:bg-blue-700 transition-colors"
        >
          Zmień
        </button>

        <p v-if="edited.error" class="text-red-600 mt-4 text-center">{{ edited.error }}</p>
        <p v-if="edited.success" class="text-green-600 mt-4 text-center">
          Baza danych została zmieniona!
        </p>
      </form>
    </div>
  </transition>
</template>

<script>
export default {
  name: "DatabasesList",

  props: {
    prefix: {
      type: String,
      required: true,
      default: '',
    }
  },

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
    async submitForm() {
      const payload = {
        database: this.edited.database,
        host: this.edited.host,
        username: this.edited.username,
        port: this.edited.port,
        password: this.edited.password,
        id: this.edited.id
      };

      try {
        const response = await fetch(`${this.prefix}/database`, {
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

        const awaitedData = await response.json();

        if (awaitedData["database"]) {
          const data = awaitedData["database"];
          const id = data?.id;
          if (id) {
            const index = this.databases.findIndex(element => element.id === id);
            if(index !== -1) {
              this.databases.splice(index, 1, data);
            }
          }
        }

        this.closeEditing();
      } catch (err) {
        this.edited.error = err.message || "Coś poszło nie tak";
        this.edited.success = false;
      }
    },

    setEditing(db) {
      if (db) {
        this.edited.id = db.id;
        this.edited.host = db.host;
        this.edited.database = db.database;
        this.edited.username = db.username;
        this.edited.port = db.port;
        this.edited.password = '';
        this.edited.error = '';
        this.edited.success = false;
        this.isEditing = true;
      }
    },

    closeEditing() {
      this.isEditing = false;
      this.edited = {
        host: '',
        id: '',
        database: '',
        username: '',
        password: '',
        port: 3306,
        error: '',
        success: false,
      };
    },

    async fetchDatabases() {
      try {
        const response = await fetch(`${this.prefix}/databases-list`);
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
        const response = await fetch(`${this.prefix}/database-conn`, {
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

        await response.json();
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

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>