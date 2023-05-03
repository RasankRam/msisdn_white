<template>
  <button class="uk-button" :class="{
      'uk-button-primary': isPrimary,
      'uk-button-default': isDefault,
      'uk-button-danger': isDanger,
      'uk-border-rounded': isRounded,
    }">
      <slot />
  </button>
</template>

<script>
import { computed } from 'vue';

export default {
  props: {
    type: {
      type: String,
      default: null,
    },
    rounded: {
      type: Boolean,
      default: false,
    },
  },
  setup(props) {
    const isDefault = computed(() => props.type === null || props.type === 'default');
    const isPrimary = computed(() => props.type === 'primary');
    const isDanger = computed(() => props.type === 'danger');

    // Выносим в отдельный computed (при развитии проекта будет больше логики и можно как минимум указать глобально кнопки
    // круглые или квадратные
    const isRounded = computed(() => props.rounded);

    return {
      isPrimary,
      isDefault,
      isDanger,
      isRounded,
    }
  }
}
</script>

<style scoped>

</style>
