define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (
    Component,
    rendererList
) {
    'use strict'
    rendererList.push(
        {
            'type': 'simplepayment',
            'component': 'OmniPro_SimplePaymentMethod/js/view/payment/method-renderer/simplepayment-method'
        }
    );
    return Component.extend({});
});