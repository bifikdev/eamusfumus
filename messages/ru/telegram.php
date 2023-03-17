<?php

return [
    'MESSAGE_TEMPLATE' => "Основная информация
{active} Уведомления  
{ready} Статус
 
Статистика:
{iconSmoke} Общее кол-во перекуров: {smokeRequestAll}
{iconSmoke} Организовал перекуры: {smokeRequestWithMe} 
{iconTimer} Последний перекур: {lastSmokeRequest}",

    'MESSAGE_BUTTON' => '{icon} {text}',

    'MESSAGE_ERROR' => 'Тебя нет в боте. Используй /start',
    'MESSAGE_START' => 'Регистрация в боте',

    'SMOKE_DATE_NO' => 'Еще не организован',
    'SMOKE_WAIT' => '{icon} {username}'
];