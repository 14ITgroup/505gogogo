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

	public function seecolor($goodid) {
		$s = D('Colorview')->where('goodsid=%d', $goodid)->group('color')->select();
		return $s;
	}

	public function seesize($goodid) {
		$s = D('Sizeview')->where('goodsid=%d', $goodid)->group('size')->select();
		return $s;
	}

	public function addgood($gooddata) {

	}

	public function addcolor($goodid, $colorname) {
		$s = D('SizeView')->where('goodsid=%d', $goodid)->group('size')->select();
		$da = D("ColorView")->where('color="%s" and goodsid=%d', $colorname, $goodid)->count();
		if ($da == '0') {
			M("goodstype")->where('color is null')->delete();
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
			M("goodstype")->where('size is null')->delete();
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

	public function delcolor($goodid, $colorname) {
		$da = D("ColorView")->where('goodsid=%d', $goodid);
		if ($da->count() == '0') {
			return false;
		} else if ($da == '1') {
			$da = $da->where('color="%s"', $colorname)->count();
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
		$da = D("SizeView")->where('goodsid=%d', $goodid);
		if ($da->count() == '0') {
			return false;
		} else if ($da == '1') {
			$da = $da->where('size="%s"', $sizername)->count();
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