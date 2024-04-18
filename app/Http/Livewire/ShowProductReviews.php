<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Lunar\Models\ProductReview;

class ShowProductReviews extends Component
{

    public $productId;

    public function mount($productId)
    {
        $this->productId = $productId;
    }


    public function render()
    {
        $productReviews = ProductReview::where('product_id', $this->productId)->paginate(10);

        return view('livewire.components.show-product-reviews', [
            'reviews' => $productReviews,
        ]);
        return view('livewire.components.show-product-reviews');
    }
}
