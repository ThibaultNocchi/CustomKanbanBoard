<template>
  <default-view>
    <v-row class="my-4">
      <v-col
        cols="12"
        xl="3"
        lg="4"
        md="6"
        sm="6"
        v-for="user in $store.state.users"
        :key="user.name"
      >
        <v-card outlined>
          <v-row>
            <v-col
              cols="auto"
              class="ml-3"
            >
              <v-avatar color="grey">{{user.name.charAt(0)}}</v-avatar>
            </v-col>
            <v-col
              cols="auto"
              class="d-flex align-center"
            >
              <div class="title">{{user.name}}</div>
            </v-col>
            <v-spacer></v-spacer>
            <v-col
              cols="auto"
              class="d-flex align-center mr-3"
            >
              <v-btn
                icon
                @click="remove_user(user.name)"
              >
                <v-icon color="red">close</v-icon>
              </v-btn>
            </v-col>
          </v-row>
        </v-card>

      </v-col>

      <v-col
        class="d-flex align-center justify-center"
        cols="12"
        xl="3"
        lg="4"
        md="6"
        sm="6"
      >
        <v-scale-transition hide-on-leave>
          <v-btn
            outlined
            color="primary"
            large
            @click="click_add_button"
            v-show="btn_add"
          >
            <v-icon left>add_circle</v-icon> Add user
          </v-btn>
        </v-scale-transition>
        <v-scale-transition hide-on-leave>
          <v-form
            @submit="submit_new_name"
            onSubmit="return false"
          >
            <v-text-field
              placeholder="New name"
              hint="Add a new user."
              persistent-hint
              autofocus
              :error-messages="new_user_errors"
              ref="new_user_input"
              v-show="!btn_add"
              v-model="new_user_field"
            >
              <template v-slot:append>
                <v-btn
                  icon
                  color="primary"
                  @click="submit_new_name"
                >
                  <v-icon>send</v-icon>
                </v-btn>
                <v-btn
                  icon
                  color="error"
                  @click="clear_new_name"
                >
                  <v-icon>close</v-icon>
                </v-btn>
              </template>
            </v-text-field>
          </v-form>
        </v-scale-transition>
      </v-col>

    </v-row>

  </default-view>
</template>

<script>
import DefaultView from "@/components/DefaultView.vue";

export default {
  components: { DefaultView },

  data() {
    return {
      btn_add: true,
      new_user_field: null,
      new_user_errors: []
    };
  },

  methods: {
    remove_user(name) {
      this.$store.dispatch("remove_user", name).then(
        () => {
          // this.clear_new_name();
        },
        () => {
          // this.new_user_errors = ['Name already taken.']
        }
      );
    },

    click_add_button() {
      this.btn_add = false;
      new Promise(resolve => {
        setTimeout(() => {
          resolve();
        });
      }, 200).then(() => {
        this.$refs.new_user_input.focus();
      });
    },

    clear_new_name() {
      this.btn_add = true;
      this.new_user_field = null;
      this.new_user_errors = [];
    },

    submit_new_name() {
      this.$store.dispatch("register_user", this.new_user_field).then(
        () => {
          this.clear_new_name();
        },
        () => {
          this.new_user_errors = ["Name already taken."];
        }
      );
    }
  }
};
</script>
