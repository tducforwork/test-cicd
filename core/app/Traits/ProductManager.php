<?php

namespace App\Traits;

use App\Constants\Status;
use App\Models\AssignProductAttribute;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductReview;
use App\Models\ProductStock;
use App\Models\StockLog;
use App\Models\ProductType;
use App\Models\ProductShippingMethod;
use App\Models\ShippingMethod;
use App\Rules\FileTypeValidate;
use Illuminate\Validation\Rule;

trait ProductManager
{
    protected function pageTitle($isTrashed, $searchKey)
    {
        if ($isTrashed) $title  =  __("All Trashed Products");
        else $title  =  __("All Products");

        if ($searchKey) $title  = __("Product Search") . " : '$searchKey'";
        return $title;
    }

    public function products($sellerId = 0, $isTrashed = false)
    {
        $search = trim(strtolower(request()->search));
        $query  = Product::query();

        if ($sellerId) $query  = $query->sellers();
        if ($isTrashed) $query = $query->onlyTrashed();

        if (request()->self_product) {
            $query->where('seller_id', 0);
        }

        if (request()->category_id) {
            $category = Category::findOrFail(request()->category_id);
            $allCategoryIds = $category->getAllChildIds();
            $query->whereHas('categories', function($q) use ($allCategoryIds) {
                $q->whereIn('categories.id', $allCategoryIds);
            });
        }

        $query = $query->with(['categories', 'brands', 'productTypes', 'stocks', 'seller', 'seller.shop']);

        $data['categories']     = Category::with('allSubcategories')->where('parent_id', null)->get();
        $data['products']       = $query->searchable(['name'])->orderBy('id', 'desc')->paginate(getPaginate());
        $data['pageTitle']      = $this->pageTitle($isTrashed, $search);

        return $data;
    }

    public function pendingProducts($sellerId = 0, $isTrashed = false)
    {
        $search = trim(strtolower(request()->search));

        $query  = Product::query();
        if ($sellerId) {
            $query  = $query->sellers();
            $query  = $query->with(['categories', 'brand', 'stocks']);
        }
        if ($isTrashed)
            $query  = $query->onlyTrashed();
        if ($search)
            $query  = $query->where('name', 'like', "%$search%");

        if (request()->category_id) {
            $category = Category::findOrFail(request()->category_id);
            $allCategoryIds = $category->getAllChildIds();
            $query->whereHas('categories', function($q) use ($allCategoryIds) {
                $q->whereIn('categories.id', $allCategoryIds);
            });
        }

        $data['categories']     = Category::with('allSubcategories')->where('parent_id', null)->get();
        $data['products']       = $query->where('status', 0)->orderByDesc('id')->paginate(getPaginate());
        $data['pageTitle']      = __('Pending Products');
        $data['emptyMessage']   = __("No product found");
        return $data;
    }
    public function productByVendor($admin = true, $isTrashed = false)
    {
        $search = trim(strtolower(request()->search));

        $query  = Product::query();
        if ($isTrashed)
            $query  = $query->onlyTrashed();
        if ($search)
            $query  = $query->where('name', 'like', "%$search%");
        if ($admin) {
            $data['pageTitle']      = __('Products By Admin');
            $query = $query->where('seller_id', 0);
        } else {
            $data['pageTitle']      = __('Products By Seller');
            $query = $query->where('seller_id', '!=', 0);
        }

        if (request()->category_id) {
            $category = Category::findOrFail(request()->category_id);
            $allCategoryIds = $category->getAllChildIds();
            $query->whereHas('categories', function($q) use ($allCategoryIds) {
                $q->whereIn('categories.id', $allCategoryIds);
            });
        }

        $data['categories']     = Category::with('allSubcategories')->where('parent_id', null)->get();
        $data['products']       = $query->orderByDesc('id')->paginate(getPaginate());
        return $data;
    }

    public function productCreate()
    {
        $data['categories'] = Category::with('allSubcategories')->where('parent_id', null)->get();
        $data['brands']     = Brand::orderBy('name')->get();
        $data['productTypes'] = ProductType::orderBy('name')->get();
        $data['tags']         = \App\Models\Tag::orderBy('name')->get();
        $data['pageTitle']  = __("Add New Product");
        return $data;
    }

    public function editProduct($id, $sellerId = 0)
    {
        if ($sellerId) {
            $data['product']    = Product::where('seller_id', $sellerId)->where('id', $id)
                ->with('categories', 'productPreviewImages', 'brands', 'productTypes')->firstOrFail();
        } else {
            $data['product']    = Product::where('id', $id)->with('categories', 'productPreviewImages', 'brands', 'productTypes')->first();
        }

        $data['categories']     = Category::with('allSubcategories')->where('parent_id', null)->get();
        $data['brands']         = Brand::orderBy('name')->get();
        $data['productTypes']   = ProductType::orderBy('name')->get();
        $data['tags']           = \App\Models\Tag::orderBy('name')->get();
        $data['images']         = [];

        foreach ($data['product']->productPreviewImages as $key => $image) {
            $img['id'] = $image->id;
            $img['src'] = getImage(getFilePath('product') . '/' . $image->image);
            $data['images'][] = $img;
        }

        $data['pageTitle']      = __("Edit Product");
        return $data;
    }


