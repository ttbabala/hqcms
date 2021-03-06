<?php
namespace app\home\model;
use think\Model;
use think\Session;
use app\admin\model\System;

class Reply_comments extends Model{
    protected $pk = 'rid';
    
    public function addData(){
        $datas = input('post.');
        $st = new System();
        $senswords = $st -> column('senswords');
        $sensStr= implode('|',$senswords);
        $sensArr = explode('|',$sensStr);
        if(!$datas['reply_text']){
            exit(json_encode(array('status'=>0,'msg'=>'回复内容不能为空哦^_^')));
        }
        if(!$datas['code']){
           exit(json_encode(array('status'=>0,'msg'=>'验证码不能为空哦^_^'))); 
        }else{
            if(strtolower($datas['code']) !== strtolower(Session::get('code','wll_'))){ //验证码不区分大小写
                exit(json_encode(array('status'=>0,'msg'=>'验证码错误哦^_^'))); 
            }
        }
        $this -> data=([
                'cid' => $datas['cid'], //2
                'fromUserid' => $datas['mid'], //14
                'fromUserip' => getIP(), //127.0.0.1
                'toUserid' => $datas['to_mid'], //13
                'reply_text' => censor($datas['reply_text'],$sensArr),
                'toUserip' => '193.215.20.52',
                'replyTime' => date('Y-m-d H:i:s',time()),
        ]);
       if($this -> save()){
             return true;
       }
       return false;
    }
}

