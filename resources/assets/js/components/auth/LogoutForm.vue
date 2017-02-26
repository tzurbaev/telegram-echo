<template>
  <div class="logout-form-component">
    <form method="POST" :action="logoutUrl" id="globalLogoutForm">
      <input type="hidden" name="_token" :value="csrfToken">
    </form>
  </div>
</template>

<script>
export default {
  props: ['logout-url', 'csrf-token'],

  mounted() {
    this.$root.sharedEventBus.$on('logoutform.logout', () => {
      this.logout()
    })
  },

  methods: {
    logout() {
      document.getElementById('globalLogoutForm').submit()
    }
  }
}
</script>
