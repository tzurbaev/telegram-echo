import Vue from 'vue'
import Vuex from 'vuex'
import components from './components'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: window.Application.state,

    getters: {
        application(state) {
            return state.application
        },

        routes(state) {
            return state.application.routes
        },

        user(state) {
            return state.user
        },

        canCreatePosts(state) {
            return state.channels.length > 0
        },

        channelsList(state) {
            return state.channels
        },

        botsLists(state) {
            return state.bots
        },

        postsList(state) {
            return state.posts
        }
    },

    mutations: {
        addBot(state, bot) {
            state.bots.push(bot)
        },

        removeBot(state, id) {
            state.bots = state.bots.filter(bot => bot.id !== id)
        },

        addChannel(state, channel) {
            state.channels.push(channel)
        },

        removeChannel(state, id) {
            state.channels = state.channels.filter(channel => channel.id !== id)
        },

        addPost(state, post) {
            state.posts.push(post)
        },

        updatePost(state, payload) {
            const postIndex = state.posts.findIndex(post => post.id === payload.id)

            if (postIndex < 0) {
                return
            }

            state.posts[postIndex] = payload.data
        },

        removePost(state, id) {
            state.posts = state.posts.filter(post => post.id !== id)
        },

        updateUserFields(state, fields) {
            for (let f in fields) {
                state.user[f] = fields[f]
            }
        },
    }
})

const app = new Vue({
    el: '#app',
    store,
    components,
    data: {
        sharedEventBus: new Vue(),
    }
})

window.axios = require('axios')

window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': window.Application.csrfToken,
    'X-Requested-With': 'XMLHttpRequest',
}