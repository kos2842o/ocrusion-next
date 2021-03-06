<template>
  <div>
    <BookListActionButton
      class="mb-4"
      @onActionButtonClicked="onActionButtonClicked"
    />

    <table
      class="border-collapse table-auto w-full bg-white table-striped overflow-hidden rounded shadow-lg"
    >
      <thead>
        <tr class="text-left sm:mb-0">
          <th
            width="50px"
            class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100"
          >
            <label
              class="text-teal-500 hover:bg-gray-200 px-2 py-2 rounded-lg cursor-pointer"
            >
              <input
                type="checkbox"
                class="form-checkbox focus:outline-none focus:shadow-outline"
                @click="toggleBooksCheck()"
              />
            </label>
          </th>

          <th
            class="bg-gray-100 sticky top-0 border-b border-gray-200 py-2 text-gray-600 font-bold tracking-wider"
          >
            名前
          </th>
          <th
            class="w-1/2 bg-gray-100 sticky top-0 border-b border-gray-200 py-2 text-gray-600 font-bold tracking-wider"
          >
            説明
          </th>
          <th
            class="bg-gray-100 sticky top-0 border-b border-gray-200 py-2 text-gray-600 font-bold tracking-wider"
          >
            状態
          </th>
        </tr>
      </thead>
      <tbody class="flex-1 sm:flex-none">
        <tr
          v-for="book in bookList"
          :key="book.book_id"
          class="hover:bg-gray-100 transition duration-300 flex flex-col flex-no wrap sm:table-row mb-2 sm:mb-0"
        >
          <td class="border-dashed border-t border-gray-200 px-3">
            <label
              class="text-teal-500 inline-flex justify-between items-center hover:bg-gray-200 px-2 py-2 rounded-lg cursor-pointer"
            >
              <input
                v-model="selectedBookIds"
                type="checkbox"
                class="form-checkbox rowCheckbox focus:outline-none focus:shadow-outline"
                :value="book.book_id"
              />
            </label>
          </td>
          <td class="border-dashed border-t border-gray-200 p-3 font-semibold">
            <span v-show="book.state === 0">{{ book.book_name }}<br /></span>
            <nuxt-link
              v-show="book.state === 1"
              :to="'/members/books/' + book.book_id"
            >
              <span>{{ book.book_name }}<br /></span>
            </nuxt-link>

            <span>
              <AppTag
                v-show="book.state === 0"
                :text="'OCR待機中...'"
                class="bg-pink-600 text-white text-xs"
              />
              <AppTag
                v-show="book.state === 1"
                :text="'OCR処理済み'"
                class="bg-indigo-600 text-white text-xs"
              />
            </span>
          </td>
          <td class="border-dashed border-t border-gray-200 p-3">
            <p class="text-gray-700">
              <!-- eslint-disable-next-line vue/no-v-html -->
              <div v-html="formatText(book.description)"></div>
            </p>
            <span class="updatedat">
              Updated at {{ displayDate(book.updated_at) }}
            </span>
          </td>
          <td class="border-dashed border-t border-gray-200 p-3 truncate">
            <span class="text-gray-700 py-3">
              {{ displayState(book.state) }}
            </span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script lang="ts">
import sanitizeHTML from 'sanitize-html'
import {
  ref,
  defineComponent,
  PropType,
  SetupContext,
} from 'nuxt-composition-api'

import { IBookList } from '~/types'
import BookListActionButton from '~/components/Book/BookListActionButton.vue'
export default defineComponent({
  name: '',
  components: { BookListActionButton },
  props: {
    bookList: {
      default: [],
      type: Array as PropType<Array<IBookList>>,
    },
  },
  setup(props: { bookList: Array<IBookList> }, { emit }: SetupContext) {
    const flagCheckToggle = ref(false)
    const selectedBookIds = ref<string[]>([])
    const displayState = (value: number) => {
      return value === 0 ? '待機中' : 'OCR済み'
    }
    const displayDate = (value: string) => {
      return new Date(value).toLocaleString()
    }
        const formatText = (description: string) => {
      return sanitizeHTML(description.replace(/\n/g, '<br />\n'), {
        allowedTags: ['br'],
      })
    }
    const toggleBooksCheck = () => {
      if (flagCheckToggle.value) {
        flagCheckToggle.value = false
        selectedBookIds.value = []
      } else {
        flagCheckToggle.value = true
        // eslint-disable-next-line camelcase
        props.bookList.forEach(({ book_id }: IBookList) => {
          selectedBookIds.value.push(book_id)
        })
      }
    }

    const onActionButtonClicked = async (action: string) => {
      // delete
      if (action === 'delete') {
        emit('onActionButtonClicked', selectedBookIds.value)
      }
    }
    return {
      onActionButtonClicked,
      formatText,
      displayState,
      displayDate,
      selectedBookIds,
      toggleBooksCheck,
    }
  },
})
</script>
<style lang="scss" scoped>
.updatedat {
  font-size: 0.7rem;
  color: #adadad;
}
</style>
