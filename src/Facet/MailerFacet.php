<?php

namespace Brix\Mailer\Facet;

use Brix\Core\Type\BrixEnv;
use Brix\Mailer\Type\T_MailerConfig;
use Brix\MailSpool\MailSpoolFacet;
use Lack\MailSpool\OutgoingMail;
use Lack\MailSpool\OutgoingMailSerializer;
use Phore\FileSystem\PhoreDirectory;

class MailerFacet
{

    public PhoreDirectory $templateDir;
    public function __construct(public T_MailerConfig $config, public BrixEnv $brixEnv)
    {
        $this->templateDir = $this->brixEnv->rootDir->withRelativePath($this->config->template_dir)->assertDirectory();
    }

    public function listTemplates () {

        $templates = [];
        foreach ($this->templateDir->listFiles("*.mail.txt") as $file) {
            $mail = OutgoingMailSerializer::LoadFromFile($this->templateDir->withRelativePath($file->getBasename())->assertFile());
            $templates[] = [
                "file" => $file->getBasename(),
                "subject" => $mail->headers["Subject"]

            ];
        }
        return $templates;
    }


    public function fromTemplate($index, \Closure $callback) {
        $templates = $this->listTemplates();
        $mail = OutgoingMail::FromTemplate(
            $this->templateDir->withRelativePath($templates[$index]["file"])->assertFile(),
            dataLoader: $callback
        );

        print_r($mail);

        MailSpoolFacet::getInstance()->spoolMail($mail);
    }


}
