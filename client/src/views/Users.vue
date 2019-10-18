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
        <new-button-input
          txt-btn="New user"
          txt-placeholder="Name"
          txt-hint="New user name."
          txt-error="Name already taken."
          :send="submit_name"
        ></new-button-input>
      </v-col>

    </v-row>

  </default-view>
</template>

<script>
import DefaultView from "@/components/DefaultView.vue";
import NewButtonInput from "@/components/NewButtonInput.vue";

export default {
  components: { DefaultView, NewButtonInput },

  methods: {
    remove_user(name) {
      this.$store.dispatch("remove_user", name);
    },

    submit_name(name) {
      return this.$store.dispatch("register_user", name);
    }
  }
};
</script>
