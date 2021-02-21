<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

use Gloudemans\Shoppingcart\Facades\Cart;


class ShopComponent extends Component {

    use WithPagination;

    public $sorting;
    public $pagesize;

    public function mount(){
        $this->sorting = "default";
        $this->pagesize = 12;
    }

    public function store($product_id, $product_name, $product_price){
        Cart::add($product_id, $product_name, 1, $product_price)->associate('App\Models\Product');
        session()->flash('success_message' , 'Articolo aggiunto nel carrello');
        return redirect()->route('product.cart');
    }

    public function render() {

        if($this->sorting =='date'){
            $products = Product::orderBy('created_at' , 'DESC')->paginate($this->pagesize);
        }else if($this->sorting == 'price'){
            $products = Product::orderBy('regular_price' , 'ASC')->paginate($this->pagesize);
        }else if($this->sorting == 'price-desc'){
            $products = Product::orderBy('regular_price' , 'DESC')->paginate($this->pagesize);
        }else{
            $products = Product::paginate($this->pagesize);
        }
        $categories = Category::all();

        return view('livewire.shop-component', ['products'=>$products, 'categories'=>$categories])->layout('layouts.base');
    }
}
