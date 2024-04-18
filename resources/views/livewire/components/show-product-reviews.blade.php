@if ($reviews->isEmpty())
<p>No reviews</p>
@else
@foreach($reviews as $review)
<article class="max-w-md my-5">
    <p class="capitalize text-sm">{{ $review->customer->fullname }}</p>
    <x-bladewind::rating clickable="false" :rating="floor($review->star_rating)" color="yellow" />
    <p class="text-muted-foreground text-xs">{{$review->created_at->format('Y-m-d H:i')}}</p>
    <p>{{ $review->review }}</p>
</article>
@endforeach
@endif

{{ $reviews->links()}}