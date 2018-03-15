<template>
  <div id="projectList">
    <panel>

      <template solt="head">
        <el-button size="mini" icon="el-icon-plus" @click="addProject()"></el-button>
        <el-button size="mini" icon="el-icon-refresh" @click="update()" :loading="refreshBtnLoad"></el-button>

        <div class="float-right">
          <el-button size="mini" :icon="isList?'el-icon-menu':'fa fa-list'" @click="isList=!isList"></el-button>
        </div>
      </template>

      <div class="project-list">

        <project @on-remove="removeProject" :project="project" :type="isList?'list':'card'" v-for="project in projects" :key="project.project_id">
        </project>

      </div>

    </panel>

  </div>
</template>
<script>
import project from "../../../components/project/project.vue";
import task from "../../../components/task/task.vue";

export default {
  name: "projectList",
  data() {
    return {
      isList: localStorage.project_is_list == "1" ? true : false,
      refreshBtnLoad: false,
      projects: []
    };
  },
  methods: {
    update() {
      this.refreshBtnLoad = true;
      this.$get("project/getList", {}, res => {
        this.refreshBtnLoad = false;
        if (res.res >= 1) {
          this.projects = res.msg;
        }
        if (res.res < 0) {
        }
      });
    },
    addProject() {
      var add = { project_title: "project" };
      this.$post("project/add", { add: add }, res => {
        if (res.res >= 1) {
          res.msg.task_count_no_ok = 0;
          this.projects.push(res.msg);
        }
        if (res.res < 0) {
        }
      });
    },
    removeProject() {
      this.update();
    }
  },
  mounted: function() {
    this.$nextTick(() => {
      this.update();
    });
  },
  components: { project: project, task: task },
  watch: {
    isList(val) {
      localStorage.project_is_list = val ? "1" : "0";
    }
  }
};

var Project = function(conf) {
  this.project_id = "";
  this.title = "project";
  this.tasks = [];
  extend(this, conf);

  this.add = function(task) {
    task.parent = this;
    this.tasks.push(task);
  };
};
var Task = function(conf) {
  this.parent = null;
  this.title = "task";
  extend(this, conf);
};

var extend = function(sub, parent) {
  if (!sub || !parent) return false;
  for (var x in sub) {
    if (parent[x] != null) {
      sub[x] = parent[x];
    }
  }
};
</script>
<style lang="scss" scoped>
@import "list.scss";
</style>