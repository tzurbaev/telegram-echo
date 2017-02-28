<template>
  <div class="post-modal-component">
    <div class="ui modal" :id="modalId">
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
          <div class="field" :class="{'error': hasMessageError}">
            <label>Текст</label>
            <textarea cols="30" rows="10" :id="modalFormId" v-model="form.message"></textarea>
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
      return this.hasChannelError || this.hasTitleError || this.hasMessageError || this.hasAttachmentsError
    },

    formTitle() {
      if (this.type === 'create') {
        return 'Добавление новой публикации'
      }

      return `Редактирование публикации ${this.post.title}`
    },

    channels() {
      return this.$store.getters.channelsList
    }
  },

  methods: {
    initModal() {
      window.jQuery(`#${this.modalId}`).modal({
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
    },

    showModal(post) {
      this.initForm(post)
      this.resetErrors()

      window.jQuery(`#${this.modalId}`).modal('show')
    },

    closeModal() {
      window.jQuery(`#${this.modalId}`).modal('hide')
    },

    initForm(post) {
      this.type = 'create'
      this.form = {
        channel_id: 0,
        title: '',
        message: '',
        attachments: '',
      }

      if (post && typeof post.id !== 'undefined') {
        this.type = 'edit'
        this.post = post
        this.form = {
          channel_id: post.channel_id,
          title: post.title,
          message: post.message,
          attachments: post.attachments,
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

      this.form.message = this.editor.value().replace(/\*\*/g, '*')

      if (!this.form.message.trim()) {
        this.hasMessageError = true

        return false
      }
/*
      if (!this.form.attachments.trim()) {
        this.hasAttachmentsError = true

        return false
      }
*/
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
          this.$store.commit('addPost', response.data.data)

          alert('Публикация была добавлена')
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
      },
      formIsBusy: false,
      hasChannelError: false,
      hasTitleError: false,
      hasMessageError: false,
      hasAttachmentsError: false,
      editor: null,
    }
  }
}
</script>
