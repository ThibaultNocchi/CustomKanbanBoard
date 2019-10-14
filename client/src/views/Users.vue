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
        <v-btn
          outlined
          color="primary"
          large
        >
          <v-icon left>add_circle</v-icon> Add user
        </v-btn>
      </v-col>

    </v-row>

  </default-view>
</template>

<script>
import DefaultView from "@/components/DefaultView.vue";

export default {
  components: { DefaultView },
  methods: {
    remove_user(name) {
      alert(name);
    }
  }
};
</script>
