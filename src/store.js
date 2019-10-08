import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    // board_code: null
    board_code: 'board code',
    lists: [
      {name: 'Pending',
      tasks: [{
        name: 'Tache 1',
        desc: 'Faire ceci'
      }]}
    ]
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
