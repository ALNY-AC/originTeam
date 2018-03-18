<template>
  <div :class="['project','project-'+type]">

    <div class="project-body" @click="show()">

      <span class="project-title">
        <i :class="['icon',project.project_icon]"></i>
        {{project.project_title}}
      </span>

      <span class="project-tool">
        <i class="el-icon-info tool-item" @click.stop="showIconList=!showIconList"></i>
      </span>

      <span class="task_count_no_ok" v-if="type=='list'">
        <span class="title text-info">待处理任务</span>
        <span class="count">{{project.task_count_no_ok}}</span>
      </span>

    </div>

    <el-card v-if="showIconList" class="icon-list">

      <div v-for="item in iconList" :key="item" :class="['icon-item',{'bg':project.project_icon==item}]" @click="setIcon(item)">
        <i :class="item"></i>
      </div>

      <el-input v-model="icon" placeholder="或 输入 icon" size="mini" style="margin-top:10px" @keyup.enter.native="setIcon(icon)"></el-input>

    </el-card>

  </div>
</template>
<script>
export default {
  name: "project",
  props: {
    project: Object,
    type: String
  },
  data() {
    return {
      showIconList: false,
      icon: "",
      iconList: [
        "fa fa-connectdevelop",
        "fa fa-android",
        "fa fa-apple",
        "fa fa-weixin",
        "fa fa-codepen",
        "fa fa-weibo",
        "fa fa-home",
        "fa fa-automobile",
        "fa fa-birthday-cake",
        "fa fa-book",
        "fa fa-bookmark",
        "fa fa-bug",
        "fa fa-briefcase",
        "fa fa-bullhorn",
        "fa fa-calendar-check-o",
        "fa fa-camera",
        "fa fa-certificate",
        "fa fa-cloud",
        "fa fa-coffee",
        "fa fa-comments",
        "fa fa-comments-o",
        "fa fa-credit-card",
        "fa fa-credit-card-alt",
        "fa fa-cutlery",
        "fa fa-database",
        "fa fa-diamond",
        "fa fa-desktop",
        "fa fa-folder",
        "fa fa-folder-o",
        "fa fa-globe",
        "fa fa-graduation-cap",
        "fa fa-group",
        "fa fa-paper-plane",
        "fa fa-paper-plane-o",
        "fa fa-plug",
        "fa fa-plane",
        "fa fa-pie-chart",
        "fa fa-recycle",
        "fa fa-shopping-bag",
        "fa fa-soccer-ball-o",
        "fa fa-television",
        "fa fa-trophy",
        "fa fa-video-camera",
        "fa fa-volume-control-phone",
        "fa fa-wrench",
        "el-icon-time",
        "el-icon-picture",
        "el-icon-phone-outline",
        "el-icon-mobile-phone",
        "el-icon-printer",
        "el-icon-goods",
        "el-icon-date",
        "el-icon-message",
        "el-icon-location"
      ]
    };
  },
  methods: {
    show() {
      this.$router.push({
        name: "show",
        query: { project_id: this.project.project_id }
      });
    },

    setIcon(icon) {
      this.project.project_icon = icon;
      this.save(this.project, "project_icon", res => {
        this.icon = "";
      });
    },
    // 保存
    save(item, saveName, fun) {
      var save = {};
      save[saveName] = item[saveName];
      var where = {};
      where["project_id"] = item["project_id"];
      this.$post("project/save", { where: where, save: save }, res => {
        if (fun) fun(res);
        if (res.res < 0) {
          this.$message({ message: "保存失败！请重试！", type: "error" });
        }
      });
    }
  },
  mounted: function() {},
  components: {},
  watch: {}
};
</script>
<style lang="scss" >
@import "project.scss";
</style>