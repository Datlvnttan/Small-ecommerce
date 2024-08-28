<?php
namespace Modules\Product\Repositories\Repository;

use App\Repositories\EloquentRepository;
use Carbon\Carbon;
use Database\Factories\Product;
use Modules\Product\Repositories\Interface\FlashSaleRepositoryInterface;

class FlashSaleRepository extends EloquentRepository implements FlashSaleRepositoryInterface
{
    public function getModel()
    {
        return \Modules\Product\Entities\FlashSale::class;
    }
    public function getFitFlashSale()
    {
        $current_time = Carbon::now();
        $data = $this->model->where(function ($query) use ($current_time){
            return $query->where('start_time','<=',$current_time)
            ->where('end_time','>',$current_time); 
        })->orWhere('start_time','>',$current_time)
        ->orderBy('start_time','asc')
        ->first();
        return $data;
    }
}
