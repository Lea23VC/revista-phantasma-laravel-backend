<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Jobs\SendEmailJob;

final readonly class SendEmail
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {


        try {
            SendEmailJob::dispatch($args['name'], $args['email'], $args['message']);
            return array(
                'success' => true,
                'message' => 'Email sent successfully',
            );
        } catch (\Exception $e) {
            return array(
                'success' => false,
                'message' => $e->getMessage(),
            );
        }
    }
}
