<template>
  <div id="modal_create_device_msisdn" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
      <form @submit.prevent="create_device_msisdn">
        <fieldset class="uk-fieldset">

          <legend class="uk-legend">Добавить номер в белый список</legend>

          <div v-if="device_id" class="uk-margin">
            <label class="uk-form-label" for="msisdn_create">MSISDN</label>
            <div class="uk-form-controls">
              <input v-model.trim="$v.msisdn.$model" class="uk-input" :class="{ 'uk-form-danger': $v.msisdn.$error}" id="msisdn_create" type="text" placeholder="Введите номер в белый список">
              <div class="uk-text-danger" v-if="$v.msisdn.$dirty && !$v.msisdn.required">MSISDN обязателен</div>
              <div class="uk-text-danger" v-if="$v.msisdn.$dirty && !$v.msisdn.alpha">MSISDN должен начинаться с 7 и содержать 11 цифр</div>
            </div>
          </div>

          <p class="uk-text-center">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Отмена</button>
            <button class="uk-button uk-button-primary" type="submit">Добавить</button>
          </p>
        </fieldset>
      </form>
    </div>
  </div>
</template>

<script>
import { helpers, required } from 'vuelidate/lib/validators'

const alpha = helpers.regex('alpha',/^7\d{10}$/)

export default {
  name: "modal_create_msisdn",
  props: ['device_id'],
  data() {
    return {
      msisdn: ''
    }
  },
  validations: {
    msisdn: {
      required,
      alpha
    }
  },
  methods: {
    create_device_msisdn() {
      this.$store.dispatch('create_device_msisdn', { msisdn: this.msisdn, device_id: this.device_id }).then(() => {
        // eslint-disable-next-line no-undef
        UIkit.modal(document.querySelector('#modal_create_device_msisdn')).hide();
        // eslint-disable-next-line no-undef
        UIkit.notification({message: `Вы успешно добавили ${this.msisdn} в белый список`, status: 'success', timeout: 1000})
        this.$v.$reset()
        this.msisdn = ''
        this.$store.dispatch('collapse_table_handler')
        // eslint-disable-next-line no-undef
      }).catch(() => { UIkit.notification({status: 'warning', message: `Номер ${this.msisdn} уже находится в белом списке`}) })
    }
  }
}
</script>

<style scoped>

</style>
