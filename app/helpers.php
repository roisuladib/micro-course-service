<?php

use Illuminate\Support\Facades\Http;

function getUser($userId) { 
   $url = env('URL_USER_SERVICE').'/users/'.$userId;
   try {
      $res = Http::timeout(10)->get($url); 
      $data = $res->json();
      $data['http_code'] = $res->getStatusCode();
      return $data;
   }
   catch (\Throwable $th) {
      return [
         'status' => 'error',
         'http_code' => 503,
         'message' => 'Service user unavailable'
      ];
   }
}

function getUserById($userIds = []) {
   $url = env('URL_USER_SERVICE').'/users/';
   try {
      if (count($userIds) === 0) {
          return [
             'status' => 'success',
             'http_code' => 200,
             'data' => []
          ];
      }
      $res = Http::timeout(10)->get($url, ['users_id[]' => $userIds]);
      $data = $res->json(); 
      $data['http_code'] = $res->getStatusCode();
      return $data;
   } 
   catch (\Throwable $th) {
      return [
         'status' => 'error',
         'http_code' => 503,
         'message' => 'Service user unavailable'
      ];
   }
}

function postOrder($params) {
   $url = env('URL_ORDER_SERVICE').'/api/orders';
   try {
      $response = Http::post($url, $params);
      $data = $response->json();
      $data['http_code'] = $response->getStatusCode();
      return $data;
   } catch (\Throwable $th) {
      return [
         'status' => 'error',
         'http_code' => 503,
         'message' => 'Service order payment unavailable'
      ];
   }
}