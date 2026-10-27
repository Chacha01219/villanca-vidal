<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerOrderModel extends Model
{
    protected $table         = 'customer_orders';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['customer_name', 'item_name', 'quantity', 'total_price', 'is_paid', 'ordered_at'];
}
