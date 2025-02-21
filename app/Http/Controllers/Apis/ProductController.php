<?php

namespace App\Http\Controllers\Apis;

use App\Models\Brand;
use App\Models\Product;
use App\Http\traits\media;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\traits\ApiTrait;

class ProductController extends Controller
{
    use media, ApiTrait;
    public function index()
    {
        // create api response    // *eloquent ORM*   
        $products = Product::all(); // select * from products   
        return $this->Data(compact('products'), "All Products"); // return data
    }

    public function create()
    {
        $brands = Brand::all();
        $subcategories = Subcategory::select('id', 'name_en')->get();
        return $this->Data(compact('brands', 'subcategories'));  // return data
    }

    public function edit($id)
    {
        // $product = Product::where('id',$id);
        // $product = Product::find($id);   // get id only without valitation
        $product = Product::findOrFail($id);  // get id with validation 
        $brands = Brand::all();
        $subcategories = Subcategory::select('id', 'name_en')->get();
        return $this->Data(compact('product', 'brands', 'subcategories')); // return data
    }

    public function store(StoreProductRequest $request)
    {
        //upload photo 
        $photoName = $this->uploadPhoto($request->image, 'products');
        // insert photo in db
        $data = $request->except('image');
        $data['image'] = $photoName;
        Product::create($data);
        return $this->SuccessMessage('Product created successfuly', 201); // return meesage
    }

    public function update(UpdateProductRequest $request, $id)
    {

        $data = $request->except('image', '_method');
        if ($request->has('image')) {
            // delete old photo and update new photo in db 
            $oldPhotoName = Product::find($id)->image;
            $photoPath = public_path('/dist/img/products/') . $oldPhotoName;
            $this->deletePhoto($photoPath);
            // upload image
            $photoName = $this->uploadPhoto($request->image, 'products');
            $data['image'] = $photoName;
        }
        // update product in db 
        Product::where('id', $id)->update($data);
        // redirect 
        return $this->SuccessMessage('Product updated successfuly'); // return message
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if ($product) {
            // delete photo 
            $oldPhotoName = $product->image;
            $photoPath = public_path('/dist/img/products/') . $oldPhotoName;
            $this->deletePhoto($photoPath);
            // delete product 
            Product::where('id', $id)->delete();
            return $this->SuccessMessage('Product deleted successfuly'); // return success message
        } else {
            return $this->ErrorMessage(['id' => 'The id is invalid'], 'The given data is invalid', 422); // return error message
        }
    }
}