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

              <v-scroll-x-transition hide-on-leave>
                <v-form
                  @submit="connect"
                  onSubmit="return false"
                  v-model="login_validity"
                  v-show="!signup_bool"
                >
                  <v-card-text>
                    <v-text-field
                      label="Code"
                      name="board_code"
                      prepend-icon="dashboard"
                      type="text"
                      counter="6"
                      :rules="[rules.code_length]"
                      :loading="loading"
                      :disabled="loading"
                      :error-messages="errors"
                      :success-messages="logged"
                      v-model="local_board_code"
                      @keydown="errors = []"
                    ></v-text-field>
                  </v-card-text>

                  <v-card-actions>
                    <div class="flex-grow-1"></div>
                    <v-btn
                      text
                      @click="signup_bool = true"
                    >New board</v-btn>
                    <v-btn
                      color="primary"
                      @click="connect"
                      :disabled="!login_validity || logged.length > 0"
                    >Connect</v-btn>
                  </v-card-actions>
                </v-form>
              </v-scroll-x-transition>

              <v-scroll-x-transition hide-on-leave>
                <v-form
                  @submit="signup"
                  onSubmit="return false"
                  v-show="signup_bool"
                >
                  <v-card-text>
                    <v-text-field
                      label="Board name"
                      name="board_name"
                      prepend-icon="create"
                      type="text"
                      :loading="loading"
                      :disabled="loading"
                      :rules="[rules.name_length]"
                      :error-messages="signup_errors"
                      :success-messages="signup_success"
                      @keydown="signup_errors = []"
                      v-model="new_board_name"
                    ></v-text-field>
                  </v-card-text>

                  <v-card-actions>
                    <div class="flex-grow-1"></div>
                    <v-btn
                      text
                      @click="signup_bool = false"
                    >Connect</v-btn>
                    <v-btn
                      color="primary"
                      @click="signup"
                      :disabled="loading"
                    >Signup and login</v-btn>
                  </v-card-actions>
                </v-form>
              </v-scroll-x-transition>

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
      local_board_code:
        this.$route.params.code !== undefined ? this.$route.params.code : null,
      new_board_name: null,
      rules: {
        code_length: value =>
          (value !== null && value.length === 6) ||
          "Code must be 6 characters.",
        name_length: value =>
          (value !== null && value.length > 0 && value.length <= 127) ||
          "Board name must have at least a character."
      },
      loading: false,
      errors: [],
      signup_errors: [],
      login_validity: null,
      logged: [],
      signup_success: [],
      signup_bool: false
    };
  },

  methods: {
    connect() {
      this.loading = true;
      this.$store
        .dispatch("login", this.local_board_code)
        .then(
          () => {
            this.loading = false;
            this.errors = [];
            this.logged = ["Board found! Loading..."];
            return this.$store.dispatch("require_everything");
          },
          e => {
            this.loading = false;
            this.logged = [];
            this.errors = ["Invalid code."];
            throw e;
          }
        )
        .then(() => {
          this.$router.push({ name: "home" });
        });
    },

    signup() {
      this.loading = true;
      this.$store
        .dispatch("register", this.new_board_name)
        .then(
          () => {
            this.loading = false;
            this.signup_errors = [];
            this.signup_success = ["Board created! Loading..."];
            return this.$store.dispatch("require_everything");
          },
          e => {
            this.loading = false;
            this.signup_success = [];
            this.errors = ["Error creating board."];
            throw e
          }
        )
        .then(() => {
          this.$router.push({ name: "home" });
        });
    }
  },

  mounted() {
    this.$store.dispatch("reset_connection");
  }
};
</script>
