<template>

  <draggable
    v-model="cards_list"
    class="row"
    draggable=".card-item"
  >

    <v-col
      cols="12"
      sm="6"
      v-for="card in cards_list"
      :key="card.name"
      class="card-item"
    >
      <task-list :card="card"></task-list>
    </v-col>

    <v-col
      class="d-flex align-center justify-center"
      cols="12"
      sm="6"
      slot="footer"
    >
      <new-button-input
        txt-btn="New card"
        txt-placeholder="Name"
        txt-hint="New card name."
        txt-error="Card already exists."
        :send="submit_card"
      ></new-button-input>
    </v-col>

  </draggable>

</template>

<script>
import TaskList from "@/components/TaskList.vue";
import NewButtonInput from "@/components/NewButtonInput.vue";
import Draggable from "vuedraggable";

export default {
  components: { TaskList, NewButtonInput, Draggable },

  methods: {
    submit_card(name) {
      return this.$store.dispatch("register_card", name);
    }
  },

  computed: {
    cards_list: {
      get() {
        return this.$store.state.cards;
      },
      set(value) {
        this.$store.commit("set_cards", value);
      }
    }
  }
};
</script>
