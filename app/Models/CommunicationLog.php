<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['customer', 'channel', 'subject', 'message', 'handled_by', 'email', 'phone'])]
class CommunicationLog extends Model

{
    use HasFactory;

    /**
     * Subjects that are available for selection in the "Add Log" form.
     *
     * @var array<int, string>
     */
    public const SUBJECTS = [
        'Product Inquiry',
        'Order Follow-up',
        'Shipping Status',
        'Quotation Request',
        'Payment Confirmation',
    ];

    public function followUp()
    {
        return $this->hasOne(FollowUp::class);
    }
}
