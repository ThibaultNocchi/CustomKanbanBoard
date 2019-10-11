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
        <v-list-item>
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

      <template v-slot:append>
        <div class="pa-2">
          <v-btn
            block
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
export default {
  data() {
    return {
      drawer: null,
      snackbar_copy_success: false,
      snackbar_copy_error: false
    };
  },

  mounted() {
    if (this.$store.state.board === null) {
      this.$router.replace("/");
    }
  },

  methods: {
    logout() {
      this.$router.push({name: 'login'});
    },

    copy_code() {
      navigator.clipboard
        .writeText(this.$store.state.board.code)
        .then(() => {this.snackbar_copy_success = true}, () => {this.snackbar_copy_error = true});
    }
  }
};
</script>
