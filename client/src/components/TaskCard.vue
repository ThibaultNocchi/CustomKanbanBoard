<template>
  <v-card
    flat
    outlined
  >
    <v-card-text class="headline">{{task.name}}</v-card-text>
    <v-card-text><span v-if="task.description">{{task.description}}</span><span
        v-else
        class="font-italic"
      >No description given.</span></v-card-text>
  </v-card>
</template>

<script>
export default {
  props: ["task"]
};
</script>
