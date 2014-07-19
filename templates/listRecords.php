<?php include "templates/include/header.php" ?>
  
  <div id="editorHeader">
    <h2>Редактор</h2>
  </div>
  
  <h1>Все записи</h1>
  
<?php if ( isset( $results['errorMessage'] ) ) { ?>
  <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>

<?php if ( isset( $results['statusMessage'] ) ) { ?>
  <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>
  
    
  <table>
    <tr>
      <th>Фамилия</th>
      <th>Имя</th>
      <th>Отчество</th>
      <th>Город</th>
      <th>Улица</th>
      <th>Дата рождения</th>
      <th>Номер телефона</th>
    </tr>
  
<?php foreach ( $results['records'] as $record ) { ?>
  
    <tr onclick="location='index.php?action=editRecord&amp;recordId=<?php echo $record->id ?>'">
      <td>
        <?php echo $record->surname ?>
      </td>  
      <td>
        <?php echo $record->name ?>
      </td>
      <td>
        <?php echo $record->pathronymic ?>
      </td>     
      <td>
        <?php foreach ( $data_cities as $city ){ ?>
          <?php if( $city->id == $record->city_id ){ ?>
            <?php echo $city->city_name ?>
          <?php } ?>
        <?php } ?>  
      </td>
      <td>
        <?php foreach ( $data_streets as $street ){ ?>
          <?php if( $street->id == $record->street_id ){ ?>
            <?php echo $street->street_name ?>
          <?php } ?>
        <?php } ?>
      </td>
      <td>
        <?php echo date ('d-m-Y', $record->date_birth) ?>
      </td>
      <td>
        <?php echo $record->number ?>
      </td>
    </tr>
  
<?php } ?>
  
  </table>
  
  <p><?php echo $results['totalRows']?> <?php echo ( $results['totalRows'] != 1 ) ? "записей" : "запись" ?> всего.</p>
  
  <p><a href="index.php?action=newRecord">Добавить новую запись</a></p>
  
<?php include "templates/include/footer.php" ?>