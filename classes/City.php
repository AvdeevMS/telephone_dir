<?php

  /**
   *Класс выбора города из выпадающего списка
   */
  class City{
  
    //Свойства

    /**
     *@var int ID города из базы данных
     */
    public $id = null;

    /**
     *@var string Название города
     */
    public $city_name = null;
    
    /**
     *Устанавливаем свойства с помощью значений в заданном массиве
     *
     *@param assoc Значения свойств
     */
    public function __construct( $data=array() ){
      if( isset( $data['id'] ) ) $this->id = (int) $data['id'];
      if( isset( $data['city_name'] ) ) $this->city_name = preg_replace( "/[^ АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя]/", "", $data['city_name'] );
    }
    
    /**
     *Возвращает все объекты городов в базе данных
     *
     *@return results|false список объектов городов
     */
    public static function getList(){
      $DB_OPTIONS = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8 COLLATE utf8_general_ci');
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD, $DB_OPTIONS );
      $sql = "SELECT * FROM cities ORDER BY city_name";              
      $st = $conn->prepare( $sql );
      $st->execute();
      $list = array();      
      while( $row = $st->fetch() ){
        $city = new City( $row );
        $list[] = $city;
      }
      $conn = null;
      return $list;
    }
  
  }  
 ?>