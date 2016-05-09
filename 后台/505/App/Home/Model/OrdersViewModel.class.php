<?php

namespace Home\Model;
use Think\Model\ViewModel;

//订单模型,方便显示数据,类似商品模型
class OrdersViewModel extends ViewModel {
	public $viewFields = array(
		'orders' => array('userid', 'goodsnum', 'totalprice', 'state', 'addtime'),
		'goodstype' => array('color', 'size', '_on' => 'orders.goodstypeid=goodstype.id'),
		'goods' => array('name', 'image', '_on' => 'goodstype.goodsid=goods.id'),
	);
}
?>