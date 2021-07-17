<?php

namespace App\Http\Controllers\api\product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Product;
use Illuminate\Support\Facades\Validator;
use App\Traits\generalTrait;

class productController extends Controller
{
    use generalTrait;

    public function index()
    {
        $lang = $this->getCurrentLang();
        $products = Product::select('id' , 'name_'.$lang.' AS name' , 'detail_'.$lang.' AS detail' , 'price' , 'photo')->paginate(3);
        return $this->returnData('products' , $products);
    } 
    public function store(Request $request)
    {
        $rules = [
            'name_en'=>'required|unique:products,name_en|string|min:2',
            'name_ar'=>'required|unique:products,name_en|string|min:2',
            'price'=>'required|numeric|min:1',
            'brand_id'=>'required|integer|exists:brands,id',
            'supplier_id'=>'required|integer|exists:suppliers,id',
            'subcat_id'=>'required|integer|exists:subcategorys,id',
            'photo'=>'required|image|mimes:png,jpg,jpeg|max:1024' //max with KB
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->returnValidationError($validator);
        }
        $imageName = $this->upploadImage($request->photo , 'products');
        $data = $request->except('photo');
        $data['photo'] = $imageName;

        Product::insert($data);
        return $this->returnSuccessMessage('the product has been successfully saved');
        
    }

    public function update(Request $request)
    {
        $rules = [
            'id'=>'required|exists:products,id',
            'name_en'=>'required|string|min:2',
            'name_ar'=>'required|string|min:2',
            'price'=>'required|numeric|min:1',
            'brand_id'=>'required|integer|exists:brands,id',
            'supplier_id'=>'required|integer|exists:suppliers,id',
            'subcat_id'=>'required|integer|exists:subcategorys,id',
            'photo'=>'image|mimes:png,jpg,jpeg|max:1024' //max with KB
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->returnValidationError($validator);
        }
        $data = $request->except('id');
        if($request->has('photo')){
            $imageName = $this->upploadImage($request->photo , 'products');
            $data = $request->except('photo','id');
            $data['photo'] = $imageName;
        }
        Product::where('id','=',$request->id)->update($data);
        return $this->returnSuccessMessage('the product has been successfully updated');
    }
    public function delete(Request $request)
    {
        $rules  = [
            'id'=>'required|exists:products,id'
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->returnValidationError($validator);
        }
        $product = Product::find($request->id);
        $photpath = public_path('images\products\\'.$product->photo);
        if(file_exists($photpath)){
            unlink($photpath);
        }
        Product::destroy($request->id);
        return $this->returnSuccessMessage('the product has been successfully deleted');
    }
}
