<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Home\Model;
use Think\Model;
use Think\Upload;

/**
 * 产品基础模型
 */
class PicModel extends Model{
    /**
     * 自动完成
     * @var array
     */
    protected $_auto = array(
        array('pic_status', 1, self::MODEL_INSERT),
        array('pic_addtime', NOW_TIME, self::MODEL_INSERT),
    );

	/**
     * 文件上传
     * @param  array  $files   要上传的文件列表（通常是$_FILES数组）
     * @param  array  $setting 文件上传配置
     * @param  string $driver  上传驱动名称
     * @param  array  $config  上传驱动配置
     * @return array           文件上传成功后的信息
     */
    public function upload($files, $setting){
        /* 上传文件 */
        $setting['callback'] = array($this, 'isFile');
		$setting['removeTrash'] = array($this, 'removeTrash');
        $Upload = new Upload($setting);
        $info   = $Upload->upload($files);
		
        if($info){ //文件上传成功，记录文件信息
            foreach ($info as $key => &$value) {
                /* 已经存在文件记录 */
                if(isset($value['pic_id']) && is_numeric($value['pic_id'])){
                    continue;
                }else{
                	$info[$key]['pic_md5'] = $info[$key]['md5'];
                	$info[$key]['pic_sha1'] = $info[$key]['sha1'];
                }

                /* 记录文件信息 */
                $save['pic_path'] = $value['pic_path'] = substr($setting['rootPath'], 1).$value['savepath'].$value['savename'];	//在模板里的url路径
                $save['pic_md5'] = $value['md5'];
                $save['pic_sha1'] = $value['sha1'];
                if($this->create($save) && ($id = $this->add())){
                    $save['pic_id'] = $id;
                	$info[$key]['pic_id'] = $id;
                } else {
                    //TODO: 文件上传成功，但是记录文件信息失败，需记录日志
                    unset($info[$key]);
                }
            }
            //echo json_encode($info);
            return $info; //文件上传成功
        } else {
            $this->error = $Upload->getError();
            return false;
        }
    }
    
	/**
     * 检测当前上传的文件是否已经存在
     * @param  array   $file 文件上传数组
     * @return boolean       文件信息， false - 不存在该文件
     */
    public function isFile($file){
        if(empty($file['md5'])){
            throw new \Exception('缺少参数:md5');
        }
        /* 查找文件 */
		$map = array('pic_md5' => $file['md5'],'pic_sha1'=>$file['sha1'],);
        return $this->field(true)->where($map)->find();
    }

	/**
	 * 清除数据库存在但本地不存在的数据
	 * @param $data
	 */
	public function removeTrash($data){
		$save['pic_path'] = $data['pic_path'];
		$save['pic_md5'] = $data['pic_md5'];
		$save['pic_sha1'] = $data['pic_sha1'];
		$this->where(array('pic_id'=>$data['pic_id'],))->save($save);
	}
	/**
	 * 获取图片详细内容
	 */
	public function detail($id, $field = true){
		$data = $this->field($field)->find($id);
		if(!is_array($data)){
			$this->error = "图片不存在";
			return false;
		}
		return $data;
	}
}