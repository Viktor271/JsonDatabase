<?php

namespace App\Classes;

use App\Classes\JsonDatabase;

class JsonTableExporter extends JsonDatabase {

    private string $tableName;
    private string $storagePath;
    private string $FullTableName;
    private array $columns;

    public function __construct(string $storagePath, string $tableName, array $columns)
    {
        $this->tableName = $tableName.".json";
        $this->storagePath = $storagePath;
        $this->FullTableName = $storagePath.$tableName.".json";
        $this->columns = $columns;
    }

    public function export(): array
    {
        $table = $this->readFile($this->FullTableName);
        $result = array();
        foreach ($table as $row) {
            $new_row = array();
            foreach ($this->columns as $column) {
                $new_row[$column] = $row[$column];
            }
            $result[] = $new_row;
        }
        return $result;
    }

    public function exportToFile(string $newTableName){
        $data = $this->export();
        dd($this->saveFile($data, $this->storagePath.$newTableName.".json"));
    }
}