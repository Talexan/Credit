define(['jquery', 'Magento_Checkout/js/view/payment/default'],
function ($, Component) 
{
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Talexan_Credit/payment/loyaltycoin',
        },
    });
}
);