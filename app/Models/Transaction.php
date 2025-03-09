<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Carbon\Carbon;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class Transaction extends Model
{
    use HasFactory;
    use LogsActivity;


    protected $fillable = [
        'user_id',
        'booking_transaction_id',
        'grand_total',
        'booking_status',
        'start_date',
        'end_date',
        'duration',
        'promo_id',
        'note',
        'down_payment',
        'remaining_payment',
    ];


    protected $casts = [
        'down_payment' => MoneyCast::class,
        'remaining_payment' => MoneyCast::class,

        'grand_total' => MoneyCast::class,
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'duration' => 'integer', // Pastikan duration dicasting ke integer
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'user.name',
                'booking_transaction_id',
                'grand_total',
                'booking_status',
                'start_date',
                'end_date',
                'duration',
                'promo.name',
                'note',
            ]);
    }



    public function generateUniqueBookingTrxId()
    {
        $prefix = 'GPR';
        do {
            $bookingTrxId = $prefix . mt_rand(0, 99999);
        } while (self::where('booking_transaction_id', $bookingTrxId)->exists());

        return $bookingTrxId;
    }

    protected static function booted()
    {
        static::creating(function ($transaction) {
            // Pastikan booking_transaction_id di-set sebelum data disimpan
            $transaction->booking_transaction_id = $transaction->generateUniqueBookingTrxId();
        });
        static::saving(function ($transaction) {

            if ($transaction->start_date) {
                $transaction->start_date = Carbon::parse($transaction->start_date)
                    ->format('Y-m-d H:i:s');
            }

            $duration = (int) $transaction->duration;

            if ($transaction->start_date && $duration) {
                $transaction->end_date = Carbon::parse($transaction->start_date)
                    ->addDays($duration)
                    ->format('Y-m-d H:i:s');
            }
            // Validasi promo_id
        });
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function DetailTransactions(): HasMany
    {
        return $this->hasMany(DetailTransaction::class);
    }
    public function rentalIncludes(): HasManyThrough
    {
        return $this->hasManyThrough(
            RentalInclude::class,
            Product::class,
            'id',
            'include_product_id'
        );
    }

    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class);
    }
}
