jQuery(document).ready(function($) {

	/*购物车中右滑编辑删除*/
	var dragEdit = $("section.chart-list li");
	var hideEdit = $("section.hideEdit");
	var hideEditBtn = $("section.hideEdit a");

	var hideEditHeight = $(dragEdit).height() + 12;
	$(hideEditBtn).css({
		height: hideEditHeight - 2,
		lineHeight: $(dragEdit).height() + "px",
	});;
	$(hideEdit).each(function(index, el) {
		var tempHeight = (index * hideEditHeight) + $(".chart-head").height() + 1;
		if(index==0) {
			tempHeight -= 1;
		}
		$(this).css({
			top: tempHeight
		});
	});

	/*购物车列表右滑出现删除按钮*/
	$(dragEdit).each(function(index, el) {
		$(this).swipe({
			/*左滑*/
			swipeLeft: function(event, direction, distance, duration, fingerCount) {
				$(this).animate({
					left: -90
				});
				/*显示删除后调整z-index*/
				$($(hideEdit)[index]).animate({
					zIndex: 1000
				})
				
			},
			/*右滑*/
			swipeRight: function(event, direction, distance, duration, fingerCount) {
				$(this).animate({
					left: 0
				});
				$($(hideEdit)[index]).css("z-index","-1");
			},
			/*重写页面上下滑动*/
			swipeStatus: function(event, phase, direction, distance, duration, fingerCount) {
				if (direction === "up") {
					$(document).scrollTop($(document).scrollTop() + distance);
				}
				if (direction === "down") {
					$(document).scrollTop($(document).scrollTop() - distance);
				}
			}
		});
	});

	/*商品详情页 尺寸及颜色选择*/
	var goodsChoice = $(".details-categories label");
	$(".details-categories label").on("click", function(){
		for(var i=0; i<$(goodsChoice).length; i++) {
			$(goodsChoice).removeClass('checked');
		}
		$(this).addClass('checked');
	})
});