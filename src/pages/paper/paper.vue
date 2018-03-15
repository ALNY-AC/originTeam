<template>
    <div id="paper" class="paper-view">
        <template>
            <router-view></router-view>
        </template>
    </div>
</template>
<script>
export default {
  name: "paper",
  data() {
    return {};
  },
  methods: {},
  mounted: function() {},
  components: {},
  watch: {}
};
</script>
<style lang="scss" scoped>
@import "paper.scss";
</style>