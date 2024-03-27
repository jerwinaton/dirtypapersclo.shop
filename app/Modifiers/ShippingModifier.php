<?php

namespace App\Modifiers;

use Lunar\DataTypes\Price;
use Lunar\DataTypes\ShippingOption;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Cart;
use Lunar\Models\TaxClass;

class ShippingModifier
{
    public function handle(Cart $cart)
    {
        // Get the tax class
        $taxClass = TaxClass::getDefault();

        ShippingManifest::addOption(
            new ShippingOption(
                name: 'Regular Delivery',
                description: 'Regular Delivery',
                identifier: 'REGDEL',
                price: new Price(4900, $cart->currency, 1),
                taxClass: $taxClass
            )
        );

        ShippingManifest::addOption(
            new ShippingOption(
                name: 'Midsize Delivery',
                description: 'Midsize Delivery (5-10 items)',
                identifier: 'MIDDEL',
                price: new Price(9900, $cart->currency, 1),
                taxClass: $taxClass
            )
        );
        ShippingManifest::addOption(
            new ShippingOption(
                name: 'Large Delivery',
                description: 'Large Delivery (11 or more items)',
                identifier: 'EXTDEL',
                price: new Price(14900, $cart->currency, 1),
                taxClass: $taxClass
            )
        );
    }
}
