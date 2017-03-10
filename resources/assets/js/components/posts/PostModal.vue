<template>
  <div class="post-modal-component">
    <div class="ui long modal" :id="modalId">
      <div class="header">{{ formTitle }}</div>
      <div class="content">
        <div class="ui form" :class="{'error': hasFormError}">
          <div class="field" :class="{'error': hasChannelError}">
            <label>Канал</label>
            <select class="ui fluid dropdown" v-model="form.channel_id">
              <option value="0">-- Выберите канал для публикации --</option>
              <option :value="channel.id" v-for="channel in channels" v-if="channel.has_bot">{{ channel.name }}</option>
            </select>
          </div>
          <div v-if="hasChannelError" class="ui error message">
            <p>Необходимо указать канал для публикации.</p>
          </div>
          <div class="field" :class="{'error': hasTitleError}">
            <label>Заголовок</label>
            <input type="text" placeholder="" v-model="form.title">
          </div>
          <div v-if="hasTitleError" class="ui error message">
            <p>Необходимо указать заголовок публикации.</p>
          </div>
          <div class="ui visible warning message">
            <p>
              Ваш часовой пояс: <strong>{{ timezoneName }}</strong>.
              <a href="javascript:;" @click="showUserProfileModal">Изменить</a>.
            </p>
          </div>
          <div class="field" :class="{'error': hasScheduleDateError}">
            <label>Дата публикации</label>
            <input type="text" class="scheduled-date-input">
          </div>
          <div v-if="hasScheduleDateError" class="ui error message">
            <p>Указана некорректная дата публикации</p>
          </div>
          <div class="field" :class="{'error': hasScheduleTimeError}">
            <label>Время публикации</label>
            <input type="text" class="scheduled-time-input">
          </div>
          <div v-if="hasScheduleTimeError" class="ui error message">
            <p>Указано некорректное время публикации</p>
          </div>
          <div class="field">
            <label>Текст</label>
            <textarea cols="30" rows="10" :id="modalFormId" class="original-textarea-field"></textarea>
          </div>
          <div v-if="hasMessageError" class="ui error message">
            <p>Необходимо указать текст публикации.</p>
          </div>
          <button type="button" class="ui primary button" @click="submitForm" :disabled="formIsBusy">
            <i class="fa fa-save"></i>
            Сохранить
          </button>
          <button v-if="type === 'edit'" type="button" class="ui red button" @click="deletePost" :disabled="formIsBusy">
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

<style>
.original-textarea-field {
  display: none;
}
</style>

<script>
import SimpleMDE from 'simplemde'

