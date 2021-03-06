import Vue from "vue";
import Vuex from "vuex";

import api from "@/plugins/api.js";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    syncing: 0,

    api: new api.API("http://localhost:8081/"),

    board: null,
    users: [],

    cards: [],

    task_colors: api.colors.colors
  },

  getters: {
    user_name: state => id => {
      let res = "";
      state.users.forEach(element => {
        if (element.id === id) res = element.name;
      });
      if (res === "") throw "NoUserId";
      else return res;
    },
    users_select: state => {
      return state.users.map(el => {
        return { text: el.name, value: el.id };
      });
    }
  },

  mutations: {
    set_board(state, val) {
      state.board = val;
    },
    set_users(state, val) {
      state.users = val;
    },
    set_cards(state, val) {
      val.sort((a, b) => {
        a.order - b.order;
      });
      state.cards = val;
    },
    increment_syncing(state) {
      state.syncing++;
    },
    decrement_syncing(state) {
      state.syncing--;
    }
  },

  actions: {
    reset_connection(context) {
      context.commit("set_board", null);
    },

    do_action(
      context,
      { api_method, params, commit_to, sync_counter, require_everything }
    ) {
      if (sync_counter) context.commit("increment_syncing");

      let pro = context.state.api[api_method](params).then(r => {
        if (sync_counter) context.commit("decrement_syncing");
        return context.state.api.parse_response(r);
      });

      if (commit_to) {
        pro = pro.then(function (obj) {
          context.commit(commit_to, obj);
        });
      }

      if (require_everything) {
        pro = pro.then(() => context.dispatch("require_everything"));
      }

      return pro;
    },

    login(context, code) {
      return context.dispatch("do_action", {
        api_method: "login",
        params: { code: code },
        commit_to: "set_board"
      });
    },

    register(context, name) {
      return context.dispatch("do_action", {
        api_method: "register",
        params: { name: name },
        commit_to: "set_board"
      });
    },

    get_users(context) {
      return context.dispatch("do_action", {
        api_method: "get_users",
        params: { board: context.state.board },
        commit_to: "set_users"
      });
    },

    register_user(context, name) {
      return context.dispatch("do_action", {
        api_method: "register_user",
        params: { board: context.state.board, name: name },
        sync_counter: true,
        require_everything: true
      });
    },

    remove_user(context, user) {
      return context.dispatch("do_action", {
        api_method: "remove_user",
        params: { board: context.state.board, user: user },
        sync_counter: true,
        require_everything: true
      });
    },

    get_cards(context) {
      return context.dispatch("do_action", {
        api_method: "get_cards",
        params: { board: context.state.board },
        commit_to: "set_cards"
      });
    },

    register_card(context, name) {
      return context.dispatch("do_action", {
        api_method: "register_card",
        params: { board: context.state.board, name: name },
        sync_counter: true,
        require_everything: true
      });
    },

    edit_name_card(context, { card, name }) {
      if (card.name !== name) {
        return context.dispatch("do_action", {
          api_method: "edit_name_card",
          params: { board: context.state.board, card: card, name: name },
          sync_counter: true,
          require_everything: true
        });
      }
    },

    remove_card(context, card) {
      return context.dispatch("do_action", {
        api_method: "remove_card",
        params: { board: context.state.board, card: card },
        sync_counter: true,
        require_everything: true
      });
    },

    switch_cards(context, { card, newIndex }) {
      return context.dispatch("do_action", {
        api_method: "switch_cards",
        params: { board: context.state.board, card: card, newIndex: newIndex },
        sync_counter: true
      });
    },

    register_task(context, { card, name }) {
      return context.dispatch("do_action", {
        api_method: "register_task",
        params: { board: context.state.board, card: card, name: name },
        sync_counter: true,
        require_everything: true
      });
    },

    edit_description_task(context, { task, description }) {
      if (task.description !== description) {
        return context.dispatch("do_action", {
          api_method: "edit_description_task",
          params: {
            board: context.state.board,
            task: task,
            description: description
          },
          sync_counter: true,
          require_everything: true
        });
      }
    },

    edit_name_task(context, { task, name }) {
      if (task.name !== name) {
        return context.dispatch("do_action", {
          api_method: "edit_name_task",
          params: { board: context.state.board, task: task, name: name },
          sync_counter: true,
          require_everything: true
        });
      }
    },

    edit_task(context, { task, color, users }) {
      users.sort((a, b) => a - b);
      let users_changed = true;
      if (users.length === task.users.length) {
        for (let i = 0; i < users.length; ++i) {
          if (users[i] !== task.users[i]) break;
          if (i === users.length - 1) users_changed = false;
        }
      }

      if (task.color !== color || users_changed) {
        let params = {
          board: context.state.board,
          task: task,
          color: undefined,
          users: undefined
        };
        if (task.color !== color) params.color = color;
        if (users_changed) params.users = users;
        return context.dispatch("do_action", {
          api_method: "edit_task",
          params: params,
          sync_counter: true,
          require_everything: true
        });
      } else {
        return new Promise(resolve => {
          resolve();
        });
      }
    },

    remove_task(context, { task }) {
      return context.dispatch("do_action", {
        api_method: "remove_task",
        params: { board: context.state.board, task: task },
        sync_counter: true,
        require_everything: true
      });
    },

    switch_tasks(context, { task, newIndex }) {
      return context.dispatch("do_action", {
        api_method: "switch_tasks",
        params: { board: context.state.board, task: task, newIndex: newIndex },
        sync_counter: true
      });
    },

    switch_tasks_into(context, { newCard, task, newIndex }) {
      return context.dispatch("do_action", {
        api_method: "switch_tasks_into",
        params: {
          board: context.state.board,
          newCard: newCard,
          task: task,
          newIndex: newIndex
        },
        sync_counter: true
      });
    },

    require_everything(context) {
      let promises = Promise.all([
        context.dispatch("get_users"),
        context.dispatch("get_cards")
      ]);
      return promises;
    }
  }
});
