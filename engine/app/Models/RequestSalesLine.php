<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestSalesLine extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $table = "request_sales_line";
    const CREATED_AT = 'createdOn';
    const UPDATED_AT = 'updatedOn';
    const DELETED_AT = 'deletedOn';

    public function requestsalesheader()
    {
        return $this->belongsTo(RequestSalesHeader::class);
    }

    public function header()
    {
        return $this->belongsTo(RequestSalesHeader::class, "sales_order_header_id");
    }

    public function stock()
    {
        return $this->belongsTo(Stok::class,"item_stock_id")->withTrashed();
    }
}
