<template>
  <div id="projectConf" class="project-conf">

    <panel v-if="isLoadModel">
      <div class="load-box">
        <i class="fa-pulse fa fa-connectdevelop icon"></i>
      </div>
    </panel>
    <panel v-if="!isLoadModel">
      <template slot="head">
        <el-button size="small" @click="$router.go(-1)" type="text">返回</el-button>
      </template>

      <h1>项目设置</h1>
      <el-form ref="form" :rules="rules" :model="project" label-width="" style="width:70%">
        <el-form-item prop="project_title">
          <el-input v-model="project.project_title"></el-input>
        </el-form-item>
        <el-form-item prop="project_info">
          <el-input type="textarea" :rows="2" resize="none" v-model="project.project_info"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="success" @click="onSubmit">保存设置</el-button>
        </el-form-item>
      </el-form>
      <hr>
      <div class="text-sm">
        <div>删除项目</div>
        <p class="text-info">项目删除后，所有的内容也将被立刻删除，不能恢复。请谨慎操作。</p>
        <el-button type="danger" size="mini" @click="delDialog=true">了解风险，删除这个项目</el-button>
      </div>

    </panel>

    <el-dialog :title="'删除项目：'+project.project_title" :visible.sync="delDialog" width="30%">
      <p class="text-lg">
        <span class="text-priority">重要提示：</span>该项目所有的内容都将被删除，不可恢复。
      </p>
      <span slot="footer" class="dialog-footer">
        <el-button size="mini" @click="delDialog = false" type="text">取消</el-button>
        <el-button size="mini" type="success" @click="del()" :loading="isDelBtnLoad">确认删除</el-button>
      </span>
    </el-dialog>

  </div>

</template>
<script>
export default {
  name: "projectConf",
  data() {
    return {
      delDialog: false,
      project_id: "",
      project: {
        project_title: "",
        project_info: ""
      },
      refreshBtnLoad: false,
      isLoadModel: false,
      isDelBtnLoad: false,
      rules: {
        project_title: [
          { required: true, message: "请输入项目标题", trigger: "blur" },
          { max: 255, message: "长度不能超过 255 个字符", trigger: "blur" }
        ],
        project_info: [
          { max: 255, message: "长度不能超过 255 个字符", trigger: "blur" }
        ]
      }
    };
  },
  methods: {
    update() {
      this.refreshBtnLoad = true;
      this.$get("project/get", { project_id: this.project_id }, res => {
        this.refreshBtnLoad = false;
        this.isLoadModel = false;
        if (res.res >= 1) {
          this.buliderList(res.msg);
        }
        if (res.res < 0) {
        }
      });
    },
    buliderList(list) {
      for (let i = 0; i < list.tasks.length; i++) {
        const task = list.tasks[i];
        task.isEdit = false;
        task.isSaveModel = false;
        for (let j = 0; j < list.taskList.length; j++) {
          const taskList = list.taskList[j];

          taskList.isEdit = false;
          taskList.isSaveModel = false;

          for (let l = 0; l < taskList.tasks.length; l++) {
            const task_l = taskList.tasks[l];
            task_l.isEdit = false;
            task_l.isSaveModel = false;
          }
        }
      }

      this.project = list;
    },
    onSubmit() {
      this.$refs["form"].validate(valid => {
        if (valid) {
          var save = {
            project_title: this.project.project_title,
            project_info: this.project.project_info
          };
          var where = {
            project_id: this.project_id
          };
          this.$post("project/save", { where: where, save: save }, res => {
            if (res.res >= 1) {
              this.$message({ message: "保存成功！", type: "success" });
            }
            if (res.res < 0) {
              this.$message({ message: "保存失败！请重试！", type: "error" });
            }
          });
        } else {
          this.$message({ message: "请输入必填项！", type: "error" });

          return false;
        }
      });
    },
    // 保存
    save(item, saveName, isInfo, isValidate, fun) {
      if (isValidate && item[saveName] == this.testValue) return;

      var save = {};
      save[saveName] = item[saveName];
      var where = {};
      where["project_id"] = item[name + "_id"];
      this.$post("project/save", { where: where, save: save }, res => {
        if (res.res >= 1 && isInfo) {
          this.$message({ message: "保存成功！", type: "success" });
        }
        if (res.res < 0) {
          this.$message({ message: "保存失败！请重试！", type: "error" });
        }
        if (fun) fun(res);
      });
    },
    del() {
      var del = { where: { project_id: this.project.project_id } };
      this.$post("project/del", del, res => {
        if (res.res >= 1) {
          this.delDialog = false;
          this.isDelBtnLoad = false;
          this.$router.push({ name: "projectList" });
        }
        if (res.res < 0) {
        }
      });
    }
  },
  computed: {},
  mounted() {
    this.refreshBtnLoad = true;
    this.isLoadModel = true;
    if (this.$route.params["project_id"] == null) {
      if (localStorage.project_id == null) {
        this.$router.go(-1);
        return;
      } else {
        this.project_id = localStorage.project_id;
      }
    } else {
      this.project_id = this.$route.params["project_id"];
    }
    localStorage.project_id = this.project_id;
    this.update();
    setTimeout(() => {}, 1000);
  },
  components: {},
  watch: {}
};
</script>

<style lang="scss" scoped>
@import "conf.scss";
</style>