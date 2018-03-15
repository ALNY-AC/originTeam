<template>
    <div :class="['panel clearfix','panel-'+type]">
        <div class="panel-head">
            <slot name="head"></slot>
        </div>
        <div class="panel-body">
            <slot></slot>
        </div>
        <div class="panel-footer">
            <slot name="footer"></slot>
        </div>
    </div>
</template>
<script>
export default {
  props: {
    type: {
      type: String,
      default: ""
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
@import "panel.scss";
</style>