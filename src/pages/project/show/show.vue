<template>
  <div id="show">
    <!-- <origin-nav></origin-nav> -->
    <panel v-if="isLoadModel">
      <div class="load-box">
        <i class="fa-pulse fa fa-connectdevelop icon"></i>
      </div>
    </panel>

    <panel v-if="!isLoadModel && !isEditModel">
      <!-- <template slot="head">
        <el-button size="mini" icon="el-icon-refresh" @click="update()" :loading="refreshBtnLoad"></el-button>
      </template> -->
      <template slot="head">
        <el-button size="small" @click="$router.push('/project/list')" type="text">返回</el-button>
      </template>

      <div class="project-head">
        <div class="project-head-info">

          <div class="project-head-tool float-right">

            <div class="project-head-tool-item" @click="update()">
              <i :class="!refreshBtnLoad?'el-icon-refresh':'el-icon-loading'"></i>
              <div class="title text-info">
                刷新
              </div>
            </div>
            <div class="project-head-tool-item">
              <span>{{taskCount}}</span>
              <div class="title text-info">
                任务
              </div>
            </div>
            <div class="project-head-tool-item">
              <span>{{taskCountNo}}</span>
              <div class="title text-info">
                待完成
              </div>
            </div>

            <div class="project-head-tool-item" @click="goConf()">
              <i class="fa fa-cog"></i>
              <div class="title text-info">
                设置
              </div>
            </div>

          </div>

          <div class="project-title">
            {{project.project_title}}
          </div>
          <div class="project-info">
            {{project.project_info}}
          </div>

        </div>

      </div>

      <el-progress :percentage="percentage"></el-progress>

      <group title="任务">
        <template slot="head">
          <el-button size="mini" @click="addTaskList">添加清单</el-button>
        </template>

        <!-- <task v-for="(item,i)  in project.tasks" :key="item.task_id" :task="item" :index="i" :list="project.tasks"></task> -->

        <task-list :project-id="project_id" v-for="(list,index) in project.taskList" :index="index" :super-list="project.taskList" :key="list.task_list_id" :task-list="list">

          <draggable v-model="list.tasks" :options="{group:'tasks'}" @end="dragEnd(list.tasks, list, 'taskList')" @add="dragAdd(list.tasks, list, 'taskList')">
            <task v-for="(item,i)  in list.tasks" :key="item.task_id" :task="item" :index="i" :list="list.tasks"></task>
          </draggable>

        </task-list>

      </group>
      <group title="文档">
        <template slot="head">
          <el-button size="mini" @click="addPaper()">创建文档</el-button>
        </template>

        <paper :index="index" :list="project.papers" :paper="item" v-for="(item,index) in project.papers" :key="item.paper_id"></paper>

      </group>

    </panel>

  </div>
