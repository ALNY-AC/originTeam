<template>
    <div class="group">
        <div class="group-head">
            <div class="group-title" v-if="title">{{title}}</div>
            <slot name="head"></slot>
        </div>
        <div class="group-body">
            <slot></slot>
        </div>
        <div class="group-footer">
            <slot name="footer"></slot>
        </div>
    </div>
</template>
<script>
export default {
  name: "group",
  props: {
    title: String
  },
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
@import "group.scss";
</style>
<style>

</style>