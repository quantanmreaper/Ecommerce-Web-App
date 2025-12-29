<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cookie;
use App\Models\Product;
class CartManagement{

    //add item to cart
    public static function addItemToCart($product_id){
        $cart_items = self::getCartItemsFromCookie();

        $existing_item_key = null;
        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                $existing_item_key = $key;
                break;
            }
        }

        if($existing_item_key !== null){
            $cart_items[$existing_item_key]['quantity']++;
            $cart_items[$existing_item_key]['total_amount'] = $cart_items[$existing_item_key]['quantity'] *
            $cart_items[$existing_item_key]['unit_amount'];
        }else{
            $product = Product::where('id', $product_id)->first(['id', 'name', 'price','images']);
            if($product){
                $cart_items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_amount' => $product->price,
                    'quantity' => 1,
                    'total_amount' => $product->price,
                    'product_image' => $product->images[0],
                ];
            }

        }
        self::addCartItemsToCookie($cart_items);
        return count($cart_items);

    }

    //add item to cart with quantity
    public static function addItemToCartWithQty($product_id,$qty =1){
        $cart_items = self::getCartItemsFromCookie();

        $existing_item_key = null;
        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                $existing_item_key = $key;
                break;
            }
        }

        if($existing_item_key !== null){
            $cart_items[$existing_item_key]['quantity'] = $qty;
            $cart_items[$existing_item_key]['total_amount'] = $cart_items[$existing_item_key]['quantity'] *
            $cart_items[$existing_item_key]['unit_amount'];
        }else{
            $product = Product::where('id', $product_id)->first(['id', 'name', 'price','images']);
            if($product){
                $cart_items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_amount' => $product->price,
                    'quantity' => $qty,
                    'total_amount' => $product->price,
                    'product_image' => $product->images[0],
                ];
            }

        }
        self::addCartItemsToCookie($cart_items);
        return count($cart_items);

    }


    //



    //remove item from cart
    public static function removeCartItem($product_id){
        $cart_items = self::getCartItemsFromCookie();
        foreach($cart_items as $key => $item){
            if($item['product_id']== $product_id){
                unset($cart_items[$key]);
            }
        }
        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }


    //add cart items to cookie
    public static function addCartItemsToCookie($cart_items){
        Cookie::queue('cart_items', json_encode($cart_items), 60*24*30); //store for 30 days
    }



    //clean cart items from cookie
    public static function clearCartItems(){
        Cookie::queue(Cookie::forget('cart_items'));
    }


    //get cart items from cookie
    public static function getCartItemsFromCookie(){
        $cart_items = json_decode(Cookie::get('cart_items'), true);
        if(!$cart_items){
            $cart_items = [];
        }
        return $cart_items;
    }


    //increment item quantity
    public static function incrementQuantityToCartItem($product_id){
        $cart_items = self::getCartItemsFromCookie();

        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                $cart_items[$key]['quantity']++;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] *
                $cart_items[$key]['unit_amount'];
                //break;
            }
        }
        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }


    //decrement item quantity
    public static function decrementQuantityToCartItem($product_id){
        $cart_items = self::getCartItemsFromCookie();

        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                if($cart_items[$key]['quantity'] > 1){
                    $cart_items[$key]['quantity']--;
                    $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] *
                    $cart_items[$key]['unit_amount'];
                }
                //break;
            }
        }
        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }


    //caluclate total price of cart items (grand total)
    public static function calculateGrandTotal($items){
        return array_sum(array_column($items, 'total_amount'));
    }



}
