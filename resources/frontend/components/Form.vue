<template>
  <form @submit.prevent="submitForm" class="max-w-[400px] mx-[auto] my-[1em] p-[2em] bg-[#fff] rounded-[8px] [box-shadow:0_0_12px_rgba(0,_0,_0,_0.1)] font-['Segoe_UI',_Tahoma,_Geneva,_Verdana,_sans-serif] text-[#333]">
    <h2 class="mb-[1.5em] text-center text-[#222]">Dodaj nową bazę danych</h2>

    <label class="flex flex-col mb-[1em] font-semibold">
      Host
      <input class="p-[0.5em] mt-[0.3em] border-[1px] border-[solid] border-[#ccc] rounded-[4px] text-[1em] [transition:border-color_0.3s_ease] focus:outline-[none] focus:border-[#4A90E2] focus:[box-shadow:0_0_4px_#4A90E2]" type="text" v-model="host" required placeholder="np. localhost"/>
    </label>

    <label class="flex flex-col mb-[1em] font-semibold">
      Nazwa bazy danych
      <input class="p-[0.5em] mt-[0.3em] border-[1px] border-[solid] border-[#ccc] rounded-[4px] text-[1em] [transition:border-color_0.3s_ease] focus:outline-[none] focus:border-[#4A90E2] focus:[box-shadow:0_0_4px_#4A90E2]" type="text" v-model="database" required placeholder="np. my_database"/>
    </label>

    <label class="flex flex-col mb-[1em] font-semibold">
      Użytkownik
      <input class="p-[0.5em] mt-[0.3em] border-[1px] border-[solid] border-[#ccc] rounded-[4px] text-[1em] [transition:border-color_0.3s_ease] focus:outline-[none] focus:border-[#4A90E2] focus:[box-shadow:0_0_4px_#4A90E2]" type="text" v-model="username" required placeholder="np. root"/>
    </label>

    <label class="flex flex-col mb-[1em] font-semibold">
      Hasło (opcjonalne)
      <input class="p-[0.5em] mt-[0.3em] border-[1px] border-[solid] border-[#ccc] rounded-[4px] text-[1em] [transition:border-color_0.3s_ease] focus:outline-[none] focus:border-[#4A90E2] focus:[box-shadow:0_0_4px_#4A90E2]" type="password" v-model="password" placeholder="hasło"/>
    </label>

    <label class="flex flex-col mb-[1em] font-semibold">
      Port
      <input class="p-[0.5em] mt-[0.3em] border-[1px] border-[solid] border-[#ccc] rounded-[4px] text-[1em] [transition:border-color_0.3s_ease] focus:outline-[none] focus:border-[#4A90E2] focus:[box-shadow:0_0_4px_#4A90E2]" type="number" v-model.number="port" required min="1" max="65535"/>
    </label>

    <button  class="w-full p-[0.75em] [transition:background-color_0.3s_ease] text-[1.1em] rounded-[4px] font-bold border-[none] text-[white]" :class="isValid ? 'hover:bg-[#357ABD] bg-[#4A90E2] cursor-pointer' : 'bg-[#a0c4ff] cursor-not-allowed'" type="submit" :disabled="!isValid">Dodaj</button>

    <p v-if="error" class="text-[#d93025] mt-[1em] text-center">{{ error }}</p>
    <p v-if="success" class="text-[#188038] mt-[1em] text-center">Baza danych została dodana!</p>
  </form>
</template>

<script>
export default {
  name: 'DatabaseAddForm',
  data() {
    return {
      host: '',
      database: '',
      username: '',
      password: '',
      port: 3306,
      error: '',
      success: false,
    };
  },
  computed: {
    isValid() {
      return this.host && this.database && this.username && this.port > 0 && this.port <= 65535;
    }
  },
  methods: {
    async submitForm() {
      this.error = '';
      this.success = false;

      if (!this.isValid) {
        this.error = 'Proszę wypełnić wszystkie wymagane pola poprawnie.';
        return;
      }

      try {
        const response = await fetch("/eskuelmyadmin/form", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            host: this.host,
            database: this.database,
            username: this.username,
            password: this.password,
            port: this.port
          })
        });

        if (!response.ok) {
          throw new Error(`Server returned ${response.status}`);
        }

        const result = await response.json();

        if (result.ok) {
          this.host = '';
          this.database = '';
          this.username = '';
          this.password = '';
          this.port = 22;

          this.success = true;
        } else {
          this.success = false;
          console.error("Server error:", result.message || result);
        }
      } catch (error) {
        console.error("Fetch error:", error);
        this.success = false;
      }
    }
  }
}
</script>