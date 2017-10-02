
//For showing the tooltips
$(function() {
	jQuery('.tooltips').tooltip();
});



//to slide messages up after a few seconds
if ($('.alert').length > 0){
	$('.alert').delay(2000).fadeTo(1000,0.01).slideUp(500);
}

