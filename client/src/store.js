import Vue from 'vue'
import Vuex from 'vuex'

import Task from '@/plugins/task.js'
import TasksList from '@/plugins/taskslist.js'

import api from '@/plugins/api.js'

Vue.use(Vuex)

export default new Vuex.Store({

  state: {
    api: new api.API('http://super.kanban/'),
    // board_code: 'board code',
    board: null,
    lists: [new TasksList('Pending', [new Task('Tache 1', 'Corps 1')])]
  },

  mutations: {
    set_board(state, val) {
      state.board = val
    }
  },

  actions: {

    reset_connection(context) {
      context.commit('set_board', null)
    },

    login(context, code) {
      return context.state.api.login(code)
              .then(function (r) { return context.state.api.parse_response(r) })
              .then(function (board) { context.commit('set_board', board) },
                    function (exc) { console.log(exc); throw exc })
    }

  }
})
