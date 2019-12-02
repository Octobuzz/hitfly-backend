<?php

namespace App\Http\GraphQL\Fields;

use Rebing\GraphQL\Support\Field;

class BasicPictureField extends Field
{
    protected function setFactor($args)
    {
        if (!empty($args['factor'])) {
            $this->factor = $args['factor'];

            return;
        }

        if (!empty(config('image.factor'))) {
            $this->factor = config('image.factor');

            return;
        }

        $this->factor = 1;
    }
}
