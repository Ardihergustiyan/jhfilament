<a href="{{ route('product.show', $product->slug) }}" class="swiper-slide bg-white hover:cursor-pointer rounded-lg overflow-hidden w-max border border-pink-200 flex items-center p-6 space-x-4">
  @if ($product->productVariant->isNotEmpty())
      <img 
          src="{{ asset('storage/' . (is_array($product->productVariant->first()->image) ? $product->productVariant->first()->image[0] : $product->productVariant->first()->image)) }}" 
          alt="{{ $product->name }}" 
          class="h-16 w-16 rounded-lg object-cover"
      >
  @else
      <img 
          src="{{ asset('storage/' . (is_array($product->image) ? $product->image[0] : $product->image)) }}" 
          alt="{{ $product->name }}" 
          class="h-16 w-16 rounded-lg object-cover"
      >
  @endif
  <div>
    <h3 class="text-gray-700 font-semibold text-lg">{{ count(explode(' ', $product->name)) > 4   
      ? implode(' ', array_slice(explode(' ', $product->name), 0, 4)) . '...' 
      : $product->name }}</h3>
    <p class="text-gray-600 text-sm">
      {{ count(explode(' ', $product->description)) > 7 
          ? implode(' ', array_slice(explode(' ', $product->description), 0, 7)) . '...' 
          : $product->description }}
  </p>
  </div>
</a>