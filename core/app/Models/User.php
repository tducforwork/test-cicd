<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\UserNotify;
use App\Traits\SellerNotify;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, UserNotify, SellerNotify;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'ver_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'ver_code_send_at' => 'datetime',
        'kyc_data' => 'object',
        'seller_activated_at' => 'datetime'
    ];


    public function loginLogs()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id', 'desc');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status', '!=', Status::PAYMENT_INITIATE);
    }

    public function appliedCoupons()
    {
        return $this->hasMany(AppliedCoupon::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class);
    }


    // Seller Relations
    public function shop()
    {
        return $this->hasOne(Shop::class, 'seller_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function sellerTransactions()
    {
        return $this->hasMany(Transaction::class, 'seller_id')->orderBy('id', 'desc');
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'seller_id')->where('status', '!=', 0);
    }

    public function reviews()
    {
        return $this->hasManyThrough(ProductReview::class, Product::class, 'seller_id', 'product_id');
    }

    public function sellerLoginLogs()
    {
        return $this->hasMany(UserLogin::class, 'seller_id');
    }

    public function totalSold()
    {
        return SellLog::where('seller_id', $this->id)->sum('qty');
    }

    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn() => $this->firstname . ' ' . $this->lastname,
        );
    }

    public function mobileNumber(): Attribute
    {
        return new Attribute(
            get: fn() => $this->dial_code . $this->mobile,
        );
    }

    public function isFeatured(): Attribute
    {
        return new Attribute(
            get: fn() => $this->featured == Status::YES,
        );
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('status', Status::USER_ACTIVE)->where('ev', Status::VERIFIED)->where('sv', Status::VERIFIED);
    }

    public function scopeBanned($query)
    {
        return $query->where('status', Status::USER_BAN);
    }

    public function scopeEmailUnverified($query)
    {
        return $query->where('ev', Status::UNVERIFIED);
    }

    public function scopeMobileUnverified($query)
    {
        return $query->where('sv', Status::UNVERIFIED);
    }

    public function scopeEmailVerified($query)
    {
        return $query->where('ev', Status::VERIFIED);
    }

    public function scopeMobileVerified($query)
    {
        return $query->where('sv', Status::VERIFIED);
    }

    // Seller Scopes
    public function scopeSeller($query)
    {
        return $query->where('is_seller', Status::YES);
    }

    public function scopeNotSeller($query)
    {
        return $query->where('is_seller', Status::NO);
    }

    public function scopeSellerActive($query)
    {
        return $query->where('is_seller', 1)->where('seller_active', 1);
    }

    public function scopeSellerPending($query)
    {
        return $query->where('is_seller', 1)->where('seller_active', 0);
    }

    public function scopeKycPending($query)
    {
        return $query->where('kv', Status::KYC_PENDING);
    }

    public function scopeKycUnverified($query)
    {
        return $query->where('kv', Status::KYC_UNVERIFIED);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', Status::YES);
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }
}
