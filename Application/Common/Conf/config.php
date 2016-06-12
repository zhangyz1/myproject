<?php
return array(
	//'配置项'=>'配置值'
  'LOAD_EXT_CONFIG' => 'db', //扩展配置文件
	// 视图层
    'DEFAULT_THEME'        => 'Default', // 默认模板主题名称
    'LAYOUT_ON'            => true, // 是否启用布局
    'LAYOUT_NAME'          => 'layout', // 当前布局名称 默认为layout
    'URL_CASE_INSENSITIVE'  =>  true,
    'URL_MODEL'       => 2,


    /*上传图片设置*/
	'UPLOAD_PIC_SETTING' => array(
			'mimes'    => '', //允许上传的文件MiMe类型
			'maxSize'  => 8388608, //上传的文件大小限制 (0-不做限制)
			'exts'     => 'jpg,gif,png,jpeg,doc,xlsx', //允许上传的文件后缀
			'autoSub'  => true, //自动子目录保存文件
			'subName'  => date('Ym'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
			'rootPath' => './Public/upload/images/', //保存根路径
			'savePath' => '', //保存路径
			'saveName' => time().mt_rand(100000,999999), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
			'saveExt'  => '', //文件保存后缀，空则使用原后缀
			'replace'  => false, //存在同名是否覆盖
			'hash'     => true, //是否生成hash编码
			'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
	),
);
