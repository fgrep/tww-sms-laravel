<?php

namespace NotificationChannels\Tww;

use Illuminate\Support\Str;
use NotificationChannels\Tww\Exceptions\CouldNotSendNotification;

class Tww
{
    /** @var SoapClient SOAP Client */
    protected $soap;

    /** @var null|string conta da TWW. */
    protected $conta = null;

    /** @var null|string senha da TWW. */
    protected $senha = null;

    /** @var null|string from da TWW. */
    protected $from = null;

    /** @var null|string from da TWW. */
    protected $pretend = null;

    /**
     * @param null $conta
     * @param null $senha
     * @param null $from
     */
    public function __construct($conta = null, $senha = null, $from = null, $pretend = false)
    {
        $this->conta   = $conta;
        $this->senha   = $senha;
        $this->from    = $from;
        $this->pretend = $pretend;
    }

    /**
     * Get SoapClient.
     *
     * @return SoapClient
     */
    protected function soapClient()
    {
        return new \SoapClient('https://webservices2.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL');
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendMessage($to, $params)
    {
        if (empty($to)) {
            throw CouldNotSendNotification::receiverNotProvided();
        }

        if (empty($this->conta)) {
            throw CouldNotSendNotification::contaNotProvided();
        }

        if (empty($this->senha)) {
            throw CouldNotSendNotification::senhaNotProvided();
        }

        try {
            if ($this->pretend === true) {
                \Log::debug('Pretending to send a SMS to: ' . $to . ' with content: ' . $this->msg($params));
                return;
            }

            return $this->soapClient()->EnviaSMS([
                'NumUsu'   => config('services.tww.conta'),
                'Senha'    => config('services.tww.senha'),
                'SeuNum'   => $params['from'] ?: $this->from,
                'Celular'  => $to,
                'Mensagem' => Str::limit($params['msg'], 160),
            ]);

        } catch (\SoapFault $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithTww($exception->getMessage());
        }
    }

    protected function msg($params)
    {
        if ($params['from']) {
            return $params['from'] . ': ' . $params['msg'];
        }

        return $this->from . ': ' . $params['msg'];
    }
}
