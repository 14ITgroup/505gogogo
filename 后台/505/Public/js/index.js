jQuery(document).ready(function($) {

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
});