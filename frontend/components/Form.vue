<template>
  <form @submit.prevent="submitForm" class="form">
    <h2>Dodaj nową bazę danych</h2>

    <label>
      Host
      <input type="text" v-model="host" required placeholder="np. localhost"/>
    </label>

    <label>
      Nazwa bazy danych
      <input type="text" v-model="database" required placeholder="np. my_database"/>
    </label>

    <label>
      Użytkownik
      <input type="text" v-model="username" required placeholder="np. root"/>
    </label>

    <label>
      Hasło (opcjonalne)
      <input type="password" v-model="password" placeholder="hasło"/>
    </label>

    <label>
      Port
      <input type="number" v-model.number="port" required min="1" max="65535"/>
    </label>

    <button type="submit" :disabled="!isValid">Dodaj</button>

    <p v-if="error" class="error">{{ error }}</p>
    <p v-if="success" class="success">Baza danych została dodana!</p>
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
      port: 3310,
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
        const response = await fetch("/eskuelmyadmin/index.php?path=/form", {
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

<style scoped>
.form {
  max-width: 400px;
  margin: 1em auto;
  padding: 2em;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #333;
}

h2 {
  margin-bottom: 1.5em;
  text-align: center;
  color: #222;
}

label {
  display: flex;
  flex-direction: column;
  margin-bottom: 1em;
  font-weight: 600;
}

input {
  padding: 0.5em;
  margin-top: 0.3em;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 1em;
  transition: border-color 0.3s ease;
}

input:focus {
  outline: none;
  border-color: #4A90E2;
  box-shadow: 0 0 4px #4A90E2;
}

button {
  width: 100%;
  padding: 0.75em;
  background-color: #4A90E2;
  border: none;
  color: white;
  font-weight: 700;
  font-size: 1.1em;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button:disabled {
  background-color: #a0c4ff;
  cursor: not-allowed;
}

button:not(:disabled):hover {
  background-color: #357ABD;
}

.error {
  color: #d93025;
  margin-top: 1em;
  text-align: center;
}

.success {
  color: #188038;
  margin-top: 1em;
  text-align: center;
}
</style>