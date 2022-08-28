<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestSalesHeader extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;
    protected $table = "request_sales_header";
    const CREATED_AT = 'createdOn';
    const UPDATED_AT = 'updatedOn';
    const DELETED_AT = 'deletedOn';
    public function customer()
    {
        return $this->belongsTo(Konsumen::class);
    }
    public function line()
    {
        return $this->hasMany(RequestSalesLine::class);
    }
    public function payment()
    {
        return $this->hasMany(SalesInvoicePayment::class);
    }
    public function staffsupir()
    {
        return $this->belongsTo(User::class, 'supir', 'id');
    }
    public function staffkenek()
    {
        return $this->belongsTo(User::class, 'kenek', 'id');
    }

    //print
    public function detail()
    {
        return $this->hasMany(RequestSalesLine::class);
    }

    public function commision()
    {
        return $this->hasOne(Komisi::class);
    }
}
