<template>

  <div class="task-list">

    <left-tool v-if="!taskList.isEdit">

      <template slot="tool">
        <left-tool-item @click="del()" icon="el-icon-delete"></left-tool-item>
        <left-tool-item @click="addTask()" icon="el-icon-circle-plus-outline"></left-tool-item>
        <left-tool-item @click="edit()" icon="el-icon-edit"></left-tool-item>
      </template>

      <div class="task-list-title">
        {{taskList.task_list_title}}
      </div>
      <div class="task-list-info">
        {{taskList.task_list_info}}
      </div>

    </left-tool>

    <template v-if="taskList.isEdit">
      <div style="width:50%">

        <el-input class="m-b-10" autosize v-focus="taskList.isEdit" resize="none" type="textarea" placeholder="" v-model="taskList.task_list_title" size="mini"></el-input>
        <el-input class="m-b-10" autosize resize="none" type="textarea" placeholder="补充说明（可选）" v-model="taskList.task_list_info" size="mini"></el-input>

      </div>
      <el-button size="mini" type="success" @click="saveList()" :loading="isSaveModel">保存修改</el-button>
      <el-button size="mini" type="text" @click="edit(false)">取消</el-button>
    </template>

    <div class="task-box">
      <slot>
        <task v-for="(item,i)  in taskList.tasks" :key="item.task_id" :task="item" :index="i" :list="taskList.tasks"></task>
      </slot>

      <el-button size="mini" class="text-a" type="text" @click="addTask()">添加新任务</el-button>

    </div>

  </div>

</template>
<script>
import $ from "jquery";
export default {
  name: "task-list",
  props: {
    taskList: {
      type: Object,
      default: {
        task_list_title: "",
        task_list_info: "",
        task_list_id: "",
        isEdit: true
      }
    },
    superList: {
      type: Array
    },
    index: {
      type: Number
    },
    projectId: String
  },
  data() {
    return {
      isSaveModel: false
    };
  },
  methods: {
    //添加一个任务
    addTask() {
      var add = { task_title: "", project_id: this.projectId };
      add.task_list_id = this.taskList.task_list_id;
      add.sort = this.taskList.tasks.length;
      this.$post("task/add", { add: add }, res => {
        if (res.res >= 1) {
          res.msg.isEdit = true;
          res.msg.isSaveModel = false;
          this.taskList.tasks.push(res.msg);
          return;
        }
        this.$message({ type: "error", message: "添加任务失败！请重试~" });
      });
    },
    //删除一个任务清单
    del() {
      this.$post(
        "taskList/del",
        {
          where: { task_list_id: this.taskList.task_list_id }
        },
        res => {
          if (res.res >= 1) {
            this.superList.splice(this.index, 1);
            return;
          }
          this.$message({ type: "error", message: "删除失败！请重试~" });
        }
      );
    },
    //编辑任务列表
    edit(is) {
      if (is !== undefined) {
        this.taskList.isEdit = is;
      } else {
        this.taskList.isEdit = !this.taskList.isEdit;
      }
    },
    //保存任务列表
    saveList() {
      var item = this.taskList;

      this.isSaveModel = true;
      item.task_list_title = $.trim(item.task_list_title);
      item.task_list_info = $.trim(item.task_list_info);

      this.save(item, ["task_list_title", "task_list_info"], true, res => {
        this.isSaveModel = false;
        this.taskList.isEdit = false;
      });
    },
    // 保存 通用控制器
    save(item, saveName, isInfo, fun) {
      var save = {};
      if (typeof saveName == "String") {
        save[saveName] = item[saveName];
      } else {
        for (let i = 0; i < saveName.length; i++) {
          save[saveName[i]] = item[saveName[i]];
        }
      }

      var where = {};
      where["task_list_id"] = item["task_list_id"];
      this.$post("taskList/save", { where: where, save: save }, res => {
        if (res.res >= 1 && isInfo) {
          this.$message({ message: "保存成功！", type: "success" });
        }
        if (res.res < 0) {
          this.$message({ message: "保存失败！请重试！", type: "error" });
        }
        if (fun) fun(res);
      });
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
@import "taskList.scss";
</style>
<style>

</style>