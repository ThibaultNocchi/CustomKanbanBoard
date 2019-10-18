<template>

  <v-card>
    <v-menu
      open-on-click
      close-on-content-click
      bottom
      offset-y
      transition="slide-y-transition"
    >
      <template v-slot:activator="{on}">
        <v-card-title>{{card.name}}
          <v-spacer></v-spacer>
          <v-btn
            icon
            v-on="on"
          >
            <v-icon>arrow_drop_down</v-icon>
          </v-btn>
        </v-card-title>
      </template>

      <v-list dense>
        <v-list-item-group>

          <v-list-item @click="dialog_delete = true">
            <v-list-item-icon class="mr-2">
              <v-icon
                dense
                color="error darken-1"
              >delete</v-icon>
            </v-list-item-icon>
            <v-list-item-content class="error--text text--darken-1">
              Delete this card
            </v-list-item-content>
          </v-list-item>

        </v-list-item-group>
      </v-list>

    </v-menu>

    <v-dialog
      max-width="500"
      v-model="dialog_delete"
    >
      <v-card>
        <v-card-title class="headline">Deleting card confirmation</v-card-title>
        <v-card-text>You are going to delete the "{{card.name}}" card and all its associated tasks. Are you sure?</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="dialog_delete = false">Cancel</v-btn>
          <v-btn color="error darken-1" @click="delete_card(card.name)">Delete</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-divider></v-divider>

    <v-card-text>

      <!-- <task-card class="ma-1" v-for="(task, idx) in card.tasks" :key="idx" :task="task"></task-card> -->

    </v-card-text>

  </v-card>

</template>

<script>
import TaskCard from "@/components/TaskCard.vue";

export default {
  data() {
    return {
      dialog_delete: false
    };
  },
  props: ["card"],
  components: { TaskCard },
  methods: {
    delete_card (name) {
      console.log(name)
    }
  }
};
</script>
