<template>
  <div class="paper" @click="toPaper()">

    <div class="icon text-error">
      <i class="fa fa-thumb-tack"></i>
    </div>

    <div class="paper-panel" v-if="paper">

      <div class="paper-head">
        <div class="paper-title">{{paper.paper_title}}</div>
        <div class="paper-info"></div>
      </div>
      <div class="paper-body">
        <div class="paper-content text-info " v-html="compiledMarkdown(paper.paper_content) "></div>
        <div class="truncated"></div>
        <div class="paper-tool">
          <div class="paper-tool-item" @click.stop="delPaper()">删除</div>
        </div>
      </div>

    </div>
    <div class="paper-tool clearfix">
      <div class="paper-tool-item float-left">{{paper.user_name}}</div>
      <div class="paper-tool-item float-right">{{paper.add_time}}</div>
    </div>
  </div>
</template>
<script>
import marked from "marked";
import $ from "jquery";
export default {
  name: "paper",
  props: {
    paper: Object,
    index: Number,
    list: Array
  },
  data() {
    return {};
  },
  methods: {
    compiledMarkdown(str) {
      if (str == null) return "";
      return marked(str, { sanitize: true });
    },
    toPaper() {
      this.$router.push({
        name: "paperShow",
        params: {
          paper_id: this.paper.paper_id
        }
      });
    },
    //删除一个文章
    delPaper() {
      var where = {
        paper_id: this.paper.paper_id
      };
      this.$post("paper/del", { where: where }, res => {
        if (res.res >= 1) {
          this.$message({ type: "success", message: "删除成功~" });
          this.list.splice(this.index, 1);
          return;
        }
        this.$message({ type: "error", message: "删除文章失败！请重试~" });
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
@import "paper.scss";
</style>
<style>

</style>