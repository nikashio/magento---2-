<?php

namespace Dev\ProductComments\Plugin\Checkout\Model;

use Magento\Checkout\Block\Checkout\LayoutProcessor as ChekcoutLayerprocessor;

class LayoutProcessor
{

    public function afterProcess(
    ChekcoutLayerprocessor $subject,
    array $jsLayout
    ) {
    $customAttributeCode = 'custom_field';
    $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
    ['shippingAddress']['children']['shipping-address-fieldset']['children']['custom_field'] = [
    'component' => 'Magento_Ui/js/form/element/abstract',
    'config' => [
    'customScope' => 'shippingAddress.custom_attributes',
    'template' => 'ui/form/field',
    'elementTmpl' => 'ui/form/element/input',
    'options' => [],
    'id' => 'custom_field'
    ],
    'dataScope' => 'shippingAddress.custom_attributes' . '.' . $customAttributeCode,
    'label' => 'custom_field',
    'provider' => 'checkoutProvider',
    'visible' => true,
    'validation' => [],
    'sortOrder' => 252,
    'id' => 'custom_field'
    ];

    return $jsLayout;
    }
}