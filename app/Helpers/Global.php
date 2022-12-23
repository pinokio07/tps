<?php

use Illuminate\Support\Facades\View;
use App\Helpers\Running;

function getMenu($name)
{
  $menu = \App\Models\Menu::with(['parent_items' => function ($q) {
                              $q->where('active', true)
                                ->orderBy('order', 'asc');
                          }, 'parent_items.children' => function($c){
                            $c->where('active', true)
                              ->orderBy('order', 'asc');
                          }])
                          ->where('name', $name)
                          ->first();
  return (!$menu) ? '' : $menu;
}

function getPage($page)
{
  if (View::exists($page)) {
      return $page;
  }

  return 'pages.default';
}

function activeCompany()
{
  return \App\Models\GlbCompany::first();
}

function toRoman($number) {
  $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
  $returnValue = '';
  while ($number > 0) {
      foreach ($map as $roman => $int) {
          if($number >= $int) {
              $number -= $int;
              $returnValue .= $roman;
              break;
          }
      }
  }
  return $returnValue;
}

function subDomain()
{
  $sub = \Illuminate\Support\Arr::first(explode('.', request()->getHost()));

  return $sub;
}

function getRestrictedExt()
{
  $data = [
    'php',
    'html',
    'exe',
    'bat',
    'vba',
    'js',
    'xml',
  ];
  return $data;
}

function createLog($model, $id, $status)
{
  \App\Models\TpsLog::create([
    'logable_type' => $model,
    'logable_id' => $id,
    'user_id' => \Auth::id(),
    'keterangan' => $status
  ]);
}

function getRunning($module, $type, $date)
{
    //Create New Running Class
    $run = new Running;
    //Get Code
    $cek = $run->getCode($module, $type, $date);
    //Check for existing Code
    if($cek != 'FALSE'){
      //If Found, Set Variable
      $running = $cek;
    } else {
      //If Not Found, Set New Default Code
      $running = $run->setCode($module, $type, $date);
    }

    return $running;
}