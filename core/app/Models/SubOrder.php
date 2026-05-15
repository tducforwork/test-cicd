<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\BelongsToSeller;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class SubOrder extends Model
{
    use BelongsToSeller;

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function scopeAdmin($query)
    {
        return $query->where('seller_id', 0);
    }

    public function scopePending($query)
    {
        return $query->where('status', Status::SUBORDER_PENDING);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', Status::SUBORDER_PROCESSING);
    }

    public function scopeReadyToPickup($query)
    {
        return $query->where('status', Status::SUBORDER_READY_TO_PICKUP);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', Status::SUBORDER_DELIVERED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', Status::SUBORDER_REJECTED);
    }

    public function scopeValid($query)
    {
        return $query->whereHas('order', function ($q1) {
            $q1->where('payment_status', '!=', Status::PAYMENT_INITIATE);
        });
    }

    public function scopeCod($query)
    {
        return $query->whereHas('order', function ($q1) {
            $q1->where('payment_status', Status::COD);
        });
    }

    public function scopeDispatched($query)
    {
        return $query->where('status', Status::SUBORDER_DISPATCHED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', Status::SUBORDER_COMPLETED);
    }

    public function scopeDisputed($query)
    {
        return $query->where('status', Status::SUBORDER_DISPUTED);
    }

    public function scopeCanceled($query)
    {
        return $query->whereHas('order', function ($q1) {
            $q1->where('status', Status::ORDER_CANCELED);
        });
    }

    public function scopeOrderNotCanceled($query)
    {
        return $query->whereHas('order', function ($q1) {
            $q1->where('status', '!=', Status::ORDER_CANCELED);
        });
    }


    public function getStatusNameAttribute()
    {
        return match ($this->status) {
            Status::SUBORDER_PENDING => 'Chờ xác nhận',
            Status::SUBORDER_PROCESSING => 'Đang xử lý',
            Status::SUBORDER_READY_TO_PICKUP => 'Đóng gói xong',
            Status::SUBORDER_DISPATCHED => 'Đang vận chuyển',
            Status::SUBORDER_DELIVERED => 'Đã giao hàng',
            Status::SUBORDER_COMPLETED => 'Hoàn thành',
            Status::SUBORDER_DISPUTED => 'Khiếu nại',
            Status::SUBORDER_REJECTED => 'Đã hủy',
            default => 'Không xác định',
        };
    }

    public function statusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                $html = '';
                if ($this->status == Status::SUBORDER_PENDING) {
                    $html = '<span class="badge badge--warning">Chờ xác nhận</span>';
                } elseif ($this->status == Status::SUBORDER_PROCESSING) {
                    $html = '<span class="badge badge--info">Đang xử lý</span>';
                } elseif ($this->status == Status::SUBORDER_READY_TO_PICKUP) {
                    $html = '<span class="badge badge--primary">Đóng gói xong</span>';
                } elseif ($this->status == Status::SUBORDER_DISPATCHED) {
                    $html = '<span class="badge badge--dark">Đang vận chuyển</span>';
                } elseif ($this->status == Status::SUBORDER_DELIVERED) {
                    $html = '<span class="badge badge--success">Đã giao</span>';
                } elseif ($this->status == Status::SUBORDER_COMPLETED) {
                    $html = '<span class="badge badge--success">Hoàn thành</span>';
                } elseif ($this->status == Status::SUBORDER_DISPUTED) {
                    $html = '<span class="badge badge--danger">Khiếu nại</span>';
                } elseif ($this->status == Status::SUBORDER_REJECTED) {
                    $html = '<span class="badge badge--danger">Đã hủy</span>';
                }
                return $html;
            }
        );
    }
}
