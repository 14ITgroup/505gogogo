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
	$(dragEdit).swipe({
		swipeLeft: function(event, direction, distance, duration, fingerCount) {
			$(this).animate({
				left: -180
			});
		},
		swipeRight: function(event, direction, distance, duration, fingerCount) {
			$(this).animate({
				left: 0
			})
		},
		swipeStatus: function(event, phase, direction, distance, duration, fingerCount) {
			if (direction === "up") {
				$(document).scrollTop($(document).scrollTop() + distance);
			}
			if (direction === "down") {
				$(document).scrollTop($(document).scrollTop() - distance);
			}
		}
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