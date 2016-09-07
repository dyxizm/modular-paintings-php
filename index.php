<?php
ini_set("display_errors",1);
error_reporting(E_ALL);

$samplesDir = __DIR__.'/samples';
$first = false;
//if($_POST['config']) var_dump($_POST);
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>imgModule</title>
		<meta name="description" content="">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="css/style.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">

	</head>
	<body>
	
		<select id="selectFile" name="filename">
		<?php 
		$f = scandir($samplesDir);
		foreach ($f as $file){	
			if(preg_match('/\.[(png)|(PNG)|(jpg)|(jpeg)|(JPG)|(JPEG)]/', $file)){
				if(!$first){
					$first = $file;
					list($w_smpl, $h_smpl, $type) = getimagesize($samplesDir.'/'.$first);

				}
		?>
				<option value="<?=$file;?>"><?=$file;?></option>
		<?php
			}
		}
		?>
		</select>
		
		<div id="preview">
			<?php if($first){?><img src="samples/<?=$first;?>" alt="" /><?php }?>
			<div id="blocks">
			<div id="out" class="back"></div>
			</div>
		</div>
		<button id="del">Clear</button>
		<button id="add">Add</button>
		<form id="styleConfig" action="/imgModule.php" method="post">
			<div id="modules"></div>
			<button id="start" disabled>Start</button>
		</form>
	
		<script type="text/tpl" id="tpl_module">
			<div class="module">
				<input class="w" type="text" name=config[<%= mlength %>][w] placeholder="W" value="0" />
				<input class="h" type="text" name=config[<%= mlength %>][h] placeholder="H" value="0" />
				<input class="x" type="text" name=config[<%= mlength %>][x] placeholder="X" value="0" />
				<input class="y" type="text" name=config[<%= mlength %>][y] placeholder="Y" value="0" />
			</div>
		</script>
		
		<script type="text/tpl" id="tpl_block">
			<div class="block">
				<div class="back marginX"></div>
				<div class="blockWidth">
					<div class="back marginYtop"></div>
					<div class="blockHeight"></div>
					<div class="back marginYbottom"></div>
				</div>
			</div>
		</script>
	
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>		
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/main.js"></script>	
	</body>
</html>
