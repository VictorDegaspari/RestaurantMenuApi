<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use Illuminate\Http\Request;
use App\Http\Resources\DemandResource as DemandResource;
use App\Http\Controllers\BaseController as BaseController;

use App\Models\Product;
use Validator;

class DemandsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Demand::with([ 'products' ])
        ->where('user_id', auth()->user()->id)
        ->paginate(25);

        return $this->sendResponse($courses, 'Demands retrieved successfully.');
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
            'products' => 'required',
            'quantities' => 'required',
        ]);

        if ($validator->fails()) return $this->sendError('Validation Error.', $validator->errors());

        $demand = Demand::create([ 'user_id' => auth()->user()->id ]);

        $productWithQuantity = [];
        foreach ($data['products'] as $key => $product) {
            $productWithQuantity[$product] = ['quantity' => empty($data['quantities'][$key]) ? 0 : $data['quantities'][$key]];
        }
        $demand->products()->sync($productWithQuantity);

        return $this->sendResponse(new DemandResource($demand), 'Demand created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $demand = Demand::with([ 'products' ])
        ->where('id', $id)
        ->first();

        if (is_null($demand)) return $this->sendError('Demand not found.');

        return $this->sendResponse($demand, 'Demand retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Demand $demand)
    {
        $data = $request->all();
        $demand->save();

        return $this->sendResponse($demand, 'Demand updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Demand $demand)
    {
        $demand->delete();
        return $this->sendResponse([], 'Demand deleted');
    }
}
