<template>
  <div class="left-tool-item" @click="click">
    <i :class="['icon',icon]"></i>
    <slot></slot>
  </div>
</template>
<script>
export default {
  name: "left-tool-item",
  props: {
    icon: String
  },
  data() {
    return {};
  },
  methods: {
    click() {
      this.$emit("click");
    }
  },
  computed: {},
  mounted() {},
  destroyed() {},
  components: {},
  watch: {}
};
</script>
<style lang="scss" scoped>
@import "leftToolItem.scss";
</style>
<style>

</style>