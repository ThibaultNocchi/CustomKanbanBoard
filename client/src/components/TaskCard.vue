<template>
  <v-card
    flat
    outlined
    :color="color_to_use ? `${color_to_use}` : ''"
  >

    <v-card-text class="pa-3 body-2 font-weight-regular">

      <v-row v-if="!editing_title">
        <v-col
          @click="edit_title"
          class="py-0 font-weight-bold align-center d-flex"
        >{{task.name}}</v-col>

        <v-col class="py-0 text-right">

          <v-menu
            open-on-click
            close-on-content-click
            bottom
            offset-y
            transition="slide-y-transition"
          >
            <template v-slot:activator="{on}">
              <v-btn
                icon
                x-small
                v-on="on"
              >
                <v-icon>more_vert</v-icon>
              </v-btn>
            </template>
            <v-list
              dense
              class="body-2"
            >
              <v-list-item-group>

                <v-list-item @click="dialog_delete = true">
                  <v-list-item-icon class="mr-2">
                    <v-icon
                      dense
                      color="error darken-1"
                    >delete</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content class="error--text text--darken-1">
                    Delete this task
                  </v-list-item-content>
                </v-list-item>

                <v-list-item @click="dialog_color = true">
                  <v-list-item-icon class="mr-2">
                    <v-icon
                      dense
                      color="primary"
                    >color_lens</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content class="primary--text">
                    Set color
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
              <v-card-title class="headline">Deleting task confirmation</v-card-title>
              <v-card-text>You are going to delete the "{{task.name}}" task. Are you sure?</v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  text
                  @click="dialog_delete = false"
                >Cancel</v-btn>
                <v-btn
                  color="error darken-1"
                  @click="delete_task()"
                >Delete</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>

        </v-col>
      </v-row>

      <v-row v-else>
        <v-col class="py-0">
          <v-form
            @submit="save_title"
            onSubmit="return false"
          >
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
        </v-col>
      </v-row>

      <v-divider class="mt-2 mb-1"></v-divider>

      <v-row v-if="!editing_desc">
        <v-col
          class="pb-0 pt-1"
          @click="edit_desc"
        >
          <span v-if="task.description">{{task.description}}</span>
          <span
            v-else
            class="font-italic"
          >No description given.</span>
        </v-col>
      </v-row>

      <v-row v-else>
        <v-col class="pb-0 pt-1">
          <v-form
            @submit="save_desc"
            onSubmit="return false"
          >
            <v-text-field
              dense
              ref="desc_input"
              placeholder="New description"
              :loading="input_description_loading"
              :disabled="input_description_disabled"
              v-model="input_desc"
              @blur="save_desc"
            ></v-text-field>
          </v-form>
        </v-col>
      </v-row>

    </v-card-text>

    <v-overlay
      absolute
      opacity="0.3"
      :value="dialog_color"
    >
      <v-row>
        <v-col class="d-flex align-center">
          <swatches v-model="custom_color" colors="material-light" />
        </v-col>
        <v-col class="d-flex align-center"><v-btn icon color="success" @click="save_color"><v-icon>done</v-icon></v-btn></v-col>
        <v-col class="d-flex align-center">
          <v-btn
            icon
            color="error"
            @click="dialog_color = false"
          >
            <v-icon>close</v-icon>
          </v-btn>
        </v-col>
      </v-row>
    </v-overlay>

  </v-card>
</template>

<script>
import swatches from "vue-swatches";
import "vue-swatches/dist/vue-swatches.min.css";
export default {
  props: ["task"],
  components: { swatches },
  computed: {
    color_to_use() {
      return this.custom_color || this.task.color
    }
  },
  data() {
    return {
      editing_desc: false,
      input_desc: null,
      input_description_loading: false,
      input_description_disabled: false,

      editing_title: false,
      input_title: null,
      input_title_loading: false,
      input_title_disabled: false,

      dialog_delete: false,
      dialog_color: false,

      custom_color: null
    };
  },
  methods: {
    edit_desc() {
      this.editing_desc = true;
      this.input_desc = this.task.description;
      new Promise(resolve => {
        setTimeout(() => {
          resolve();
        });
      }, 200).then(() => {
        this.$refs.desc_input.focus();
      });
    },

    edit_title() {
      this.editing_title = true;
      this.input_title = this.task.name;
      new Promise(resolve => {
        setTimeout(() => {
          resolve();
        });
      }, 200).then(() => {
        this.$refs.title_input.focus();
      });
    },

    save_desc() {
      this.input_description_loading = true;
      this.input_description_disabled = true;
      this.$store
        .dispatch("edit_description_task", {
          task: this.task,
          description: this.input_desc
        })
        .finally(() => {
          this.input_description_loading = false;
          this.input_description_disabled = false;
          this.editing_desc = false;
        });
    },

    save_title() {
      this.input_title_loading = true;
      this.input_title_disabled = true;
      this.$store
        .dispatch("edit_name_task", {
          task: this.task,
          name: this.input_title
        })
        .finally(() => {
          this.input_title_loading = false;
          this.input_title_disabled = false;
          this.editing_title = false;
        });
    },

    delete_task() {
      this.$store.dispatch("remove_task", { task: this.task });
    },

    save_color() {
      alert(this.custom_color)
      this.dialog_color = false
      this.custom_color = null
    }

  }
};
</script>
