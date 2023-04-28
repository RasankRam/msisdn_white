<template>
  <nav class="uk-navbar-container uk-navbar-transparent" uk-navbar>
    <div style="width:1200px" class="uk-container">
<!--      $emit('load_devices',$route.params.page,search_item, search)-->
    <div class="uk-navbar-left uk-float-left uk-width-3-5">
      <router-link :to="'/'" style="font-weight:lighter" class="uk-navbar-item uk-logo">Сеть связи</router-link>
      <div class="uk-navbar-item">
        <form class="uk-search uk-search-default">
          <span class="uk-search-icon-flip" uk-search-icon></span>
          <input v-model="search" @input="form" class="uk-search-input" type="search" placeholder="Search">
        </form>
      </div>
      <div class="uk-navbar-item">
        <select v-model="search_type" class="uk-select">
          <option value="device_title">По названию устройства</option>
          <option value="imei">По IMEI</option>
          <option value="imsi">По IMSI</option>
          <option value="msisdn">По MSISDN</option>
        </select>
      </div>
      <a @click="reset" class="reset_button" uk-tooltip="Переводит сайт в начальное состояние">Сбросить</a>
    </div>
    <div class="uk-navbar-right uk-width-2-5 uk-flex-right">
      <div v-if="!$store.getters.isAuthenticated" style="padding-right:0;" class="uk-navbar-item">
        <button uk-toggle="target: #modal_login" class="uk-button uk-button-text uk-margin-right">Авторизоваться</button>
        <button uk-toggle="target: #modal_register" id="register_button" class="uk-button uk-button-text">Зарегистрироваться</button>
      </div>
      <div class="uk-navbar-item" style="padding-right:0" v-else>
        <a id="auth_dropdown_button" class="uk-text" type="button">
          <span>{{ $store.getters.authUser.name }}</span>
          <span uk-icon="chevron-down"></span>
        </a>
        <div id="auth_dropdown" uk-dropdown="mode: click;pos: bottom-right;toggle: #auth_dropdown_button">
          <ul class="uk-list uk-list-striped">
            <li><a class="auth_dropdown_link" @click.prevent="hide_dropdown" href="#modal_update_profile" uk-toggle>Изменить<br> профильные данные</a></li>
            <li><a style="text-align:center;" class="auth_dropdown_link" @click.prevent="logout">Выйти</a></li>
          </ul>
        </div>
      </div>
    </div>
    </div>

  </nav>
</template>

<script>

export default {
  name: "v_header",
  data() {
    return {
      search_type: 'device_title',
      search: '',
    }
  },
  watch: {
    '$store.getters.authUser': function () {
      setTimeout(() => {
        // eslint-disable-next-line no-undef
        UIkit.dropdown(document.querySelector('#auth_dropdown'), {
          toggle: '#auth_dropdown_button'
        })
      })
    },
  },
  methods: {
    logout() {
      this.$store.dispatch('logout').then(() => {
        // eslint-disable-next-line no-undef
        UIkit.notification({message: `Вы успешно вышли из аккаунта`, status: 'success', timeout: 1000})
      })
    },
    reset() {
      this.$store.dispatch('reset').then(() => {
        // eslint-disable-next-line no-undef
        UIkit.notification({message: `Сайт сброшен в начальное состояние`, status: 'success', timeout: 1000})
        this.goToUrl('/')
      })
    },

    goToUrl(url) {
      if (url === location.pathname + location.search) {
        this.$router.push({
          path: '/'
        });
      }
      this.$router.push(url);
    },

    form() {
      if (!this.search) {
        let queryPush = {...this.$route.query}
        delete queryPush.search
        delete queryPush.type
        this.$router.push({name: this.$route.name, query: queryPush})
      } else {
        this.$router.push({ name: this.$route.name, query: { ...this.$route.query, type: this.search_type, search: this.search } })
      }

      // this.$store.dispatch('router_push',`search_type=${this.search_type}&search=${this.search}`)
    },
    hide_dropdown() {
      console.log('fasdjfkl')
      // eslint-disable-next-line no-undef
      UIkit.dropdown(document.querySelector('#auth_dropdown')).hide(0)
    }
  }
}
</script>

<style scoped>
#auth_dropdown {
  padding:0;
  min-width:150px;
}
#auth_dropdown .uk-list {
  margin-bottom:0
}
#register_button {
  color: #39f
}
#register_button::before {
  border-bottom-color: dodgerblue;
}
.reset_button {
  border-radius: 5px;
  margin-left: 43px;
  background: #ffedd3;
  color: #52606f;
  padding: 2px 5px;
  transition: 0.2s all;
  border: 1px solid #d2d2d2;
}

.reset_button:hover {
  text-decoration:none;
  background: #e2f5e7;
}
.auth_dropdown_link {
  display: block;
}
</style>
