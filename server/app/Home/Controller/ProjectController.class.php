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
class ProjectController  extends CommonController{
    
    public function test(){
        
    }
    
    
    /**
    * 新增
    */
    public function add(){
        
        
        $add=I('add');
        if($add){
            
            $add['add_time']=time();
            $add['edit_time']=time();
            $add['project_id']=getMd5();
            
            $model=M('project');
            $result=$model->add($add);
            if($result){
                $res['res']=1;
                $where=[];
                $where['project_id']=$add['project_id'];
                $project=$model->where($where)->find();
                
                $res['msg']=$project;
                
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
        
        $model=M('project');
        $project_id=I('project_id');
        $where=[];
        $where['project_id']=$project_id;
        $project=$model
        ->where($where)
        ->find();
        
        
        // ===============================
        // 找文章
        $papers= M('paper')
        ->where("o_paper.project_id = '$project_id' ")
        ->field('o_paper.*,o_user.user_id,o_user.user_name,o_user.user_head,o_user.user_info')
        ->join('o_user ON o_paper.user_id = o_user.user_id','LEFT')
        ->order('o_paper.add_time desc,o_paper.sort asc')
        ->select();
        
        $papers=toHtml($papers,'paper_content');
        $papers=to_format_date($papers,'add_time');
        $project['papers']=$papers;
        // 找文章 end
        // ===============================
        
        
        // 找清单列表
        $taskList=M('taskList')
        ->where($where)
        ->order('sort asc')
        ->select();
        $TaskModel=M('task');
        foreach ($taskList as $key => $value) {
            
            $task_list_id= $value['task_list_id'];
            
            $tasks= $TaskModel
            ->where("o_task.task_list_id = '$task_list_id' ")
            ->field('o_task.*,o_user.user_id,o_user.user_name,o_user.user_head,o_user.user_info')
            ->join('o_user ON o_task.run_user = o_user.user_id','LEFT')
            ->order('o_task.sort asc')
            ->select();
            
            $taskList[$key]['tasks']= $tasks;
            
        }
        $project['taskList']=$taskList;
        // ===============================
        
        
        if($project){
            $res['res']=1;
            $res['msg']=$project;
            
        }else{
            $res['res']=-1;
            $res['msg']=$project;
        }
        echo json_encode($res);
        
        
    }
    
    //获得列表
    public function getList(){
        
        $model=M('project');
        $where=I('where')?I('where'):[];
        
        $result=$model
        ->where($where)
        ->order('add_time asc')
        ->select();
        
        // =========判断=========
        if($result){
            $result=toTime($result);
            
            $model=M('task');
            $where=[];
            $where['is_ok']=0;
            
            foreach ($result as $key => $value) {
                $where['project_id']=$value['project_id'];
                $result[$key]['task_count_no_ok']=$model->where($where)->count();
            }
            
            $res['count']=$model->count()+0;
            $res['res']=1;
            $res['msg']=$result;
            
            
        }else{
            $res['res']=0;
        }
        
        echo json_encode($res);
        
        
    }
    public function save(){
        
        $model=M('project');
        
        $where=I('where');
        $save=I('save','',false);
        
        unset($save['project_id']);
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
        
        $model=M('project');
        $where=I('where');
        $result = $model->where($where)->delete();
        
        if($result){
            
            $res['res']=$result;
            $res['msg']=$result;
            
            M('task')->where($where)->delete();
            M('task_list')->where($where)->delete();
            
            
        }else{
            $res['res']=-1;
            $res['msg']=$result;
        }
        echo json_encode($res);
    }
    
}