// @ts-nocheck
import Vue from 'vue';
import App from './App.vue';
import VueRouter from "vue-router";
import ElementUI from 'element-ui';
import $ from 'jquery';
import 'element-ui/lib/theme-chalk/index.css';
import 'font-awesome-webpack';
import panel from "./components/panel/panel.vue";
import label from "./components/label/label.vue";
import task from "./components/task/task.vue";
import taskList from "./components/taskList/taskList.vue";
import leftTool from "./components/leftTool/leftTool.vue";
import leftToolItem from "./components/leftTool/leftToolItem.vue";
import group from "./components/group/group.vue";
import paperComp from "./components/paper/paper.vue";

Vue.component('paper', paperComp)
Vue.component('panel', panel)
Vue.component('taskList', taskList)
Vue.component('leftTool', leftTool)
Vue.component('leftToolItem', leftToolItem)
Vue.component('task', task)
Vue.component('group', group)
Vue.component('o-panel', panel)
Vue.component('o-label', label)
// Vue.component('origin-nav', originNav)

Vue.use(ElementUI);
Vue.use(VueRouter);

//自定义扩展Vue

var Url = {};
Url.install = function (Vue, options) {

  var server = 'http://origin.river.com/index.php/';
  var serverAdmin = server + 'Admin/';
  var serverHome = server + 'Home/';


  //获得处理过的地址，主要用于获得图片的地址
  Vue.prototype.$getUrl = function (url) {

    if (!url) {
      console.warn('【url为空】：' + url);
      return '';
    }
    //开始判断是不是http开头，如果是就不再添加头了
    var _url;
    if (url.indexOf('http') == -1) {
      _url = server + url;
    } else {
      _url = url;
    }
    //取出index.php
    _url = _url.replace('index.php/', '');
    return _url;

  }
  //服务器地址
  Vue.prototype.$server = server;
  Vue.prototype.$serverAdmin = serverAdmin;
  Vue.prototype.$serverHome = serverHome;
  //上传文件地址
  Vue.prototype.$serverUpFile = serverAdmin + "Use/upFile";


}

var Login = function () {

  this.noLogin = function () {
    console.log("没有登录");
    router.push("/index");
  }

}


//起源插件
var Origin = {};
Origin.install = function (Vue, options) {
  Vue.prototype.$getUserInfo = function () {
    return JSON.parse(localStorage.userInfo);
  }

  Vue.prototype.$get = function (url, data, success, error) {

    if (data.token == null) {
      data.token = localStorage.token ? localStorage.token : '';
    }
    if (data.user_id == null) {
      data.user_id = localStorage.user_id ? localStorage.user_id : '';
    }

    if (url.indexOf("http") == -1) {
      url = this.$serverHome + url;
    }

    $.ajax({
      url: url,
      type: 'get',
      data: data,
      success(res) {
        try {
          res = JSON.parse(res);
        } catch (error) {
          console.error(url + '：接口出现错误！');
          console.error(error);
          console.error(res);
          if (error) {
            error(false, error);
          }
          return false;
        }

        //登录验证
        if (res.res == -992 || res.res == -991) {
          new Login().noLogin();
        } else {
          if (success) {
            success(res);
          }
        }
      }

    });

  }

  Vue.prototype.$post = function (url, data, success, error) {
    if (data.token == null) {
      data.token = localStorage.token ? localStorage.token : '';
    }
    if (data.user_id == null) {
      data.user_id = localStorage.user_id ? localStorage.user_id : '';
    }

    if (url.indexOf("http") == -1) {
      //没有http
      url = this.$serverHome + url;
    }
    $.ajax({
      url: url,
      type: 'post',
      data: data,
      success(res) {
        try {
          res = JSON.parse(res);
        } catch (error) {
          console.error(url + '：接口出现错误！');
          console.error(error);
          console.error(res);
          if (error) {
            error(false, error);
          }
          return false;
        }

        //登录验证
        if (res.res == -992 || res.res == -991) {
          new Login().noLogin();
        } else {
          if (success) {
            success(res);
          }
        }
      }

    });

  }

  Vue.prototype.$getTextCount = function (str) {
    if (str == null || str == undefined) {
      str = '';
    }
    return str.length;
  }

  //二次封装饿了么的消息插件
  Vue.prototype.$warn = function (msg) {
    this.$message({ type: "warning", message: msg });
  }

  Vue.prototype.$error = function (msg) {
    this.$message({ type: "error", message: msg });
  }

  Vue.prototype.$info = function (msg) {
    this.$message({ type: "info", message: msg });
  }

  Vue.prototype.$success = function (msg) {
    this.$message({ type: "success", message: msg });
  }

}


Vue.use(Origin)
Vue.use(Url)

var focus = function (el, binding) {
  if (binding.value) {
    $(el).find('*')[0].focus();
  }
}

Vue.directive('focus', {
  inserted: focus,
  // update: focus,
  // componentUpdated: focus,
})



//自定义扩展Vue  ==end==


//路由文件配置

import index from './pages/index/index.vue';

import project from './pages/project/project.vue';
import project_list from './pages/project/list/list.vue';
import project_show from './pages/project/show/show.vue';
import project_task from './pages/project/task/task.vue';
import project_conf from './pages/project/conf/conf.vue';


//文章
import paper from './pages/paper/paper.vue';
import paper_show from './pages/paper/show/show.vue';
import paper_edit from './pages/paper/edit/edit.vue';

// =============

import { EINPROGRESS } from 'constants';

const router = new VueRouter({
  // mode: 'history',
  base: __dirname,
  routes: [
    {
      path: '/', component: index
    },
    {
      path: '/index', component: index
    },
    {
      path: '/project', component: project, children: [
        { path: 'list', name: 'projectList', component: project_list, meta: { name: '项目列表' }, },
        { path: 'show', name: 'show', component: project_show, meta: { name: '项目详情' }, },
        { path: 'task', name: 'task', component: project_task, meta: { name: '任务详情' }, },
        { path: 'conf', name: 'projectConf', component: project_conf, meta: { name: '项目设置' }, },
      ]
    },
    {
      path: '/paper', component: paper, children: [
        { path: 'show', name: 'paperShow', component: paper_show, meta: { name: '文章详情' }, },
        { path: 'edit', name: 'paperEdit', component: paper_edit, meta: { name: '编辑文章' }, },
      ]
    },
  ]
});
// router.push('/project/list');
//路由   <===  end  ===>

// 现在我们可以启动应用了！
// 路由器会创建一个 App 实例，并且挂载到选择符 #app 匹配的元素上。
const app = new Vue({
  router: router,
  render: h => h(App),
  mounted: function () {
    this.$nextTick(() => {
    })
  }
}).$mount('#app')