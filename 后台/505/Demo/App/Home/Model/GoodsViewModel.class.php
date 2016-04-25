<?php

namespace Home\Model;
use Think\Model\ViewModel;

////商品视图模型，用于全方面的返回商品的信息
///模型列： id classfyid，name，image，price，addtime，type_id，color,size,detail,goodsleft,clssifyname
///方法:
//seecolor/seesize:参数$goodid，返回商品id为goodid的所有颜色分类/尺寸分类
//addcolor/addsize:参数$goodid,$colorname/$sizename,给商品id为goodid添加一个名字为colorname/sizename的颜色/尺寸分类。自动补全所有组合情况;返回值
//delcolor/delsize:参数$goodid,$colorname/$sizename,给商品id为goodid删除一个名字为colorname/sizename的颜色/尺寸分类。只剩一个分类的时候改为null；返回值
class GoodsViewModel extends ViewModel {
	public $viewFields = array(
		'goods' => array('id', 'classifyid', 'name', 'image', 'price', 'addtime'),
		'goodstype' => array('id' => 'type_id', 'color', 'size', 'detail', 'goodsleft', '_on' => 'goods.id=goodstype.goodsid'),

		'goodsclassify' => array('classifyname', '_on' => 'goods.classifyid=goodsclassify.id'),
	);

	/**
	 * 查看分类
	 *
	 * 参数 商品id
	 *
	 * 返回类别数组 id 颜色/尺寸
	 *
	 **/
	public function seecolor($goodid) {
		$s = D('Colorview')->where('goodsid=%d', $goodid)->group('color')->select();
		return $s;
	}

	public function seesize($goodid) {
		$s = D('Sizeview')->where('goodsid=%d', $goodid)->group('size')->select();
		return $s;
	}

	/**
	 * 添加商品
	 *
	 * 参数  商品的数组 (classifyid,name,image,price)
	 *
	 * 效果 添加一个商品 默认没有颜色尺寸分类，剩余货量为0
	 *
	 * 返回状态 成功与否
	 *
	 **/
	public function addgood($gooddata) {
		$good = M('goods')->where('name=%s', $gooddata['name'])->select();
		$good = count($good);
		if ($good == 0) {
			$good = M('goods');
			$good->classifyid = $gooddata['classifyid'];
			$good->name = $gooddata['name'];
			$good->image = $gooddata['image'];
			$good->price = $gooddata['price'];
			$good->addtime = date('Y-m-d H:i:s') . "";
			$result = $good->add();
			if ($result) {
				$good = M('goods')->where('name=%s', $gooddata['name'])->select();
				$type = M('goodtype');
				$type->goodsid = $good['id'];
				$type->goodsleft = 0;
				$result = $type->add();
				if ($result) {
					return ture;
				} else {
					return false;
				}

			} else {
				return false;
			}

		} else {
			return false;
		}
	}

	/**
	 * 删除商品
	 *
	 * 参数 商品id
	 *
	 * 效果 删除一个商品，以及它的分类
	 *
	 * 返回状态 成功与否
	 *
	 **/

	public function delgood($goodid) {
		$result = M('goods')->where('id=%d', $goodid)->delete();
		if (!$result) {
			return false;
		}
		$result = M('goodstype')->where('goodsid=%d', $goodid)->delete();
		if ($result) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * 添加分类
	 *
	 * 参数 商品id，颜色/尺寸名字
	 *
	 * 效果 添加一个颜色/尺寸分类，并且把组合补全
	 *
	 * 返回状态 成功与否
	 *
	 **/

	public function addcolor($goodid, $colorname) {
		$s = D('SizeView')->where('goodsid=%d', $goodid)->group('size')->select();
		$da = D("ColorView")->where('color="%s" and goodsid=%d', $colorname, $goodid)->count();
		if ($da == '0') {
			M("goodstype")->where('goodsid=%d and color is null', $goodid)->delete();
			foreach ($s as $value) {
				$goodtype = M("goodstype");
				$goodtype->color = $colorname;
				$goodtype->goodsid = $goodid;
				$goodtype->goodsleft = 0;
				$goodtype->size = $value['size'];
				$goodtype->add();
			}
			return true;
		} else {
			return false;
		}

	}

	public function addsize($goodid, $sizename) {
		$s = D('ColorView')->where('goodsid=%d', $goodid)->group('color')->select();
		$da = D("SizeView")->where('size="%s" and goodsid=%d', $sizename, $goodid)->count();
		if ($da == '0') {
			M("goodstype")->where('goodsid=%d and size is null', $goodid)->delete();
			foreach ($s as $value) {
				$goodtype = M("goodstype");
				$goodtype->color = $value['color'];
				$goodtype->goodsid = $goodid;
				$goodtype->goodsleft = 0;
				$goodtype->size = $sizename;
				$goodtype->add();
			}
			return true;
		} else {
			return false;
		}

	}

	/**
	 * 删除分类
	 *
	 * 参数 商品id，颜色/尺寸名字
	 *
	 * 效果 删除一个颜色/尺寸分类，若该分类只剩这一个，把所有改为null
	 *
	 * 返回状态 成功与否
	 *
	 **/

	public function delcolor($goodid, $colorname) {
		$da = D('GoodsView')->seecolor($goodid);
		$da = count($da);
		if ($da == 0) {
			return false;
		} else if ($da == 1) {
			$da = D('ColorView')->where('color="%s" and goodsid=%d', $colorname, $goodid)->group('color')->select();
			$da = count($da);
			if ($da == '0') {
				return false;
			} else {
				$ds = M('goodstype');
				$data['color'] = null;
				$ds->where('goodsid=%d', $goodid)->save($data);
				return true;
			}
		} else {
			$ds = M('goodstype')->where('goodsid=%d and color="%s"', $goodid, $colorname)->delete();
			return true;
		}
	}

	public function delsize($goodid, $sizename) {
		$da = D('GoodsView')->seesize($goodid);
		$da = count($da);
		if ($da == 0) {
			return false;
		} else if ($da == 1) {
			$da = D('SizeView')->where('size="%s" and goodsid=%d', $sizename, $goodid)->group('size')->select();
			$da = count($da);
			if ($da == '0') {
				return false;
			} else {
				$ds = M('goodstype');
				$data['size'] = null;
				$ds->where('goodsid=%d', $goodid)->save($data);
				return true;
			}
		} else {
			$ds = M('goodstype')->where('goodsid=%d and size="%s"', $goodid, $sizename)->delete();
			return true;
		}
	}

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