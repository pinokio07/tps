<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SetupImport implements ToCollection
{
    use Importable;

    private $model;

    public function __construct(string $model)
    {
        $this->model = $model;
    }
    public function collection(Collection $rows)
    {              
        $headers = collect($rows[0]);
        $headers->shift();
        $kolom = 0;
        foreach ($rows as $key => $col) {
          if($key > 0 && $col[1] != ''){
            unset($col[0]);            
            $data = $headers->combine($col);
            $this->model::firstOrCreate($data->toArray());
          }
        }
    }
}
