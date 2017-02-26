<template>
  <div class="channel-modal-component">
    <div class="ui modal" :id="modalId">
      <div class="header">{{ formTitle }}</div>
      <div class="content">
        <div class="ui form" :class="{'error': hasFormError}">
          <div class="field" :class="{'error': hasNameError}">
            <label>Название</label>
            <input type="text" placeholder="" v-model="form.name">
          </div>
          <div v-if="hasNameError" class="ui error message">
            <p>Необходимо указать имя канала.</p>
          </div>
          <div class="field" :class="{'error': hasChatIdError}">
            <label>Идентификатор чата</label>
            <input type="text" placeholder="" v-model="form.chat_id">
          </div>
          <div v-if="hasChatIdError" class="ui error message">
            <p>Необходимо указать Идентификатор бота.</p>
            <p>
              Идентификатор - это адрес вашего чата.
              Он начинается с символа "@" и содержится в ссылке "telegram.me/идентификатор" или "t.me/идентификатор".
            </p>
          </div>
          <div class="field" :class="{'error': hasBotError}">
            <label>Бот</label>
            <select class="ui fluid dropdown" v-model="form.bot_id">
              <option value="0">-- Выберите бота --</option>
              <option v-for="bot in bots" :value="bot.id">{{ bot.username }}</option>
            </select>
          </div>
          <div v-if="hasBotError" class="ui error message">
            <p>
              Необходимо выбрать бота, который будет публиковать записи в канале.
              Если у вас нет бота, сначала нужно его создать.
            </p>
          </div>
          <button type="button" class="ui primary button" @click="submitForm" :disabled="formIsBusy">
            <i class="fa fa-save"></i>
            Сохранить
          </button>
          <button v-if="type === 'edit'" type="button" class="ui red button" @click="deleteChannel" :disabled="formIsBusy">
            <i class="fa fa-trash"></i>
            Удалить
          </button>
          <button type="button" class="ui default button" @click="closeModal" :disabled="formIsBusy">
            Отменить
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    this.initModal()

    this.$root.sharedEventBus.$on('channelmodal.show', channel => {
      this.showModal(channel)
    })
  },

  computed: {
    httpVerb() {
      return this.type === 'create' ? 'post' : 'put'
    },

    submitFormUrl() {
      if (this.type === 'create') {
        return this.$store.getters.routes.channels.store.url
      }

      return this.channel.urls.update.url
    },

    hasFormError() {
      return this.hasNameError || this.hasChatIdError || this.hasBotError
    },

    formTitle() {
      if (this.type === 'create') {
        return 'Добавление нового канала'
      }

      return `Редактирование канала ${this.channel.name}`
    },

    bots() {
      return this.$store.getters.botsLists
    }
  },

  methods: {
    initModal() {
      window.jQuery(`#${this.modalId}`).modal({
        closable: false,
        keyboardShortcuts: false,
      })
    },

    showModal(channel) {
      this.initForm(channel)
      this.resetErrors()

      window.jQuery(`#${this.modalId}`).modal('show')
    },

    closeModal() {
      window.jQuery(`#${this.modalId}`).modal('hide')
    },

    initForm(channel) {
      this.type = 'create'
      this.form = {
        name: '',
        chat_id: '',
        bot_id: 0,
      }

      if (channel && typeof channel.id !== 'undefined') {
        this.type = 'edit'
        this.channel = channel
        this.form = {
          name: channel.name,
          chat_id: channel.chat_id,
          bot_id: channel.bot_id,
        }
      }
    },

    randomStr() {
      return Math.random().toString(36).substr(2, 10)
    },

    resetErrors() {
      this.hasNameError = false
      this.hasChatIdError = false
      this.hasBotError = false
    },

    validate() {
      this.resetErrors()

      if (!this.form.name.trim()) {
        this.hasNameError = true

        return false
      }

      if (!this.form.chat_id.trim()) {
        this.hasChatIdError = true

        return false
      }

      if (parseInt(this.form.bot_id, 10) <= 0) {
        this.hasBotError = true

        return false
      }

      return true
    },

    submitForm() {
      if (this.validate() === false) {
        return
      }

      this.formIsBusy = true

      window.axios[this.httpVerb](this.submitFormUrl, this.form)
        .then(response => {
          this.closeModal()
          this.formIsBusy = false
          this.$store.commit('addChannel', response.data.data)

          alert('Канал был добавлен')
        }, error => {
          this.formIsBusy = false

          alert('Не удалось добавить канал')
        })
    },

    deleteChannel() {
      this.formIsBusy = true

      window.axios.delete(this.channel.urls.destroy.url)
        .then(response => {
          this.closeModal()
          this.formIsBusy = false
          this.$store.commit('removeChannel', this.channel.id)

          alert('Канал был успешно удален')
        }, error => {
          this.formIsBusy = false

          alert('Не удалось удалить канал')
        })
    },
  },

  data() {
    return {
      modalId: `channel-modal-component-${this.randomStr()}`,
      type: 'create',
      channel: {},
      form: {
        name: '',
        chat_id: '',
        bot_id: 0,
      },
      formIsBusy: false,
      hasNameError: false,
      hasChatIdError: false,
      hasBotError: false,
    }
  }
}
</script>
