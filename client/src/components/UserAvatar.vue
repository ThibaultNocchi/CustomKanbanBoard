<template>
  <v-avatar color="grey">{{ user_name.charAt(0) }}</v-avatar>
</template>

<script>
export default {
  props: {
    user_name: String
  }
};
</script>
