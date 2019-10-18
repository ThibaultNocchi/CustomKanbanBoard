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
      <new-button-input
          txt-btn="New card"
          txt-placeholder="Name"
          txt-hint="New card name."
          txt-error="Card already exists."
          :send="submit_card"
        ></new-button-input>
    </v-col>

  </v-row>
</template>

<script>
import TaskList from "@/components/TaskList.vue";
import NewButtonInput from "@/components/NewButtonInput.vue";

export default {
  components: { TaskList, NewButtonInput },
  methods: {
    submit_card (name) {
      alert(`adding ${name} card`)
    }
  }
};
</script>
