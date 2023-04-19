<?php
require_once 'Message.php';

class GuestBook {

    private $file;//propriété

    public function __construct(string $file)
    {
       $directory = dirname($file);// donne le dossier dans lequel on souhaite sauvegarder le fichier
       if (!is_dir($directory)) { //s'il n'existe pas, il faut le créer
            mkdir($directory, 0777, true); 
       }
       if(!file_exists($file)) { //si le fichier nexiste pas le créer
        touch($file);
       }
       $this->file = $file; // initialisation de la propriété
    }

    public function addMessage(Message $message): void
    {
        file_put_contents($this->file, $message->toJSON() . PHP_EOL, FILE_APPEND); 

    }

    public function getMessages(): array
    {
        $content = trim(file_get_contents($this->file));
        $lines = explode(PHP_EOL, $content);// récupération de chaque ligne de message par le dernier caractère
        $messages =[];
        foreach ($lines as $line) {
            $messages[]= Message::fromJSON($line);
        }
        return array_reverse($messages);
    }
}
?>