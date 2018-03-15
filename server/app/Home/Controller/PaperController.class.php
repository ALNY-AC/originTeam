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
class PaperController  extends CommonController{
    
    
    /**
    * 新增
    */
    public function add(){
        
        
        $add=I('add');
        if($add){
            
            $add['add_time']=time();
            $add['edit_time']=time();
            $add['paper_id']=getMd5();
            
            $model=M('paper');
            $result=$model->add($add);
            
            $where['paper_id']=$add['paper_id'];
            $paper=$model->where($where)->find();
            
            $paper=to_format_date([$paper],'add_time')[0];
            
            if($result){
                $res['res']=1;
                $res['msg']=$paper;
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
        
        $model=M('paper');
        $where['paper_id']=I('paper_id');
        $paper=$model
        ->where($where)
        ->find();
        
        $paper['paper_content']=html($paper['paper_content']);
        $paper=to_format_date([$paper],'add_time')[0];
        $paper=to_format_date([$paper],'edit_time')[0];
        
        
        
        //=========判断=========
        if($paper){
            $res['res']=1;
            $res['msg']=$paper;
            
            // $where=[];
            // $where['project_id']=$paper['project_id'];
            // $project=$model=M('project');
            // $project=$model
            // ->where()
            // ->field('project_id,project_title')
            // ->find();
            // $res['project']=$project;
            
            
        }else{
            $res['res']=-1;
            $res['msg']=$paper;
        }
        //=========判断end=========
        //=========输出json=========
        echo json_encode($res);
        //=========输出json=========
        
        
    }
    
    
    //获得列表
    public function getList(){
        
        $model=M('paper');
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
        
        $model=M('paper');
        
        $where=I('where');
        $save=I('save');
        
        unset($save['paper_id']);
        unset($save['add_time']);
        $save['edit_time']=time();
        
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
        
        $model=M('paper');
        $where=I('where');
        $result = $model->where($where)->delete();
        if($result){
            $res['res']=$result;
        }else{
            $res['res']=-1;
        }
        echo json_encode($res);
    }
    
}