    public function storeProduct($request, $id, $sellerId = 0)
    {
        $validationRules = $this->getProductValidationRule($id);
        $request->validate($validationRules, [
            'specification.*.name.required'   =>  __('All specification name is required'),
            'specification.*.value'           =>  __('All specification value is required'),
        ]);

        //Check if the sku is already taken
        if ($request->sku && $this->checkSKU($request->sku, $id)) {
            $notify[] = ['error', __('This SKU has already been taken')];
            return $notify;
        }

        $product = new Product();

        if ($id) {
            $product                = Product::findOrFail($id);
            $prev_track_inventory   = $product->track_inventory;
            $prev_has_variants      = $product->has_variants;

            if ($sellerId && $product->seller_id != $sellerId) {
                $notify[] = ['error', __("This product doesn't belong to this seller")];
                return $notify;
            }
        }

        if (!$id) {
            $product->status = $sellerId ? Status::DISABLE : Status::ENABLE;
        }

        if ($request->hasFile('main_image')) {
            try {
                $product->main_image = fileUploader($request->main_image, getFilePath('product'), getFileSize('product'), @$product->main_image, getFileThumb('product'));
            } catch (\Exception $exp) {
                $notify[] = ['error', __('Could not upload the main image')];
                return $notify;
            }
        }

        if (!$id) {
            $product->seller_id = $sellerId;
        }
        
        if (!$id) {
            $product->sku = $this->generateUniqueSKU();
        } elseif (!$product->sku) {
             $product->sku = $this->generateUniqueSKU();
        }
        
        $product->name              = $request->name;
        $product->unit              = $request->unit;
        $product->slug              = $request->slug ?? \Illuminate\Support\Str::slug($request->name);

        $product->has_variants      = $request->has_variants ?? 0;
        $product->show_in_frontend  = 1;
        $product->track_inventory   = 1;
        $product->video_link        = null;
        $product->description       = $request->description;
        $product->summary           = null;
        $product->specification     = null;
        $product->base_price        = $request->base_price ?? 0;
        $product->discount_price    = $request->discount_price ?? 0;
        $product->currency_type     = $request->currency_type ?? 1;
        $product->cny_price         = $request->cny_price ?? null;
        $product->cny_discount_price = $request->cny_discount_price ?? null;
        $product->meta_title        = $request->meta_title;
        $product->meta_description  = $request->meta_description;
        $product->meta_keywords     = $request->meta_keywords ?? null;
        $product->brand_id          = is_array($request->brands) ? @$request->brands[0] : $request->brands;
        $product->is_search         = $request->is_search ? 1 : 0;
        $product->is_topdeal        = $request->is_topdeal ? 1 : 0;
        $product->is_suggestion     = $request->is_suggestion ? 1 : 0;
        $product->flash_percentage  = $request->flash_percentage;
        $product->flash_text        = $request->flash_text;
        $product->condition         = $request->condition;

        $product->save();
        
        if (!$id && $request->quantity != null && !$product->has_variants) {
            $stock = ProductStock::where('product_id', $product->id)->where('attributes', null)->first();
            $qty = $request->quantity;
            
            if ($stock) {
                $diff = $qty - $stock->quantity;
                if ($diff != 0) {
                    $stock->quantity = $qty;
                    $stock->save();
                    
                    $log = new StockLog();
                    $log->stock_id = $stock->id;
                    $log->quantity = $diff;
                    $log->type = 1; // 1 for addition/adjustment
                    $log->save();
                }
            } else {
                $stock = new ProductStock();
                $stock->product_id = $product->id;
                $stock->attributes = null;
                $stock->sku = $product->sku;
                $stock->quantity = $qty;
                $stock->save();
                
                $log = new StockLog();
                $log->stock_id = $stock->id;
                $log->quantity = $qty;
                $log->type = 1;
                $log->save();
            }
        }

        //Check Old Images
        $previous_images = $product->productPreviewImages->pluck('id')->toArray();
        $imageToRemove = array_values(array_diff($previous_images, $request->old ?? []));

        foreach ($imageToRemove as $item) {
            $productImage   = ProductImage::find($item);
            $location       = getFilePath('product');

            fileManager()->removeFile($location . '/' . $productImage->image);
            fileManager()->removeFile($location . '/thumb_' . $productImage->image);
            $productImage->delete();
        }

        if ($request->hasFile('photos')) {
            foreach ($request->photos as $image) {
                try {
                    $image = fileUploader($image, getFilePath('product'), getFileSize('product'), null, getFileThumb('product'));
                    $productImage = new ProductImage();
                    $productImage->product_id   = $product->id;
                    $productImage->image        = $image;
                    $productImage->save();
                } catch (\Exception $exp) {
                    $notify[] = ['error', __('Could not upload additional images')];
                    return $notify;
                }
            }
        }

        $message = $sellerId 
            ? __('Product added successfully and waiting for admin approval') 
            : __('Product added successfully');

        $categories = is_array($request->categories) ? array_filter($request->categories) : [];

        if ($id) {
            $product->categories()->sync($categories);
            $product->productTypes()->sync($request->product_types ?? []);
            $product->tags()->sync($request->tags ?? []);
            $message = __('Product updated successfully');

            //If the value of track_inventory or has_variants is changed then delete the prev inventory
            if (($prev_has_variants != $product->has_variants) || $prev_track_inventory != $product->track_inventory) {
                $product_stocks = $product->stocks();
                foreach ($product_stocks->get() as $st) {
                    $st->stockLogs()->delete();
                }
                $product_stocks->delete();
            }

            // Check stock table to update the sku in stock table
            if ($product->sku) {
                $stock = ProductStock::where('product_id', $product->id)->where('attributes', null)->first();
                if ($stock) {
                    $stock->sku = $product->sku;
                    $stock->save();
                }
            }

            $assignAttributes = AssignProductAttribute::where('product_id', $product->id)->get();
            if (!$product->has_variants) {
                foreach ($assignAttributes as $assignAttribute) {
                    $assignAttribute->status = 0;
                    $assignAttribute->save();
                }
            } else {
                foreach ($assignAttributes as $assignAttribute) {
                    $assignAttribute->status = 1;
                    $assignAttribute->save();
                }
            }
        } else {
            $product->categories()->attach($categories);
            $product->productTypes()->attach($request->product_types ?? []);
            $product->tags()->attach($request->tags ?? []);
        }

        $notify[] = ['success', $message];

        return $notify;
    }

