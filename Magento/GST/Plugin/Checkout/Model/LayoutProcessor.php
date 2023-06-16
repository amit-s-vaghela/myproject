<?php

namespace Magento\GST\Plugin\Checkout\Model;

use Magento\Checkout\Block\Checkout\LayoutProcessor as ChekcoutLayerprocessor;

class LayoutProcessor
{
    public function afterProcess(ChekcoutLayerprocessor $subject, array $jsLayout)
    {
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['gst_number'] = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'options' => [],
                'id' => 'gst_number'
            ],
            'dataScope' => 'shippingAddress.gst_number',
            'label' => 'GST IN',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [],
            'sortOrder' => 252,
            'id' => 'gst_number'
        ];
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                ['shippingAddress']['children']['shipping-address-fieldset']['children']['company_name'] = [
                    'component' => 'Magento_Ui/js/form/element/abstract',
                    'config' => [
                        'customScope' => 'shippingAddress',
                        'template' => 'ui/form/field',
                        'elementTmpl' => 'ui/form/element/input',
                        'options' => [],
                        'id' => 'company_name'
                    ],
                    'dataScope' => 'shippingAddress.company_name',
                    'label' => 'Company Name',
                    'provider' => 'checkoutProvider',
                    'visible' => true,
                    'validation' => [],
                    'sortOrder' => 253,
                    'id' => 'company_name'
                ];

        return $jsLayout;
    }
}
