<template>
  <div class="ui large vertical menu">
    <div class="item">
      <div class="header">Боты</div>
      <div class="menu">
        <a v-for="bot in bots" href="javascript:;" class="item" @click="showCreateBotModal(bot)">
          {{ bot.username }}
        </a>
        <a href="javascript:;" class="item menu-add-item-link" @click="showCreateBotModal">Добавить бота</a>
      </div>
    </div>
    <div class="item">
      <div class="header">Каналы</div>
      <div class="menu">
        <a v-for="channel in channels" href="javascript:;" class="item" @click="showCreateChannelModal(channel)">
          {{ channel.name }}
        </a>
        <a href="javascript:;" class="item menu-add-item-link" @click="showCreateChannelModal">Добавить канал</a>
      </div>
    </div>
    <div class="item">
      <div class="header">{{ user.name }}</div>
      <div class="menu">
        <a href="javascript:;" class="item">Настройки аккаунта</a>
        <a href="javascript:;" class="item" @click="performLogout">Выход</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    channels() {
      return this.$store.getters.channelsList
    },

    bots() {
      return this.$store.getters.botsLists
    },

    user() {
      return this.$store.getters.user
    }
  },

  methods: {
    showCreateBotModal(bot) {
      this.$root.sharedEventBus.$emit('botmodal.show', bot)
    },

    showCreateChannelModal(channel) {
      this.$root.sharedEventBus.$emit('channelmodal.show', channel)
    },

    performLogout() {
      this.$root.sharedEventBus.$emit('logoutform.logout')
    }
  }
}
</script>
