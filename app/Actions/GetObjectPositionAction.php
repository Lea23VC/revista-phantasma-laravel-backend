
<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

class GetObjectPositionAction
{
    use AsAction;

    public function handle($object)
    {
        return $object->position;
    }
}
