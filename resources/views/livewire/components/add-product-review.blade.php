<div class="w-full">
    <form wire:submit.prevent="submitReview">
        <div class="flex flex-col">
            <x-input-label for="review">Review:</x-input-label>
            <textarea wire:model.defer="review" name="review" rows="2"></textarea>
            @error('review') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="flex flex-col">
            <x-input-label for="starRating">Star Rating:</x-input-label>
            <x-bladewind::rating :rating="$starRating" color="yellow" name="starRating" wire:model="$starRating" />
        </div>

        <x-primary-button class="mt-4" type="submit">Submit Review</x-primary-button>
    </form>
</div>