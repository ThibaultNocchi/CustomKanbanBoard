import Vue from 'vue'
import Vuex from 'vuex'

import Task from '@/plugins/task.js'
import TasksList from '@/plugins/taskslist.js'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    // board_code: null
    board_code: 'board code',
    lists: [new TasksList('Pending', [new Task('Tache 1', 'Corps 1')])]
  },
  mutations: {
    set_board_code(state, val) {
      state.board_code = val
    }
  },
  actions: {
    reset_connection(context) {
      context.commit('set_board_code', null)
    }
  }
})
