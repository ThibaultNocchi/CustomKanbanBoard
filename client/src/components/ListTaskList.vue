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
  </v-row>
</template>

<script>
import TaskList from "@/components/TaskList.vue";

export default {
  components: { TaskList }
};
</script>
