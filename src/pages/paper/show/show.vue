<template>
  <div id="paperShow">

    <panel type="bottom" @click.native="$router.push('/project/show')">
      {{project.project_title}}
    </panel>

    <panel v-if="isLoadModel">
      <div class="load-box">
        <i class="fa-pulse fa fa-connectdevelop icon"></i>
      </div>
    </panel>
    <panel v-if="!isLoadModel && !isEditModel">
      <div class="paper-head">

        <div class="paper-title"> {{paper.paper_title}}</div>
        <div class="paper-info text-info">river {{paper.edit_time}}保存 </div>
        <el-button type="text" class="text-a" @click="toEdit()">编辑</el-button>

      </div>
      <div class="paper-body">
        <div class="paper-content markdown-body" v-html="compiledMarkdown"></div>
      </div>

    </panel>

  </div>
</template>
<script>
import marked from "marked";
import hljs from "highlight.js";
import "highlight.js/styles/atom-one-light.css";
import "../../../assets/css/github-markdown.css";

var hl = require("highlight").Highlight;
export default {
  name: "paperShow",
  data() {
    return {
      paper_id: "",
      paper: {
        task_count: 0
      },
      project: {
        project_title: localStorage.project_title
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
      this.$get("paper/get", { paper_id: this.paper_id }, res => {
        this.refreshBtnLoad = false;
        this.isLoadModel = false;
        if (res.res >= 1) {
          this.paper = res.msg;
        }
        if (res.res < 0) {
        }
      });
    },
    //构建基本列表
    buliderList() {},
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
    toEdit() {
      this.$router.push({
        name: "paperEdit",
        query: {
          paper_id: this.paper_id
        }
      });
    }
  },
  mounted() {
    this.refreshBtnLoad = true;
    this.isLoadModel = true;
    if (this.$route.query["paper_id"] == null) {
      if (localStorage.paper_id == null) {
        this.$router.go(-1);
        return;
      } else {
        this.paper_id = localStorage.paper_id;
      }
    } else {
      this.paper_id = this.$route.query["paper_id"];
    }
    localStorage.paper_id = this.paper_id;

    this.update();
    // ====
    var rendererMD = new marked.Renderer();
    marked.setOptions({
      renderer: rendererMD,
      gfm: true,
      tables: true,
      breaks: false,
      pedantic: false,
      sanitize: false,
      smartLists: true,
      smartypants: false
    });
    marked.setOptions({
      highlight: function(code) {
        return hljs.highlightAuto(code).value;
      }
    });
  },
  components: {},
  computed: {
    compiledMarkdown() {
      return marked(this.paper.paper_content, { sanitize: true });
    }
  },
  watch: {}
};
</script>
<style lang="scss" >
@import "show.scss";
</style>

