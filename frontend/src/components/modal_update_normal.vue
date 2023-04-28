<template>
  <div id="modal_update_device" uk-modal>
    <div class="uk-modal-dialog">
      <button class="uk-modal-close-default" type="button" uk-close></button>
      <div class="uk-modal-header">
        <h2 class="uk-modal-title">Редактировать устройство</h2>
      </div>
      <div class="uk-modal-body">
        <form @submit.prevent="update_device">
          <input name="id" type="hidden">
          <div class="uk-margin">
            <label class="uk-form-label" for="imei_update">IMEI</label>
            <div class="uk-form-controls">
              <input v-model.trim="$v.imei.$model" class="uk-input" :class="{ 'uk-form-danger': $v.imei.$error, }" id="imei_update" type="text" placeholder="Уникальный номер устройства">
              <div class="uk-text-danger" v-if="$v.imei.$dirty && !$v.imei.required">IMEI обязателен</div>
              <div class="uk-text-danger" v-if="$v.imei.$dirty && !$v.imei.alpha">IMEI должен быть строго 15 цифр</div>
            </div>
          </div>

          <div class="uk-margin">
            <label class="uk-form-label" for="device_title_update">Название устройства</label>
            <div class="uk-form-controls">
              <input class="uk-input" v-model.trim="$v.title.$model" :class="{ 'uk-form-danger': $v.title.$error }" id="device_title_update" type="text" placeholder="Имя устройства">
              <div class="uk-text-danger" v-if="$v.title.$dirty && !$v.title.required">Введите название устройства</div>
            </div>
          </div>

          <div class="uk-margin">
            <label class="uk-form-label" for="imsi_update">IMSI</label>
            <div class="uk-form-controls">
              <input class="uk-input" v-model.trim="$v.imsi.$model" :class="{ 'uk-form-danger': $v.imsi.$error }" id="imsi_update" type="text" placeholder="Номер устройства в международной сети">
              <div class="uk-text-danger" v-if="$v.imsi.$dirty && !$v.imsi.required">IMSI обязателен</div>
              <div class="uk-text-danger" v-if="$v.imsi.$dirty && !$v.imsi.alpha">IMSI должен быть строго 15 цифр</div>
            </div>
          </div>

          <div class="uk-margin">
            <label class="uk-form-label" for="msisdn_update">MSISDN</label>
            <div class="uk-form-controls">
              <input v-model.trim="$v.msisdn.$model" class="uk-input" :class="{ 'uk-form-danger': $v.msisdn.$error}" id="msisdn_update" type="text" placeholder="Номер устройства в мобильной сети">
              <div class="uk-text-danger" v-if="$v.msisdn.$dirty && !$v.msisdn.required">MSISDN обязателен</div>
              <div class="uk-text-danger" v-if="$v.msisdn.$dirty && !$v.msisdn.alpha_2">MSISDN должен начинаться с 7 и содержать 11 цифр</div>
            </div>
          </div>

        </form>
      </div>
      <div class="uk-modal-footer uk-text-right">
        <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
        <button class="uk-button uk-button-primary" @click.prevent="update_device" type="button">Редактировать</button>
      </div>
    </div>
  </div>
</template>

<script>
import { required, helpers} from 'vuelidate/lib/validators'

const alpha = helpers.regex('alpha',/^\d{15}$/)
const alpha_2 = helpers.regex('alpha_2',/^7\d{10}$/)

export default {
  name: "modal_update_normal",
  props: ['device'],
  data() {
    return {
      id: '',
      title: '',
      imei: '',
      imsi: '',
      msisdn: '',
    }
  },
  watch: {
    device: function (val) {
      for (let key in val) {
        for (let key_data in this.$data) {
          if (key_data === key) {
            if (typeof val[key_data] === 'object') {
              this.$data[key] = val[key][key]
            } else {
              this.$data[key] = val[key]
            }
          }
        }
      }
    }
  },
  validations: {
    imei: {
      required,
      alpha
    },
    title: {
      required,
    },
    imsi: {
      required,
      alpha
    },
    msisdn: {
      required,
      alpha_2
    }
  },
  methods: {
    update_device() {
      if (this.$v.$invalid) {
        this.$v.$touch() // активизируем валидацию
        return
      }

      // eslint-disable-next-line no-undef
      UIkit.modal(document.querySelector('#modal_update_device')).hide();
      this.$store.dispatch('update_device', this.$data);
    }
  }
}
</script>

<style scoped>

</style>
