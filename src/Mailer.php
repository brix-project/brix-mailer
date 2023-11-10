<?php

namespace Brix\Mailer;

use Brix\Core\AbstractBrixCommand;
use Brix\Mailer\Facet\MailerFacet;
use Brix\Mailer\Type\T_MailerConfig;
use http\Exception\InvalidArgumentException;
use Lack\MailSpool\OutgoingMail;
use Phore\Cli\CLIntputHandler;
use Phore\Cli\Output\Out;

class Mailer extends AbstractBrixCommand
{

    public T_MailerConfig $config;


    public MailerFacet $facet;

    public function __construct()
    {
        parent::__construct();
        $this->config = $this->brixEnv->brixConfig->get(
            "mailer",
            T_MailerConfig::class,
            file_get_contents(__DIR__ . "/config_tpl.yml")
        );
        $this->facet = new MailerFacet($this->config, $this->brixEnv);
    }


    public function new_mail(string $presetId = null)
    {
        $cli = new CLIntputHandler();
        $templates = $this->facet->listTemplates();
        if ($presetId === null) {
            Out::Table($templates);
            $presetId = $cli->askLine("Select template id");
        }
        $presetId = (int)$presetId;


        $this->facet->fromTemplate($presetId-1, fn($key) => $cli->askLine("Enter $key: "));


    }

    public function list () {
        Out::Table($this->facet->listTemplates());
    }

}
