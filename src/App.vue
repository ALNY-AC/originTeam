<template>
  <div id="App" class="">

    <!-- <origin-nav></origin-nav> -->
    <template>
      <router-view class="router-view"></router-view>
    </template>

  </div>
</template>
<script>
export default {
  name: "App",
  data() {
    return {};
  },
  methods: {},
  mounted: function() {},
  components: {},
  watch: {}
};
</script>
<style lang="scss" >
@import "pages.scss";
</style>
<style lang="scss" scoped>
@import "App.scss";
</style> 
