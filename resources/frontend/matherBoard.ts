import app from "./app";
import SQL from './components/SQL.vue'
import ESKUEL from "./components/ESKUEL.vue";

app.component('sql-editor', SQL)
app.component("eskuel-editor", ESKUEL)
app.mount('#app')
