<template>
  <div class="bot-modal-component">
    <div class="ui modal" :id="modalId">
      <div class="header">{{ formTitle }}</div>
      <div class="content">
        <div class="ui message">
          Возникли вопросы? Прочитайте <a href="javascript:;">руководство по созданию Telegram-ботов</a>.
        </div>
        <div class="ui form" :class="{'error': hasFormError}">
          <div class="field" :class="{'error': hasNameError}">
            <label>Название</label>
            <input type="text" placeholder="" v-model="form.name">
          </div>
          <div v-if="hasNameError" class="ui error message">
            <p>Необходимо указать имя бота. Имя может быть произвольным.</p>
          </div>
          <div class="field" :class="{'error': hasUsernameError}">
            <label>Юзернейм бота</label>
            <input type="text" placeholder="" v-model="form.username">
          </div>
          <div v-if="hasUsernameError" class="ui error message">
            <p>Необходимо указать юзернейм бота.</p>
            <p>Юзернейм - это имя, по которому можно обратиться к боту в чате. Он начинается с символа "@" и заканчивается словом "bot".</p>
          </div>
          <div class="field" :class="{'error': hasTokenError}">
            <label>Токен</label>
            <input type="text" placeholder="" v-model="form.token">
          </div>
          <div v-if="hasTokenError" class="ui error message">
            <p>Необходимо указать токен. Токен имеет вид <code>12345:ABCD</code>.</p>
          </div>
          <button type="button" class="ui primary button" @click="submitForm" :disabled="formIsBusy">
            <i class="fa fa-save"></i>
            Сохранить
          </button>
          <button v-if="type === 'edit'" type="button" class="ui red button" @click="deleteBot" :disabled="formIsBusy">
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

    this.$root.sharedEventBus.$on('botmodal.show', bot => {
      this.showModal(bot)
    })
  },

  computed: {
    httpVerb() {
      return this.type === 'create' ? 'post' : 'put'
    },

    submitFormUrl() {
      if (this.type === 'create') {
        return this.$store.getters.routes.bots.store.url
      }

      return this.bot.urls.update.url
    },

    hasFormError() {
      return this.hasNameError || this.hasUsernameError || this.hasTokenError
    },

    formTitle() {
      if (this.type === 'create') {
        return 'Добавление нового бота'
      }

      return `Редактирование бота ${this.bot.name}`
    }
  },

  methods: {
    initModal() {
      window.jQuery(`#${this.modalId}`).modal({
        closable: false,
        keyboardShortcuts: false,
      })
    },

    showModal(bot) {
      this.initForm(bot)
      this.resetErrors()

      window.jQuery(`#${this.modalId}`).modal('show')
    },

    closeModal() {
      window.jQuery(`#${this.modalId}`).modal('hide')
    },

    initForm(bot) {
      this.type = 'create'
      this.form = {
        name: '',
        username: '',
        token: '',
      }

      if (bot && typeof bot.id !== 'undefined') {
        this.type = 'edit'
        this.bot = bot
        this.form = {
          name: bot.name,
          username: bot.username,
          token: bot.token,
        }
      }
    },

    randomStr() {
      return Math.random().toString(36).substr(2, 10)
    },

    resetErrors() {
      this.hasNameError = false
      this.hasUsernameError = false
      this.hasTokenError = false
    },

    validate() {
      this.resetErrors()

      if (!this.form.name.trim()) {
        this.hasNameError = true

        return false
      }

      if (!this.form.username.trim()) {
        this.hasUsernameError = true

        return false
      }

      if (!this.form.token.trim()) {
        this.hasTokenError = true

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
          this.$store.commit('addBot', response.data.data)

          alert('Бот был добавлен')
        }, error => {
          this.formIsBusy = false

          alert('Не удалось добавить бота')
        })
    },

    deleteBot() {
      this.formIsBusy = true

      window.axios.delete(this.bot.urls.destroy.url)
        .then(response => {
          this.closeModal()
          this.formIsBusy = false
          this.$store.commit('removeBot', this.bot.id)

          alert('Бот был успешно удален')
        }, error => {
          this.formIsBusy = false

          alert('Не удалось удалить бота')
        })
    },
  },

  data() {
    return {
      modalId: `bot-modal-component-${this.randomStr()}`,
      type: 'create',
      bot: {},
      form: {
        name: '',
        username: '',
        token: '',
      },
      formIsBusy: false,
      hasNameError: false,
      hasUsernameError: false,
      hasTokenError: false,
    }
  }
}
</script>
