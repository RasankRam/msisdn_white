<template>
  <div>
    <Header />
    <div style="margin-bottom:70px;" class="uk-container">
      <hr style="margin-top:15px;margin-bottom:40px;" class="uk-grid-divider">
      <div class="uk-margin uk-text-right uk-position-relative">
        <button id="edit_title_btn" v-visible="!wysiwyg_visibility" @click="wysiwyg_visibility = !wysiwyg_visibility"
                class="uk-button uk-button-default uk-border-rounded">123Изменить заголовок</button>
      </div>

      <tiptap style="margin-left: -19.2px;" :active.sync="wysiwyg_visibility" @change_wysiwyg_visibility="change_wysiwyg_visibility" class="uk-margin-medium-bottom" />

<!--      <div v-visible="!wysiwyg_visibility" class="page-content uk-margin-medium-bottom uk-border-rounded">-->
<!--        <div v-html="content.find(item => item.block === 'main_content').content"></div>-->
<!--      </div>-->

<!--      <div v-html="main_content"></div>-->

      <div :style="{'margin-top': wysiwyg_visibility ? '67px' : '0'}" class="uk-grid">
        <div class="uk-width-1-2">
          <button class="register_btn uk-button uk-button-primary" uk-toggle="target: #modal_create_device">Зарегистрировать устройство</button>
        </div>
        <div class="uk-width-1-2 uk-text-right">
          <a class="call_btn" href="#modal_call" v-if="$store.getters.isAuthenticated" uk-toggle uk-tooltip="Позвонить">
            <span style="position:relative;left: -9.1px;top: 5px;" uk-icon="icon: receiver"></span>
          </a>
          <div v-else><a href="#modal_login" uk-toggle> Авторизуйтесь!</a> Чтобы кому-то позвонить!</div>
        </div>
      </div>

      <devices_table :devices="devices" @get_item="get_item" />
      <Paginate v-if="$store.getters.pagination_response && $store.getters.pagination_response.meta.last_page !== 1"
          v-model="page"
          :page-count="pageCount"
          :margin-pages="2"
          :click-handler="pageChangeHandler"
          :prev-text="'&laquo;'"
          :next-text="'&raquo;'"
          :container-class="'uk-pagination'"
          :page-class="''"
          :active-class="'uk-active'"
      />

      <hr style="margin-top:40px;" class="uk-grid-divider">
      <div class="uk-flex-center" uk-grid>
        <div>Telegram: @ramazon_sangov</div>
        <div>Github: https://github.com/RasankRam</div>
      </div>
    </div>



    <modal_create :item="item" />
    <modal_create_msisdn :device_id="item ? item.id : null" />
    <modal_delete :item="item" />
    <modal_update :device="item" />
    <modal_login />
    <modal_register />
    <modal_call v-if="$store.getters.isAuthenticated" />
    <modal_update_profile />

  </div>
</template>

<script>
import Tiptap from '../components/Tiptap.vue'
import Header from '../components/Header.vue'
import modal_update from '../components/modal_update_normal'
import modal_create from '../components/modal_create'
import devices_table from '../components/devices_table'
import modal_delete from "../components/modal_delete"
import modal_create_msisdn from "../components/modal_create_msisdn"
import modal_register from "../components/modal_register";
import modal_login from "../components/modal_login"
import modal_call from '../components/modal_call'
import modal_update_profile from "../components/modal_update_profile";
import axios from "axios";

export default {
  name: "black_list",
  metaInfo() {
    return { title: 'Сеть связи' }
  },
  watch: {
    '$route.query': function(query_object) {
      this.page = query_object.page ? query_object.page : 1
    },
  },
  data() {
    return {
      page: +this.$route.query.page || 1,
      item: null,
      wysiwyg_visibility: false,
    }
  },
  computed: {
    content() {
      return this.$store.getters.content
    },
    devices() {
      return this.$store.getters.pagination_response.data
    },
    pageSize() {
      if (this.$store.getters.pagination_response) return this.$store.getters.pagination_response.meta.per_page
      return 1
    },
    pageCount() {
      if (this.$store.getters.pagination_response) return this.$store.getters.pagination_response.meta.last_page
      return 1
    }
  },
  mounted() {
    // this.main_content = this.content.find(item => item.block === 'main_content').content
    setTimeout(() => this.$store.dispatch('collapse_table_handler'), 200)
  },
  methods: {

    change_wysiwyg_visibility(value) {
      this.wysiwyg_visibility = value
    },

    // Получить девайс

    get_item(item) {
      this.item = item
    },

    async pingRequest() {
      console.log('tryingPingRequest...');
      const { data } = await axios.get('/api/ping_some');
      console.log('receivedPingData', data);
    },

    // Срабатывает при изменении страницы

    pageChangeHandler(page) {

      if (+page === 1) {
        this.queryPush = {...this.$route.query}
        if (Object.keys(this.$route.query).includes('page')) {
          delete this.queryPush.page
        }
        this.$router.push({name: this.$route.name, query: this.queryPush})
      } else {
        this.$router.push({name: this.$route.name, query: {...this.$route.query, page}})
      }
    }
  },
  components: {
    modal_update_profile,
    modal_register, modal_login, Tiptap,
    modal_delete, devices_table, Header, modal_create, modal_update,
    modal_create_msisdn, modal_call
  },
}
</script>

<style lang="scss" scoped>
#edit_title_btn {
  //background: rgb(203, 211, 242);
  background: #badaff;
  font-size: 16px;
  position: absolute;
  right: 40px;
  top: -20px;
  border: 1px solid rgb(156, 156, 156);
  z-index: 100;
}
#edit_title_btn:hover {
  background: #d8ddec;
}
.uk-form-label {
  font-size:15px;
}
.call_btn {
  background:#1e87f0;
  display:inline-block;
  height:40px;
  width:40px;
  color:white;
  border-radius:50%;
}
.register_btn {
  background: linear-gradient(180deg, #5F7FEE 0%, #6D8BF5 100%);
  box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25), 0px 0px 4px rgba(0, 0, 0, 0.25);
}
.register_btn:hover {
  background: linear-gradient(180deg, #6D8BF5 0%, #5F7FEE 100%);
  box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.25), 0px 0px 2px rgba(0, 0, 0, 0.25);
}
.page-content {
  border: 2px solid #c1c1c1;
  padding: 15px;
}
</style>
