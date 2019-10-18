<template>
  <v-row dense>

    <v-col
      sm="6"
      cols="12"
      v-for="card in $store.state.cards"
      :key="card.name"
    >
      <task-list :card="card"></task-list>
    </v-col>

    <v-col
      class="d-flex align-center justify-center"
      cols="12"
      sm="6"
    >
      <v-scale-transition hide-on-leave>
        <v-btn
          outlined
          color="primary"
          large
          @click="new_card_btn"
          v-show="btn_add"
        >
          <v-icon left>add_circle</v-icon>New card
        </v-btn>
      </v-scale-transition>
      <v-scale-transition hide-on-leave>
        <v-form
          @submit="new_card_submit"
          onSubmit="return false"
        >
          <v-text-field
            placeholder="New card name"
            hint="Add a new card."
            persistent-hint
            autofocus
            :error-messages="new_card_errors"
            ref=""
            v-show="!btn_add"
            v-model="new_card_value"
          >
            <template v-slot:append>
              <v-btn
                icon
                color="primary"
                @click="new_card_submit"
              >
                <v-icon>send</v-icon>
              </v-btn>
              <v-btn
                icon
                color="error"
                @click="new_card_clear"
              >
                <v-icon>close</v-icon>
              </v-btn>
            </template>
          </v-text-field>
        </v-form>
      </v-scale-transition>
    </v-col>

  </v-row>
</template>

<script>
import TaskList from "@/components/TaskList.vue";

export default {
  components: { TaskList },

  data() {
    return {
      btn_add: true,

      new_card_errors: [],
      new_card_value: null
    };
  },

  methods: {
    new_card_btn() {
      this.btn_add = false;
    },

    new_card_submit() {
      alert("submitting new card");
    },

    new_card_clear() {
      this.btn_add = true
    }
  }
};
</script>
