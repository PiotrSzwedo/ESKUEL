<template>
  <div ref="editor" class="overflow-auto h-[500px] border-[1px] border-[solid] border-[#ddd] bg-[#f3f3f3]"></div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import {EditorView, keymap} from '@codemirror/view'
import { EditorState } from '@codemirror/state'
import { sql, MySQL } from '@codemirror/lang-sql'
import { autocompletion } from '@codemirror/autocomplete'
import { tags } from '@codemirror/highlight'
import { HighlightStyle, syntaxHighlighting } from '@codemirror/language'
import { defaultKeymap, indentWithTab } from '@codemirror/commands'
import { closeBracketsKeymap } from '@codemirror/autocomplete'


const editor = ref(null)
let view = null

const schema = {
  users: ['id', 'name', 'email'],
  orders: ['id', 'user_id', 'total']
}

onMounted(() => {
  view = new EditorView({
    parent: editor.value,
    state: EditorState.create({
      doc: 'SELECT * FROM users ',
      extensions: [
        sql({ dialect: MySQL, schema, upperCaseKeywords: true }),
        autocompletion(),
        syntaxHighlighting(HighlightStyle.define([
          {tag: tags.keyword, color: '#4a90e2', fontWeight: 'bold'},
          {tag: tags.function(tags.name), color: '#6f42c1'},
          {tag: tags.string, color: '#032f62'}
        ])),
        keymap.of([
          indentWithTab,
          ...defaultKeymap,
          ...closeBracketsKeymap
        ])
      ]
    })
  })
})

onBeforeUnmount(() => {
  if (view) view.destroy()
})
</script>