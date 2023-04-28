<template>
  <div id="modal_delete_device" uk-modal>
    <div class="uk-modal-dialog">
      <button class="uk-modal-close-default" type="button" uk-close></button>
      <div v-if="item" class="uk-modal-body">
        Вы действительно хотите удалить {{ item.title ? `устройство "${item.title}" c IMEI ${item.imei}` : `MSISDN ${item.msisdn}` }}
      </div>
      <div class="uk-modal-footer">
        <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
        <button class="uk-button uk-button-danger" @click.prevent="del" type="button">Удалить</button>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: "modal_delete",
  props: ["item"],
  methods: {
    del() {
       this.$store.dispatch(`remove_${this.item.class_name}`, `${this.item.id}`).then(() => {
         // eslint-disable-next-line no-undef
         UIkit.modal(document.querySelector('#modal_delete_device')).hide();
         // eslint-disable-next-line no-undef
         UIkit.notification({
           message: `Вы успешно удалили ${this.item.title} IMEI ${this.item.imei}`,
           status: 'success',
         })
       }).catch((err) => console.log(err))
    }
  }
}
</script>

<style scoped>

</style>
