<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\traits\media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use media;
    public function index()
    {
        // get all product 

        $products = DB::table('products')
            ->select('id', 'name_en', 'name_ar', 'price', 'status', 'quantity', 'code', 'created_at')
            ->get();
        return view('backend.products.index', compact('products'));
    }

    public function create()
    {
        $brands = DB::table('brands')->get();
        $subcategories = DB::table('subcategories')->select('id', 'name_en')->get();
        return view('backend.products.create', compact('brands', 'subcategories'));
    }

    public function edit($id)
    {
        $brands = DB::table('brands')->get();
        $subcategories = DB::table('subcategories')->select('id', 'name_en')->get();
        $product = DB::table('products')->where('id', $id)->first(); // return data as object
        return view('backend.products.edit', compact('product', 'brands', 'subcategories'));
    }

    public function store(StoreProductRequest $request)
    {
        // write validation logic
        // validation
        //upload photo 
        $photoName = $this->uploadPhoto($request->image, 'products');
        // insert photo in db
        $data = $request->except('_token', 'image', 'page');
        $data['image'] = $photoName;
        DB::table('products')->insert($data);
        //redirect
        $this->redirectAcordingToRequest($request);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        // validation 
        // if photo exists => upload it
        $data = $request->except('_token', '_method', 'page', 'image');
        if ($request->has('image')) {
            // delete old photo and update new photo in db 
            $oldPhotoName = DB::table('products')->select('image')->where('id', $id)->first()->image;
            $photoPath = public_path('/dist/img/products/');
            $this->deletePhoto($photoPath . $oldPhotoName);
            // upload image
            $photoName = $this->uploadPhoto($request->image, 'products');
            $data['image'] = $photoName;
        }
        // update product in db 
        DB::table('products')->where('id', $id)->update($data);
        // redirect 
        $this->redirectAcordingToRequest($request);
    }

    public function delete($id)
    {
        // delete photo 
        $oldPhotoName = DB::table('products')->select('image')->where('id', $id)->first()->image;
        $photoPath = public_path('/dist/img/products/');
        $this->deletePhoto($photoPath . $oldPhotoName);
        // delete product 
        DB::table('products')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Successfuly deleted');
    }
    // redirect method
    private function redirectAcordingToRequest($request)
    {
        if ($request->page == 'back') {
            return redirect()->back()->with('success', 'Successfuly updated');
        } else {
            return redirect()->route('product.index')->with('success', 'Successfuly updated');
        }
    }
}
