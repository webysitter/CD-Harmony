<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Product Card -->
    <div class="bg-white shadow-lg p-4">
        <img src="image_url_here" alt="Product Image" class="w-full h-48 object-cover object-center">
        <h2 class="text-2xl font-semibold mt-2">CD Title</h2>
        <p class="text-sm text-gray-600">Artist: Artist Title</p>
        <p class="mt-2">Album Description here...</p>
        <div class="mt-4">
            <div class="text-lg font-semibold mb-2">Price:</div>
            <div>
                <input type="radio" name="condition" value="new" id="new" class="mr-1">
                <label for="new">New: $NewPriceHere</label>
            </div>
            <div>
                <input type="radio" name="condition" value="old" id="old" class="mr-1">
                <label for="old">Old: $OldPriceHere</label>
            </div>
        </div>
        <div class="mt-4">
            <div class="text-lg font-semibold">Stock Quantities:</div>
            <div>
                <button class="px-4 py-2 bg-green-500 text-white rounded mr-2">+</button>
                <span>10</span>
                <button class="px-4 py-2 bg-red-500 text-white rounded ml-2">-</button>
            </div>
        </div>
    </div>
    <!-- Repeat the above structure for each product -->
</div>
