<?php
/*
    Copyright 2022 - Aden Potts

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

        http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.
*/

class Database {
    private $conn;

    public $affected = 0;
    public $error = NULL;
    public $errorMsg = "";

    public function __construct($host, $user, $password, $database) {
        $dsn = "mysql:host=$host;dbname=$database;port=3306";
        try {
            $this->conn = new PDO($dsn, $user, $password);
        } catch(PDOException $e) {
            // why yes, im throwing an exception from an exception, how could you tell?
            throw new Exception("PDO Connection failed! Error: {$e->getMessage()}");
        }
    }

    /**
    *   This function runs a query. Generally used for statements where no userinput is given. Otherwise, use one of the other functions that prepare & bind input.
    *   @param String $q: The query. U S E P A R A M E T E R S. Example, query("SELECT * FROM `users` WHERE `steamid` = ? AND `rank` = ? AND `suspended` = ?", ["steamidhere", "stinker", 0])
    *   @param Array $params: Ordered like ['stinker', 3151, true] for example.
    *   @return Array An array of results or true if no results were returned.
    */
    
    public function query($q, $params=NULL) {
        $this->error = false;
        $this->errorMsg = "";

        $statement = $this->conn->prepare($q);

        try {
            $statement->execute($params);
        } catch(PDOException $e) {
            $this->error = true;
            $this->errorMsg = $e->getMessage();

            return false;
        }

        /*if(!empty($statement->errorInfo())) {
            $this->error = true;
            $this->errorMsg = $statement->errorInfo();

            return false;
        }*/

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * A quick way to select data from the database. Does not support OR in $where. For that, use Database->query() at least until i make/figure out a way to do it.
     * 
     * @param String $table - The table that data is to be selected from.
     * @param String $columns - The columns that are required to be selected. IE `id`, `username`, `password`
     * @param Array $where - Defaults to NULL. If used, must be used like this: array("id" => 5);
     * 
     * @return Array $data - the data returned.
    **/
    public function select($table, $columns, $where=NULL) {
        $this->error = false;
        $this->errorMsg = '';

        $q = "SELECT $columns FROM $table ";

        if($where) {
            $q .= "WHERE ";
            $i = 1;

            foreach($where as $key => $val) {
                if($i == count($where)) {
                    $q .= "$key = :$key";
                } else {
                    // no need for OR i dont think.
                    $q .= "$key = :$key AND ";
                }

                $i++;
            }
        }

        $statement = $this->conn->prepare($q);
        if($where) {
            foreach($where as $key => $val) {
                if(gettype((int)$val) == "integer") {
                    $statement->bindValue($key, $val, PDO::PARAM_INT);
                } else {
                    $statement->bindValue($key, $val, PDO::PARAM_STR);
                }
            }
        }

        try {
            $statement->execute();
        } catch(PDOException $e) {
            $this->error = true;
            $this->errorMsg = $e->getMessage();

            return false;
        }
        
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * A quick way to insert data into the database.
     * 
     * @param String $table - The table that data is to be selected from.
     * @param Array $data - Data being inserted. Column name is the array key. Example: array("username" => "John", "password" => "12345")
     * 
     * @return Bool - true if success, false if an error appeared.
    **/
    public function insert($table, $data) {
        $this->error = false;
        $this->errorMsg = "";

        $statement = $this->conn->prepare("INSERT INTO `$table` (".implode(", ", array_keys($data)).") VALUES (:".implode(', :', array_keys($data)).")");

        foreach($data as $column => $value) {
            $statement->bindValue($column, $value, PDO::PARAM_STR);
        }

        try {
            $statement->execute();
        } catch(PDOException $e) {
            $this->error = true;
            $this->errorMsg = $e->getMessage();

            return false;
        }

        return true;
    }

    
    /**
     * A quick way to insert data into the database.
     * 
     * @param String $table - The table that data is to be selected from.
     * @param Array $data - Data being updated. Column name is the array key. Example: array("username" => "John", "password" => "12345")
     * @param Array $where - Defaults to null, works the same as method select()
     * 
     * @return Bool - true if success, false if an error appeared.
    **/
    public function update($table, $data, $where=NULL) {
        $q = "UPDATE $table SET ";
        $i = 1;

        foreach($data as $key => $val) {
            if($i == count($data)) {
                $q .= "`$key` = :$key";
            } else {
                $q .= "`$key` = :$key, ";
            }

            $i++;
        }

        if($where && gettype($where) == "array") {
            $q .= " WHERE ";
            $i = 1;

            foreach($where as $key => $val) {
                if($i == count($where)) {
                    $q .= "`$key` = :$key";
                } else {
                    // no need really for ORs, i dont think anyways lolololol
                    $q .= "`$key` = :$key AND ";
                }

                $i++;
            }
        }


        $statement = $this->conn->prepare($q);

        foreach($data as $key => $val) {
            $statement->bindValue($key, $val, PDO::PARAM_STR);
        }

        if($where) {
            foreach($where as $key => $val) {
                if(gettype((int)$val) == "integer") {
                    $statement->bindValue($key, $val, PDO::PARAM_INT);
                } else {
                    $statement->bindValue($key, $val, PDO::PARAM_STR);
                }
            }
        }

        try {
            $statement->execute();
        } catch(PDOException $e) {
            $this->error = true;
            $this->errorMsg = $e->getMessage();

            return false;
        }

        return $q;
    }

    public function count($table, $where=NULL, $params=NULL) {
        $this->error = false;
        $this->errorMsg = "";

        $q = "SELECT COUNT(*) FROM $table";
        if($where) {
            $q .= " WHERE $where";
        }

        $statement = $this->conn->prepare($q);

        try {
            $statement->execute($params);
        } catch(PDOException $e) {
            $this->error = true;
            $this->errorMsg = $e->getMessage();

            return false;
        }

        $d = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $d[0]["COUNT(*)"];
    }

    public function close() {
        $this->conn = NULL;
    }
}
