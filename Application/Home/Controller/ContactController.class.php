<?php
namespace Home\Controller;
use Think\Controller;
class ContactController extends Controller {
    public function index(){
        if (IS_POST) {
          $data= I('post.');
          $user = D('Massage')->insert(array('name'=>$data['name'],'email'=>$data['email'],'content'=>$data['message'],'updated_time'=>time(),'created_time'=>time()));
          if ($user) {
            echo 'ddddd';
          }
        }
        $this->display('index');
    }
}
