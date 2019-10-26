<template>

  <draggable
    class="scrolling-wrapper-flexbox"
    v-model="cards_list"
    draggable=".card-item"
    @change="cards_switched"
  >

    <task-list
      draggable_ptn
      v-for="card in cards_list"
      :key="card.id"
      :card="card"
      class="card-item ma-2"
      :style="{width: '300px'}"
    ></task-list>

      <new-button-input
        txt-btn="New card"
        txt-placeholder="Name"
        txt-hint="New card name."
        txt-error="Card already exists."
        :send="submit_card"
        large
        outlined
        class="ml-8 my-auto"
        slot="footer"
      ></new-button-input>

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
    },

    cards_switched(type) {
      if ("moved" in type) {
        this.$store.dispatch("switch_cards", {
          card: this.$store.state.cards[type.moved.newIndex],
          newIndex: type.moved.newIndex
        });
      }
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

<style lang="scss" scoped>
.scrolling-wrapper-flexbox {
  display: flex;
  flex-wrap: nowrap;
}
</style>
