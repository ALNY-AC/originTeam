<template>
  <div id="taskInfo" class="task">
    <panel>asd</panel>
  </div>
</template>
<script>
export default {
  name: "taskInfo",
  data() {
    return {
      task_id: ""
    };
  },
  methods: {
    update() {
      this.$get("task/get", { where: { task_id: this.task_id } }, res => {
        console.log(res);
        if (res.res >= 1) {
        }
        if (res.res == 0) {
        }
        if (res.res < 0) {
        }
      });
    }
  },
  computed: {},
  mounted() {
    if (this.$route.params["task_id"] == null) {
      if (localStorage.task_id == null) {
        this.$router.go(-1);
        return;
      } else {
        this.task_id = localStorage.task_id;
      }
    } else {
      this.task_id = this.$route.params["task_id"];
    }
    localStorage.task_id = this.task_id;
    this.update();
  },
  components: {},
  watch: {}
};
</script>
<style lang="scss" scoped>
@import "task.scss";
</style>