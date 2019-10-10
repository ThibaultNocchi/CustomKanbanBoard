<template>
  <v-app>
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
                <v-form @submit="connect" onSubmit="return false" v-model="form_validity">
                  <v-text-field
                    label="Code"
                    name="board_code"
                    prepend-icon="dashboard"
                    type="text"
                    autofocus
                    counter="6"
                    :rules="[rules.length]"
                    :loading="connecting"
                    :disabled="connecting"
                    :error-messages="errors"
                    :success-messages="logged"
                    v-model="local_board_code"
                    @keydown="errors = []"
                  ></v-text-field>
                </v-form>
              </v-card-text>

              <v-card-actions>
                <div class="flex-grow-1"></div>
                <v-btn
                  color="primary"
                  @click="connect"
                  :disabled="!form_validity || logged.length > 0"
                >Connect</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      local_board_code: null,
      rules: {
        length: value => (value !== null && value.length === 6) || 'Code must be 6 characters.'
      },
      connecting: false,
      errors: [],
      form_validity: null,
      logged: []
    };
  },

  methods: {
    connect() {
      this.connecting = true
      this.$store.dispatch("login", this.local_board_code).then(
        () => {
          this.connecting = false
          this.errors = []
          this.logged = ['Board found! Loading...']
          new Promise(resolve => setTimeout(resolve, 1500)).then(() => {this.$router.push("home")})
        },
        () => {
          this.connecting = false
          this.logged = []
          this.errors = ['Invalid code.']
        }
      );
    }
  },

  mounted() {
    this.$store.dispatch("reset_connection");
  }
};
</script>
