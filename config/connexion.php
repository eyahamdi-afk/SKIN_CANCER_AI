<?php

class Database
{
    private $host = "localhost";
    private $port = "5432";
    private $dbname = "skin_cancer_ai";
    private $user = "postgres";
    private $password = "123456";

    public function getConnection()
    {
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            $db = new PDO($dsn, $this->user, $this->password);

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $db;

        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
}