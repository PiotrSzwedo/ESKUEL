import app from "./app";
import Form from './components/Form.vue';
import DatabasesList from "./components/DatabasesList.vue";

app.component('database-add-form', Form);
app.component('databases-list', DatabasesList)
app.mount('#app');
