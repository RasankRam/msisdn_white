<template>
  <div id="app">
    <router-view />
  </div>
</template>
<script>

export default {
  name: 'App',
  watch: {
    '$route.query': {
      handler: function(query_string) {
        this.$store.dispatch('change_loading', true)
        this.$store.dispatch('fetch_devices', query_string).then(() => {
          this.$store.dispatch('change_loading', false)
          setTimeout(() => this.$store.dispatch('collapse_table_handler'))
        })
      },
      deep: true,
      immediate: true
    }
  }
}
</script>

<style lang="scss">
@import './styles/index.scss';
</style>
