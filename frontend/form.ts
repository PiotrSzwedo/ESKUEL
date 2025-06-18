import { createApp } from 'vue';
import Form from './components/Form.vue';

const app = createApp({});
app.component('database-add-form', Form);
app.mount('#app');
