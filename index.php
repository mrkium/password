<?php
error_reporting( E_ALL ); 
//session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Generador de Contraseñas</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<link href="css/bootstrap-switch.css" rel="stylesheet">
</head>
<body>

<div class="container">

<div class="row">
<h2 class="text-center">Generador de contraseñas</h2>
</div>


<div class="row">
<div class="span12">
  <form action="" method="post" class="form-horizontal">
    <div class="row-fluid">
      <div class="span12">
        <div class="well">
          <div class="control-group span3">
            <label class="control-label">Mayúsculas (A-Z)</label>
            <div class="controls">
              <div class="switch" tabindex="0">
                <input id="upper" type="checkbox" name="upper" <?php if(isset($_POST['upper'])){echo "checked";}elseif(empty($_SESSION['str'])) echo "checked";?> />
              </div>
            </div>
          </div>
          <div class="control-group span3">
            <label class="control-label">Minúsculas (a-z)</label>
            <div class="controls">
              <div class="switch" tabindex="0">
                <input id="low" type="checkbox" name="low" <?php if(isset($_POST['low'])){ echo "checked";}elseif(empty($_SESSION['str'])) echo "checked";?> />
              </div>
            </div>
          </div>
          <div class="control-group span3">
            <label class="control-label">Númerico (0-9)</label>
            <div class="controls">
              <div class="switch" tabindex="0">
                <input id="numerals" type="checkbox" name="numerals" <?php if(isset($_POST['numerals'])) { echo "checked";}elseif(empty($_SESSION['str'])) echo "checked";?> />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="span6">
              <div class="control-group">
                <label class="control-label">Caracteres</label>
                <div class="controls">
                  <select class="input-mini" name="lenght">
                    <?php $i=4; while($i<20){?>
                    <option <?php if(isset($_POST['lenght']) && $i==$_POST['lenght']){echo "selected";}elseif($i==8 && !isset($_POST['lenght'])) {echo "selected";}?>><?php echo $i++?></option>
                    <?php }?>
                  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label"># de contraseñas</label>
                <div class="controls">
                  <select class="input-mini" name="num">
                    <?php $j=1; while($j<=10){?>
                    <option <?php if(isset($_POST['lenght']) && $j==$_POST['num']){echo "selected";}elseif($j==1 && !isset($_POST['num'])) {echo "selected";}?>><?php echo $j++?></option>
                    <?php }?>
                  </select>
                </div>
              </div>
            </div>
            <div class="span6">
              <div class="control-group">
                <label class="control-label">Prefijo</label>
                <div class="controls">
                  <input type="text" id="prefix" class="input-mini" name="prefix" style="z-index:913" maxlength="4" value="<?php if(isset($_POST['prefix'])) echo $_POST['prefix'];?>">
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">Sufijo</label>
                <div class="controls">
                  <input type="text" id="suffix" class="input-mini" name="suffix" maxlength="4" value="<?php if(isset($_POST['suffix'])) echo $_POST['suffix'];?>">
                </div>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-primary btn-large">Generar contraseña</button>
    </div>
  </form>
  <?php
	if(count($_POST) > 3){
		function factorial($n) {
		  return ($n <= 1) ? 1 : $n * factorial($n - 1);        
		}
		if(isset($_POST['num']) && $_POST['num'] > 9999) $_POST['num'] = 9999;
		set_time_limit(0);
		$t = microtime(true);
		$words = $passwords = array();
		$str = '';
		$i = 0;
		if(isset($_POST['low'])){
			$low = range('a','z');
			$words = array_merge($words, $low);
		}
		if(isset($_POST['upper'])){
			$upper = range('A','Z');
			$words = array_merge($words, $upper);
		}
		if(isset($_POST['numerals'])){
			$numerals = range('0','9');
			$words = array_merge($words, $numerals);
		}
		if(isset($_POST['simv'])){
			$simv = array('~','!','@','#','$','%','^','&','*','(',')','_','+','-','=','.',',',';',':');
			$words = array_merge($words, $simv);
		}
		while($i<$_POST['num']){
			shuffle($words);
			$letter = $pass=array();
			$pwd = "";
			if($_POST['lenght'] <= count($words)){
				$pass = array_rand($words,$_POST['lenght']);
			}else{
				$words_new = array();
				for($n=0;$n<$_POST['lenght'];$n++){
					$words_new[]=array_rand($words,1);
				}
				$pass = $words_new;
			}			
			foreach($pass as $key){
				$letter[] = $words[$key];
			}
			$pwd = implode('',$letter);			
			if(!preg_match("#[0-9]+#", $pwd) && isset($_POST['numerals'])) {}
			elseif(!preg_match("#[a-z]+#", $pwd) && isset($_POST['low'])) {}
			elseif(!preg_match("#[A-Z]+#", $pwd) && isset($_POST['upper'])) {}
			elseif((is_array($other) && is_array($letter) && count($other) > 0) && !@array_intersect($other,$letter)){}
			elseif(@array_search($pwd,$password) && is_array($password) && count($password) > 0){}
			else{
				if(isset($_POST['prefix']) && !empty($_POST['prefix'])){
					$pwd = substr($pwd,0,($_POST['lenght'])-strlen(trim($_POST['prefix'])));
					$pwd = trim($_POST['prefix']).$pwd;
				}
				if(isset($_POST['suffix']) && !empty($_POST['suffix'])){
					$pwd = substr($pwd,0,-(strlen(trim($_POST['suffix']))));
					$pwd = $pwd.trim($_POST['suffix']);
				}
				$passwords[] = $pwd;
				$i++;
			}
		}
		foreach($passwords as $val){
			$str .= $val."\n";
		}
		$_SESSION['str'] = $passwords;
		?>
  <div class="row-fluid">
    <div class="well">
      <textarea class="span12" rows="10"><?php echo $str;?></textarea>
      <?	
	}
?>
    </div>
  </div>
</div>
<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="span12">
        <div class="text-center">Cloud Computing Apps</div>
      </div>
    </div>
  </div>
</footer>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script> 
<script src="js/bootstrap.js"></script> 
<script src="js/bootstrap-switch.js"></script>
</body>
</html>
