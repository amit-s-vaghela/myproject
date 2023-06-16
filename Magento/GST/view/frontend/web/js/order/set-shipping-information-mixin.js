
define([
    "jquery",
    "mage/utils/wrapper",
    "Magento_Checkout/js/model/quote",
], function ($, wrapper, quote, customer) {
    "use strict";

    return function (setShippingInformationAction) {
        return wrapper.wrap(
            setShippingInformationAction,
            function (originalAction) {
                var shippingAddress = quote.shippingAddress();

                if (shippingAddress["extension_attributes"] === undefined) {
                    shippingAddress["extension_attributes"] = {};
                }
                // if (!customer.isLoggedIn()) {
                    shippingAddress["extension_attributes"]["gst_number"] = $(
                        '[name="gst_number"]'
                    ).val();
                    shippingAddress["extension_attributes"]["company_name"] = $(
                        '[name="company_name"]'
                    ).val();
                // }
                // pass execution to original action ('Magento_Checkout/js/action/set-shipping-information')
                return originalAction();
            }
        );
    };
});
