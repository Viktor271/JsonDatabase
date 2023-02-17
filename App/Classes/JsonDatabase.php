<?php

namespace App\Classes;

use App\Classes\DatabaseInterface;
use Exception;
use RuntimeException;


class JsonDatabase implements DatabaseInterface {

        private string $tableName;
        private string $storagePath;
        private string $FullTableName;
        private array $columns = [];

        public function __construct(string $storagePath, string $tableName, array $columns)
        {
            if (!file_exists($storagePath)) {
                throw new RuntimeException('Directory not found');
            }
            $this->storagePath = $storagePath;

            if(!$this->createTable($tableName,$columns)){
                $this->chooseTable($tableName);
            }
        }

        public function createTable(string $tableName, array $columns): bool
        {
            $this->columns = $columns;
            if (file_exists($this->storagePath.$tableName.".json")) {
                return false;
            }
            $this->tableName = $tableName.".json";
            file_put_contents($this->storagePath.$this->tableName, '[]', LOCK_EX);
            $this->FullTableName = $this->storagePath.$this->tableName;
            return true;
        }

        public function chooseTable(string $tableName): bool
        {
            if (!file_exists($this->storagePath . $tableName . ".json")) {
                throw new Exception('File not found');
            }
            $this->tableName = $tableName.".json";
            $this->FullTableName = $this->storagePath.$this->tableName;
            return true;
        }

        public function add(array $data): bool
        {
            if(!$data){
                throw new Exception('Variable $data is empty');
            }
            $table = $this->readFile($this->FullTableName);
            if ($this->validateColumns($data, $table)){
                if (end($table)){
                    $data['id'] = end($table)['id']+1;
                }else{
                    $data['id'] = 1;
                }
                $table[] = $data;
                if($this->saveFile($table,$this->FullTableName)){
                    return true;
                }
                throw new Exception('File not saved');
            }
            throw new Exception('Invalid data');
        }

        public function read(int $id):array
        {
            $table = $this->readFile($this->FullTableName);
            foreach ($table as $row) {
                if ($row['id'] == $id) {
                    return $row;
                }
            }
            throw new Exception('Record with this ID not found');
        }

        public function update(int $id, array $data): bool
        {
            $table = $this->readFile($this->FullTableName);
            if ($this->validateColumns($data, $table)) {
                foreach ($table as $key => $row) {
                    if ($row['id'] == $id) {
                        $data['id'] = $id;
                        $table[$key] = $data;
                        if($this->saveFile($table,$this->FullTableName)){
                            return true;
                        }
                        throw new Exception('File not saved');
                    }
                }
                return false;
            }
            throw new Exception('Invalid data');
        }

        public function delete(int $id): bool
        {
            $table = $this->readFile($this->FullTableName);
            foreach ($table as $key => $row) {
                if ($row['id'] == $id) {
                    unset($table[$key]);
                    if($this->saveFile($table,$this->FullTableName)){
                        return true;
                    }
                    throw new Exception('File not saved');
                }
            }
            throw new Exception('Record with this ID not found');
        }

        public function validateColumns(array $data, array $table): bool
        {
            if(!$this->columns){
                foreach ($table as $key => $value){
                    $this->columns = array_keys($value);
                    if (!empty($this->columns)){
                        break;
                    }
                }
            }
            foreach ($data as $key => $value){
                if (!in_array($key, $this->columns)){
                    return false;
                }
            }
            return true;
        }

        public function readFile($fullTableName): array
        {
            $json = file_get_contents($fullTableName);
            return json_decode($json, true);
        }

        public function saveFile(array $data, string $fullTableName): bool
        {
            if (file_put_contents($fullTableName, json_encode($data), LOCK_EX) === false){
                return false;
            }
            return true;
        }
}