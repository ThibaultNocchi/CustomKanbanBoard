<template>
  
  <v-card>
    <v-card-title>{{list.name}}</v-card-title>
    <v-divider></v-divider>
    
    <v-card-text>

      <task-card class="ma-1" v-for="(task, idx) in list.tasks" :key="idx" :task="task"></task-card>

    </v-card-text>

  </v-card>

</template>

<script>
import TaskCard from '@/components/TaskCard.vue'

export default {
  props: ['list'],
  components: {TaskCard}
}
</script>
