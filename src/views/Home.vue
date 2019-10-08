<template>
  <default-view>
    <list-task-list></list-task-list>
  </default-view>
</template>

<script>
import DefaultView from "@/components/DefaultView.vue";
import ListTaskList from "@/components/ListTaskList.vue";

export default {
  components: { DefaultView, ListTaskList }
};
</script>
