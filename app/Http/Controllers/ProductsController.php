<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Product;
use Validator;
use App\Http\Resources\ProductResource as ProductResource;
use App\Models\Demand;

class ProductsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(25);
        return $this->sendResponse($products, 'Products retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required',
            'price' => 'required',
            'image' => 'required',
        ]);

        if ($validator->fails()) return $this->sendError('Validation Error.', $validator->errors());

        $product = Product::create($data);

        return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::where('id', $id)->first();

        if (is_null($product)) return $this->sendError('Product not found.');

        return $this->sendResponse($product, 'Product retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->all();

        if (!empty($data[ 'detail' ])) $product->detail = $data['detail'];
        if (!empty($data[ 'name' ])) $product->name = $data['name'];
        if (!empty($data[ 'price' ])) $product->price = $data['price'];
        if (!empty($data[ 'quantity' ])) $product->quantity = $data['quantity'];

        $product->save();
        return $this->sendResponse($product, 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->sendResponse([], 'Product deleted');
    }
}
