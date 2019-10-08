<template>
  
  <v-card>
    <v-card-title>{{title}}</v-card-title>
    <v-divider></v-divider>
    
    <v-card-text>

      <task-card class="ma-1" v-for="i in 4" :key="i"></task-card>

    </v-card-text>

  </v-card>

</template>

<script>
import TaskCard from '@/components/TaskCard.vue'

export default {
  computed: {
    title() {
      if (this.listType === 'pending') return 'Pending'
      if (this.listType === 'ready') return 'Ready'
      if (this.listType === 'ongoing') return 'Ongoing'
      if (this.listType === 'done') return 'Done'
      return ''
    }
  },
  props: {
    listType: {
      type: String,
      validator: (value) => (['pending', 'ready', 'ongoing', 'done'].indexOf(value) !== -1),
      default: 'pending'
    }
  },
  components: {TaskCard}
}
</script>
