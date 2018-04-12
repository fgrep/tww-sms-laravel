<?php

namespace NotificationChannels\Tww\Exceptions;

use GuzzleHttp\Exception\ClientException;

class CouldNotSendNotification extends \Exception
{
    /**
     * Thrown when there's a bad request and an error is responded.
     *
     * @param ClientException $exception
     *
     * @return static
     */
    public static function serviceRespondedWithAnError(ClientException $exception)
    {
        $statusCode  = $exception->getResponse()->getStatusCode();
        $description = 'no description given';

        if ($result = json_decode($exception->getResponse()->getBody())) {
            $description = $result->description ?: $description;
        }

        return new static("Tww responded with an error `{$statusCode} - {$description}`");
    }

    /**
     * Thrown when we're unable to communicate with Tww.
     *
     * @return static
     */
    public static function couldNotCommunicateWithTww($message)
    {
        return new static($message);
    }

    /**
     * Thrown when there is no 'conta' provided.
     *
     * @return static
     */
    public static function contaNotProvided()
    {
        return new static('Tww account not provided');
    }

    /**
     * Thrown when there is no 'senha' provided
     *
     * @return static
     */
    public static function senhaNotProvided()
    {
        return new static('Tww password not provided');
    }

    /**
     * Thrown when there is no receiver provided
     *
     * @return static
     */
    public static function receiverNotProvided()
    {
        return new static('SMS receiver not provided');
    }
}
