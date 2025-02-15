<a href="{{ route('product.show', $product->slug) }}" 
  class="swiper-slide bg-white hover:cursor-pointer rounded-lg overflow-hidden w-full sm:w-max border border-pink-200 flex items-center p-4 sm:p-6 space-x-3 sm:space-x-4 h-24 sm:h-32">
 <!-- Gambar Produk -->
 @if ($product->productVariant->isNotEmpty())
   <img 
     src="{{ asset('storage/' . (is_array($product->productVariant->first()->image) ? $product->productVariant->first()->image[0] : $product->productVariant->first()->image)) }}" 
     alt="{{ $product->name }}" 
     class="h-12 w-12 sm:h-16 sm:w-16 rounded-lg object-cover flex-shrink-0"
   >
 @else
   <img 
     src="{{ asset('storage/' . (is_array($product->image) ? $product->image[0] : $product->image)) }}" 
     alt="{{ $product->name }}" 
     class="h-12 w-12 sm:h-16 sm:w-16 rounded-lg object-cover flex-shrink-0"
   >
 @endif

 <!-- Konten Teks -->
 <div class="flex-1 overflow-hidden">
   <h3 class="text-gray-700 font-semibold text-base sm:text-lg line-clamp-2">
     {{ $product->name }}
   </h3>
   <p class="text-gray-600 text-xs sm:text-sm line-clamp-2 mt-1">
     {{ $product->description }}
   </p>
 </div>
</a>