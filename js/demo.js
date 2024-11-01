jQuery(document).ready(function(){
jQuery("#submit").click(function(){
    
    
    
    var name = jQuery("#dname").val();
    
jQuery.ajax({
type: 'POST',
url: MyAjax.ajaxurl,
data: {"action": "wpik_post_word_count", "dname":name},
success: function(html){
//alert(html);
jQuery("#get_name").hide();	
jQuery("#show").html(html);
}





});

});

});