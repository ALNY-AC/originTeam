<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2018年2月28日00:04:43
* 最新修改时间：2018年2月28日00:04:43
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####商品管理控制器#####
* @author 代码狮
*
*/
namespace Home\Controller;
use Think\Controller;
class taskListController  extends CommonController{
    
    
    /**
    * 新增
    */
    public function add(){
        
        
        $add=I('add');
        if($add){
            
            $add['add_time']=time();
            $add['edit_time']=time();
            $add['task_list_id']=getMd5();
            
            $model=M('taskList');
            $result=$model->add($add);
            
            $where['task_list_id']=$add['task_list_id'];
            $task_list=$model->where($where)->find();
            
            if($result){
                $res['res']=1;
                $res['msg']=$task_list;
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
        
        $model=M('taskList');
        $where=I('where')?I('where'):[];
        $task_list=$model
        ->where($where)
        ->find();
        
        //=========判断=========
        if($task_list){
            $res['res']=1;
            $res['msg']=$task_list;
        }else{
            $res['res']=-1;
            $res['msg']=$task_list;
        }
        //=========判断end=========
        //=========输出json=========
        echo json_encode($res);
        //=========输出json=========
        
        
    }
    
    
    //获得列表
    public function getList(){
        
        $model=M('taskList');
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
        
        $model=M('taskList');
        
        $where=I('where');
        $save=I('save','',false);
        
        unset($save['task_list_id']);
        unset($save['add_time']);
        $save['edit_time']=time();
        
        
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
        
        $model=M('taskList');
        $where=I('where');
        $result = $model->where($where)->delete();
        if($result){
            $res['res']=$result;
            $res['msg']=$result;
            
            $model=M('task');
            $save['task_list_id']='0';
            $model->where($where)->save($save);
            
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
}