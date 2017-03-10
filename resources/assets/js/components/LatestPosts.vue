<template>
  <div class="latest-posts-component">
    <div class="dashboard-header-section">
      <div class="ui grid">
        <div class="eight wide column">
          <h1>Последние записи</h1>
        </div>
        <div class="eight wide right aligned column">
          <a href="javascript:;" :class="createPostButtonClasses" @click="showNewPostDialog">
            <i class="fa fa-plus"></i> Добавить
          </a>
        </div>
      </div>
    </div>
    <div class="posts-list">
      <div class="ui items" v-if="posts.length">
        <post v-for="post in posts" :post="post"></post>
      </div>
      <template v-else>
        <p>Нет записей для отображения.</p>
        <p v-if="!canCreatePosts">Для того, чтобы добавить первую запись, необходимо создать бота и канал.</p>
        <p>
          Ваш текущий часовой пояс: <strong>{{ timezoneName }}</strong>.
          Вы можете изменить его в <a href="javascript:;" @click="showUserProfileModal">настройках аккаунта</a>.
        </p>
      </template>
    </div>
  </div>
</template>

<script>
import Post from './Post.vue'

export default {
  components: {
    Post,
  },

  computed: {
    posts() {
      return this.$store.getters.postsList
    },

    canCreatePosts() {
      return this.$store.getters.canCreatePosts
    },

    createPostButtonClasses() {
      return {
        'ui primary button': true,
        'disabled': !this.canCreatePosts,
      }
    },

    timezoneName() {
      return this.$store.getters.user.timezone_name
    }
  },

  methods: {
    showNewPostDialog() {
      this.$root.sharedEventBus.$emit('postmodal.show')
    },

    showUserProfileModal() {
      this.$root.sharedEventBus.$emit('profilemodal.show')
    },
  },

  data() {
    return {}
  }
}
</script>
