define(['uiComponent', 'Magento_Checkout/js/model/payment/renderer-list'],
    function ( Component, rendererList) 
    {
        'use strict';
        rendererList.push(
            {
                type: 'loyaltycoin',
                component: 'Talexan_Credit/js/view/payment/method-renderer/loyalty-coin'
            }
        );

        return Component.extend({});
    }
);
