<?php


namespace Brix\Mailer;





use Brix\Core\BrixEnvFactorySingleton;
use Brix\Core\Type\BrixEnv;
use Brix\MailSpool\Mailspool;
use Brix\MailSpool\MailSpoolFacet;
use Phore\Cli\CliDispatcher;

CliDispatcher::addClass(Mailer::class);
