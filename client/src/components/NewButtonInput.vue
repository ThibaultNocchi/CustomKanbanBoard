<template>
  <div class="new_input_button">
    <v-scale-transition hide-on-leave>
      <v-btn
        outlined
        color="primary"
        large
        @click="btn_click"
        v-show="btn_add"
      >
        <v-icon left>add_circle</v-icon>{{txtBtn}}
      </v-btn>
    </v-scale-transition>
    <v-scale-transition hide-on-leave>
      <v-form
        @submit="submit"
        onSubmit="return false"
        v-show="!btn_add"
      >
        <v-text-field
          :placeholder="txtPlaceholder"
          :hint="txtHint"
          persistent-hint
          autofocus
          :error-messages="errors"
          ref="input"
          v-model="input_value"
        >
          <template v-slot:append>
            <v-btn
              icon
              color="primary"
              @click="submit"
            >
              <v-icon>send</v-icon>
            </v-btn>
            <v-btn
              icon
              color="error"
              @click="clear"
            >
              <v-icon>close</v-icon>
            </v-btn>
          </template>
        </v-text-field>
      </v-form>
    </v-scale-transition>
  </div>
</template>

<script>
export default {
  data() {
    return {
      btn_add: true,
      errors: [],
      input_value: null
    };
  },
  props: ["txtBtn", "txtPlaceholder", "txtHint", "txtError", "send"],
  methods: {
    btn_click() {
      this.btn_add = false;
      new Promise(resolve => {
        setTimeout(() => {
          resolve();
        });
      }, 200).then(() => {
        this.$refs.input.focus();
      });
    },

    clear() {
      this.btn_add = true;
      this.input_value = null;
      this.errors = [];
    },

    submit() {
      this.send(this.input_value).then(
        () => {
          this.clear();
        },
        () => {
          this.errors = [this.txtError];
        }
      );
    }
  }
};
</script>