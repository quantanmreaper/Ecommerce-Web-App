<?php

namespace App\Livewire;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;

#[Title('Home Page - My E-commerce Site')]

class HomePage extends Component
{
    public function render()
    {
        $brands = Brand::where('is_active', true)->get();
        return view('livewire.home-page',[
            'brands' => $brands
        ]);
    }
}
#according to the tutorial i am following this is like a controller
#its for the business logic of the home page
