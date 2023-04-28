<template>
  <div id="modal_update" uk-modal>
    <div class="uk-modal-dialog">
      <button class="uk-modal-close-default" type="button" uk-close></button>
      <div class="uk-modal-body">
        <form v-if="item">
          <fieldset class="uk-fieldset">

            <legend class="uk-legend">Редактировать устройство</legend>

            <div class="uk-margin" v-for="(key, name) in this.$data" :key="key+name">
              <div v-if="!unnecessary_props.includes(name)">
                <label class="uk-form-label" :for="name+'_update'">{{ convert_data_name(name) }}</label>
                <div class="uk-form-controls">
                  <input v-model.trim="$v[name].$model" class="uk-input" :class="{ 'uk-form-danger': $v[name].$error, }" type="text" :placeholder="name" :id="name+'_update'">
                    <div v-for="(item, index) in $v[name].$params" :key="index">
                      <div class="uk-text-danger" v-if="$v[name].$dirty && !$v[name][item]">{{ rules.filter(item => item.field_name === name)[0].validation_text[item.type]  }}</div>
                    </div>
                </div>
              </div>
            </div>

            <input name="id" type="hidden" v-bind:value="item.id">

          </fieldset>
        </form>
      </div>
      <div class="uk-modal-footer uk-text-right">
        <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
        <button class="uk-button uk-button-primary" type="button">Редактировать</button>
      </div>
    </div>
  </div>
</template>

<script>
// eslint-disable-next-line no-unused-vars
import {between, minLength, required} from "vuelidate/lib/validators";

export default {
  name: "modal_update",
  props: ['item'],
  data() {
    return {
      // eslint-disable-next-line no-undef
      unnecessary_props: ['id','class_name','black_list','created_at','updated_at','rules','unnecessary_props'],
      rules: [
        {
          field_name: 'title',
          validation: [['required', required]],
          validation_text: {
            required: 'Введите название устройства'
          }
        },
        {
          field_name: 'imei',
          validation: [
            ['required', required],
            ['minLength', minLength(15)]
          ],
          validation_text: {
            required: 'IMEI обязателен',
            minLength: 'IMEI должен быть не менее 15 символов'
          }
        },
        {
          field_name: 'imsi',
          validation: [
            ['required', required],
            ['minLength', minLength(15)]
          ],
          validation_text: {
            required: "IMSI обязателен",
            minLength: "IMSI должен быть не менее 15 символов"
          }
        },
        {
          field_name: 'msisdn',
          validation: [
            ['required', required],
            ['between', between(70000000000, 79999999999)]
          ],
          validation_text: {
            required: 'MSISDN обязателен',
            between: 'Значение должно начинаться с 7 и содержать 11 символов'
          }
        }
      ]
    }
  },
  watch: {
    item: function () {
      if (!this.item) return

      for (let key in this.item) {
        if (!this.unnecessary_props.includes(key)) this.$data[key] = ''
      }

    },
  },
  validations() {
    let rules = {};

    for (let key in this.$data) {
      let key_rule = this.rules.filter(item => item.field_name === key)
      if (key_rule.length !== 0) {
        rules[key] = Object.fromEntries(key_rule[0].validation)
      }
    }

    return rules
  },
  methods: {
    convert_data_name(str) {
      switch (str) {
        case 'title':
          return 'Название устройства'
        default:
          return str
      }
    }
  }
}
</script>

<style scoped>

</style>