</template>
<script>
import draggable from "vuedraggable";
import marked from "marked";
import $ from "jquery";
export default {
  name: "show",
  data() {
    return {
      project_id: "",
      project: {
        task_count: 0,
        task_count_no_ok: 0,
        project_title: "",
        project_info: "",
        tasks: [],
        taskList: [],
        papers: []
      },
      refreshBtnLoad: false,
      //记录用的值
      testValue: "",
      isLoadModel: true,
      isEditModel: false
    };
  },
  methods: {
    //从服务器获取最新数据
    update() {
      this.refreshBtnLoad = true;
      this.$get("project/get", { project_id: this.project_id }, res => {
        this.refreshBtnLoad = false;
        this.isLoadModel = false;
        localStorage.project_title = res.msg.project_title;
        if (res.res >= 1) {
          this.buliderList(res.msg);
        }
        if (res.res < 0) {
        }
      });
    },
    //构建基本列表
    buliderList(list) {
      // for (let i = 0; i < list.tasks.length; i++) {
      //   const task = list.tasks[i];
      //   task.isEdit = false;
      //   task.isSaveModel = false;
      //   task.sort = parseInt(task.sort);
      // }
      for (let j = 0; j < list.taskList.length; j++) {
        const taskList = list.taskList[j];
        taskList.isEdit = false;
        taskList.isSaveModel = false;

        for (let l = 0; l < taskList.tasks.length; l++) {
          const task_l = taskList.tasks[l];
          task_l.isEdit = false;
          task_l.isSaveModel = false;
          task_l.sort = parseInt(task_l.sort);
        }
      }
      this.project = list;
    },

    //添加一个任务列表
    addTaskList() {
      var add = {
        task_list_title: "",
        project_id: this.project_id,
        sort: this.project.taskList.length
      };
      this.$post("taskList/add", { add: add }, res => {
        if (res.res >= 1) {
          res.msg.tasks = [];
          res.msg.isEdit = true;
          res.msg.isSaveModel = false;
          this.project.taskList.push(res.msg);
          return;
        }
        this.$message({ type: "error", message: "添加任务清单失败！请重试~" });
      });
    },
    //添加一个文章
    addPaper() {
      var add = {
        project_id: this.project_id
      };
      this.$post("paper/add", { add: add }, res => {
        if (res.res >= 1) {
          this.project.papers.unshift(res.msg);
          return;
        }

        this.$message({ type: "error", message: "添加文章失败！请重试~" });
      });
    },

    // 保存 通用控制器
    save(item, saveName, isInfo, isValidate, name, fun) {
      if (isValidate && item[saveName] == this.testValue) return;

      var save = {};
      save[saveName] = item[saveName];
      var where = {};
      where[name + "_id"] = item[name + "_id"];
      this.$post(name + "/save", { where: where, save: save }, res => {
        if (res.res >= 1 && isInfo) {
          this.$message({ message: "保存成功！", type: "success" });
        }
        if (res.res < 0) {
          this.$message({ message: "保存失败！请重试！", type: "error" });
        }
        if (fun) fun(res);
      });
    },
    //拖拽完成的时候
    dragEnd(list, parent, type) {
      for (let i = 0; i < list.length; i++) {
        if (list[i].sort != i) {
          list[i].sort = i;
          console.log(list[i]);
          this.save(list[i], "sort", false, false, "task");
        }
      }
    },
    //拖拽添加到一个新列表的时候
    dragAdd(list, parent, type) {
      for (let i = 0; i < list.length; i++) {
        if (list[i].sort != i) {
          list[i].sort = i;
          this.save(list[i], "sort", false, false, "task");
        }

        if (type == "project") {
          if (list[i]["task_list_id"] != "0") {
            list[i]["task_list_id"] = "0";
            this.save(list[i], "task_list_id", false, false, "task");
          }
        }
        if (type == "taskList") {
          if (list[i]["task_list_id"] != parent.task_list_id) {
            list[i]["task_list_id"] = parent.task_list_id;
            this.save(list[i], "task_list_id", false, false, "task");
          }
        }
      }
    },

    //去设置项目页面
    goConf() {
      this.$router.push({
        name: "projectConf",
        query: {
          project_id: this.project_id
        }
      });
    },
    compiledMarkdown(str) {
      if (str == null) return "";
      return marked(str, { sanitize: true });
    },
    //获得总任务量
    getTaskCount() {
      var project = this.project;
      var count = 0;
      for (let i = 0; i < project.taskList.length; i++) {
        const taskList = project.taskList[i];
        count += taskList.tasks.length;
      }
      return count;
    },
    // 未完成任务
    getTaskCountNo() {
      var project = this.project;
      var count = 0;

      for (let i = 0; i < project.taskList.length; i++) {
        const taskList = project.taskList[i];
        for (let j = 0; j < taskList.tasks.length; j++) {
          const task = taskList.tasks[j];
          if (task.is_ok == 0 && task.task_title != "----") {
            count++;
          }
        }
      }
      return count;
    }
  },
  mounted() {
    this.refreshBtnLoad = true;
    this.isLoadModel = true;
    if (this.$route.query["project_id"] == null) {
      if (localStorage.project_id == null) {
        this.$router.go(-1);
        return;
      } else {
        this.project_id = localStorage.project_id;
      }
    } else {
      this.project_id = this.$route.query["project_id"];
    }
    localStorage.project_id = this.project_id;
    this.update();
  },
  components: {
    draggable
  },
  //
  computed: {
    percentage() {
      //完成率=实际完成/总任务*100%;
      var rate =
        (this.getTaskCount() - this.getTaskCountNo()) /
        this.getTaskCount() *
        100;
      if (isNaN(rate)) return 0;

      return Math.round(rate);
    },
    taskCount() {
      return this.getTaskCount();
    },
    taskCountNo() {
      return this.getTaskCountNo();
    }
  },
  watch: {}
};
</script>
<style lang="scss" >
@import "show.scss";
</style>