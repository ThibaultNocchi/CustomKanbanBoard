<template>
  
  <v-card>
    <v-card-title>{{card.name}}</v-card-title>
    <v-divider></v-divider>
    
    <v-card-text>

      <!-- <task-card class="ma-1" v-for="(task, idx) in card.tasks" :key="idx" :task="task"></task-card> -->

    </v-card-text>

  </v-card>

</template>

<script>
import TaskCard from '@/components/TaskCard.vue'

export default {
  props: ['card'],
  components: {TaskCard}
}
</script>
