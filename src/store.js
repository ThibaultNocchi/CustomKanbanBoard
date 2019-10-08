import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    board_code: null
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
