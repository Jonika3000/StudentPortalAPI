<?php

namespace App\Shared;

use Symfony\Component\HttpFoundation\FileBag;
use App\Shared\Response\ConstraintViolation;
use App\Shared\Response\Exception\ValidatorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

abstract class BaseRequest
{
    /**
     * @throws ValidatorException
     */
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
        $this->hydrate();
        $this->validate();
    }

    /**
     * @throws ValidatorException
     */
    public function validate(): void
    {
        $errors = $this->validator->validate($this);
        $messages = [];
        if (count($errors) > 0) {
            foreach ($errors as $message) {
                $messages[] = new ConstraintViolation(
                    $message->getPropertyPath(),
                    $message->getInvalidValue(),
                    $message->getMessage(),
                );
            }

            throw new ValidatorException($messages);
        }
    }

    public function getRequest(): Request
    {
        return Request::createFromGlobals();
    }

    protected function hydrate(): void
    {
        $request = $this->requestStack->getCurrentRequest();
        Assert::notNull($request);

        $this->serializer->deserialize(
            $request->request->all()['data'],
            static::class,
            'json',
            ['object_to_populate' => $this]
        );
    }

    public function getFiles(): FileBag
    {
        return $this->requestStack->getCurrentRequest()->files;
    }
}
