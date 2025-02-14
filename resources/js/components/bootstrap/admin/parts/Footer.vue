<script setup>
const APP_NAME = 'Solutik'
const dinas = {
  short_name: 'Diskominfotik',
  long_name: 'Dinas Komunikasi, Informatika dan Statistik'
}
</script>
<template>
  <footer class="page-footer" role="contentinfo">
    <div class="d-flex align-items-center flex-1 text-muted">
      <span class="hidden-md-down fw-700">
        2023 © {{ APP_NAME }} made with <span class="text-danger">❤</span> by&nbsp;
        <a
          v-tippy="{ content: dinas.long_name }"
          href="#"
          class="text-primary fw-700"
          target="_blank"
        >
          {{ dinas.short_name }}
        </a>
      </span>
    </div>
    <div>
      <ul class="list-table m-0">
        <li class="pl-3"><a href="#" class="text-secondary fw-700">Documentation</a></li>
        <li class="pl-3 fs-xl">
          <a href="#" class="text-secondary" target="_blank">
            <i class="fal fa-question-circle" aria-hidden="true"></i>
          </a>
        </li>
      </ul>
    </div>
  </footer>
</template>
