define(['jquery', 'Magento_Checkout/js/view/payment/default'],
function ($, Component) 
{
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Talexan_Credit/payment/loyaltycoin',
        },
        // Мне нечего добавить. 
        // Попробую сказать в модели метода 

        /** @inheritdoc */
        /* initObservable: function () {
            this._super()
                .observe('amountLoyaltyCreditCoins');

            return this;
        }, */

        // Скорее всего данные относящиеся к заказчику
        // должны передаваться в customerData?!
        /* getAmountLoyaltyCreditCoins: function () {
            return window.checkoutConfig.payment.loyaltyCreditCoins.amountCoins;
        }, */

        /**
         * Get payment method type.
         * Этот метод определен в базовом классе
         * и метод есть в модели метода на бэкэнде?!
         */
         /* getTitle: function () {
            // this.item.title
            return 'Loyalty Credit Coins' + ' - You have ' + 
                this.getAmountLoyaltyCreditCoins() +
                ' coins!';
        }, */

        /**
         * @return {Boolean}
         * Проверять особо нечего!
         */
         /* validate: function () {
            // Можно выолнить проверку перед размещением заказа и
            // отправки данных на сервер. Ложь - если не отправлять
            return true;
        }, */
    });
}
);