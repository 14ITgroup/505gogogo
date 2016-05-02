<?php

namespace Home\Model;
use Think\Model\ViewModel;
//购物车模型,方便显示数据,类似商品模型
class CartsViewModel extends ViewModel {
	public $viewFields = array(
		'cart' => array('id','userid','goodstypeid','goodsnum'),
		'goodstype' => array('id'=>'type_id','color', 'size', '_on' => 'cart.goodstypeid=goodstype.id'),
		'goods' => array('name','image','price','_on' => 'goodstype.goodsid=goods.id'),
	);
}
?>