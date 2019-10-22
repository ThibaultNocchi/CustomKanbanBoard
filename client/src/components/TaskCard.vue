<template>
  <v-card
    flat
    outlined
  >
    <v-card-text class="headline">
      <div
        v-if="!editing_title"
        @click="edit_title"
      >{{task.name}}</div>
      <v-form
        @submit="save_title"
        onSubmit="return false"
        v-else
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
    </v-card-text>
    <v-divider></v-divider>
    <v-card-text>

      <div
        v-if="!editing_desc"
        @click="edit_desc"
      >
        <span v-if="task.description">{{task.description}}</span>
        <span
          v-else
          class="font-italic"
        >No description given.</span>
      </div>

      <v-form
        @submit="save_desc"
        onSubmit="return false"
        v-else
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

    </v-card-text>
  </v-card>
</template>

<script>
export default {
  props: ["task"],
  data() {
    return {
      editing_desc: false,
      input_desc: null,
      input_description_loading: false,
      input_description_disabled: false,

      editing_title: false,
      input_title: null,
      input_title_loading: false,
      input_title_disabled: false
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
    }

  }
};
</script>
