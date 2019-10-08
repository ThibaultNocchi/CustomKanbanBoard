<template>
  <default-view>
    <v-row dense>
      <v-col sm="6" cols="12">
        <task-list list-type="ready"></task-list>
      </v-col>
      <v-col sm="6" cols="12">
        <task-list list-type="pending"></task-list>
      </v-col>
      <v-col sm="6" cols="12">
        <task-list list-type="ongoing"></task-list>
      </v-col>
      <v-col sm="6" cols="12">
        <task-list list-type="done"></task-list>
      </v-col>
    </v-row>
  </default-view>
</template>

<script>
import DefaultView from "@/components/DefaultView.vue";
import TaskList from "@/components/TaskList.vue";

export default {
  components: { DefaultView, TaskList }
};
</script>
