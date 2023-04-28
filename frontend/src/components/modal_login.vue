<template>
  <div id="modal_login" uk-modal>
    <div class="uk-modal-dialog">
      <button class="uk-modal-close-default" type="button" uk-close></button>
      <div class="uk-modal-header">
        <div class="uk-modal-title uk-text-center">Вход</div>
      </div>
      <div class="uk-modal-body">
        <form @submit.prevent="sign_in">
          <div class="uk-margin">
            <div class="uk-inline uk-width-1-1">
              <span class="uk-form-icon" uk-icon="icon: mail"></span>
              <input v-model.trim="$v.email.$model" class="uk-input uk-form-large" type="text" placeholder="Введите почту">
            </div>
            <div class="uk-text-small uk-text-danger" v-if="$v.email.$dirty && !$v.email.required">Введите почту</div>
            <div class="uk-text-small uk-text-danger" v-if="$v.email.$dirty && !$v.email.email">Не подходит под формат почты</div>
          </div>
          <div class="uk-margin">
            <div class="uk-inline uk-width-1-1">
              <span class="uk-form-icon" uk-icon="icon: lock"></span>
              <input v-model.trim="$v.password.$model" class="uk-input uk-form-large" type="password" placeholder="Пароль">
            </div>
            <div class="uk-text-small uk-text-danger" v-if="$v.password.$dirty && !$v.password.required">Введите пароль</div>
          </div>
          <div class="uk-margin">
            <button class="uk-button uk-button-primary uk-button-large uk-width-1-1">Войти</button>
          </div>
          <div class="uk-text-small uk-text-center">
            Нету аккаунта? <a @click.prevent="navigate_to_register">Создай!</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import {email, required} from "vuelidate/lib/validators";

export default {
  name: "modal_login",
  data() {
    return {
      email : '',
      password : ''
    }
  },
  validations: {
    email: {
      required,
      email
    },
    password: {
      required,
    }
  },
  methods: {
    sign_in() {
      if (this.$v.$invalid) {
        this.$v.$touch() // активизируем валидацию
        return
      }

      this.$store.dispatch('sign_in', this.$data).then(() => {
        // eslint-disable-next-line no-undef
        UIkit.modal(document.querySelector('#modal_login')).hide();
        // eslint-disable-next-line no-undef
        UIkit.notification({message: `Вы успешно вошли в систему`, status: 'success', timeout: 1000})
        this.$v.$reset()
        this.email = ''
        this.password = ''
      })
    },
    navigate_to_register() {
      // eslint-disable-next-line no-undef
      UIkit.modal(document.querySelector('#modal_login')).hide();
      // eslint-disable-next-line no-undef
      UIkit.modal(document.querySelector('#modal_register')).hide();
    }
  }
}
</script>

<style scoped>
.uk-modal-title {
  font-size: 24px;
}
.uk-modal-dialog {
  width:414px;
}
</style>
