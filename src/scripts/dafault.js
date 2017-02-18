$(function() {
    $("#about__phone").mask("0 (000) 000-00-00");
});

$(".about__form").on("submit", function() {
	yaCounter42859779.reachGoal("feedback");

	var phone = $("#about__phone");

	if(phone.val() == "") {
		phone.focus();
	} else {
		$.ajax({
			type: "POST",
			url: "../ajax/feedback.php",
			data: {
				"phone": phone.val()
			},
			dataType: "json",
			success: function(data){
				alert(data.status);
			}
		});
	}
});