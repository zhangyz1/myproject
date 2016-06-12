<?php
namespace Home\Controller;
use Think\Controller;
class ComposeController extends Controller {
    public function add(){
        if (IS_POST) {
          $data= I('post.');
          $user = D('Article')->add(array('title'=>$data['title'],'categroy'=>$data['categroy'],'describe'=>$data['describe'],'content'=>$data['message'],'updated_time'=>time(),'created_time'=>time()));
          if ($user) {
            echo 'ddddd';
          }
        }

        // $res = D('Message')->find_all();
        // $this->assign('res',$res);
        $this->display('add');
    }
}