<template>
  <v-card flat outlined>
    <v-card-text class="subtitle-1">{{task.name}}</v-card-text>
    <v-card-text>{{task.description}}</v-card-text>
  </v-card>
</template>

<script>
export default {
  props: ['task']
}
</script>
