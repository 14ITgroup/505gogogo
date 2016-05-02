<?php
namespace Home\Model;
use Think\Model\ViewModel;

////订单视图模型，用于全方面的返回商品的信息
///模型列： order_id, goodsnum, order_state, addtime, user_name, goodsleft, goods_name, detail
class OrdersViewModel extends ViewModel {
	public $viewFields = array(
		'orders'=>array('id'=>'order_id','goodsnum','state'=>'order_state','addtime'),
		'users'=>array('name'=>'user_name','_on'=>'orders.userid=users.id'),
		'goodstype'=>array('goodsleft','_on'=>'orders.goodstypeid=goodstype.id'),
		'goods'=>array('name'=>'goods_name','_on' => 'goodstype.goodsid=goods.id'),
	);
}
