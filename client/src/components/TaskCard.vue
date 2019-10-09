<template>
  <v-card flat hover outlined>
    <v-card-title>{{task.name}}</v-card-title>
    <v-card-text>{{task.desc}}</v-card-text>
  </v-card>
</template>

<script>
export default {
  props: ['task']
}
</script>
