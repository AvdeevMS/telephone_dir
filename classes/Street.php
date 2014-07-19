<?php

  /**
   *Класс выбора улицы из выпадающего списка
   */
  class Street{
  
    //Свойства

    /**
     *@var int ID улицы из базы данных
     */
    public $id = null;

    /**
     *@var string Название улицы
     */
    public $street_name = null;
    
    /**
     *@var int ID улицы из базы данных
     */
    public $city_id = null;
    
    /**
     *Устанавливаем свойства с помощью значений в заданном массиве
     *
     *@param assoc Значения свойств
     */
    public function __construct( $data=array() ){
      if( isset( $data['id'] ) ) $this->id = (int) $data['id'];
      if( isset( $data['street_name'] ) ) $this->street_name = preg_replace( "/[^ АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя]/", "", $data['street_name'] );
      if( isset( $data['city_id'] ) ) $this->city_id = (int) $data['city_id'];
    }
    
    /**
     *Возвращает все объекты улиц в базе данных
     *
     *@return results|false список объектов улиц
     */
    public static function getList(){
      $DB_OPTIONS = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8 COLLATE utf8_general_ci');
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD, $DB_OPTIONS );
      $sql = "SELECT * FROM streets ORDER BY street_name";              
      $st = $conn->prepare( $sql );
      $st->execute();
      $list = array();      
      while( $row = $st->fetch() ){
        $street = new Street( $row );
        $list[] = $street;
      }
      $conn = null;
      return $list;
    }
  
  }  
 ?>