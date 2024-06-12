(function($){
	$(document).ready(function(){
		$(document).on("bubble_click", function(e, $origin){
			if ( $origin.is("a")){
				var href = $origin.attr("href") ;
				if($origin.hasClass("product_link_tracking") && href.indexOf("traking") == -1){
					href += "&traking=1" ;  
					$origin.attr("href" , href );
				}
			}
		})
	});
}(jQuery));