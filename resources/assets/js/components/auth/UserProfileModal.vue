<template>
  <div class="user-profile-modal-component">
    <div class="ui modal" id="user-profile-modal">
      <div class="header">Настройки аккаунта</div>
      <div class="content">
        <div class="ui form" :class="{'error': hasFormError}">
          <div class="field" :class="{'error': hasNameError}">
            <label>Имя</label>
            <input type="text" v-model="form.name">
          </div>
          <div class="field" :class="{'error': hasEmailError}">
            <label>E-Mail</label>
            <input type="email" v-model="form.email">
          </div>
          <div class="field" :class="{'error': hasPasswordError}">
            <label>Новый пароль (необязательно)</label>
            <input type="password" v-model="form.password">
          </div>
          <div class="field">
            <label>Подтверждение нового пароля</label>
            <input type="password" v-model="form.password_confirmation">
          </div>
          <div class="field" :class="{'error': hasCurrentPasswordError}">
            <label>Текущий пароль (если меняете пароль)</label>
            <input type="password" v-model="form.current_password">
          </div>
          <div class="field" :class="{'error': hasTimezoneError}">
            <label>Часовой пояс</label>
            <select class="ui fluid dropdown" v-model="form.timezone">
              <option value="">-- Выберите часовой пояс --</option>
              <option :value="tz.id" v-for="tz in timezonesList">{{ tz.name }}</option>
            </select>
          </div>
          <button type="button" class="ui primary button" @click="submitForm" :disabled="formIsBusy">
            <i class="fa fa-save"></i>
            Сохранить
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

    this.$root.sharedEventBus.$on('profilemodal.show', () => {
      if (!this.timezonesListLoaded) {
        return this.loadTimezones(() => {
          this.showModal()
        })
      }

      this.showModal()
    })
  },

  computed: {
    hasFormError() {
      return false
    },

    submitFormUrl() {
      return this.$store.getters.routes.settings.update.url
    },

    timezonesListUrl() {
      return this.$store.getters.routes.settings.timezones.url
    }
  },

  data() {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        current_password: '',
        timezone: '',
      },
      timezonesList: [],
      timezonesListLoaded: false,
      timezonesListLoading: false,
      formIsBusy: false,
      hasNameError: false,
      hasEmailError: false,
      hasPasswordError: false,
      hasPasswordConfirmationError: false,
      hasCurrentPasswordError: false,
      hasTimezoneError: false,
    }
  },

  methods: {
    initModal() {
      window.jQuery('#user-profile-modal').modal({
        closable: false,
        keyboardShortcuts: false,
      })
    },

    showModal() {
      const user = this.$store.getters.user

      console.log(user.timezone)

      this.form.name = user.name
      this.form.email = user.email
      this.form.timezone = user.timezone

      this.resetErrors()

      window.jQuery('#user-profile-modal').modal('show')
    },

    closeModal() {
      window.jQuery('#user-profile-modal').modal('hide')
    },

    loadTimezones(callback) {
      if (this.timezonesListLoading) {
        return
      }

      this.timezonesListLoading = true
      this.timezonesListLoaded = true

      if (typeof callback !== 'function') {
        callback = () => {}
      }

      window.axios
            .get(this.timezonesListUrl)
            .then(response => {
              this.timezonesList = response.data.data
              this.timezonesListLoading = false

              return callback()
            }, error => {
              alert('Не удалось загрузить список часовых поясов')
              this.timezonesListLoading = false

              return callback()
            })
    },

    resetErrors() {
      this.hasNameError = false
      this.hasEmailError = false
      this.hasTimezoneError = false
      this.hasPasswordError = false
      this.hasPasswordConfirmationError = false
      this.hasCurrentPasswordError = false
    },

    validate() {
      this.resetErrors()

      let form = {
        name: this.form.name.trim(),
        email: this.form.email.trim(),
        timezone: this.form.timezone.trim()
      }

      if (!form.name) {
        this.hasNameError = true
        return false
      }

      if (!form.email) {
        this.hasEmailError = true
        return false
      }

      if (!form.timezone) {
        this.hasTimezoneError = true
        return false
      }

      if (!this.form.password) {
        return form;
      }

      if (!this.form.password_confirmation) {
        this.hasPasswordConfirmationError = true
        return false
      } else {
        form.password = this.form.password
        form.password_confirmation = this.form.password_confirmation
      }

      if (!this.form.current_password) {
        this.hasCurrentPasswordError = true
        return false
      } else {
        form.current_password = this.form.current_password
      }

      return form
    },

    submitForm() {
      const form = this.validate()

      if (form === false) {
        return
      }

      this.formIsBusy = true

      window.axios
            .put(this.submitFormUrl, form)
            .then(response => {
              this.formIsBusy = false

              this.$store.commit('updateUserFields', {
                name: this.form.name,
                email: this.form.email,
                timezone: this.form.timezone
              })

              alert('Настройки аккаунта были успешно сохранены!')
              this.closeModal()
            }, error => {
              this.formIsBusy = false
              alert('Не удалось сохранить настройки аккаунта!')
            })
    }
  }
}
</script>
