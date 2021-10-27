<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;



class ProductController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {

      $products = Product::all();
      return $products;
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {

      $request->validate([
         'name' => 'required',
         'price' => 'required',
         'slug' => 'required',
      ]);

      $product = new Product;

      $product->name = $request->input('name');
      $product->price = $request->input('price');
      $product->descrition = $request->input('descrition');
      $product->slug = $request->input('slug');
      //  $product->file_path = $request->file('file')->store('products') ;
      $product->save();

      return $product;
   }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
      $product = Product::find($id);
      if (!$product) {
         return "Product with this ID doesn't exist anymore!!!";
      };

      return $product;
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $id)
   {
      $product = Product::find($id);

      $request->validate([
         'name' => 'required',
         'price' => 'required',
         'slug' => 'required',
      ]);

      //Moze i vaka skrateno direkt se od request neka se updatira
      // $product->update($request->all());

      $product->name = $request->input('name');
      $product->price = $request->input('price');
      $product->descrition = $request->input('descrition');
      $product->slug = $request->input('slug');
      //  $product->file_path = $request->file('file')->store('products') ; //ako ima slika ili file e ova 
      $product->save();

      return $product;
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function delete($id)
   {
      //$product = Product::where('id', $id);
      $product = Product::find($id);
      if (!$product) {
         return "No product with this ID was found!!!";
      } else {
         $product->delete();
         return "Product was deleted!!!";
      };


      //Same
      // $result = Product::where('id', $id)->delete(); //mora delete right after where or error

      // if ($result) {
      //    return ["result" => "product has been deleted"];
      // } else {
      //    return ['There is no such product in database';
      // }
   }

   //Za da imame search preku imeto na produktot
   public function search($key)
   {
      //
      // $result = Product::where('name', $key)->get(); //For full name search 
      $product = Product::where('name', 'Like', "%$key%")->get();

      return $product;
   }
}
