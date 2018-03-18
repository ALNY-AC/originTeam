<template>
  <div id="index">

    <div class="head">
      <div class="logo text-info text-center">
        <i :class="['fa fa-connectdevelop',{'fa-spin':isLoading}]"></i>
      </div>
      <el-form>

        <el-form-item label="user id">

          <el-input v-model="data.user_id" placeholder="user id"></el-input>

        </el-form-item>

        <el-form-item label="user password">

          <el-input v-model="data.user_pwd" type="password" placeholder="user password"></el-input>

        </el-form-item>

        <el-form-item>

          <el-checkbox v-model="isReg">is reg</el-checkbox>

        </el-form-item>

        <el-form-item v-if="isReg" label="Enter a password again">

          <el-input v-model="data.user_pwd2" type="password" placeholder="Enter a password again"></el-input>

        </el-form-item>

        <el-form-item>

          <el-button @click="onSubmit" :disabled="isLoading" :loading="isLoading">{{isReg?'reg' : 'login'}}</el-button>

        </el-form-item>

      </el-form>

    </div>

  </div>
</template>
<script>
export default {
  name: "index",
  props: {},
  data() {
    return {
      isLoading: false,
      isReg: false,
      data: {
        user_id: "",
        user_pwd: "",
        user_pwd2: ""
      }
    };
  },
  methods: {
    onSubmit() {
      this.isLoading = true;
      if (this.isReg) {
        this.reg();
      } else {
        this.login();
      }
    },
    login() {
      var data = {
        user_id: this.data.user_id,
        user_pwd: this.data.user_pwd
      };
      this.$post("index/login", data, res => {
        if (res.res >= 1) {
          localStorage.token = res.token;
          localStorage.user_id = res.user_id;
          //取得用户数据
          this.$get("user/getUserInfo", {}, res => {
            this.isLoading = false;
            if (res.res >= 1) {
              localStorage.userInfo = JSON.stringify(res.userInfo);
              this.$success("login ok");
              setTimeout(() => {
                this.$router.push("/project/list");
              }, 500);
              return;
            }
          });
          return;
        }
        this.$error("no login");
      });
    },
    reg() {
      this.$post("index/reg", this.data, res => {
        this.isLoading = false;
        if (res.res >= 1) {
          this.$success("reg ok");
          return;
        }
        if (res.res == -2) {
          this.$error("no param");
          return;
        }
        if (res.res == -3) {
          this.$error("pwd != pwd");
          return;
        }
        if (res.res == -4) {
          this.$error("user_id Already existed");
          return;
        }
        this.$error("Interface Error");
      });
    }
  },
  computed: {},
  filters: {},
  mounted() {},
  destroyed() {},
  components: {},
  watch: {}
};
</script>
<style lang="scss" scoped>
@import "index.scss";
</style>
