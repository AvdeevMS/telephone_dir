<?php 

  require ("config.php" );
  
  $action = isset( $_GET['action'] ) ? $_GET['action'] : "";
  
  switch ( $action ){
    case 'newRecord':
      newRecord();
      break;
    case 'editRecord':
      editRecord();
      break;
    case 'deleteRecord':
      deleteRecord();
      break;
    default:
      listRecords();
  }
  
  function newRecord() {

    $results = array();
    $data_cities = City::getList();
    $data_streets = Street::getList();
    $results['pageTitle'] = "Новая запись";
    $results['formAction'] = "newRecord";
 
    if ( isset( $_POST['saveChanges'] ) ) {

      // Пользователь получает форму редактирования записи: сохраняем новую запись
      $record= new Record;
      $record->storeFormValues( $_POST );
      $record->insert();
      header( "Location: index.php?status=changesSaved" );
 
    } elseif ( isset( $_POST['cancel'] ) ) {
 
      // Пользователь сбросил результаты редактирования: возвращаемся к списку записей
        header( "Location: index.php" );
    } else {
 
      // Пользователь еще не получил форму редактирования: выводим форму
      $results['record'] = new Record;
      require( TEMPLATE_PATH . "/editRecord.php" );
    }
 
  }
 
 
  function editRecord() {
  
    $results = array();
    $data_cities = City::getList();
    $data_streets = Street::getList();
    $results['pageTitle'] = "Изменить запись";
    $results['formAction'] = "editRecord";
 
    if ( isset( $_POST['saveChanges'] ) ) {
 
      // Пользователь получил форму редактирования записи: сохраняем изменения
 
      if ( !$record = Record::getById( (int)$_POST['recordId'] ) ) {
        header( "Location: index.php?error=recordNotFound" );
        return;
      }
 
      $record->storeFormValues( $_POST );
      $record->update();
      header( "Location: index.php?status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {
 
      // Пользователь отказался от результатов редактирования: возвращаемся к списку записей
      header( "Location: index.php" );
    } else {
 
      // Пользвоатель еще не получил форму редактирования: выводим форму
      $results['record'] = Record::getById( (int)$_GET['recordId'] );
      require( TEMPLATE_PATH . "/editRecord.php" );
    }
 
  }
 
	 
  function deleteRecord() {
 
    if ( !$record = Record::getById( (int)$_GET['recordId'] ) ) {
      header( "Location: index.php?error=recordNotFound" );
      return;
    }
 
    $record->delete();
    header( "Location: index.php?status=recordDeleted" );
  }
 
 
  function listRecords() {
    $results = array();
    $data_cities = City::getList();
    $data_streets = Street::getList();
    $data = Record::getList();
    $results['records'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['pageTitle'] = "Все записи";
 
    if ( isset( $_GET['error'] ) ) {
      if ( $_GET['error'] == "recordNotFound" ) $results['errorMessage'] = "Error: Record not found.";
    }
 
    if ( isset( $_GET['status'] ) ) {
      if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Ваши изменения были сохраненны.";
      if ( $_GET['status'] == "recordDeleted" ) $results['statusMessage'] = "Запись удалена.";
    }
 
    require( TEMPLATE_PATH . "/listRecords.php" );
  }
    
?>