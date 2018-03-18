<template>
  <div :class="['task',{'ok':task.is_ok==1}]">

    <left-tool v-if="task">

      <template slot="tool">
        <left-tool-item @click="delTask(task)" icon="el-icon-delete"></left-tool-item>
        <left-tool-item @click="editTask()" icon="el-icon-edit"></left-tool-item>
        <left-tool-item @click="runTask(1)" icon="fa fa-play" v-if="task.is_run == 0"></left-tool-item>
        <left-tool-item @click="runTask(0)" icon="fa fa-pause" v-if="task.is_run == 1"></left-tool-item>
      </template>
      <div class="check-box" @click="setTaskOk()" v-if="task.task_title!='----'">
        <i :class="['fa',{'fa-check-square-o ok':task.is_ok==1},{'fa-square-o no':task.is_ok==0}]"></i>
      </div>

      <div class="task-title">
        <!-- {{task.sort}} -->

        <div v-if="task.isEdit">
          <i :class="'level-head fa fa-circle hig '+levels[task.level]" v-if="task.level>=0" style="vertical-align: top;top:4px"></i>
          <el-input v-focus="task.isEdit" resize="none" type="textarea" :rows="4" v-model="task.task_title" size="mini" style="margin-bottom:10px;width:300px;display: inline-block;"></el-input>

          <div class="level">

            <div class="level-item" v-for="(item,i) in levels" :key="item" @click="setLevel(i)">
              <i :class="'fa fa-circle hig '+item"></i>
            </div>
            <div class="level-item" @click="setLevel(-1)">
              <i class="fa fa-circle-o text-info"></i>
            </div>

          </div>

          <div>
            <el-button size="mini" type="success" @click="saveTask()" :loading="task.isSaveModel">保存修改</el-button>
            <el-button size="mini" type="text" @click="editTask()">取消</el-button>
          </div>

        </div>

        <span @click="showTask(task)" v-if="!task.isEdit && task.task_title!='----'">
          <i :class="'level-head fa fa-circle hig '+levels[task.level]" v-if="task.level>=0"></i>
          <div class="user-img-list" v-if="task.is_run==1">
            <img class="user-img-item" :src="$getUrl(task.user_head)">
          </div>
          <o-label type="primary" v-if="getTaskTitle().tag">{{getTaskTitle().tag}}</o-label>
          <span v-html="getTaskTitle().cue"></span>
          <span>{{getTaskTitle().title}}</span>
        </span>

      </div>

    </left-tool>

    <hr v-if="task.task_title=='----'">

  </div>
</template>
<script>
import $ from "jquery";
export default {
  name: "task",
  props: {
    task: {
      type: Object
    },
    index: Number,
    list: Array
  },
  data() {
    return {
      testValue: "",
      levels: [
        "text-danger",
        "text-warning",
        "text-success",
        "text-a",
        "text-info"
      ]
    };
  },
  methods: {
    //标记为正在进行中/暂停中
    runTask(type) {
      this.$post(
        "task/run",
        { task_id: this.task.task_id, is_run: type },
        res => {
          if (res.res >= 1) {
            this.task.is_run = type;
            this.task.user_head = this.$getUserInfo().user_head;
            return;
          }
          this.$error("操作失败！请重试~");
        }
      );
    },
    //设置级别
    setLevel(level) {
      this.task.level = level;
      this.save(this.task, "level", false, false, res => {
        this.task.isSaveModel = false;
      });
    },
    //保存任务
    saveTask() {
      this.task.isSaveModel = true;
      this.task.task_title = $.trim(this.task.task_title);
      this.save(this.task, "task_title", true, false, res => {
        this.task.isSaveModel = false;
        this.task.isEdit = false;
      });
    },
    //设置任务编辑状态
    editTask() {
      this.task.isEdit = !this.task.isEdit;
      if (!this.task.isEdit) {
        if (this.task.task_title == "") {
          this.delTask();
        }
      }
    },
    //设置一个任务完成状态
    setTaskOk(state) {
      if (state != null) {
        this.task.is_ok = state;
      } else {
        this.task.is_ok = this.task.is_ok == 1 ? 0 : 1;
      }
      this.save(this.task, "is_ok", false, false);
    },
    delTask() {
      this.$post(
        "task/del",
        {
          where: { task_id: this.task.task_id }
        },
        res => {
          if (res.res >= 1) {
            this.$emit("on-remove", this.task, this.index, this.list);
            this.list.splice(this.index, 1);
            return;
          }
          this.$message({ type: "error", message: "删除失败！请重试~" });
        }
      );
    },
    save(item, saveName, isInfo, isValidate, fun) {
      if (isValidate && item[saveName] == this.testValue) return;
      var save = {};
      save[saveName] = item[saveName];
      var where = {};
      where["task_id"] = item["task_id"];
      this.$post("task/save", { where: where, save: save }, res => {
        if (fun) fun(res);
        if (res.res >= 1 && isInfo) {
          this.$message({ message: "保存成功！", type: "success" });
        }
        if (res.res < 0) {
          this.$message({ message: "保存失败！请重试！", type: "error" });
        }
      });
    },
    //点击任务后显示任务，跳转页面显示
    showTask() {
      this.$router.push({
        name: "task",
        query: { task_id: this.task.task_id }
      });
    },
    getTaskTitle() {
      var title = this.task.task_title;
      var obj = {
        tag: "",
        cue: "",
        title: "",
        level: ""
      };
      var str = title.match(/#(\S*)#/);
      if (str) {
        obj.tag = str[1];
        title = title.replace(`#${str[1]}#`, ``);
      }

      // =======
      if (title.indexOf("!!!") == 0) {
        obj.cue = "<span class='text-priority'>!!! </span>";
        title = title.replace(`!!!`, ``);
      }

      obj.title = title;
      return obj;
    }
  },
  mounted: function() {},
  computed: {},
  components: {},
  watch: {}
};
</script>
<style lang="scss" scoped>
@import "task.scss";
</style>