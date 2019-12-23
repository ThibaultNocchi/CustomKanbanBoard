<template>
  <v-card flat outlined :color="color_to_use ? `${color_to_use}` : ''">
    <v-card-text class="pa-3 body-2 font-weight-regular">
      <v-row v-if="!editing_title" no-gutters>
        <v-col
          @click="edit_title"
          class="py-0 font-weight-bold align-center d-flex"
          cols="10"
        >
          <div class="text-truncate">{{ task.name }}</div>
        </v-col>

        <v-col class="py-0 text-right">
          <v-icon v-if="draggable_bool" small class="draggable_handle"
            >drag_indicator</v-icon
          >

          <v-menu
            open-on-click
            close-on-content-click
            bottom
            offset-y
            transition="slide-y-transition"
          >
            <template v-slot:activator="{ on }">
              <v-btn icon x-small v-on="on">
                <v-icon>more_vert</v-icon>
              </v-btn>
            </template>
            <v-list dense class="body-2">
              <v-list-item-group>
                <v-list-item @click="edit_task">
                  <v-list-item-icon class="mr-2">
                    <v-icon dense>settings</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                    Settings
                  </v-list-item-content>
                </v-list-item>

                <v-list-item @click="dialog_delete = true">
                  <v-list-item-icon class="mr-2">
                    <v-icon dense color="error darken-1">delete</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content class="error--text text--darken-1">
                    Delete this task
                  </v-list-item-content>
                </v-list-item>
              </v-list-item-group>
            </v-list>
          </v-menu>

          <v-dialog max-width="500" v-model="dialog_delete">
            <v-card>
              <v-card-title class="headline"
                >Deleting task confirmation</v-card-title
              >
              <v-card-text
                >You are going to delete the "{{ task.name }}" task. Are you
                sure?</v-card-text
              >
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text @click="dialog_delete = false">Cancel</v-btn>
                <v-btn color="error darken-1" @click="delete_task()"
                  >Delete</v-btn
                >
              </v-card-actions>
            </v-card>
          </v-dialog>

          <v-dialog max-width="700" v-model="dialog_settings" persistent>
            <v-card>
              <v-card-title>Task settings</v-card-title>
              <v-divider></v-divider>

              <v-card-text>
                <v-form onSubmit="return false">
                  <v-row>
                    <v-col>
                      <p class="subtitle-1 mb-0">Users :</p>
                      <v-autocomplete
                        :items="$store.getters.users_names"
                        multiple
                        chips
                        clearable
                        deletable-chips
                      ></v-autocomplete>
                    </v-col>
                  </v-row>

                  <v-row>
                    <v-col>
                      <p class="subtitle-1 mb-0">Task color :</p>
                      <swatches
                        v-model="custom_color"
                        :colors="Object.values($store.state.task_colors)"
                        inline
                        show-border
                      />
                    </v-col>
                  </v-row>
                </v-form>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text :disabled="settings_loading" @click="close_settings"
                  >Cancel</v-btn
                >
                <v-btn
                  depressed
                  color="primary"
                  :loading="settings_loading"
                  @click="save_settings"
                  >Save</v-btn
                >
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-col>
      </v-row>

      <v-row v-else>
        <v-col class="py-0">
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
        </v-col>
      </v-row>

      <v-divider class="mt-2 mb-1"></v-divider>

      <v-row v-if="!editing_desc">
        <v-col class="pb-0 pt-1" @click="edit_desc">
          <span v-if="task.description" v-html="parsed_description"></span>
          <span v-else class="font-italic">No description given.</span>
        </v-col>
      </v-row>

      <v-row v-else>
        <v-col class="pb-0 pt-1">
          <v-form @submit="save_desc" onSubmit="return false">
            <v-textarea
              auto-grow
              rows="1"
              ref="desc_input"
              placeholder="New description"
              :loading="input_description_loading"
              :disabled="input_description_loading"
              v-model="input_desc"
              @blur="save_desc"
            >
              <template v-slot:append>
                <v-btn
                  icon
                  color="primary"
                  :disabled="input_description_loading"
                  @click="save_desc"
                >
                  <v-icon>send</v-icon>
                </v-btn>
              </template>
            </v-textarea>
          </v-form>
        </v-col>
      </v-row>

      <v-divider class="mt-2 mb-1"></v-divider>

      <!-- USERS IN TASKS -->
      <v-row v-if="task.users.length === 0">
        <v-col class="pb-0 pt-1 caption">
          No user assigned to task.
        </v-col>
      </v-row>

      <v-row v-else>
        <v-col
          cols="auto"
          class="pb-0 pt-1"
          v-for="(name, idx) in user_names"
          :key="idx"
        >
          <v-tooltip top>
            <template v-slot:activator="{ on }">
              <div v-on="on">
                <user-avatar :user_name="name"></user-avatar>
              </div>
            </template>
            <span>{{ name }}</span>
          </v-tooltip>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
import swatches from "vue-swatches";
import "vue-swatches/dist/vue-swatches.min.css";
import UserAvatar from "@/components/UserAvatar.vue";

export default {
  props: {
    task: Object,
    draggable_bool: {
      type: Boolean,
      required: false,
      default: false
    }
  },
  components: { swatches, UserAvatar },
  computed: {
    color_to_use() {
      return this.custom_color || this.task.color;
    },
    parsed_description() {
      return this.task.description.replace(/(?:\r\n|\r|\n)/g, "<br />");
    },
    user_names() {
      return this.task.users.map(el => this.$store.getters.user_name(el));
    }
  },
  data() {
    return {
      editing_desc: false,
      input_desc: null,
      input_description_loading: false,

      editing_title: false,
      input_title: null,
      input_title_loading: false,
      input_title_disabled: false,

      dialog_delete: false,
      dialog_settings: false,

      custom_color: null,

      settings_loading: false
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
      this.$store
        .dispatch("edit_description_task", {
          task: this.task,
          description: this.input_desc
        })
        .finally(() => {
          this.input_description_loading = false;
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
      if (
        this.task.color === undefined ||
        this.custom_color.toLowerCase() !== this.task.color.toLowerCase()
      ) {
        return this.$store
          .dispatch("edit_color_task", {
            task: this.task,
            color: this.custom_color
          })
          .catch(() => {});
      } else {
        return new Promise(resolve => {
          resolve();
        });
      }
    },

    edit_task() {
      this.custom_color = this.task.color;
      this.dialog_settings = true;
    },

    close_settings() {
      this.custom_color = null;
      this.settings_loading = false;
      this.dialog_settings = false;
    },

    save_settings() {
      this.settings_loading = true;
      Promise.all([this.save_color()]).then(() => {
        this.close_settings();
      });
    }
  }
};
</script>

<style lang="scss" scoped>
.draggable_handle {
  cursor: grab;
}
</style>
