<?php

namespace App\Shared\Request;

use App\Shared\Response\ConstraintViolation;
use App\Shared\Response\Exception\ValidatorException;
use App\Validator\FileValidator;
use Symfony\Component\HttpFoundation\FileBag;
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
        private readonly FileValidator $fileValidator,
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
        }

        try {
            $this->fileValidator->validateFiles($this->getFiles()->all());
        } catch (ValidatorException $e) {
            $messages = array_merge($messages, $e->getViolation());
        }

        if (!empty($messages)) {
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
        $requestData = $request->request->all()['data'] ?? null;

        if (null === $requestData) {
            throw new \InvalidArgumentException("Missing 'data' field in request.");
        }

        $this->serializer->deserialize(
            $requestData,
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
