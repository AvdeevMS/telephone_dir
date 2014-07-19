<?php include "templates/include/header.php" ?>
      <script type="text/javascript" src="js/change_select_city.js"></script>
      <div id="editorHeader">
        <h2>Редактор</h2>
      </div>
  
      <h1><?php echo $results['pageTitle']?></h1>
  
	    <form action="index.php?action=<?php echo $results['formAction']?>" method="post">
        <input type="hidden" name="recordId" value="<?php echo $results['record']->id ?>"/>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
        <ul>
  
          <li>
            <label for="surname">Фамилия</label>
            <input type="text" name="surname" id="surname" placeholder="Введите фамилию" required autofocus maxlength="40" value="<?php echo htmlspecialchars( $results['record']->surname )?>" />
          </li>
          
          <li>
            <label for="name">Имя</label>
            <input type="text" name="name" id="name" placeholder="Введите имя" required autofocus maxlength="40" value="<?php echo htmlspecialchars( $results['record']->name )?>" />
          </li>
          
          <li>
            <label for="pathronymic">Отчество</label>
            <input type="text" name="pathronymic" id="pathronymic" placeholder="Введите отчество" required autofocus maxlength="40" value="<?php echo htmlspecialchars( $results['record']->pathronymic )?>" />
          </li>
          
          <li>
            <label for="city">Город</label>
            <select name="city_id" id="city" size="1" onchange="select_city(this);"> 
            <?php foreach ( $data_cities as $city ){ ?>
              <?php if( $city->id == $results['record']->city_id ){ ?>
                <option value="<?php echo $city->id ?>" selected><?php echo $city->city_name ?></option>
              <?php }else{ ?>
                <option value="<?php echo $city->id ?>"><?php echo $city->city_name ?></option>
              <?php } ?>
            <?php } ?>
            </select>
          </li>
          
          <li>
            <label for="street">Улица</label>
            <select name="street_id" id="street" size="1"> 
            <?php foreach( $data_streets as $street ) { ?>
              <?php if( $street->id == $results['record']->street_id ){ ?>
                <option value="<?php echo $street->id ?>" selected data="<?php echo $street->city_id ?>"><?php echo $street->street_name ?></option>
              <?php }else{ ?>
                <option value="<?php echo $street->id ?>" data="<?php echo $street->city_id ?>"><?php echo $street->street_name ?></option>
              <?php } ?>
            <?php } ?>
            </select>
          </li>
          
          <li>
            <label for="date_birth">Дата рождения</label>
            <input type="date" name="date_birth" id="date_birth" placeholder="ГГГГ-ММ-ДД" required maxlength="10" value="<?php echo $results['record']->date_birth ? date( "Y-m-d", $results['record']->date_birth ) : "" ?>" />
          </li>
          
          <li>
            <label for="number">Номер телефона</label>
            <input type="text" name="number" id="number" placeholder="Введите номер телефона" required autofocus maxlength="40" value="<?php echo htmlspecialchars( $results['record']->number )?>" />
          </li>
 
        </ul>
  
        <div class="buttons">
          <input type="submit" name="saveChanges" value="Сохранить изменения" />
          <input type="submit" formnovalidate name="cancel" value="Отменить" />
        </div>
 
	    </form>
  
<?php if ( $results['record']->id ) { ?>
            <p><a href="index.php?action=deleteRecord&amp;recordId=<?php echo $results['record']->id ?>" onclick="return confirm('Delete This Record?')">Удалить эту запись</a></p>
<?php } ?>

<?php include "templates/include/footer.php" ?>