<?php
//文章
$router->group(['prefix' => 'article'],function ($router)
{
    $router->any('index','ArticleController@index')->name('article.index');
    $router->get('getdata','ArticleController@getdata')->name('article.ajaxIndex');
    $router->post('destroy','ArticleController@destroy')->name('article.destroy');
});
$router->resource('article','ArticleController');