<script setup>
import { onMounted, ref } from 'vue'
import { _http, _route } from '@/js/utils/common'

const items = ref({ message: '' })

onMounted(async () => {
  let result = await _http.get(_route('backend.testing'))
  items.value.message = result.data.message
  console.log('Component mounted.')
})
</script>

<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Backend Components</div>

          <div class="card-body" :data-target="_route('backend.testing')">
            {{ items.message }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
