<template>
  <div class="uk-section uk-section-muted uk-flex uk-flex-middle uk-animation-fade" uk-height-viewport>
    <div class="uk-width-1-1">
      <div class="uk-container">
        <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
          <div class="uk-width-1-1@m">
            <div class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
              <h3 class="uk-card-title uk-text-center">Создание аккуанта</h3>
              <form>
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
                    <input v-model.trim="$v.name.$model" class="uk-input uk-form-large" type="password" placeholder="Имя">
                  </div>
                  <div class="uk-text-small uk-text-danger" v-if="$v.name.$dirty && !$v.name.required">Введите имя</div>
                </div>
                <div class="uk-margin">
                  <div class="uk-inline uk-width-1-1">
                    <span class="uk-form-icon" uk-icon="icon: lock"></span>
                    <input v-model.trim="$v.password.$model" class="uk-input uk-form-large" type="password" placeholder="Пароль">
                  </div>
                  <div class="uk-text-small uk-text-danger" v-if="$v.password.$dirty && !$v.password.required">Введите пароль</div>
                </div>
                <div class="uk-margin">
                  <button class="uk-button uk-button-primary uk-button-large uk-width-1-1">Создать аккаунт!</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { email, required} from "vuelidate/lib/validators";
export default {
  name: "register",
  data() {
    return {
      email: '',
      name: '',
      password: '',
    }
  },
  validations: {
    email: {
      required,
      email
    },
    name: {
      required
    },
    password: {
      required
    }
  },
  methods: {
    register() {
      axios.post('/api/register', this.$data)
    }
  }
}
</script>

<style scoped>

</style>
