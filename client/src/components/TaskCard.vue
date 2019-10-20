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
        onSubmit="return false"
        v-else
      >
        <v-text-field
          dense
          ref="desc_input"
          placeholder="New description"
          v-model="task.description"
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
      editing_desc: false
    };
  },
  methods: {
    edit_desc() {
      this.editing_desc = true;
      new Promise(resolve => {
        setTimeout(() => {
          resolve();
        });
      }, 200).then(() => {
        this.$refs.desc_input.focus();
      });
    },

    save_desc() {
      this.editing_desc = false
    }
  }
};
</script>
