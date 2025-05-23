<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Filter</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .filters { margin-bottom: 20px; }
        select, input, button { margin: 0 10px; padding: 5px; }
        .product-list { display: flex; flex-wrap: wrap; gap: 10px; }
        .product { border: 1px solid #ccc; padding: 10px; width: 200px; }
    </style>
</head>
<body>
    <h1>Product Filter</h1>

    <form method="GET" action="{{ route('products.index') }}">
        <div class="filters">
            <label for="category">Category:</label>
            <select name="category" id="category" onchange="this.form.submit()">
                <option value="">All</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>

            <label for="min_price">Min Price:</label>
            <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" placeholder="0" step="0.01">

            <label for="max_price">Max Price:</label>
            <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" placeholder="1000" step="0.01">

            <label for="sort">Sort by Popularity:</label>
            <select name="sort" id="sort" onchange="this.form.submit()">
                <option value="">Default</option>
                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Low to High</option>
                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>High to Low</option>
            </select>

            <button type="submit">Apply Filters</button>
        </div>
    </form>

    <div class="product-list">
        @forelse ($products as $product)
            <div class="product">
                <strong>{{ $product->name }}</strong><br>
                Category: {{ $product->category->name }}<br>
                Price: ${{ number_format($product->price, 2) }}<br>
                Popularity: {{ $product->popularity }}
            </div>
        @empty
            <p>No products found.</p>
        @endforelse
    </div>
</body>
</html>