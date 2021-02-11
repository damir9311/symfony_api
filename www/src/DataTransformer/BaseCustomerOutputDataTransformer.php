<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\CustomerOutput;
use App\Entity\Customer;

/**
 * Class BaseCustomerOutputDataTransformer
 */
abstract class BaseCustomerOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        /** @var Customer $data */
        /** @var CustomerOutput $to */
        return $to::createFromObject($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return is_subclass_of($to, CustomerOutput::class) && $data instanceof Customer;
    }
}