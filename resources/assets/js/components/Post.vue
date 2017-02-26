<template>
  <div class="item">
    <div class="content">
      <div class="header">
        <a href="javascript:;">{{ post.title }}</a> <span>в канале <a href="javascript:;">{{ post.channel_name }}</a></span>
      </div>
      <div class="description">
        {{ post.message }}
      </div>
      <div class="ui divider"></div>
      <div class="extra">
        <p>
          <template v-if="post.scheduled && !post.was_published">
            Эта запись была создана {{ post.created_at }} и будет опубликована {{ post.scheduled_at }}.
          </template>
          <template v-if="!post.scheduled && post.was_published">
            Эта запись была опубликована {{ post.published_at }}.
          </template>
        </p>
        <template v-if="!post.was_published">
          <a href="javascript:;" class="ui default button">
            <i class="fa fa-edit"></i> Правка
          </a>
          <a href="javascript:;" class="ui primary button" v-if="post.scheduled">
            <i class="fa fa-paper-plane"></i> Опубликовать сейчас
          </a>
        </template>
        <a href="javascript:;" class="ui red button">
          <i class="fa fa-trash"></i> Удалить
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['post'],
}
</script>
