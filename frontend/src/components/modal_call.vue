<template>
  <div id="modal_call" uk-modal>
    <div class="uk-modal-dialog">
      <button class="uk-modal-close-default" type="button" uk-close></button>
      <div class="uk-modal-header">
        <h2 class="uk-modal-title uk-text-center">Позвонить</h2>
      </div>

      <div v-if="$store.getters.isAuthenticated" class="uk-modal-body">

        <div style="padding-left:97px;" class="uk-margin">
          <div uk-form-custom="target: > * > span:last-child">
            <select @change="change">
              <option selected disabled>Выберите один из своих номеров</option>
              <option v-for="my_device in my_devices" :key="my_device.id" v-bind:value="my_device.msisdn.msisdn">{{my_device.title + ' ' + my_device.msisdn.msisdn}}</option>
            </select>
            <span class="uk-link">
                <span uk-icon="icon: rss"></span>
                <span></span>
            </span>
          </div>
        </div>

        <div class="uk-flex-center uk-flex-middle" uk-grid>
          <div style="padding-left:29px;" class="uk-width-1-2">
            <input v-model.trim="to_phone" class="uk-input" type="text" placeholder="Введите номер абонента">
          </div>
          <div class="uk-width-auto">
            <a @click.prevent="call" style="position: relative;right: 6px;" class="modal_call_btn uk-flex uk-flex-center" uk-icon="receiver" uk-tooltip="Позвонить"></a>
          </div>
        </div>
        <div class="uk-grid-small uk-flex-center" uk-grid>
          <button class="uk-button uk-button-default" type="button">Открыть список телефонных номеров</button>
          <div uk-dropdown="mode: click">
            <ul class="uk-list uk-list-disc uk-list-primary">
              <li v-for="msisdn in msisdns" :key="msisdn.id"><span>{{msisdn.msisdn}}</span> <a @click="call($event,msisdn.msisdn)" style="margin-left:10px;" uk-icon="receiver" uk-tooltip="Позвонить"></a></li>
<!--              <li v-for="msisdn in msisdns" :key="msisdn">{{msisdn}}</li>-->
            </ul>
          </div>
        </div>
      </div>


    </div>
  </div>
</template>

<script>
import {required, helpers} from "vuelidate/lib/validators";

const alpha = helpers.regex('alpha',/^7\d{10}$/)

export default {
  name: "modal_call",
  data() {
    return {
      to_phone: '',
      from_phone: '',
      my_devices: [],
      msisdns: [],
    }
  },
  validations: {
    phone: {
      required,
      alpha
    }
  },
  mounted() {
    this.$store.dispatch('fetch_my_devices').then(res => {
      this.my_devices = res
    })
    this.$store.dispatch('fetch_all_msisdns').then(res => {
      this.msisdns = res
    })
  },
  methods: {

    call(event, to_phone) {
      if (to_phone) {
        this.to_phone = to_phone
      }
      if (!this.from_phone || !this.to_phone) {
        // eslint-disable-next-line no-undef
        UIkit.notification({message: 'Вы не ввели исходящий и/или вызываемый номер', status: 'warning'})
        return
      }


      this.$store.dispatch('call', { to_phone: this.to_phone, from_phone: this.from_phone }).then(() => {
        // eslint-disable-next-line no-undef
        UIkit.modal(document.querySelector('#modal_call')).hide();
        // eslint-disable-next-line no-undef
        UIkit.notification({message: `Вы успешно успешно позвонили на номер ${this.to_phone}`, status: 'success', timeout: 1000})
      }).catch((err) => {
        console.log(err)
        // eslint-disable-next-line no-undef
        UIkit.notification({message: `Позвонить на номер ${this.to_phone} не удалось!`, status: 'warning', timeout: 1000})
      })
    },

    change(event) {
      this.from_phone = event.target.value
    }
  }
}
</script>

<style scoped>
.modal_call_btn {
  transition:0.5s all;
  border-radius:50%;
  width:34px;
  height:34px;
  border:1px solid #0f6ecd;
  color: #0f6ecd;
}
.modal_call_btn:hover {
  border-color:#666;
  color:#666;
}
</style>
