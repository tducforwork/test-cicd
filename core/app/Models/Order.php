<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['computed_status', 'computed_status_name'];

    public function subOrders()
    {
        return $this->hasMany(SubOrder::class);
    }

    protected function computedStatus(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $subOrders = $this->subOrders()->get();
                
                if ($subOrders->isEmpty()) {
                    return Status::ORDER_CANCELED;
                }

                $allRejected = $subOrders->every(function ($sub) {
                    return $sub->status == Status::SUBORDER_REJECTED;
                });
                
                if ($allRejected) {
                    return Status::ORDER_CANCELED;
                }

                $activeSubOrders = $subOrders->where('status', '!=', Status::SUBORDER_REJECTED);
                
                if ($activeSubOrders->contains('status', Status::SUBORDER_DISPUTED)) {
                    return Status::ORDER_PROCESSING;
                }

                if ($activeSubOrders->contains('status', Status::SUBORDER_PENDING)) {
                    return Status::ORDER_PENDING;
                }
                if ($activeSubOrders->contains('status', Status::SUBORDER_PROCESSING)) {
                    return Status::ORDER_PROCESSING;
                }
                if ($activeSubOrders->contains('status', Status::SUBORDER_READY_TO_PICKUP)) {
                    return Status::ORDER_READY_TO_DELIVER;
                }
                if ($activeSubOrders->contains('status', Status::SUBORDER_DISPATCHED)) {
                    return Status::ORDER_DISPATCHED;
                }
                if ($activeSubOrders->contains('status', Status::SUBORDER_DELIVERED)) {
                    return Status::ORDER_DELIVERED;
                }
                if ($activeSubOrders->contains('status', Status::SUBORDER_COMPLETED)) {
                    return Status::ORDER_DELIVERED;
                }

                return $this->status;
            }
        );
    }

    protected function computedStatusName(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $status = $this->computed_status;
                return match ($status) {
                    Status::ORDER_PENDING => 'Chờ xác nhận',
                    Status::ORDER_PROCESSING => 'Đang xử lý',
                    Status::ORDER_READY_TO_DELIVER => 'Đóng gói xong',
                    Status::ORDER_DISPATCHED => 'Đang vận chuyển',
                    Status::ORDER_DELIVERED => 'Đã giao hàng',
                    Status::ORDER_CANCELED => 'Đã hủy',
                    default => 'Không xác định',
                };
            }
        );
    }

    public function getSubOrderStatusSummary()
    {
        $subOrders = $this->subOrders;
        return [
            'total' => $subOrders->count(),
            'pending' => $subOrders->where('status', Status::SUBORDER_PENDING)->count(),
            'processing' => $subOrders->where('status', Status::SUBORDER_PROCESSING)->count(),
            'ready_to_pickup' => $subOrders->where('status', Status::SUBORDER_READY_TO_PICKUP)->count(),
            'dispatched' => $subOrders->where('status', Status::SUBORDER_DISPATCHED)->count(),
            'delivered' => $subOrders->whereIn('status', [Status::SUBORDER_DELIVERED, Status::SUBORDER_COMPLETED])->count(),
            'rejected' => $subOrders->where('status', Status::SUBORDER_REJECTED)->count(),
            'disputed' => $subOrders->where('status', Status::SUBORDER_DISPUTED)->count(),
        ];
    }


    public function appliedCoupon()
    {
        return $this->hasOne(AppliedCoupon::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function deposit()
    {
        return $this->hasOne(Deposit::class, 'order_id', 'id')->latest()->withDefault();
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, OrderDetail::class, 'order_id', 'id');
    }

    public function orderDetail()
    {
        return $this->hasManyThrough(OrderDetail::class, SubOrder::class)->where('sub_orders.status', '!=', Status::SUBORDER_REJECTED);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function getAmountAttribute()
    {
        return $this->total_amount - $this->shipping_charge;
    }


    public function scopePending($query)
    {
        return $query->where('status', Status::ORDER_PENDING)->whereIn('payment_status', [Status::PAYMENT_SUCCESS, Status::COD]);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', Status::PAYMENT_INITIATE);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', Status::PAYMENT_SUCCESS);
    }

    public function scopeCod($query)
    {
        return $query->where('payment_status', Status::COD);
    }

    public function scopeValid($query)
    {
        return $query->where('payment_status', '!=', Status::PAYMENT_INITIATE);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', Status::ORDER_PROCESSING);
    }

    public function scopeDispatched($query)
    {
        return $query->where('status', Status::ORDER_DISPATCHED);
    }

    public function scopeReadyToDeliver($query)
    {
        return $query->where('status', Status::ORDER_READY_TO_DELIVER);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', Status::ORDER_DELIVERED);
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', Status::ORDER_CANCELED);
    }

    public function statusName()
    {
        $statuses = [
            Status::ORDER_PENDING => 'Pending',
            Status::ORDER_PROCESSING => 'Processing',
            Status::ORDER_DISPATCHED => 'Dispatched',
            Status::ORDER_READY_TO_DELIVER => 'Ready to Deliver',
            Status::ORDER_DELIVERED => 'Delivered',
            Status::ORDER_CANCELED => 'Cancelled',
        ];
        return $statuses[$this->status] ?? 'Unknown';
    }

    public function statusBadge($addtionalClass = '')
    {
        if ($this->status == Status::ORDER_PENDING) {
            return makeHtmlElement('span', 'warning', 'Pending', $addtionalClass);
        } elseif ($this->status == Status::ORDER_PROCESSING) {
            return makeHtmlElement('span', 'primary', 'Processing', $addtionalClass);
        } elseif ($this->status == Status::ORDER_DISPATCHED) {
            return makeHtmlElement('span', 'dark', 'Dispatched', $addtionalClass);
            return makeHtmlElement('span', 'primary', 'Processing', $addtionalClass);
        } elseif ($this->status == Status::ORDER_READY_TO_DELIVER) {
            return makeHtmlElement('span', 'dark', 'Ready to Deliver', $addtionalClass);
        } elseif ($this->status == Status::ORDER_DELIVERED) {
            return makeHtmlElement('span', 'success', 'Delivered', $addtionalClass);
        } else {
            return makeHtmlElement('span', 'danger', 'Cancelled', $addtionalClass);
        }
    }

    public function paymentBadge($addtionalClass = '')
    {
        if ($this->payment_status == Status::PAYMENT_SUCCESS) {
            return makeHtmlElement('span', 'success', 'Paid', $addtionalClass);
        } elseif ($this->payment_status == Status::COD) {
            return makeHtmlElement('span', 'warning', 'COD', $addtionalClass);
        } else {
            return makeHtmlElement('span', 'danger', 'Unpaid', $addtionalClass);
        }
    }

    public function autoCancel()
    {
        $this->status = Status::ORDER_CANCELED;
        $this->save();

        if ($this->user) {
            notify($this->user, 'ORDER_CANCELLATION_CONFIRMATION', [
                'site_name' => gs('sitename'),
                'order_id'  => $this->order_number
            ]);
        }
    }

    public function notifyParties($isCod = false, $deposit = null)
    {
        $user = $this->user;
        if (!$user) return;

        // 1. Notify User
        notify($user, $isCod ? 'ORDER_ON_PROCESSING_CONFIRMATION' : 'BUYER_PAYMENT_CONFIRM', [
            'fullname'     => $user->fullname,
            'username'     => $user->username,
            'order_number' => $this->order_number,
            'amount'       => showAmount($this->total_amount, currencyFormat: false),
            'currency'     => gs('cur_text'),
            'site_name'    => gs('site_name'),
        ]);

        // 2. Notify Admin
        $adminEmail = gs('contact_email') ?: \App\Models\Admin::first()?->email;
        if ($adminEmail) {
            $adminUser = (object)['email' => $adminEmail, 'fullname' => 'Admin', 'username' => 'admin'];
            notify($adminUser, $isCod ? 'ADMIN_COD_ORDER_ALERT' : 'ADMIN_PAYMENT_ALERT', [
                'order_number'   => $this->order_number,
                'amount'         => showAmount($this->total_amount, currencyFormat: false),
                'currency'       => gs('cur_text'),
                'buyer_username' => $user->username,
                'method_name'    => $deposit ? $deposit->gateway->name : 'N/A',
                'trx'            => $deposit ? $deposit->trx : 'N/A',
                'site_name'      => gs('site_name'),
            ]);
        }

        // 3. Notify Sellers
        $subOrders = $this->subOrders()->with('seller')->get();
        foreach ($subOrders as $subOrder) {
            if ($subOrder->seller_id != 0 && $subOrder->seller) {
                notify($subOrder->seller, $isCod ? 'SELLER_COD_ORDER_ALERT' : 'SELLER_NEW_ORDER', [
                    'seller_name'     => $subOrder->seller->fullname,
                    'order_number'    => $this->order_number,
                    'suborder_number' => $subOrder->order_number,
                    'subtotal_amount' => showAmount($subOrder->total_amount, currencyFormat: false),
                    'currency'        => gs('cur_text'),
                    'site_name'       => gs('site_name'),
                ]);
            }
        }
    }

    public function syncStatus()
    {
        $activeSubOrders = $this->subOrders()->where('status', '!=', \App\Constants\Status::SUBORDER_REJECTED)->get();
        
        if ($activeSubOrders->count() > 0) {
            $statuses = $activeSubOrders->pluck('status')->unique()->toArray();
            
            if (in_array(\App\Constants\Status::SUBORDER_DISPUTED, $statuses)) {
                $this->status = \App\Constants\Status::ORDER_PROCESSING;
            } elseif (in_array(\App\Constants\Status::SUBORDER_PENDING, $statuses)) {
                $this->status = \App\Constants\Status::ORDER_PENDING;
            } elseif (in_array(\App\Constants\Status::SUBORDER_PROCESSING, $statuses)) {
                $this->status = \App\Constants\Status::ORDER_PROCESSING;
            } elseif (in_array(\App\Constants\Status::SUBORDER_READY_TO_PICKUP, $statuses)) {
                $this->status = \App\Constants\Status::ORDER_READY_TO_DELIVER;
            } elseif (in_array(\App\Constants\Status::SUBORDER_DISPATCHED, $statuses)) {
                $this->status = \App\Constants\Status::ORDER_DISPATCHED;
            } elseif (in_array(\App\Constants\Status::SUBORDER_DELIVERED, $statuses)) {
                $this->status = \App\Constants\Status::ORDER_DELIVERED;
                if ($this->payment_status != \App\Constants\Status::PAYMENT_SUCCESS) {
                    $this->payment_status = \App\Constants\Status::PAYMENT_SUCCESS;
                }
            } else {
                // All active suborders are Status::SUBORDER_COMPLETED (5)
                $this->status = \App\Constants\Status::ORDER_DELIVERED;
                if ($this->payment_status != \App\Constants\Status::PAYMENT_SUCCESS) {
                    $this->payment_status = \App\Constants\Status::PAYMENT_SUCCESS;
                }
            }
        } else {
            // All suborders are rejected/canceled
            $this->status = \App\Constants\Status::ORDER_CANCELED;
        }
        $this->save();
    }
}
