<template>
  <div>
    名前: <input v-model="name" type="text" /><br />
    <button @click="onSettingChanged()">ボタン</button>
  </div>
</template>
<script lang="ts">
import { ref } from 'nuxt-composition-api'
import { UserSettings } from '~/api/UserSettings'

export default {
  name: 'UserSettings',
  setup() {
    const name = ref('')
    const onSettingChanged = async () => {
      const data = {
        name: name.value,
      }
      try {
        const userSettings = new UserSettings()
        const res = userSettings.post(data)
      } catch (e) {
        alert(`${e.message}`)
      }
    }
    return {
      name,
      onSettingChanged,
    }
  },
}
</script>
<style lang="scss" scoped></style>
