<template>
  <v-app>

    <v-snackbar
      v-model="snackbar_copy_success"
      color="success"
      :right="true"
      :timeout="5000"
      :top="true"
    >
      Copied!
      <v-btn
        dark
        text
        @click="snackbar_copy_success = false"
      >
        Close
      </v-btn>
    </v-snackbar>

    <v-snackbar
      v-model="snackbar_copy_error"
      color="error"
      :right="true"
      :timeout="5000"
      :top="true"
    >
      Can't copy due to old browser.
      <v-btn
        dark
        text
        @click="snackbar_copy_error = false"
      >
        Close
      </v-btn>
    </v-snackbar>

    <v-navigation-drawer
      app
      disable-route-watcher
      v-model="drawer"
    >

      <template v-slot:prepend>
        <v-list-item selectable>
          <v-list-item-content>
            <v-list-item-title>{{$store.state.board.name}}</v-list-item-title>
            <v-list-item-subtitle>Code: {{$store.state.board.code}} <v-btn
                text
                x-small
                @click="copy_code"
              >Copy</v-btn>
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </template>

      <v-divider></v-divider>

      <v-list nav>

        <v-list-item
          link
          :to="{name: 'home'}"
        >
          <v-list-item-icon>
            <v-icon>home</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Home</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item
          link
          :to="{name: 'users'}"
        >
          <v-list-item-icon>
            <v-icon>person</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Users</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

      </v-list>

      <v-divider></v-divider>

      <v-list
        nav
        dense
      >

        <v-list-item
          link
          @click="copy_link"
        >
          <v-list-item-icon>
            <v-icon>share</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Share link</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

      </v-list>

      <template v-slot:append>

        <v-list nav>
          <v-list-item>
            <v-list-item-icon>
              <v-icon
                color="success darken-2"
                v-if="!$store.state.syncing"
              >done</v-icon>
              <v-progress-circular
                indeterminate
                color="warning darken-2"
                size="20"
                width="2"
                v-if="$store.state.syncing"
              ></v-progress-circular>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title
                class="success--text text--darken-2"
                v-if="!$store.state.syncing"
              >Synced!</v-list-item-title>
              <v-list-item-title
                class="warning--text text--darken-2"
                v-if="$store.state.syncing"
              >Syncing...</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>

        <div class="pa-2">
          <v-btn
            block
            color="primary lighten-1"
            @click="refresh"
            :loading="refreshing"
          >Refresh</v-btn>
        </div>

        <div class="pa-2">
          <v-btn
            block
            color="error lighten-1"
            @click="logout"
          >Logout</v-btn>
        </div>

      </template>

    </v-navigation-drawer>

    <v-app-bar
      app
      collapse-on-scroll
      color="blue-grey lighten-2"
      dense
      dark
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>Super Kanban</v-toolbar-title>
    </v-app-bar>

    <v-content>
      <v-container>
        <slot></slot>
      </v-container>
    </v-content>

  </v-app>
</template>

<script>
import copy from "copy-to-clipboard";
export default {
  data() {
    return {
      drawer: null,
      snackbar_copy_success: false,
      snackbar_copy_error: false,
      refreshing: false
    };
  },

  mounted() {
    if (this.$store.state.board === null) {
      this.$router.replace({ name: "login" });
    }
  },

  methods: {
    logout() {
      this.$router.push({ name: "login" });
    },

    refresh() {
      this.refreshing = true;
      this.$store
        .dispatch("require_everything")
        .finally(() => (this.refreshing = false));
    },

    copy_txt(txt) {
      let copied = copy(txt, { format: "text/plain" });
      if (copied) this.snackbar_copy_success = true;
      else this.snackbar_copy_error = true;
    },

    copy_code() {
      this.copy_txt(this.$store.state.board.code);
    },

    copy_link() {
      let link = this.$router.resolve({
        name: "login_with_code",
        params: { code: this.$store.state.board.code }
      });
      this.copy_txt(window.location.host + link.href);
    }
  }
};
</script>
