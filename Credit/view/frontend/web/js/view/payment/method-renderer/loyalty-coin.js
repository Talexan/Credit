define(['jquery', 'Magento_Checkout/js/view/payment/default'],
function ($, Component) 
{
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Talexan_Credit/payment/loyaltycoin',
        },

//       initObservable: function () {
//
//          this._super()
//                .observe(['canActive', 'customerCoinsAmount']);
//            return this;
//        },
/*
        getCode: function() {
            return 'loyaltycoin';
        },

        /**
         * Enable/Disable payment method of credit coin
         * @returns bool
         */
 /*       canActive: function() {
            var message;

            if (!window.checkout.payment.loyaltycoin.canActive){
                this.Messages.addErrorMessage('Sorry, but You do not have enough credit coins to pay:(')
                return false;
            }
            return true;
        },

        /**
         * Amount the customer credit coins
         * @returns int
         */
   /*     customerCoinsAmount: function() {
            return window.checkout.payment.loyaltycoin.customerCoinsAmount; 
        }*/
    });
}
);