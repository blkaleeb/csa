<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseInvoiceLine extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $table = "purchase_invoice_line";
    const CREATED_AT = 'createdOn';
    const UPDATED_AT = 'updatedOn';
    const DELETED_AT = 'deletedOn';

    public function header()
    {
        return $this->belongsTo(PurchaseInvoiceHeader::class, "purchase_invoice_header_id");
    }
    public function poline()
    {
        return $this->belongsTo(PoLine::class,"po_line_id");
    }
}
