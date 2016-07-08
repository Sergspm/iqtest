


$(function () {
	$('.captcha-img').click(function () {
		this.src = '?captcha&' + Math.random();
	});
});