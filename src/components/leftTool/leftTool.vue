<template>
    <div class="left-tool">

        <div class="left-tool-box">
            <div class="left-tool-list">
                <slot name="tool"></slot>
            </div>
        </div>
        <div class="left-view">
            <slot></slot>
        </div>

    </div>
</template>
<script>
export default {
  name: "left-tool",
  props: {},
  data() {
    return {};
  },
  methods: {},
  computed: {},
  mounted() {},
  destroyed() {},
  components: {},
  watch: {}
};
</script>
<style lang="scss" scoped>
@import "leftTool.scss";
</style>
<style>

</style>