<template>
  <div
    :class="{
      'uk-alert-primary': isPrimary,
      'uk-alert-success': isSuccess,
      'uk-alert-warning': isWarning,
      'uk-alert-danger': isDanger,
    }"
    class="uk-alert-primary"
    uk-alert
  >
    <a class="uk-alert-close" uk-close></a>
    <p><slot /></p>
  </div>
</template>

<script>
import { computed } from 'vue';
export default {
  props: {
    type: {
      type: String,
      default: null,
    },
  },
  setup(props) {
    const isPrimary = computed(() => !props.type || props.type === 'primary');
    const isSuccess = computed(() => props.type === 'success');
    const isWarning = computed(() => props.type === 'isWarning');
    const isDanger = computed(() => props.type === 'danger');

    return { isPrimary, isSuccess, isWarning, isDanger };
  },
};
</script>

<style scoped></style>
