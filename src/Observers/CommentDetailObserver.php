<?php

namespace JRApp\Observers\Utility;

use JRApp\Libraries\Utility\MaintenanceManagement;
use JRApp\Models\Accounts\ExpenseVoucher;
use JRApp\Models\Utility\ServicingHistory;
use JRApp\Models\Utility\Vendor;
use Drivezy\LaravelUtility\Observers\BaseObserver;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class CommentDetailObserver
 * @package JRApp\Observers\Utility
 */
class  CommentDetailObserver extends BaseObserver
{
    /**
     * @var array
     */
    protected $rules = [
        'comments' => 'required',
    ];

    /**
     * @param Eloquent $model
     */
    public function created (Eloquent $model)
    {
        parent::created($model);

        if ( $model->source == 'ServicingHistory' ) {
            $vendor = Vendor::where('user_id', $model->created_by)->first();
            if ( !$vendor ) {
                $servicing = ServicingHistory::find($model->source_id);
                if ( $servicing ) {
                    MaintenanceManagement::updateServicingTicket($servicing, $model->comments);
                }
            }
        } elseif ( $model->source == 'ExpenseVoucher' && ( strpos($model->comments, 'status updated to') == false ) ) {
            $expenseVoucher = ExpenseVoucher::where('id', $model->source_id)->where('state', 1070)->first();
            if ( $expenseVoucher ) {
                $expenseVoucher->state = 276;
                $expenseVoucher->save();
            }
        }
    }
}