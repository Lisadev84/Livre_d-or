<?php
class Message {

    const LIMIT_USERNAME = 3;
    const LIMIT_MESSAGE = 10;
    private $username;
    private $message;
    private $date;

    public static function fromJSON(string $json): Message
    {
        $data= json_decode($json,true);
        return new self($data['username'], $data['message'], new DateTime("@" . $data['date']));
    }

    public function __construct(string $username, string $message, ?DateTime $date = null)
    {
        $this->username = $username;
        $this->message = $message;
        $this->date = $date ?: new DateTime(); //sauvegarde de la date du jour si elle n'existe pas(?:)
    }

    public function isValid():bool
    {
       return empty($this->getErrors());

    }

    public function getErrors(): array
    {
        $error = [];
        if (strlen($this->username) < self::LIMIT_USERNAME){
            $error['username'] = 'Votre pseudo est trop court';
        }
        if (strlen($this->message) < self::LIMIT_MESSAGE) {
            $error['message'] = 'Votre message est trop court';
        } 
        return $error;
    }
    public function toHTML(): string 
    {
        $username = htmlentities($this->username);
        $this->date->setTimezone(new DateTimeZone('Europe/Paris'));
        $date = $this->date->format('d/m/Y à H:i');
        $message = nl2br(htmlentities($this->message));// nl2br insère un retour à la ligne html à chq nvlle ligne
        return <<<HTML
        <p>
            <strong>{$username}</strong> <em>le {$date} </em><br>
            {$message}
        </p>

HTML;        
    }
    public function toJSON():string 
    {
        return json_encode([
            'username'=> $this->username,
            'message' =>$this->message,       // méthode qui permet d'encoder le message utilisateur pour qu'il puisse être lu par la méthode Addmessage dans class GuestBook
            'date'=> $this->date->getTimestamp()
        ]);
    }

}

