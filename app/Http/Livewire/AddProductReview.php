<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Lunar\Models\ProductReview;

class AddProductReview extends Component
{
    public $productVariantId;
    public $productId;
    public $review;
    public $starRating;

    protected $rules = [
        'review' => 'required|min:5',
        'starRating' => 'required|numeric|min:1|max:5',
    ];

    public function mount($productId, $productVariantId)
    {
        $this->productId = $productId;
        $this->productVariantId = $productVariantId;
        $this->starRating = 2;
    }

    public function render()
    {

        return view('livewire.components.add-product-review');
    }

    public function submitReview()
    {
        $validatedData = $this->validate();

        ProductReview::create([
            'review' => $validatedData['review'],
            'star_rating' => $validatedData['starRating'],
            'product_id' =>  $this->productId,
            'product_variant_id' => $this->productVariantId,
            // You may need to adjust this if you have a relationship with product variants.
            'customer_id' => auth()->id(), // Assuming the current user is the customer
        ]);

        $this->reset(['review', 'starRating']);

        // Emit an event to close the modal after submitting the review
        $this->dispatchBrowserEvent('close');

        $this->emit('reviewSubmitted');
    }
    // Method to handle rating changes
    public function setRating($newRating)
    {
        $this->starRating = $newRating;
    }
}