    public function deleteProduct($id, $sellerId = 0)
    {
        $query    = Product::where('id', $id);
        if ($sellerId) $query = $query->where('seller_id', $sellerId);

        $product  = $query->firstOrFail();
        $product->delete();

        $notify[] = ['success', __("Product deleted successfully")];
        return $notify;
    }

    public function restoreProduct($id, $sellerId = 0)
    {
        if ($sellerId) {
            $product = Product::withTrashed()->where('seller_id', $sellerId)->findOrFail($id);
        } else {
            $product = Product::withTrashed()->findOrFail($id);
        }

        $product->restore();
        $notify[] = ['success', __("Product restored successfully")];
        return $notify;
    }

    protected function getProductValidationRule($id)
    {
        $rules =  [
            'name'                  => 'required|string|max:191',
            'condition'             => 'nullable|string|max:191',
            'unit'                  => 'nullable|string|max:40',
            'brand_id'              => 'nullable|integer',
            'base_price'            => 'required|numeric',
            'quantity'              => 'nullable|numeric|min:0',
            'discount_price'        => 'nullable|numeric',
            "categories"            => 'required|array|min:1',
            'description'           => 'required|string',
            'sku'                   => 'nullable',
            'meta_title'            => 'nullable|string',
            'meta_description'      => 'nullable|string',
            'meta_keywords'         => 'nullable|array',
            'meta_keywords.array.*' => 'nullable|string',
            'photos'                => 'required_if:id,0|array|min:1',
            'photos.*'              => ['image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'is_search'             => 'nullable|integer|in:0,1',
            'is_topdeal'            => 'nullable|integer|in:0,1',
            'is_suggestion'         => 'nullable|integer|in:0,1',
            'flash_percentage'      => 'nullable|integer|min:0|max:100',
            'flash_text'            => 'nullable|string|max:255',
            'tags'                  => 'nullable|array',
            'tags.*'                => 'integer|exists:tags,id',
        ];

        if ($id == 0) {
            $rules['main_image']  = ['required', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])];
        } else {
            $rules['main_image']  = ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])];
        }

        return $rules;
    }

    protected function checkSKU($sku, $id)
    {
        Product::where('sku', $sku)->where('id', '!=', $id)->with('stocks')->orWhereHas('stocks', function ($q) use ($sku, $id) {
            $q->where('sku', $sku)->where('product_id', '!=', $id);
        })->first();
    }


    public function productReviews($sellerId = 0)
    {
        $query = ProductReview::with(['product', 'user']);

        if ($sellerId) {
            $query = $query->whereHas('product', function ($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
            });
        } else {
            $query = $query->whereHas('product');
        }

        $query = $query->searchable(['review', 'rating', 'user:username', 'product:name']);

        $data['reviews'] = $query->latest()->paginate(getPaginate());

        $data['pageTitle']      = __("Product Reviews");
        $data['emptyMessage']   = __("No review yet");

        return $data;
    }

    public function generateUniqueSKU()
    {
        $sku = strtoupper(\Illuminate\Support\Str::random(10));
        $check = Product::where('sku', $sku)->exists() || ProductStock::where('sku', $sku)->exists();
        if ($check) {
            return $this->generateUniqueSKU();
        }
        return $sku;
    }
}
