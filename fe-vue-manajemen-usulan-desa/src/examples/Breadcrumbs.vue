<script setup>
import { computed } from "vue";
import { useStore } from "vuex";

const store = useStore();
const isRTL = computed(() => store.state.isRTL);
const isNavFixed = computed(() => store.state.isNavFixed);

defineProps({
  currentPage: {
    type: String,
    default: "",
  },
  currentDirectory: {
    type: String,
    default: "",
  },
});
</script>
<template>
  <nav aria-label="breadcrumb">
    <ol
      class="px-0 pt-1 pb-0 mb-0 bg-transparent breadcrumb"
      :class="`${isRTL ? '' : ' me-sm-6'} ${
        isNavFixed ? 'text-dark' : 'text-white'
      }`"
    >
      <li class="text-sm breadcrumb-item">
        <a
          :class="'text-dark'"
          class="opacity-8"
          href="#"
          >Pages</a
        >
      </li>
      <li
        class="text-sm breadcrumb-item active"
        :class="'text-dark'"
        aria-current="page"
      >
        {{ currentPage }}
      </li>
    </ol>
    <h6
      class="mb-0 font-weight-bolder"
      :class="'text-dark'"
    >
      {{ currentDirectory }}
    </h6>
  </nav>
</template>
