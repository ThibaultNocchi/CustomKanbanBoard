<template>
  <v-content>
    <v-container
      fluid
      class="fill-height"
    >
      <v-row
        align="center"
        justify="center"
      >
        <v-col
          cols="12"
          sm="8"
          md="4"
        >
          <v-card elevation="12">
            <v-toolbar
              color="primary"
              dark
              flat
            >
              <v-toolbar-title>Super Kanban</v-toolbar-title>
            </v-toolbar>

            <v-card-text>
              <v-form>
                <v-text-field
                  label="Code"
                  name="board_code"
                  prepend-icon="dashboard"
                  type="text"
                  autofocus
                  v-model="local_board_code"
                ></v-text-field>
              </v-form>
            </v-card-text>

            <v-card-actions>
              <div class="flex-grow-1"></div>
              <v-btn
                color="primary"
                @click="connect"
              >Connect</v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </v-content>
</template>

<script>
export default {

  data () {
    return {
      local_board_code: null
    }
  },

  methods: {
    connect() {
      this.$store.commit('set_board_code', this.local_board_code)
    }
  }
};
</script>
