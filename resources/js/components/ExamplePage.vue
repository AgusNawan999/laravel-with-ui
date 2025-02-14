<script setup>
import { onMounted, ref } from 'vue'
const items = ref({
  message: '',
})

onMounted(() => {
  items.value.message = `I'm an example component`
  console.log('Component mounted.')
})
</script>

<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Example Component</div>

          <div class="card-body">
            {{ items.message }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
