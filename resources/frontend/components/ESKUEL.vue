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

    <div v-else-if="message" class="text-gray-800 mb-4">{{ message }}</div>

    <div v-else class="text-gray-600">Brak wyników.</div>
  </div>
</template>
<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { EditorView, keymap } from '@codemirror/view'
import { EditorState } from '@codemirror/state'
import { sql, MySQL } from '@codemirror/lang-sql'
import { tags } from '@codemirror/highlight'
import { HighlightStyle, syntaxHighlighting } from '@codemirror/language'
import { defaultKeymap, indentWithTab } from '@codemirror/commands'
import { closeBracketsKeymap } from '@codemirror/autocomplete'
import { autocompletion } from '@codemirror/autocomplete'

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
const completions = ref([])

async function fetchPolishKeywords() {
  const allKeywords = []
  let currentPage = 1
  let totalPages = 1

  try {
    while (currentPage <= totalPages) {
      const res = await fetch(`${props.prefix}/eskuel/keywords?page=${currentPage}`)

      if (!res.ok) {
        console.error(`Błąd pobierania strony ${currentPage}: ${res.statusText}`)
        break
      }

      const data = await res.json()

      totalPages = data.total_pages || 1

      if (Array.isArray(data.key_words)) {
        allKeywords.push(...data.key_words)
      }

      currentPage++
    }

    completions.value = allKeywords.map(word => ({
      label: word,
      type: 'keyword',
      boost: 100,
    }))

  } catch (err) {
    console.error('Błąd ładowania słów kluczowych:', err)
    completions.value = []
  }
}

function customCompletionSource(context) {
  const word = context.matchBefore(/\w*/)

  if (!word || (word.from === word.to && !context.explicit)) return null

  return {
    from: word.from,
    options: completions.value,
  }
}

const schema = {
  users: ['id', 'name', 'email'],
  orders: ['id', 'user_id', 'total'],
}

async function sendQuery(sql) {
  const response = await fetch(`${props.prefix}/eskuel/execute`, {
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

onMounted(async () => {
  await fetchPolishKeywords()

  view = new EditorView({
    parent: editor.value,
    state: EditorState.create({
      doc: 'WYBIERZ * Z users;',
      extensions: [
        sql({ dialect: MySQL, schema, upperCaseKeywords: true }),
        syntaxHighlighting(
            HighlightStyle.define([
              { tag: tags.keyword, color: '#4a90e2', fontWeight: 'bold' },
              { tag: tags.function(tags.name), color: '#6f42c1' },
              { tag: tags.string, color: '#032f62' },
            ])
        ),
        autocompletion({
          override: [customCompletionSource],
        }),
        keymap.of([indentWithTab, ...defaultKeymap, ...closeBracketsKeymap]),
      ],
    }),
  })
})

onBeforeUnmount(() => {
  if (view) view.destroy()
})
</script>