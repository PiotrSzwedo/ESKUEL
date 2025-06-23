<template>
  <div>
    <div ref="editor" class="overflow-auto h-[500px] border border-solid border-gray-300 bg-gray-100 mb-4"></div>

    <button @click="handleQuery" class="mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
      Wykonaj
    </button>

    <div v-if="loading">Ładowanie...</div>
    <div v-if="error" class="text-red-600 mb-4">{{ error }}</div>

    <!-- Jeśli wynik to result, wyświetl tabelę -->
    <table v-if="result && result.columns?.length" class="min-w-full border border-gray-300">
      <thead>
      <tr>
        <th v-for="col in result.columns" :key="col" class="border border-gray-300 px-2 py-1 bg-gray-200">
          {{ col }}
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(row, idx) in result.rows" :key="idx" class="hover:bg-gray-100">
        <td v-for="col in result.columns" :key="col" class="border border-gray-300 px-2 py-1">
          {{ row[col] }}
        </td>
      </tr>
      </tbody>
    </table>

    <!-- Jeśli wynik to message, wyświetl wiadomość -->
    <div v-else-if="message" class="text-gray-800 mb-4">{{ message }}</div>

    <div v-else class="text-gray-600">Brak wyników.</div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { EditorView, keymap } from '@codemirror/view'
import { EditorState } from '@codemirror/state'
import { sql, MySQL } from '@codemirror/lang-sql'
import { autocompletion } from '@codemirror/autocomplete'
import { tags } from '@codemirror/highlight'
import { HighlightStyle, syntaxHighlighting } from '@codemirror/language'
import { defaultKeymap, indentWithTab } from '@codemirror/commands'
import { closeBracketsKeymap } from '@codemirror/autocomplete'

const props = defineProps({
  prefix: {
    type: String,
    required: true,
    default: '',
  }
})


const editor = ref(null)
let view = null

const result = ref(null)
const message = ref('')
const loading = ref(false)
const error = ref(null)

const schema = {
  users: ['id', 'name', 'email'],
  orders: ['id', 'user_id', 'total'],
}

async function sendQuery(sql) {
  const response = await fetch(`${props.prefix}/db/execute`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ query: sql }),
  })

  if (!response.ok) {
    throw new Error(`Błąd serwera: ${response.statusText}`)
  }
  return response.json()
}

function handleQuery() {
  const query = view.state.doc.toString()
  loading.value = true
  error.value = null
  result.value = null
  message.value = ''

  sendQuery(query)
      .then((data) => {
        console.log('Wynik:', data)

        if (data.type === 'result') {
          result.value = {
            columns: data.columns || [],
            rows: data.rows || [],
          }
          message.value = ''
        } else if (data.type === 'message') {
          message.value = data.message || ''
          result.value = null
        } else {
          error.value = 'Nieoczekiwany format danych z serwera.'
          result.value = null
          message.value = ''
        }
      })
      .catch((err) => {
        error.value = err.message
        result.value = null
        message.value = ''
      })
      .finally(() => {
        loading.value = false
      })
}

onMounted(() => {
  view = new EditorView({
    parent: editor.value,
    state: EditorState.create({
      doc: 'SELECT * FROM users;',
      extensions: [
        sql({ dialect: MySQL, schema, upperCaseKeywords: true }),
        autocompletion(),
        syntaxHighlighting(
            HighlightStyle.define([
              { tag: tags.keyword, color: '#4a90e2', fontWeight: 'bold' },
              { tag: tags.function(tags.name), color: '#6f42c1' },
              { tag: tags.string, color: '#032f62' },
            ])
        ),
        keymap.of([indentWithTab, ...defaultKeymap, ...closeBracketsKeymap]),
      ],
    }),
  })
})

onBeforeUnmount(() => {
  if (view) view.destroy()
})
</script>

<style scoped>
table {
  border-collapse: collapse;
  width: 100%;
}
th,
td {
  border: 1px solid #ddd;
  padding: 8px;
}
th {
  background-color: #f2f2f2;
  text-align: left;
}
button {
  cursor: pointer;
}
</style>