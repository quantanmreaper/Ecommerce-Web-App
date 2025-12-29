<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Livewire\Partials\Navbar;

#[Title('Products Page')]
class ProductsPage extends Component
{
    use WithPagination;

    #[Url]
    public $selected_categories = [];

    #[Url]
    public $selected_brands = [];

    #[Url]
    public $featured = null;

    #[Url]
    public $on_sale = null;

    #[Url]
    public $price_range= 300000;

    #[Url]
    public $sort = 'latest';

    //add product to cart
    public function addToCart($product_id){
        $total_count = CartManagement::addItemToCart($product_id);

        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);
    }

    public function render()
    {

        if(!empty($this->selected_categories)) {
            $products = Product::where('is_active', true)
                ->whereIn('category_id', $this->selected_categories)
                ->paginate(9);
            return view('livewire.products-page', [
                'products' => $products,
                'brands' => Brand::where('is_active', true)->get(['id', 'name', 'slug']),
                'categories' => Category::where('is_active', true)->get(['id', 'name', 'slug']),
            ]);
        }
        if(!empty($this->selected_brands)) {
            $products = Product::where('is_active', true)
                ->whereIn('brand_id', $this->selected_brands)
                ->paginate(9);
            return view('livewire.products-page', [
                'products' => $products,
                'brands' => Brand::where('is_active', true)->get(['id', 'name', 'slug']),
                'categories' => Category::where('is_active', true)->get(['id', 'name', 'slug']),
            ]);
        }

        $query = Product::where('is_active', true);

        if ($this->featured) {
            $query->where('is_featured', 1);
        }
        if ($this->on_sale) {
            $query->where('on_sale', 1);
        }

        if( $this->price_range ) {
            $query->whereBetween('price', [0, $this->price_range]);
        }

        if($this->sort == 'latest'){
            $query->latest();
        }

        if($this->sort == 'price'){
            $query->orderBy('price', 'ASC');
        }

        $products = $query->paginate(9);
        return view('livewire.products-page', [
            'products' => $products,
            'brands' => Brand::where('is_active', true)->get(['id', 'name', 'slug']),
            'categories' => Category::where('is_active', true)->get(['id', 'name', 'slug']),
        ]);
    }
}
