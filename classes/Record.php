<?php
  
  /**
   *Класс для обработки записей
   */
   
  class Record{
    //Свойства

    /**
     *@var int ID записи из базы данных
     */
    public $id = null;

    /**
     *@var string Фамилия
     */
    public $surname = null;

    /**
     *@var string Имя
     */     
    public $name = null;
    
    /**
     *@var string Отчество
     */
    public $pathronymic = null;
    
    /**
     *@var int ID Города из базы данных городов
     */
    public $city_id = null;
    
    /**
     *@var int ID Улицы из базы данных улиц
     */
    public $street_id = null;

    /**
     *@var int Дата рождения
     */
    public $date_birth = null;
    
    /**
     *@var string Номер телефона
     */
    public $number = null;
    
    /**
     *Устанавливаем свойства с помощью значений в заданном массиве
     *
     *@param assoc Значения свойств
     */
    public function __construct( $data=array() ){
      if( isset( $data['id'] ) ) $this->id = (int) $data['id'];
      if( isset( $data['surname'] ) ) $this->surname = preg_replace( "/[^ АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя]/", "", $data['surname'] );
      if( isset( $data['name'] ) ) $this->name = preg_replace( "/[^ АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя]/", "", $data['name'] );
      if( isset( $data['pathronymic'] ) ) $this->pathronymic = preg_replace( "/[^ АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя]/", "", $data['pathronymic'] );
      if( isset( $data['city_id'] ) ) $this->city_id = (int) $data['city_id'];
      if( isset( $data['street_id'] ) ) $this->street_id = (int) $data['street_id'];
      if( isset( $data['date_birth'] ) ) $this->date_birth = (int) $data['date_birth'];
      if( isset( $data['number'] ) ) $this->number = preg_replace( "/[^\+\-0-9()]/", "", $data['number'] );
    }
    
    /**
     *Устанавливаем свойства с помощью значений формы редактирования записи в заданном массиве
     *
     *@param assoc Значение записи формы
     */
    public function storeFormValues( $params ){
      
      //Сохраняем все параметры
      $this->__construct( $params );
      
      //Разбиваем и сохраняем дату рождения
      if ( isset( $params['date_birth'] ) ){
        $date_birth = explode( '-', $params['date_birth'] );
        
        if( count( $date_birth ) == 3 ){
          list( $y, $m, $d ) = $date_birth;
          $this->date_birth = mktime( 0, 0, 0, $m, $d, $y );
        }
      }
    }
    
    /**
     * Возвращаем объект статьи соответствующий заданному ID статьи
     *
     * @param int ID записи
     * @return Record|false Объект записи или false, если запись не найдена или возникли проблемы
     */
	 
    public static function getById( $id ) {    
      $DB_OPTIONS = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8 COLLATE utf8_general_ci');
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD, $DB_OPTIONS );
      $sql = "SELECT *, UNIX_TIMESTAMP(date_birth) AS date_birth FROM records WHERE id = :id";
      $st = $conn->prepare( $sql );
      $st->bindValue( ":id", $id, PDO::PARAM_INT );
      $st->execute();
      $row = $st->fetch();
      $conn = null;
      if ( $row ) return new Record( $row );
	  }
    
    /**
     *Возвращает все объекты записей в базе данных
     *
     *@param string Optional Столбец по которому производится сортировка записей (по умолчанию "surname")
     *@return Array|false Двух элементный массив: results => массив, список объектов записей; totalRows => общее количество записей
     */
    public static function getList( $order="surname" ){
      $DB_OPTIONS = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8 COLLATE utf8_general_ci');
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD, $DB_OPTIONS );
      $sql = "SELECT *, UNIX_TIMESTAMP( date_birth ) AS date_birth FROM records
              ORDER BY " . mysql_escape_string( $order );
              
      $st = $conn->prepare( $sql );
      $st->execute();
      $list = array();
      
      while( $row = $st->fetch() ){
        $record = new Record( $row );
        $list[] = $record;
      }
      
      //Получаем общее количество записей, которые соответствуют критерию
      $sql = "SELECT FOUND_ROWS() AS totalRows";
      $totalRows = $conn->query( $sql )->fetch();
      $conn = null;
      return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
    }
    
    /**
     *Вставляем текущий объект записи в базу данных, устанавливаем его свойства.
     */
    public function insert(){
      //Есть у объекта записи ID?
      if( !is_null( $this->id ) ) trigger_error( "Record::insert(): Attempt to insert an Record object that already has its ID property set (to $this->id).", E_USER_ERROR );
    
      //Вставляем запись
      $DB_OPTIONS = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8 COLLATE utf8_general_ci');
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD, $DB_OPTIONS );
      $sql = "INSERT INTO records ( surname, name, pathronymic, city_id, street_id, date_birth, number ) VALUES (:surname, :name, :pathronymic, :city_id, :street_id, FROM_UNIXTIME(:date_birth), :number )";
      $st = $conn->prepare( $sql );
      $st->bindValue( ":surname", $this->surname, PDO::PARAM_STR );
      $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
      $st->bindValue( ":pathronymic", $this->pathronymic, PDO::PARAM_STR );
      $st->bindValue( ":city_id", $this->city_id, PDO::PARAM_INT );
      $st->bindValue( ":street_id", $this->street_id, PDO::PARAM_INT );
      $st->bindValue( ":date_birth", $this->date_birth, PDO::PARAM_INT );
      $st->bindValue( ":number", $this->number, PDO::PARAM_STR );
      $st->execute();
      $this->id = $conn->lastInsertId();
      $conn = null;
    }
  
    /**
     *Обновляем текущий объект записи в базе данных
     */
    public function update(){
      //Есть ли у объекта статьи ID?
      if( is_null( $this->id ) ) trigger_error( "Record::update(): Attempt to update an Record object that does not have its ID property set.", E_USER_ERROR );
    
      //Обновляем запись
      $DB_OPTIONS = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8 COLLATE utf8_general_ci');
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD, $DB_OPTIONS );
      $sql = "UPDATE records SET surname=:surname, name=:name, pathronymic=:pathronymic, city_id=:city_id, street_id=:street_id, date_birth=FROM_UNIXTIME(:date_birth), number=:number WHERE id = :id";
      $st = $conn->prepare( $sql );
      $st->bindValue( ":surname", $this->surname, PDO::PARAM_STR );
      $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
      $st->bindValue( ":pathronymic", $this->pathronymic, PDO::PARAM_STR );
      $st->bindValue( ":city_id", $this->city_id, PDO::PARAM_INT );
      $st->bindValue( ":street_id", $this->street_id, PDO::PARAM_INT );
      $st->bindValue( ":date_birth", $this->date_birth, PDO::PARAM_INT );
      $st->bindValue( ":number", $this->number, PDO::PARAM_STR );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }
  
    /**
     *Удаляем текущий объект записи из базы данных
     */
    public function delete(){
      //Есть ли у объекта записи ID?
      if( is_null( $this->id ) ) trigger_error( "Record::delete(): Attempt to delete an Record object that does not have its ID property set.", E_USER_ERROR );
    
      //Удаляем запись
      $DB_OPTIONS = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8 COLLATE utf8_general_ci');
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD, $DB_OPTIONS );
      $st = $conn->prepare( "DELETE FROM records WHERE id = :id LIMIT 1" );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }
  }
  
?>