export default {
  mounted() {
    this.initModal()

    this.$root.sharedEventBus.$on('postmodal.show', post => {
      this.showModal(post)
    })
  },

  computed: {
    httpVerb() {
      return this.type === 'create' ? 'post' : 'put'
    },

    submitFormUrl() {
      if (this.type === 'create') {
        return this.$store.getters.routes.posts.store.url
      }

      return this.post.urls.update.url
    },

    hasFormError() {
      return (
        this.hasChannelError || this.hasTitleError || this.hasMessageError ||
        this.hasAttachmentsError || this.hasScheduleDateError || this.hasScheduleTimeError
      )
    },

    formTitle() {
      if (this.type === 'create') {
        return 'Добавление новой публикации'
      }

      return `Редактирование публикации ${this.post.title}`
    },

    channels() {
      return this.$store.getters.channelsList
    },

    timezoneName() {
      return this.$store.getters.user.timezone_name
    },
  },

  methods: {
    initModal() {
      const modalRoot = window.jQuery(`#${this.modalId}`)

      modalRoot.modal({
        closable: false,
        keyboardShortcuts: false,
      })

      this.editor = new SimpleMDE({
        element: document.getElementById(this.modalFormId),
        autosave: false,
        blockStyles: {
          italic: '_',
        },
        forceSync: true,
        indentWithTabs: false,
        shortcuts: false,
        spellChecker: false,
        tabSize: 4,
        toolbar: ['bold', 'italic', 'code', 'link'],
      })

      window.jQuery('.scheduled-date-input', modalRoot).inputmask('dd.mm.yyyy')
      window.jQuery('.scheduled-time-input', modalRoot).inputmask('hh:mm')
    },

    showModal(post) {
      this.initForm(post)
      this.resetErrors()

      window.jQuery(`#${this.modalId}`).modal('show')
    },

    closeModal() {
      window.jQuery(`#${this.modalId}`).modal('hide')
    },

    showUserProfileModal() {
      if (!confirm('Если вы перейдете в настройки аккаунта, эта форма будет закрыта. Продолжить?')) {
        return
      }

      this.closeModal()
      setTimeout(() => {
        this.$root.sharedEventBus.$emit('profilemodal.show')
      }, 500)
    },

    initForm(post) {
      this.type = 'create'
      this.form = {
        channel_id: 0,
        title: '',
        message: '',
        attachments: '',
        scheduled_at: '',
      }

      if (post && typeof post.id !== 'undefined') {
        this.type = 'edit'
        this.post = post
        this.form = {
          channel_id: post.channel_id,
          title: post.title,
          message: post.message,
          attachments: post.attachments,
          scheduled_at: '',
        }
      }

      this.editor.value(this.form.message)
    },

    randomStr() {
      return Math.random().toString(36).substr(2, 10)
    },

    resetErrors() {
      this.hasChannelError = false
      this.hasTitleError = false
      this.hasMessageError = false
      this.hasAttachmentsError = false
      this.hasScheduleDateError = false
      this.hasScheduleTimeError = false
    },

    validate() {
      this.resetErrors()

      if (!parseInt(this.form.channel_id, 10)) {
        this.hasChannelError = true

        return false
      }

      if (!this.form.title.trim()) {
        this.hasTitleError = true

        return false
      }

      this.form.message = this.editor.value()

      if (!this.form.message.trim()) {
        this.hasMessageError = true

        return false
      }

      this.form.scheduled_at = this.makeScheduledAtField()

/*
      if (!this.form.attachments.trim()) {
        this.hasAttachmentsError = true

        return false
      }
*/
      return true
    },

    makeScheduledAtField() {
      return ($ => {
        const unmaskedDate = $('.scheduled-date-input', $(`#${this.modalId}`)).val()
        const unmaskedTime = $('.scheduled-time-input', $(`#${this.modalId}`)).val()

        if (!unmaskedDate.trim() || !unmaskedTime.trim()) {
          return null
        }

        const date = unmaskedDate.split('.')

        return `${date[2]}-${date[1]}-${date[0]} ${unmaskedTime}`
      })(window.jQuery)
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

          if (this.type === 'create') {
            this.$store.commit('addPost', response.data.data)

            alert('Публикация была добавлена')
          } else {
            this.$store.commit('updatePost', {
              id: this.post.id,
              data: response.data.data,
            })

            alert('Публикация была обновлена')
          }
        }, error => {
          this.formIsBusy = false

          alert('Не удалось добавить публикацию')
        })
    },

    deletePost() {
      this.formIsBusy = true

      window.axios.delete(this.post.urls.destroy.url)
        .then(response => {
          this.closeModal()
          this.formIsBusy = false
          this.$store.commit('removePost', this.post.id)

          alert('Публикация была успешно удалена')
        }, error => {
          this.formIsBusy = false

          alert('Не удалось удалить публикацию')
        })
    },
  },

  data() {
    return {
      modalId: `post-modal-component-${this.randomStr()}`,
      modalFormId: `post-modal-component-form-${this.randomStr()}`,
      type: 'create',
      post: {},
      form: {
        channel_id: 0,
        title: '',
        message: '',
        scheduled_at: '',
      },
      formIsBusy: false,
      hasChannelError: false,
      hasTitleError: false,
      hasMessageError: false,
      hasAttachmentsError: false,
      hasScheduleDateError: false,
      hasScheduleTimeError: false,
      editor: null,
    }
  }
}
</script>
