<?php


class DB extends PDO
{

    const dsn = 'mysql:dbname=mails;host=localhost';

    function __construct(Config $c)
    {
        $config = $c->loadConfig();
        parent::__construct(DB::dsn, $config['database']['username'], $config['database']['password']);
    }

    public function log($to, $status)
    {
        try {
            $query = $this->prepare('insert into mail_logs VALUES (?,?,?,?)');
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query->execute([null, $to, date('Y-m-d h:i:sa'), $status]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function logError($to)
    {
        if ($this->log($to, 'ERROR')) {
            return true;
        }
        return false;
    }

    public function logSuccess($to)
    {
        if ($this->log($to, 'SUCCESS'))
            return true;
        return false;
    }
}