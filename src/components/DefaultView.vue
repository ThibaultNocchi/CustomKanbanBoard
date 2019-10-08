<template>
  <v-app>

    <v-navigation-drawer app disable-route-watcher v-model="drawer"></v-navigation-drawer>

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
  data () {
    return {
      drawer: null
    }
  },
  mounted () {
    if(this.$store.state.board_code === null) {
      this.$router.replace('/')
    }
  }
}
</script>
