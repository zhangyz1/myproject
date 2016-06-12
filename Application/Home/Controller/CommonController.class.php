<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
    /**
	 * Ajax上传图片
	 */
	public function uploadi(){
		$w = I('w');
		$h = I('h');
		$picTable = D("pic");
		$info = $picTable->upload($_FILES,C("UPLOAD_PIC_SETTING"));
 		
		if(!$info) {// 上传错误提示错误信息
			$r['ret'] = 0;
			$r['msg'] = $picTable->getError();
		}else{// 上传成功 获取上传文件信息
			$r['ret'] = 1;
			$r['data'] = $info['Filedata']['pic_path'];
			
			$r['pid'] = $info['Filedata']['pic_id'];
			if($w || $h){
				$r['thumb'] = thumb($r['data'], $w, $h);
			}
		}
		echo json_encode($r);
	}
	
	public function uploadImg(){
		date_default_timezone_set('PRC');
		//layout(false);
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   = 8388608 ;// 设置附件上传大小
		$upload->exts      = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->saveName  = time().mt_rand(100000,999999);
		$rootPath = './Public/upload/images/';
		if (!file_exists($rootPath)){
			mkdir($rootPath);
		}
		$upload->rootPath  = $rootPath;// 设置附件上传目录
		$subName = date('Ym').'/';
		$upload->subName   = $subName;// 设置附件上传目录
		//设置回调函数
		$upload->callback = "";
		
		$info = $upload->upload();
		if(!$info) {// 上传错误提示错误信息
			$data['ret'] = 0;
			$data['msg'] = $upload->getError();
		}else{// 上传成功 获取上传文件信息
			$data['ret'] = 1;
			$data['data'] = substr($rootPath,1).$subName.$info['Filedata']['savename'];
			
			$save['pic_path'] = $data['data'];
			$save['pic_status'] = 1;
			$save['pic_addtime'] = time();
			
			$pid = $picTable = M("pic")->add($save);
			$data['pid'] = $pid;
		}
		return $data;
	}
	//截取缩略图
	public function thumb($src, $width, $height){
		if (empty($src) || !is_file('.' . $src)) {
			//$src = C('WEB_NOPIC');
			return;
		}
		if (empty($width) && empty($height)) {
			return;
		}
		$thumbsrc = thumb_src($src, $width, $height);
		if (!is_file('.' . $thumbsrc)) {
			$thumb = new \Think\Image();
			$image=$thumb->open('.' . $src);
			if(!$height){
				$w=$image->width();
				$h=$image->height();
				$height=intval($width*($h/$w));
			}
			$thumb->thumb($width, $height, \Think\Image::IMAGE_THUMB_CENTER)->save('.' . $thumbsrc);
		}
	
		return;
	}
}