head.ready("jquery", function() {

	/* dropdown login click bug fix */
	$('.dropdown-toggle').dropdown();// Setup drop down menu*/
	$('.dropdown input, .dropdown label').click(function(e){e.stopPropagation();});// Fix input element click problem

    /*placeholder polyfill*/
	var placeholderSupport = jQuery.support.placeholder = (function() {
    	var i = document.createElement('input');
    	var t = document.createElement('textarea');
    	if(!('placeholder' in i) || !('placeholder' in t)){
            head.js({placeholder: window.baseUrl+"/js/libs/jquery.placeholder.js"});
            head.ready("placeholder", function() {
                $('input, textarea').placeholder();
            });
    	}
	})();

    /*footer animations Animations: tada, swing, wiggle, pulse*/

    $("#social-media li").hover(function(){
        $(this).addClass('animated').addClass('pulse');
    }, function(){
        $(this).removeClass('animated').removeClass('pulse');
    });
    

	
});