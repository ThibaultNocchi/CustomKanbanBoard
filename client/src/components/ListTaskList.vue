<template>

  <draggable
    v-model="cards_list"
    class="row"
  >
    <task-list
      v-for="card in cards_list"
      :key="card.name"
      :card="card"
      class="col-12 col-sm-6"
    ></task-list>
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
