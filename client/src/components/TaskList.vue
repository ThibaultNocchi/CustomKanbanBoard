<template>
  <v-card>
    <v-menu
      open-on-click
      close-on-content-click
      bottom
      offset-y
      transition="slide-y-transition"
    >
      <template v-slot:activator="{ on }">
        <v-card-title v-if="!editing_title" class="pa-3"
          ><span @click="edit_title">{{ card.name }}</span>
          <v-spacer></v-spacer>
          <v-icon v-if="draggable_bool" class="drag_handle"
            >drag_indicator</v-icon
          >
          <v-btn icon v-on="on">
            <v-icon>more_vert</v-icon>
          </v-btn>
        </v-card-title>

        <v-card-title v-else>
          <v-form @submit="save_title" onSubmit="return false">
            <v-text-field
              dense
              ref="title_input"
              placeholder="New title"
              :loading="input_title_loading"
              :disabled="input_title_disabled"
              v-model="input_title"
              @blur="save_title"
            ></v-text-field>
          </v-form>
        </v-card-title>
      </template>

      <v-list dense>
        <v-list-item-group>
          <v-list-item @click="dialog_delete = true">
            <v-list-item-icon class="mr-2">
              <v-icon dense color="error darken-1">delete</v-icon>
            </v-list-item-icon>
            <v-list-item-content class="error--text text--darken-1">
              Delete this card
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-menu>

    <v-dialog max-width="500" v-model="dialog_delete">
      <v-card>
        <v-card-title class="headline">Deleting card confirmation</v-card-title>
        <v-card-text
          >You are going to delete the "{{ card.name }}" card and all its
          associated tasks. Are you sure?</v-card-text
        >
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="dialog_delete = false">Cancel</v-btn>
          <v-btn color="error darken-1" @click="delete_card()">Delete</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-divider></v-divider>

    <v-card-text class="pa-2">
      <draggable
        v-model="card.tasks"
        group="tasks"
        handle=".draggable_handle"
        draggable=".task-item"
        @change="switch_task"
        @start="task_moving = true"
        @end="task_moving = false"
      >
        <task-card
          draggable_bool
          class="ma-1 task-item"
          v-for="(task, idx) in card.tasks"
          :key="idx"
          :task="task"
        ></task-card>

        <v-card
          flat
          outlined
          slot="header"
          v-if="!card.tasks.length"
          transition="slide-y-transition"
        >
          <v-card-text class="text-center">No task yet.</v-card-text>
        </v-card>
      </draggable>

      <v-card flat>
        <v-card-text>
          <new-button-input
            txtBtn="New task"
            txtPlaceholder="Task title"
            txtError="Unexpected error."
            iconName="add"
            text
            block
            inputDense
            :send="add_task"
          ></new-button-input>
        </v-card-text>
      </v-card>
    </v-card-text>
  </v-card>
</template>

<script>
import TaskCard from "@/components/TaskCard.vue";
import NewButtonInput from "@/components/NewButtonInput.vue";
import Draggable from "vuedraggable";

export default {
  data() {
    return {
      dialog_delete: false,

      task_moving: false,

      input_title: null,
      editing_title: false,
      input_title_loading: false,
      input_title_disabled: false
    };
  },

  props: {
    card: Object,
    draggable_bool: {
      type: Boolean,
      required: false,
      default: false
    }
  },

  components: { TaskCard, NewButtonInput, Draggable },

  methods: {
    switch_task(type) {
      if ("moved" in type) {
        this.$store.dispatch("switch_tasks", {
          task: type.moved.element,
          newIndex: type.moved.newIndex
        });
      } else if ("added" in type) {
        this.$store.dispatch("switch_tasks_into", {
          newCard: this.card,
          task: type.added.element,
          newIndex: type.added.newIndex
        });
      }
    },

    delete_card() {
      this.$store.dispatch("remove_card", this.card);
    },

    add_task(name) {
      return this.$store.dispatch("register_task", {
        card: this.card,
        name: name
      });
    },

    edit_title() {
      this.editing_title = true;
      this.input_title = this.card.name;
      new Promise(resolve => {
        setTimeout(() => {
          resolve();
        });
      }, 200).then(() => {
        this.$refs.title_input.focus();
      });
    },

    save_title() {
      this.input_title_loading = true;
      this.input_title_disabled = true;
      this.$store
        .dispatch("edit_name_card", {
          card: this.card,
          name: this.input_title
        })
        .finally(() => {
          this.input_title_loading = false;
          this.input_title_disabled = false;
          this.editing_title = false;
        });
    }
  }
};
</script>

<style lang="scss" scoped>
.drag_handle {
  cursor: grab;
}
</style>
