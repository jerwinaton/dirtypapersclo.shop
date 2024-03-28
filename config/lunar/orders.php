<?php

use Illuminate\Support\Facades\Mail;
use Lunar\Base\OrderReferenceGenerator;

return [

    /*
    |--------------------------------------------------------------------------
    | Order Reference Generator
    |--------------------------------------------------------------------------
    |
    | Here you can specify how you want your order references to be generated
    | when you create an order from a cart.
    |
    */
    'reference_generator' => OrderReferenceGenerator::class,

    /*
    |--------------------------------------------------------------------------
    | Draft Status
    |--------------------------------------------------------------------------
    |
    | When a draft order is created from a cart, we need an initial status for
    | the order that's created. Define that here, it can be anything that would
    | make sense for the store you're building.
    |
    */
    'draft_status' => 'awaiting-payment',

    'statuses' => [
        'dispatched' => [
            'color' => '#34eb77',
            'label' => 'Dispatched',
            'mailers' => [\App\Mail\OrderShipped::class],
            'notifications' => [],
        ],
        'completed' => [
            'color' => '#0EA5E9',
            'label' => 'Completed',
            'mailers' => [\App\Mail\OrderCompleted::class],
            'notifications' => [],
        ],

        'awaiting-payment' => [
            'label' => 'Awaiting Payment',
            'color' => '#848a8c',
            'mailers' => [],
            'notifications' => [],
        ],

        'payment-offline' => [
            'label' => 'Payment Offline',
            'color' => '#0A81D7',
            'mailers' => [],
            'notifications' => [],
        ],

        'payment-received' => [
            'label' => 'Payment Received',
            'color' => '#6a67ce',
            'mailers' => [\App\Mail\OrderPlaced::class],
            'notifications' => [],
        ],
        'cancelled' => [
            'label' => 'Cancelled',
            'color' => '#E75F5F',
            'mailers' => [],
            'notifications' => [],
        ],


    ],

    /*
    |--------------------------------------------------------------------------
    | Order Pipelines
    |--------------------------------------------------------------------------
    |
    | Define which pipelines should be run throughout an orders lifecycle.
    | The default ones provided should suit most needs, however you are
    | free to add your own as you see fit.
    |
    | Each pipeline class will be run from top to bottom.
    |
    */
    'pipelines' => [
        'creation' => [
            Lunar\Pipelines\Order\Creation\FillOrderFromCart::class,
            Lunar\Pipelines\Order\Creation\CreateOrderLines::class,
            Lunar\Pipelines\Order\Creation\CreateOrderAddresses::class,
            Lunar\Pipelines\Order\Creation\CreateShippingLine::class,
            Lunar\Pipelines\Order\Creation\CleanUpOrderLines::class,
            Lunar\Pipelines\Order\Creation\MapDiscountBreakdown::class,
        ],
    ],

];
