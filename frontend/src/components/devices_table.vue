<template>
    <table class="uk-table uk-table-justify uk-table-divider">
      <thead>
      <tr>
        <th class="uk-width-small">IMEI</th>
        <th>Название устройства</th>
        <th>IMSI</th>
        <th>MSISDN</th>
        <th class="uk-text-right">Регистрация</th>
        <th class="uk-text-right">Дата изменения</th>
        <th class="uk-text-right">Кто добавил<span uk-tooltip="Изменять можно только свои или нечейные записи!" class="info_icon" uk-icon="info"></span></th>
        <th style="width:97px;" class="uk-text-right uk-width-small">
          <label v-if="$store.getters.isAuthenticated" style="color: #7497b2;margin-right:7px;" class="filter-toggle" @click.prevent="order_by('mine','on')"><input :checked="$route.query.mine" class="uk-checkbox" type="checkbox">Мои</label>
          <a uk-icon="settings" uk-tooltip="Отсортировать"></a>
          <div uk-dropdown="mode: click;pos: bottom-justify">
            <ul class="uk-list uk-list-divider uk-text-left">
              <li><label class="filter-toggle" @click.prevent="order_by('title','asc')"><input :checked="$route.query.title && $route.query.title === 'asc'" class="uk-checkbox" type="checkbox">По названию устройства (алф.)</label></li>
              <li><label class="filter-toggle" @click.prevent="order_by('created_at','asc')"><input :checked="$route.query.created_at && $route.query.created_at === 'asc'" class="uk-checkbox" type="checkbox">По дате регистрации (по возрастанию)</label></li>
              <li><label class="filter-toggle" @click.prevent="order_by('created_at','desc')"><input :checked="$route.query.created_at && $route.query.created_at === 'desc'" class="uk-checkbox" type="checkbox">По дате регистрации (по убыванию)</label></li>
              <li><label class="filter-toggle" @click.prevent="order_by('imei','asc')"><input :checked="$route.query.imei && $route.query.imei === 'asc'" class="uk-checkbox" type="checkbox">По IMEI (возрастанию)</label></li>
              <li><label class="filter-toggle" @click.prevent="order_by('imei','desc')"><input :checked="$route.query.imei && $route.query.imei === 'desc'" class="uk-checkbox" type="checkbox">По IMEI (убыванию)</label></li>
            </ul>
          </div>
        </th>
      </tr>
      </thead>
      <tbody :style="{'border-right': (function(){if (device.black_list.length !== 0) {return '1px solid #e5e5e5'} else return 'none'}())}" v-for="device in devices" :key="device.id">
      <tr  :class="{'collapsed': device.is_collapsed,'collapsable': device.is_collapsable, 'added' : (device.status === 'success'), 'failed' : (device.status === 'failed'), 'updated': (device.status === 'updated') }" style="transition:all 0.5s" >
        <td>
          <span v-html="device.imei"></span>
          <a v-if="!device.creator || ($store.getters.authUser && $store.getters.authUser.id === device.creator.id)" uk-icon="plus-circle" style="position:absolute;width:17px;margin-top:-3px;margin-left:4px;" @click="$emit('get_item',device)" uk-toggle="target: #modal_create_device_msisdn"></a>
        </td>
        <td v-html="device.title"></td>
        <td v-html="device.imsi.imsi"></td>
        <td v-html="device.msisdn.msisdn"></td>
        <td class="uk-text-right">{{convert_date(device.created_at)}}</td>
        <td class="uk-text-right">{{convert_date(device.updated_at)}}</td>
        <td class="uk-text-right">{{device.creator ? device.creator.name : 'Неизвестно'}}</td>
        <td class="uk-text-right">
          <a v-if="!device.creator || ($store.getters.authUser && $store.getters.authUser.id === device.creator.id)" @click="$emit('get_item',device)" uk-toggle="target: #modal_update_device" uk-icon="file-edit" uk-tooltip="Редактировать"></a>
          <a v-if="!device.creator || ($store.getters.authUser && $store.getters.authUser.id === device.creator.id)" @click="$emit('get_item',device)" uk-toggle="target: #modal_delete_device" uk-icon="trash" uk-tooltip="Удалить"></a>
        </td>
      </tr>
      <tr style="border-top:none" v-for="msisdn in device.black_list" :key="msisdn">
        <td style="padding-top:0;padding-bottom:0">{{msisdn}}</td>
        <td style="padding-top:0;padding-bottom:0"><a @click="remove_msisdn({device_id : device.id, msisdn: msisdn})" uk-icon="trash"></a></td>
      </tr>
      </tbody>
    </table>
</template>

<script>


export default {
  name: "devices_table",
  props: ['devices'],
  methods: {

    convert_date(dateString) {
      const date = new Date(Date.parse(dateString));
      const months = [
        "января",
        "февраля",
        "марта",
        "апреля",
        "мая",
        "июня",
        "июля",
        "августа",
        "сентября",
        "октября",
        "ноября",
        "декабря"
      ];
      return `${date.getDate()} ${months[date.getMonth()]} (${
          date.getFullYear() % 1000
      }г.)`
    },

    // eslint-disable-next-line no-unused-vars
    order_by(key, value) {
      let route = this.$route
      if (Object.keys(route.query).includes(key)) {
        let queryPush = {...route.query}
        if (route.query[key] === 'asc' && value === 'desc' || route.query[key] === 'desc' && value === 'asc') {
          queryPush[key] = value
        }
        else {
          delete queryPush[key]
        }
        delete queryPush.page
        this.$router.push({ name: this.$route.name, query: queryPush })
      }else{
        this.$router.push({name: this.$route.name, query: {...this.$route.query, [`${key}`]: value}})
      }
      },

    remove_msisdn(data) {
      this.$store.dispatch('remove_device_msisdn', data)
    }
  }
}
</script>

<style scoped>
.uk-dropdown {
  padding: 3px 5px;
  text-transform: none;
  min-width:180px;
}
.failed {
  background: #FFDAC5 !important;
  transition: all 0.5s;
}
.added {
  background: #EBF8F1 !important;
  transition: all 0.5s;
}
.updated {
  background: #E9F4FA !important;
}
.uk-checkbox {
  position: relative;
  z-index: -1
}
.filter-toggle {
  display:inline-block;
  cursor: pointer;
  -webkit-touch-callout: none; /* iOS Safari */
  -webkit-user-select: none;   /* Chrome/Safari/Opera */
  -khtml-user-select: none;    /* Konqueror */
  -moz-user-select: none;      /* Firefox */
  -ms-user-select: none;       /* Internet Explorer/Edge */
  user-select: none;
}
.info_icon {
  margin-left: 5px;
  position:relative;
  top:-3px;
}
</style>
