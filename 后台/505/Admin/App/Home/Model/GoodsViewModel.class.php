<?php

namespace Home\Model;
use Think\Model\ViewModel;

class GoodsViewModel extends ViewModel {
	public $viewFields = array(
		'goods' => array('id', 'classifyid', 'name', 'image', 'price', 'addtime'),
		'goodstype' => array('id' => 'type_id', 'color', 'size', 'detail', 'goodsleft', '_on' => 'goods.id=goodstype.goodsid'),

		'goodsclassify' => array('classifyname', '_on' => 'goods.classifyid=goodsclassify.id'),
	);

}

class ColorViewModel extends ViewModel {
	public $viewFields = array(
		'goodstype' => array('goodsid', 'color'),
	);

}

class SizeViewModel extends ViewModel {
	public $viewFields = array(
		'goodstype' => array('goodsid', 'size'),
	);

}

?>