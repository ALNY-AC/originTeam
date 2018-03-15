<template>
  <div id="paperEdit" class="paper-edit">

    <panel v-if="isLoadModel">
      <div class="load-box">
        <i class="fa-pulse fa fa-connectdevelop icon"></i>
      </div>
    </panel>
    <div class="tool">

      <div class="tool-item" @click="$router.go(-1)">
        <i class="fa fa-chevron-left"></i>
      </div>
      <div class="tool-item">
        <el-input @focus="testValue=paper.paper_title" v-model="paper.paper_title" @blur="save(paper,'paper_title',true,true);" @keyup.enter.native="save(paper,'paper_title',true,true)" style="width:300px" size="mini"></el-input>
      </div>
      <div class="float-right">

        <div class="tool-item">
          {{saveTitle}}
        </div>
        <div class="tool-item" @click="update()">
          <i :class="!refreshBtnLoad?'el-icon-refresh':'el-icon-loading'"></i>
        </div>
        <div class="tool-item" @click="savePaper()">
          <i :class="isSave?'el-icon-loading':'fa fa-save'">
          </i>
        </div>
      </div>

    </div>

    <div v-if="!isLoadModel">

      <div id="editor">
        <mavon-editor @save="savePaper('保存完成~')" :toolbars="toolbars" style="height: 100%" v-model="paper.paper_content"></mavon-editor>
        <!-- testValue -->
      </div>

    </div>

  </div>

</template>
<script>
import marked from "marked";
import { mavonEditor } from "mavon-editor";
import "mavon-editor/dist/css/index.css";
export default {
  name: "paperEdit",
  data() {
    return {
      delDialog: false,
      paper_id: "",
      paper: {
        paper_title: "",
        paper_info: "",
        paper_content: ""
      },

      refreshBtnLoad: false,
      isLoadModel: false,
      isSave: false,
      toolbars: {
        bold: true, // 粗体
        italic: true, // 斜体
        header: true, // 标题
        underline: true, // 下划线
        strikethrough: true, // 中划线
        mark: true, // 标记
        superscript: true, // 上角标
        subscript: true, // 下角标
        quote: true, // 引用
        ol: true, // 有序列表
        ul: true, // 无序列表
        link: true, // 链接
        imagelink: true, // 图片链接
        code: true, // code
        table: true, // 表格
        subfield: true, // 是否需要分栏
        fullscreen: true, // 全屏编辑
        readmodel: true, // 沉浸式阅读
        htmlcode: true, // 展示html源码
        help: true // 帮助
      },
      interval: null,
      saveTitle: "",
      testValue: ""
    };
  },
  methods: {
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

    savePaper(title) {
      var save = this.paper;
      this.isSave = true;
      var where = {
        paper_id: this.paper_id
      };
      this.$post("paper/save", { where: where, save: save }, res => {
        setTimeout(() => {
          this.isSave = false;

          if (res.res >= 1) {
            if (title != null) {
              this.saveTitle = title;
              setTimeout(() => {
                this.saveTitle = "";
              }, 1000);
            } else {
              this.$message({
                message: "保存成功！",
                type: "success"
              });
            }
          }
          if (res.res < 0) {
            this.$message({ message: "保存失败！请重试！", type: "error" });
          }
        }, 500);
      });
    },
    // 保存
    save(item, saveName, isInfo, isValidate, fun) {
      if (isValidate && item[saveName] == this.testValue) return;
      var save = {};
      save[saveName] = item[saveName];
      var where = {};
      where["paper_id"] = item["paper" + "_id"];
      this.$post("paper/save", { where: where, save: save }, res => {
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
  computed: {
    compiledMarkdown() {
      return marked(this.paper.paper_content, { sanitize: true });
    }
  },
  mounted() {
    this.$nextTick(() => {
      this.refreshBtnLoad = true;
      this.isLoadModel = true;
      if (this.$route.params["paper_id"] == null) {
        if (localStorage.paper_id == null) {
          this.$router.go(-1);
          return;
        } else {
          this.paper_id = localStorage.paper_id;
        }
      } else {
        this.paper_id = this.$route.params["paper_id"];
      }
      localStorage.paper_id = this.paper_id;
      this.update();

      this.interval = setInterval(() => {
        this.savePaper("自动保存完成~");
      }, 10000);
    });
  },
  components: { mavonEditor },
  watch: {},
  destroyed() {
    clearInterval(this.interval);
  }
};
</script>

<style lang="scss" >
@import "edit.scss";
</style>