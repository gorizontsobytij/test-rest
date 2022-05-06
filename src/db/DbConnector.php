<?php

namespace Src\db;

use PDO;

abstract class DbConnector
{

    protected function connect(): \PDO
    {
        try {
            $connection = new PDO(getenv('DATABASE_DNS') .';'
                . "dbname=" .getenv('DATABASE_NAME'),
                getenv('DATABASE_USER'),
                getenv('DATABASE_PASSWORD'));
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $connection;
        } catch (\Exception $exception) {
            echo $exception->getMessage();
            exit;
        }
    }



}