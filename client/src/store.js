import Vue from 'vue'
import Vuex from 'vuex'

import api from '@/plugins/api.js'

Vue.use(Vuex)

export default new Vuex.Store({

  state: {

    syncing: 0,

    api: new api.API('http://super.kanban:8372/'),

    board: null,
    users: [],

    cards: []
  },

  mutations: {
    set_board(state, val) {
      state.board = val
    },
    set_users(state, val) {
      state.users = val
    },
    set_cards(state, val) {
      val.sort((a, b) => { a.order - b.order })
      state.cards = val
    },
    increment_syncing(state) {
      state.syncing++
    },
    decrement_syncing(state) {
      state.syncing--
    }
  },

  actions: {

    reset_connection(context) {
      context.commit('set_board', null)
    },

    do_action(context, { api_method, params, commit_to, sync_counter, require_everything }) {

      if (sync_counter) context.commit('increment_syncing')

      let pro = context.state.api[api_method](params)
        .then(r => { if (sync_counter) context.commit('decrement_syncing'); return context.state.api.parse_response(r) })

      if (commit_to) {
        pro.then(function (obj) { context.commit(commit_to, obj) },
          function (exc) { console.log(exc); throw exc })
      } else {
        pro.catch(exc => { console.log(exc); throw exc })
      }

      if (require_everything) {
        pro.then(() => context.dispatch('require_everything'), exc => { console.log(exc); throw exc }).then(() => null, exc => { console.log(exc); throw exc })
      }

      return pro
    },

    login(context, code) {
      return context.dispatch('do_action', { api_method: 'login', params: { code: code }, commit_to: 'set_board' })
    },

    register(context, name) {
      return context.dispatch('do_action', { api_method: 'register', params: { name: name }, commit_to: 'set_board' })
    },

    get_users(context) {
      return context.dispatch('do_action', { api_method: 'get_users', params: { board: context.state.board }, commit_to: 'set_users' })
    },

    register_user(context, name) {
      return context.dispatch('do_action', { api_method: 'register_user', params: { board: context.state.board, name: name }, sync_counter: true, require_everything: true })
    },

    remove_user(context, user) {
      return context.dispatch('do_action', { api_method: 'remove_user', params: { board: context.state.board, user: user }, sync_counter: true, require_everything: true })
    },

    get_cards(context) {
      return context.dispatch('do_action', { api_method: 'get_cards', params: { board: context.state.board }, commit_to: 'set_cards' })
    },

    register_card(context, name) {
      return context.dispatch('do_action', { api_method: 'register_card', params: { board: context.state.board, name: name }, sync_counter: true, require_everything: true })
    },

    remove_card(context, card) {
      return context.dispatch('do_action', { api_method: 'remove_card', params: { board: context.state.board, card: card }, sync_counter: true, require_everything: true })
    },

    switch_cards(context, { card1, card2 }) {
      return context.dispatch('do_action', { api_method: 'switch_cards', params: { board: context.state.board, card1: card1, card2: card2 }, sync_counter: true })
    },

    register_task(context, { card, name }) {
      return context.dispatch('do_action', { api_method: 'register_task', params: { board: context.state.board, card: card, name: name }, sync_counter: true, require_everything: true })
    },

    require_everything(context) {
      let promises = Promise.all([context.dispatch('get_users'), context.dispatch('get_cards')]);
      return promises;
    }

  }
})
