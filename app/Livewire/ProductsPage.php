<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Livewire\WithPagination;

#[Title('Products Page')]
class ProductsPage extends Component
{
    use WithPagination;
    public function render()
    {
        $products = Product::where('is_active', true)->paginate(6);
        return view('livewire.products-page', [
            'products' => $products,
            'brands' => Brand::where('is_active', true)->get(['id', 'name', 'slug']),
            'categories' => Category::where('is_active', true)->get(['id', 'name', 'slug']),
        ]);
    }
}
