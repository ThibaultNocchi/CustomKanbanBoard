<template>
  <v-card
    flat
    outlined
  >
    <v-card-text class="headline">{{task.name}}</v-card-text>
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
      input_description_disabled: false
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
    }
  }
};
</script>
