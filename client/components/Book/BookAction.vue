<template>
  <div>
    ddddddddd
    <button @click="onActionClicked()">消す</button>
    <table>
      <tr v-for="book in list2" :key="book.book_id">
        <td>
          <input v-model="bookIds" type="checkbox" :value="book.book_id" />
        </td>
        <td>{{ book.book_id }}</td>
        <td>{{ book.book_name }}</td>
      </tr>
    </table>
    <button @click="onActionButtonClicked">消す</button>
  </div>
</template>
<script lang="ts">
import { ref } from 'nuxt-composition-api'
export default {
  name: '',
  setup() {
    const bookIds = ref([])
    const onActionButtonClicked = async () => {}

    return { onActionButtonClicked, bookIds }
  },
}
</script>
<style lang="scss" scoped></style>
