<?php

namespace Modules\Shipping\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Modules\Shipping\Entities\Country;

class CountryTransformer extends TransformerAbstract
{
    public function transform(Country $country)
    {
        return [
            'country_id' => $country->id,
            'country_name' => $country->country_name,
            'international_calling_code' => $country->international_calling_code,
            'iso_code' => $country->iso_code,
            'shipping_methods' => $this->includeShippingMethods($country)['data'],
        ];
    }
    public function includeShippingMethods($country)
    {
        $fractal = new Manager();
        $result = $this->collection($country->shippingMethods, new ShippingMethodCountryTransformer());
        return $fractal->createData($result)->toArray();
    }
}
