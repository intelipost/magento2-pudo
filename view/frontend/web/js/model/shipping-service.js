/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'ko',
    'Magento_Checkout/js/model/checkout-data-resolver'
], function (ko, checkoutDataResolver) {
    'use strict';

    var shippingRates = ko.observableArray([]);

    return {
        isLoading: ko.observable(false),

        /**
         * Set shipping rates
         *
         * @param {*} ratesData
         */
        setShippingRates: function (ratesData) {
            shippingRates(ratesData);
            shippingRates.valueHasMutated();
            checkoutDataResolver.resolveShippingRates(ratesData);

            //var postcode = jQuery('input[name="postcode"]').val("");

            jQuery("#block-custom-intelipost").css('display', 'none');

            jQuery('input:radio[name^="ko"]').each(function(index, element){

                jQuery(element).click(function(){

                    if(element.value=="pickup_pickup"){
                        jQuery("#block-custom-intelipost").css('display', 'block');
                    } else {
                        jQuery("#block-custom-intelipost").css('display', 'none');
                    }

                });
            });

            //select state and show city's
            jQuery('#pudo-state').change(function(){

                jQuery.ajax({
                    url: INTELIPOST_PICKUP_CITIES_URL + "?state=" + this.value,
                    showLoader: true, // enable loader

                    success: function(data) {

                        jQuery("#pudo-city option[value='X']").each(function() {
                            jQuery(this).remove();
                        });

                        if(data!=""){

                            jQuery('#pudo-city').append(data);

                        } else {
                            jQuery("#pudo-city").find('option').remove()
                            jQuery('#pudo-city').html("<option>SELECIONE</option>");
                        }
                    },

                });
            });

            //select state and show city's
            jQuery('#pudo-city').change(function(){

                var state = jQuery("#pudo-state option:selected").val();

                jQuery.ajax({
                    url: INTELIPOST_PICKUP_PUDO_URL + "?state=" + state + "&city=" + this.value + "&type=store",
                    showLoader: true, // enable loader

                    success: function(data) {

                        jQuery("#pudos-show ").html(data);

                        //set pudo choice in session
                        jQuery('.pudo_id').each(function(index, element){

                            jQuery(element).change(function(){

                                jQuery.ajax({
                                    url: INTELIPOST_PICKUP_SET_QUOTE + "?pudo_id=" + this.value,
                                    showLoader: true, // enable loader
                                });

                            });

                        });

                    },

                });
            });

        },

        /**
         * Get shipping rates
         *
         * @returns {*}
         */
        getShippingRates: function () {
            return shippingRates;
        }
    };
});
