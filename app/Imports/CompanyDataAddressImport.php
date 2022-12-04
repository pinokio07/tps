<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\OrgHeader;
use App\Models\OrgAddress;
use Auth;

class CompanyDataAddressImport implements ToCollection
{
    use Importable;

    public function collection(Collection $rows)
    {
        $headers = collect($rows[0]);
        $headers->shift();
        $timeUTC = now()->setTimezone('UTC');

        foreach ($rows as $key => $col) {
          if($key > 0 && $col[38] != ''){
            $orgHeader = OrgHeader::where('OH_Code', $col[38])->first();

            if($orgHeader){
              unset($col[0]);
              $data = $headers->combine($col);

              $orgAddress = OrgAddress::updateOrCreate([
                'OA_OH' => $orgHeader->id,
                'OA_Address1' => $col[6],
              ], $data->except(['OA_OH', 'OA_Address1'])->toArray());

              if($orgAddress->wasRecentlyCreated){
                $orgAddress->OA_SystemCreateTimeUtc = $timeUTC;
                $orgAddress->OA_SystemCreateUser = Auth::id();
              } else {
                $orgAddress->OA_SystemLastEditTimeUtc = $timeUTC;
                $orgAddress->OA_SystemLastEditUser = Auth::id();
              }

              $orgAddress->save();
            }
          }
        }
    }
}
