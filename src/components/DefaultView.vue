<template>
  <v-app>

    <v-navigation-drawer
      app
      disable-route-watcher
      v-model="drawer"
    >

      <template v-slot:prepend>
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title>Board name</v-list-item-title>
            <v-list-item-subtitle>{{$store.state.board_code}}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </template>

      <v-divider></v-divider>

      <template v-slot:append>
        <div class="pa-2">
          <v-btn block @click="logout">Logout</v-btn>
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

  </v-app>
</template>

<script>
export default {

  data() {
    return {
      drawer: null
    };
  },

  mounted() {
    if (this.$store.state.board_code === null) {
      this.$router.replace("/");
    }
  },

  methods: {
    logout () {
      this.$router.push('/')
    }
  }

};
</script>
