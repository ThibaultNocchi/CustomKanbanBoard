<template>
  <v-row dense>
    <v-col
      sm="6"
      cols="12"
      v-for="list in $store.state.lists"
      :key="list.name"
    >
      <task-list :list="list"></task-list>
    </v-col>
  </v-row>
</template>

<script>
import TaskList from "@/components/TaskList.vue";

export default {
  components: { TaskList }
};
</script>
