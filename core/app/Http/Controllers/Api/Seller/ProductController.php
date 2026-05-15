<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Traits\ProductManager;
use App\Traits\ProductVariantManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ProductManager, ProductVariantManager;

    public function __construct()
    {
        // Custom id and seller methods for API context
    }

    protected function seller()
    {
        return auth()->user();
    }

    protected function id()
    {
        return $this->seller()->id;
    }

    public function index()
    {
        $products = Product::where('seller_id', $this->id())->latest()->paginate(getPaginate());
        $notify[] = 'Seller product list';
        return response()->json([
            'remark' => 'seller_products',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'products' => $products,
            ]
        ]);
    }



    public function edit($id)
    {
        $data = $this->editProduct($id, $this->id());
        $notify[] = 'Product edit data';


        return response()->json([
            'remark' => 'product_edit',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => $data
        ]);
    }

    public function store(Request $request, $id)
    {
        $validationRules = $this->getProductValidationRule($id);
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        // Note: Trait's storeProduct returns notify array.
        $result = $this->storeProduct($request, $id, $this->id());

        $status = 'success';
        foreach ($result as $msg) {
            if ($msg[0] == 'error') $status = 'error';
        }

        return response()->json([
            'remark' => $id == 0 ? 'product_added' : 'product_updated',
            'status' => $status,
            'message' => [$status => $result],
        ]);
    }

    public function variants($productId)
    {
        $data = $this->addProductVariant($productId, $this->id());
        $notify[] = 'Product variants data';
        return response()->json([
            'remark' => 'product_variants',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => $data
        ]);
    }

    public function storeVariant(Request $request, $productId)
    {
        $result = $this->storeProductVariant($request, $productId, $this->id());
        $status = 'success';
        foreach ($result as $msg) {
            if ($msg[0] == 'error') $status = 'error';
        }
        return response()->json([
            'remark' => 'variant_stored',
            'status' => $status,
            'message' => [$status => $result],
        ]);
    }

    public function updateVariant(Request $request, $variantId)
    {
        $result = $this->updateProductVariant($request, $variantId, $this->id());
        $status = 'success';
        foreach ($result as $msg) {
            if ($msg[0] == 'error') $status = 'error';
        }
        return response()->json([
            'remark' => 'variant_updated',
            'status' => $status,
            'message' => [$status => $result],
        ]);
    }

    public function deleteVariant($variantId)
    {
        $result = $this->deleteProductVariant($variantId, $this->id());
        return response()->json([
            'remark' => 'variant_deleted',
            'status' => 'success',
            'message' => ['success' => $result],
        ]);
    }

    public function variantImages($variantId)
    {
        $data = $this->addProductVariantImages($variantId, $this->id());
        $notify[] = 'Variant images data';
        return response()->json([
            'remark' => 'variant_images',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => $data
        ]);
    }

    public function saveVariantImages(Request $request, $variantId)
    {
        $result = $this->saveProductVariantImages($request, $variantId, $this->id());
        $status = 'success';
        foreach ($result as $msg) {
            if ($msg[0] == 'error') $status = 'error';
        }
        return response()->json([
            'remark' => 'variant_images_saved',
            'status' => $status,
            'message' => [$status => $result],
        ]);
    }

    public function delete($id)
    {
        $result = $this->deleteProduct($id, $this->id());
        return response()->json([
            'remark' => 'product_deleted',
            'status' => 'success',
            'message' => ['success' => $result],
        ]);
    }

}
