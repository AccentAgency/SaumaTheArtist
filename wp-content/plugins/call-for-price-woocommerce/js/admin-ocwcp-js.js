jQuery(document).ready(function(){

    //slider setting options by tabbing
    jQuery('.ocwcp-container ul.tabs li').click(function(){
        var tab_id = jQuery(this).attr('data-tab');
        jQuery('.ocwcp-container ul.tabs li').removeClass('current');
        jQuery('.ocwcp-container .tab-content').removeClass('current');
        jQuery(this).addClass('current');
        jQuery("#"+tab_id).addClass('current');
    })

    /*singlr product page button setting code*/
    hidshow();
    jQuery('[name="ocwcp-button-enabled"]').change(function(){
    	hidshow();
    });

    jQuery('[name="ocwcp-allproduct-button-enabled"]').change(function(){
    	hidshow();
    });
    
    jQuery('[name="ocwcp-product-categories-enabled"]').change(function(){
        hidshow();
    });

    jQuery('[name="ocwcp-product-tags-enabled"]').change(function(){
        hidshow();
    });

    jQuery('[name="ocwcp-product-price-enabled"]').change(function(){
        hidshow();
    });
    


    function hidshow(){
        if(jQuery('[name="ocwcp-button-enabled"]').is(':checked') || jQuery('[name="ocwcp-allproduct-button-enabled"]').is(':checked') || jQuery('[name="ocwcp-product-categories-enabled"]').is(':checked') || jQuery('[name="ocwcp-product-tags-enabled"]').is(':checked')  || jQuery('[name="ocwcp-product-price-enabled"]').is(':checked')){
            jQuery(".single_product_setting").show();
        }else{
            jQuery(".single_product_setting").hide();
        }
    }



    /*link choices code*/
    jQuery('input[name="linkchoice"]').change(function(){
    	var radioValue = jQuery('input[name="linkchoice"]:checked').val();
        if(radioValue == "whatshapp"){
        	jQuery('input[name="whatshapp_link"]').show();
        }else {
        	jQuery('input[name="whatshapp_link"]').hide();
        }
        if(radioValue == "call"){
        	jQuery('input[name="call_link"]').show();
        }else {
        	jQuery('input[name="call_link"]').hide();
        }
        if(radioValue == "custom"){
        	jQuery('input[name="ocwcp-button-link-url"]').show();
        }else{
        	jQuery('input[name="ocwcp-button-link-url"]').hide();
        }
    });


    var radioValue = jQuery('input[name="linkchoice"]:checked').val();
    //alert(radioValue);
    if(radioValue == "whatshapp"){
        jQuery('input[name="whatshapp_link"]').show();
    }else {
        jQuery('input[name="whatshapp_link"]').hide();
    }
    if(radioValue == "call"){
        jQuery('input[name="call_link"]').show();
    }else {
        jQuery('input[name="call_link"]').hide();
    }
    if(radioValue == "custom"){
        jQuery('input[name="ocwcp-button-link-url"]').show();
    }else{
        jQuery('input[name="ocwcp-button-link-url"]').hide();
    }

    
})

