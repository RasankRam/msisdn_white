<template>
  <div id="modal_update_profile" uk-modal>
    <div class="uk-modal-dialog">
      <button class="uk-modal-close-default" type="button" uk-close></button>
      <div class="uk-modal-header">
        <h2 class="uk-modal-title">Изменить профиль</h2>
      </div>
      <div v-if="$store.getters.isAuthenticated" class="uk-modal-body">
        <form @submit.prevent="update_profile">
          <!--          <fieldset class="uk-fieldset">-->
          <div class="uk-margin">
            <label class="uk-form-label" for="user_name">Имя</label>
            <div class="uk-form-controls">
              <input v-model.trim="$v.name.$model" class="uk-input" :class="{ 'uk-form-danger': $v.name.$error, }" id="user_name" type="text" placeholder="Имя">
              <div class="uk-text-danger" v-if="$v.name.$dirty && !$v.name.required">Имя обязательно</div>
              <div class="uk-text-danger" v-if="!$v.name.minLength">Имя должно быть не менее {{$v.name.$params.minLength.min}} символов</div>
            </div>
          </div>

          <div class="uk-margin">
            <label class="uk-form-label" for="user_email">Эл. почта</label>
            <div class="uk-form-controls">
              <input class="uk-input" v-model.trim="$v.email.$model" :class="{ 'uk-form-danger': $v.email.$error }" id="user_email" type="text" placeholder="Эл.почта">
              <div class="uk-text-danger" v-if="$v.email.$dirty && !$v.email.required">Эл. почта должна быть обязательно</div>
              <div class="uk-text-danger" v-if="!$v.email.email">Не выглядит как почта</div>
            </div>
          </div>

          <div class="uk-margin">
            <label class="uk-form-label" for="old_password">Старый пароль</label>
            <div class="uk-form-controls">
              <input class="uk-input" v-model.trim="$v.old_password.$model" :class="{ 'uk-form-warning': $v.old_password.$error }" id="old_password" type="text" placeholder="Старый пароль">
              <div class="uk-text-warning" v-if="!$v.old_password.required">Введите старый пароль</div>
<!--              <div class="uk-text-danger" v-if="$v.old_password.$dirty && !$v.old_password.minLength">Пароль должен быть не менее 6 символов</div>-->
            </div>
          </div>

          <div v-if="old_password || password || password_confirm" class="uk-margin">

            <label class="uk-form-label" for="password">Новый пароль</label>
            <div class="uk-form-controls">
              <input class="uk-input" v-model.trim="$v.password.$model" :class="{ 'uk-form-warning': $v.password.$error }" id="password" type="text" placeholder="Новый пароль">
              <div class="uk-text-warning" v-if="!$v.password.required">А теперь введите новый пароль</div>
              <div class="uk-text-warning" v-if="!$v.password.minLength">Он должен быть не менее {{$v.password.$params.minLength.min}} символов</div>
            </div>
          </div>

          <div v-if="old_password && password || password_confirm" class="uk-margin">
            <label class="uk-form-label" for="password_confirm">Подтверждение пароля</label>
            <div class="uk-form-controls">
              <input class="uk-input" v-model.trim="$v.password_confirm.$model" :class="{ 'uk-form-warning': $v.password_confirm.$error }" id="password_confirm" type="text" placeholder="Подтвердите новый пароль">
              <div class="uk-text-warning" v-if="!$v.password_confirm.required">Введите еще раз для запоминания</div>
              <div class="uk-text-warning" v-if="$v.password_confirm.required && !$v.password_confirm.sameAsPassword">Пароли не совпадают</div>
            </div>
          </div>

          <!--          </fieldset>-->
        </form>
      </div>
      <div class="uk-modal-footer uk-text-right">
        <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
        <button class="uk-button uk-button-primary" @click.prevent="update_profile" type="button">Обновить профиль</button>
      </div>
    </div>
  </div>
</template>

<script>
import { required, requiredIf, sameAs, minLength, email } from 'vuelidate/lib/validators'
export default {
  name: "modal_update_profile",
  computed: {
    user() {
      // eslint-disable-next-line vue/no-side-effects-in-computed-properties
      this.name = this.$store.getters.authUser.name
      // eslint-disable-next-line vue/no-side-effects-in-computed-properties
      this.email = this.$store.getters.authUser.email
      return this.$store.getters.authUser
    }
  },
  data() {
    return {
      name: '',
      email: '',
      old_password: '',
      password: '',
      password_confirm: '',
    }
  },
  validations: {
    email: {
      required,
      email
    },
    name: {
      required,
      minLength: minLength(6),
    },
    old_password: {
      // !required -> ошибка, required возвращает false --> ошибка
      required: requiredIf(function () {
       if (this.password || this.password_confirm) {
         this.$v.old_password.$touch()
       } // this.old_password
        return this.password || this.password_confirm // заполнено поле password или заполнено password_confirm
      })
    },
    password: {
      minLength: minLength(6),
      required: requiredIf(function () { // true --> ошибка
        // if (!!this.old_password || !!this.password_confirm) { // если у нас заполнены поля password password_confirm
        //   this.$v.old_password.$error = true
        //   this.$v.password_confirm.$error = true
        // }
        // if (this.old_password || this.password_confirm) {
        //
        // }
        if (this.old_password || this.password_confirm) {
          this.$v.password.$touch()
        }
        return this.old_password || this.password_confirm
      })
    },
    password_confirm: {
      sameAsPassword: sameAs('password'),
      required: requiredIf(function () {
        // if (!!this.password || !!this.old_password) { // если у нас заполнены поля password password_confirm
        //   this.$v.password.setError(true);
        //   this.$v.old_password.setError(true);
        // }
        if (this.password || this.old_password) {
          this.$v.password_confirm.$touch()
        }
        return !!this.password || !!this.old_password
      })
    },
  },
  mounted() {
    this.name = this.$store.getters.authUser.name
    this.email = this.$store.getters.authUser.email
  },
  methods: {
    update_profile() {
      this.$store.dispatch('update_profile', this.$data).then(() => {
        // eslint-disable-next-line no-undef
        UIkit.notification('Вы успешно изменили профильные данные')
        // eslint-disable-next-line no-undef
        UIkit.modal(document.querySelector('#modal_update_profile')).hide();
      })
      .catch(() => {
        // eslint-disable-next-line no-undef
        UIkit.notification('Мы не можем обновить ваши профильные данные')
      })
    }
  }
}
</script>

<style scoped>

</style>
