<?php

namespace App\Imports;

use App\Models\OrgHeader;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Str;

class OrgImport implements ToCollection
{
    use Importable;

    public function collection(Collection $rows)
    {
      $headers = collect($rows[0]);
      $headers->shift();

      foreach ($rows as $key => $col) {
        if($key > 0 && $col[4] != ''){
          unset($col[0]);            
          $data = $headers->combine($col);

          if($col[2] != ''){
            $orgHeader = OrgHeader::updateOrCreate(['OH_Code' => $col[2]], $data->toArray());
          } else {
            $orgHeader = OrgHeader::firstOrCreate($data->toArray());

            $exc = ['PT', 'PT.', 'CV', 'CV.'];

            $name = explode(' ', $col[4]);

            if(in_array(Str::upper($name[0]), $exc)){
              unset($name[0]);
            }
            
            $name = array_merge($name);         

            if($col[30] > 0){
              $countcode = '_ID';
            } elseif($col[29] > 0){
              $countcode = '_WW';
            } else {
              if($col[40] != ''){
                $countcode = substr($col[40], -3);
              }              
            }

            $jml = count($name);

            if($jml > 1){
              $namaSet = Str::upper(substr($name[0],0, 3).substr($name[1], 0, 3).$countcode);
            } else {
              $namaSet = Str::upper(substr($name[0],0, 6).$countcode);
            }

            $namaDepan = preg_replace('/[^A-Za-z0-9\-]/', '', $namaSet);

            $cek = OrgHeader::where('OH_Code', 'LIKE', $namaDepan.'%')->count();

            $urut = sprintf('%03d', $cek + 1);
                      
            $orgHeader->OH_Code = $namaDepan.$urut;
            $orgHeader->save();
          }        
        }
      }
    }
}
