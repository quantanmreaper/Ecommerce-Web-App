<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

#[Title('My Orders Page')]
class MyOrdersPage extends Component
{
    use WithPagination;
    public function render()
    {
        $my_orders = Order::where('user_id', auth()->guard()->user()->id)->with('address')->latest()->paginate(5);
        return view('livewire.my-orders-page', [
            'my_orders' => $my_orders,
        ]);
    }
}
