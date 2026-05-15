<?php

namespace App\Http\Controllers\Api\Seller;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\SubOrder;
use App\Models\StockLog;
use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use App\Models\ProductStock;
use App\Models\AssignProductAttribute;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ProductManager;
use App\Traits\ProductVariantManager;

class SellerController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $data['total_products'] = Product::where('seller_id', $user->id)->count();
        $data['total_orders']   = Order::whereHas('subOrder', function ($q) use ($user) {
            $q->where('seller_id', $user->id);
        })->count();
        $data['total_transactions'] = Transaction::where('seller_id', $user->id)->count();
        $data['balance'] = $user->balance;

        $notify[] = 'Seller dashboard data';
        return response()->json([
            'remark' => 'seller_dashboard',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => $data
        ]);
    }

    public function products()
    {
        $user = auth()->user();
        $products = Product::where('seller_id', $user->id)->with('categories', 'brand')->latest()->get();

        $notify[] = 'Seller product list';
        return response()->json([
            'remark' => 'seller_products',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'products' => $products
            ]
        ]);
    }

    public function addProductPage()
    {
        $data['categories'] = Category::with('allSubcategories')->where('parent_id', null)->get();
        $data['brands']     = Brand::active()->orderBy('name')->get();

        $notify[] = 'Add product page data';
        return response()->json([
            'remark' => 'add_product_data',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => $data
        ]);
    }

    public function editProduct($id)
    {
        $user = auth()->user();
        $product = Product::where('seller_id', $user->id)->with('categories', 'productPreviewImages')->find($id);

        if (!$product) {
            $notify[] = 'Sản phẩm không tồn tại';
            return response()->json([
                'remark' => 'product_not_found',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $data['product'] = $product;
        $data['categories'] = Category::with('allSubcategories')->where('parent_id', null)->get();
        $data['brands']     = Brand::active()->orderBy('name')->get();

        $notify[] = 'Edit product data';
        return response()->json([
            'remark' => 'edit_product_data',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => $data
        ]);
    }

    public function storeProduct(Request $request, $id)
    {
        $user = auth()->user();

        $rules = [
            'name'                  => 'required|string|max:191',
            'base_price'            => 'required|numeric',
            'categories'            => 'required|array|min:1',
            'main_image'            => [$id == 0 ? 'required' : 'nullable', 'image', 'max:2048'],
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        $product = $id == 0 ? new Product() : Product::where('seller_id', $user->id)->findOrFail($id);

        if ($request->hasFile('main_image')) {
            $product->main_image = fileUploader($request->main_image, getFilePath('product'), getFileSize('product'), @$product->main_image, getFileThumb('product'));
        }

        $product->seller_id         = $user->id;
        $product->brand_id          = $request->brand_id ?? 0;
        $product->name              = $request->name;
        $product->slug              = \Illuminate\Support\Str::slug($request->name) . '-' . time();
        $product->description       = $request->description;
        $product->summary           = $request->summary;
        $product->base_price        = $request->base_price ?? 0;
        $product->has_variants      = $request->has_variants ?? 0;
        $product->track_inventory   = $request->track_inventory ?? 0;
        $product->status            = Status::ENABLE;


        $product->save();
        $product->categories()->sync($request->categories);
        if ($request->brand_id) {
            $product->brand()->sync([$request->brand_id]);
        }

        if ($request->hasFile('photos')) {
            foreach ($request->photos as $image) {
                $img = fileUploader($image, getFilePath('product'), getFileSize('product'), null, getFileThumb('product'));
                $productImage = new ProductImage();
                $productImage->product_id = $product->id;
                $productImage->image = $img;
                $productImage->save();
            }
        }

        $notify[] = $id == 0 ? 'Thêm sản phẩm thành công' : 'Cập nhật sản phẩm thành công';
        return response()->json([
            'remark' => 'product_stored',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => ['product' => $product]
        ]);
    }

    public function orders(Request $request)
    {
        $user = auth()->user();
        $orders = SubOrder::valid()->orderNotCanceled()->where('seller_id', $user->id);

        if ($request->status == 'pending') {
            $orders->pending();
        } elseif ($request->status == 'processing') {
            $orders->processing();
        } elseif ($request->status == 'ready_to_pickup') {
            $orders->readyToPickup();
        } elseif ($request->status == 'delivered') {
            $orders->delivered();
        } elseif ($request->status == 'rejected') {
            $orders->rejected();
        }

        $orders = $orders->searchable(['order_number'])->orderBy('id', 'DESC')->with('order', 'orderDetail.product')->withSum('orderDetail as total_products', 'quantity')->paginate(getPaginate());

        $notify[] = 'Seller order list';
        return response()->json([
            'remark' => 'seller_orders',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'orders' => $orders
            ]
        ]);
    }

    public function orderDetail($id)
    {
        $user = auth()->user();
        $suborder = SubOrder::valid()->where('seller_id', $user->id)->with('order.user', 'order.shippingMethod', 'orderDetail.product')->find($id);

        if (!$suborder) {
            $notify[] = 'Không tìm thấy đơn hàng';
            return response()->json([
                'remark' => 'order_not_found',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $notify[] = 'Seller order detail';
        return response()->json([
            'remark' => 'seller_order_detail',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'suborder' => $suborder
            ]
        ]);
    }

    public function markAsProcessing($id)
    {
        $user = auth()->user();
        $suborder = SubOrder::valid()->orderNotCanceled()->pending()->where('seller_id', $user->id)->with('order.user')->find($id);

        if (!$suborder) {
            $notify[] = 'Đơn hàng không hợp lệ để chuyển trạng thái';
            return response()->json([
                'remark' => 'invalid_order',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $suborder->status = Status::SUBORDER_PROCESSING;
        $suborder->save();

        $order = $suborder->order;
        if ($order->status == Status::ORDER_PENDING) {
            $order->status = Status::ORDER_PROCESSING;
            $order->save();

            if ($order->user) {
                notify($order->user, 'ORDER_ON_PROCESSING_CONFIRMATION', [
                    'site_name' => gs('sitename'),
                    'order_id'  => $order->order_number
                ]);
            }
        }

        $notify[] = 'Đơn hàng đã được chuyển sang đang xử lý';
        return response()->json([
            'remark' => 'order_processing',
            'status' => 'success',
            'message' => ['success' => $notify],
        ]);
    }

    public function markAsReadyToPickUp($id)
    {
        $user = auth()->user();
        $suborder = SubOrder::valid()->orderNotCanceled()->processing()->where('seller_id', $user->id)->with('order.user')->find($id);

        if (!$suborder) {
            $notify[] = 'Đơn hàng không hợp lệ';
            return response()->json([
                'remark' => 'invalid_order',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $suborder->status = Status::SUBORDER_READY_TO_PICKUP;
        $suborder->save();

        $notify[] = 'Đơn hàng đã sẵn sàng giao';
        return response()->json([
            'remark' => 'order_ready_to_pickup',
            'status' => 'success',
            'message' => ['success' => $notify],
        ]);
    }

    public function reject($id)
    {
        $user = auth()->user();
        $suborder = SubOrder::valid()->orderNotCanceled()->pending()->where('seller_id', $user->id)->with('orderDetail.product')->find($id);

        if (!$suborder) {
            $notify[] = 'Không thể từ chối đơn hàng này';
            return response()->json([
                'remark' => 'invalid_order',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $suborder->status = Status::SUBORDER_REJECTED;
        $suborder->save();

        $order = Order::with('subOrders', 'user')->find($suborder->order_id);
        $order->total_amount -= $suborder->total_amount;
        $order->save();

        StockLog::restoreStock($suborder->id, true);

        if (@$order->user) {
            $products = $suborder->orderDetail->map(function ($item) {
                return $item->product->name . ' (' . $item->quantity . ')';
            })->join(', ');

            notify($order->user, 'ORDER_ITEM_CANCELED', [
                'order_number' => $suborder->order_number,
                'products' => $products
            ]);
        }

        if ($order->subOrders->where('status', Status::SUBORDER_REJECTED)->count() == $order->subOrders->count()) {
            $order->autoCancel();
        }

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'Seller rejected the order #' . $suborder->order_number;
        $adminNotification->click_url = urlPath('admin.order.details', $suborder->order_id);
        $adminNotification->save();

        $notify[] = 'Đơn hàng đã bị hủy';
        return response()->json([
            'remark' => 'order_rejected',
            'status' => 'success',
            'message' => ['success' => $notify],
        ]);
    }

    public function sellLogs()
    {
        $user = auth()->user();
        $logs = \App\Models\SellLog::where('seller_id', $user->id)->with('order')->latest()->paginate(getPaginate());
        $notify[] = 'Sell logs';
        return response()->json([
            'remark' => 'sell_logs',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'logs' => $logs
            ]
        ]);
    }

    public function deleteProduct($id)
    {
        $user = auth()->user();
        $product = Product::where('seller_id', $user->id)->find($id);

        if (!$product) {
            $notify[] = 'Sản phẩm không tồn tại';
            return response()->json([
                'remark' => 'product_not_found',
                'status' => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $product->delete();
        $notify[] = 'Xóa sản phẩm thành công';
        return response()->json([
            'remark' => 'product_deleted',
            'status' => 'success',
            'message' => ['success' => $notify],
        ]);
    }

    public function variantImages($id)
    {
        $user = auth()->user();
        $product = Product::where('seller_id', $user->id)->findOrFail($id);
        $images = ProductImage::where('product_id', $id)->where('assign_product_attribute_id', '!=', 0)->get();

        $notify[] = 'Variant images';
        return response()->json([
            'remark' => 'variant_images',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'images' => $images,
                'product' => $product
            ]
        ]);
    }

    public function variants($id)
    {
        $user = auth()->user();
        $product = Product::where('seller_id', $user->id)->with('assignAttributes.productAttribute')->findOrFail($id);
        $attributes = ProductAttribute::all();

        $notify[] = 'Product variants';
        return response()->json([
            'remark' => 'product_variants',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'product' => $product,
                'attributes' => $attributes
            ]
        ]);
    }

    public function shop()
    {
        $user = auth()->user();
        $shop = \App\Models\Shop::where('seller_id', $user->id)->first();
        $notify[] = 'Shop information';
        return response()->json([
            'remark' => 'shop_info',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'shop' => $shop
            ]
        ]);
    }

    public function shopUpdate(Request $request)
    {
        $user = auth()->user();
        $shop = \App\Models\Shop::where('seller_id', $user->id)->first();

        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|max:40',
            'phone'                 => 'required|string|max:40',
            'address'               => 'required|string|max:600',
            'opening_time'          => 'nullable|date_format:H:i',
            'closing_time'          => 'nullable|date_format:H:i',
            'image'                 => [(($shop && $shop->logo) ? 'nullable' : 'required'), 'image', 'max:2048'],
            'cover_image'           => [(($shop && $shop->cover) ? 'nullable' : 'required'), 'image', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'remark' => 'validation_error',
                'status' => 'error',
                'message' => ['error' => $validator->errors()->all()],
            ]);
        }

        if (!$shop) {
            $shop = new \App\Models\Shop();
            $shop->seller_id = $user->id;
        }

        if ($request->hasFile('image')) {
            $location   = getFilePath('sellerShopLogo');
            $shop->logo = fileUploader($request->image, $location, null, @$shop->logo);
        }

        if ($request->hasFile('cover_image')) {
            $location    = getFilePath('sellerShopCover');
            $size        = getFileSize('sellerShopCover');
            $shop->cover = fileUploader($request->cover_image, $location, $size, @$shop->cover);
        }

        $shop->name              = $request->name;
        $shop->phone             = $request->phone;
        $shop->address           = $request->address;
        $shop->opens_at          = $request->opening_time;
        $shop->closed_at         = $request->closing_time;
        $shop->save();

        $notify[] = 'Cập nhật thông tin Shop thành công';
        return response()->json([
            'remark' => 'shop_updated',
            'status' => 'success',
            'message' => ['success' => $notify],
            'data' => [
                'shop' => $shop
            ]
        ]);
    }
}
