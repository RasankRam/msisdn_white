<template>
  <div id="modal_create_device" uk-modal>
    <div class="uk-modal-dialog">
      <button class="uk-modal-close-default" type="button" uk-close></button>
      <div class="uk-modal-header">
        <h2 class="uk-modal-title">Добавить устройство</h2>
      </div>
      <div class="uk-modal-body">
        <form @submit.prevent="create_device">
<!--          <fieldset class="uk-fieldset">-->
            <div class="uk-margin">
              <label class="uk-form-label" for="imei">IMEI</label>
              <div class="uk-form-controls">
                <input v-model.trim="$v.imei.$model" class="uk-input" :class="{ 'uk-form-danger': $v.imei.$error, }" id="imei" type="text" placeholder="Уникальный номер устройства">
                <div class="uk-text-danger" v-if="$v.imei.$dirty && !$v.imei.required">IMEI обязателен</div>
                <div class="uk-text-danger" v-if="$v.imei.$dirty && !$v.imei.alpha">IMEI должен быть строго 15 цифр</div>
              </div>
            </div>

            <div class="uk-margin">
              <label class="uk-form-label" for="device_title">Название устройства</label>
              <div class="uk-form-controls">
                <input class="uk-input" v-model.trim="$v.title.$model" :class="{ 'uk-form-danger': $v.title.$error }" id="device_title" type="text" placeholder="Имя устройства">
                <div class="uk-text-danger" v-if="$v.title.$dirty && !$v.title.required">Введите название устройства</div>
              </div>
            </div>

            <div class="uk-margin">
              <label class="uk-form-label" for="imsi">IMSI</label>
              <div class="uk-form-controls">
                <input class="uk-input" v-model.trim="$v.imsi.$model" :class="{ 'uk-form-danger': $v.imsi.$error }" id="imsi" type="text" placeholder="Номер устройства в международной сети">
                <div class="uk-text-danger" v-if="$v.imsi.$dirty && !$v.imsi.required">IMSI обязателен</div>
                <div class="uk-text-danger" v-if="$v.imsi.$dirty && !$v.imsi.alpha">IMSI должен быть строго 15 цифр</div>
              </div>
            </div>

            <div class="uk-margin">
              <label class="uk-form-label" for="msisdn">MSISDN</label>
              <div class="uk-form-controls">
                <input v-model.trim="$v.msisdn.$model" class="uk-input" :class="{ 'uk-form-danger': $v.msisdn.$error}" id="msisdn" type="text" placeholder="Номер устройства в мобильной сети">
                <div class="uk-text-danger" v-if="$v.msisdn.$dirty && !$v.msisdn.required">MSISDN обязателен</div>
                <div class="uk-text-danger" v-if="$v.msisdn.$dirty && !$v.msisdn.alpha_2">MSISDN должен начинаться с 7 и содержать 11 цифр</div>
              </div>
            </div>

<!--          </fieldset>-->
        </form>
      </div>
      <div class="uk-modal-footer uk-text-right">
        <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
        <button class="uk-button uk-button-primary" @click.prevent="create_device" type="button">Добавить</button>
      </div>
    </div>
  </div>
</template>

<script>
import { required, helpers} from 'vuelidate/lib/validators'

const alpha = helpers.regex('alpha',/^\d{15}$/)
const alpha_2 = helpers.regex('alpha_2',/^7\d{10}$/)

export default {
  name: "modal_create",
  data() {
    return {
      imei: '',
      title: '',
      imsi: '',
      msisdn: '',
    }
  },
  computed: {
    devices() {
      return this.$store.getters.devices;
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
    create_device() {

      if (this.$v.$invalid) {
        this.$v.$touch() // активизируем валидацию
        return
      }

      // eslint-disable-next-line no-undef
      UIkit.modal(document.querySelector('#modal_create_device')).hide();
      this.$store.dispatch('create_device', this.$data).then(() => {
        this.$v.$reset()
        for (let key in this.$data) {
          this.$data[key] = ''
        }
      });
    }
  }
}
</script>

<style scoped>

</style>
