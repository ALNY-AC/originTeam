<template>
    <div :class="['label','label-'+type]">
        <span class="label-body">
            {{title}}
            <slot></slot>
        </span>
    </div>
</template>
<script>
export default {
  props: {
    title: String,
    type: {
      type: String,
      default: "default"
    }
  },
  data() {
    return {};
  },
  methods: {},
  computed: {},
  mounted() {},
  components: {},
  watch: {}
};
</script>
<style lang="scss" scoped>
@import "label.scss";
</style>