<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年3月3日15:40:46
* 最新修改时间：2018年3月3日15:40:46
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####任务控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class TaskController  extends CommonController{
    
    
    /**
    * 新增
    */
    public function add(){
        
        
        $add=I('add');
        if($add){
            
            $add['add_time']=time();
            $add['edit_time']=time();
            $add['task_id']=getMd5();
            
            $model=M('task');
            $result=$model->add($add);
            
            $where['task_id']=$add['task_id'];
            $task=$model->where($where)->find();
            
            if($result){
                $res['res']=1;
                $res['msg']=$task;
            }else{
                $res['res']=-1;
                $res['msg']=$result;
            }
        }else{
            $res['res']=-1;
            $res['msg']='没有add参数';
            
        }
        
        
        echo json_encode($res);
        
    }
    
    public function get(){
        
        $model=M('task');
        $where=I('where')?I('where'):[];
        $task=$model
        ->where($where)
        ->find();
        
        $task['task_content']=html($task['task_content']);
        
        //=========判断=========
        if($task){
            $res['res']=1;
            $res['msg']=$task;
        }else{
            $res['res']=-1;
            $res['msg']=$task;
        }
        //=========判断end=========
        //=========输出json=========
        echo json_encode($res);
        //=========输出json=========
        
        
    }
    
    
    //获得列表
    public function getList(){
        
        $model=M('task');
        $where=I('where')?I('where'):[];
        
        $result=$model
        ->where($where)
        ->order('add_time asc')
        ->select();
        
        // =========判断=========
        if($result){
            $result=toTime($result);
            
            $res['count']=$model->count()+0;
            $res['res']=1;
            $res['msg']=$result;
            
            
        }else{
            $res['res']=0;
        }
        
        echo json_encode($res);
        
        
    }
    public function save(){
        
        $model=M('task');
        
        $where=I('where');
        $save=I('save','',false);
        
        unset($save['task_id']);
        unset($save['add_time']);
        $save['edit_time']=time();
        
        
        foreach ($save as $key => $value) {
            $save[$key]=trim($value);
        }
        $res['save']=$save;
        
        $save=arrToString($save);
        $result = $model->where($where)->save($save);
        $res['msg']=$result;
        
        if($result===false){
            $res['res']=-1;
        }
        if($result>0){
            $res['res']=1;
        }
        if($result===0){
            $res['res']=0;
        }
        
        echo json_encode($res);
        
    }
    
    public function del(){
        
        $model=M('task');
        $where=I('where');
        $result = $model->where($where)->delete();
        if($result){
            $res['res']=$result;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
    public function run(){
        $model=M('task');
        $save=[];
        $save['task_id']=I('task_id');
        $save['is_run']=I('is_run');
        $save['run_user']=$save['is_run']==1?session('user_id'):null;
        $save['edit_time']=time();
        $result = $model->where($where)->save($save);
        if($result){
            $res['res']=$result;
            $res['msg']=$result;
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
}