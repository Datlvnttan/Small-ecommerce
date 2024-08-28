<?php

namespace Modules\Order\Http\Requests;

use App\Http\Requests\FailedReturnJsonFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FailedReturnJsonFormRequest
{
    public function methodPost()
    {
        return $this->validateForPostAndPut();
    }
    public function methodPut()
    {
        return $this->validateForPostAndPut();
    }
    protected function validateForPostAndPut()
    {
        return [
            'feedbackRating' => ['required', 'numeric', 'between:1,5'],
            'feedbackTitle' => ['required', 'string', 'min:10', 'max:255'],
            'feedbackReview' => ['required', 'string'],
            'feedbackImage' => ['file','mimes:jpeg,jpg,png,JPG','max:5000'],
            'feedbackImageOld'=>['string','exists:Modules\Order\Entities\OrderDetail,feedback_image']
        ];
    }
}
