<!DOCTYPE html>
<html>
<head>
	<title>Map Creator</title>
	<style>
		.grid { margin:1em auto; border-collapse:collapse }
		.grid td {
		    cursor:pointer;
		    width:30px; height:30px;
		    border:1px solid #ccc;
		    text-align:center;
		    font-family:sans-serif; font-size:13px
		}
		.grid td.clicked {
		    background-color:yellow;
		    font-weight:bold; color:red;
		}	
	</style>
</head>
<body>
	<h1>Map Creator</h1>

	<?php
		$min = 1;
		$max = 500;
	?>

	<form method = "POST">
		Width of Map(Range: <?=$min?> - <?=$max?> pixels):
		<br/>
		<input type="number" name="width" min=<?=$min?> max=<?=$max?> />
		<br/>
		Height of Map(Range: 1 - 500 pixels):
		<br/>
		<input type="number" name="height" min=<?=$min?> max=<?=$max?>/>
		<br/>
		x-coordinate of Starting Location: <br/>
		<input type="number" name="xPosition" min=<?=$min?> max=<?=$max?> /> <br/>
		y-coordinate of Starting Location: <br/>
		<input type="number" name="yPosition" min=<?=$min?> max=<?=$max?> /> <br/>
		x-coordinate of Ending Location: <br/>
		<input type="number" name="xPosition" min=<?=$min?> max=<?=$max?> /> <br/>
		y-coordinate of Ending Location: <br/>
		<input type="number" name="yPosition" min=<?=$min?> max=<?=$max?> /> <br/>
		<input type="submit" value="Submit" /> <br/>
	</form>
	<hr/>

	<script>
		//array that stores starting + ending location
		//array that stores barrier

		//array that stores all points
		var allPoints = [];

		var grid = clickableGrid(10,10);
		document.body.appendChild(grid);

		//
		function clickableGrid(rows, cols){
		    var grid = document.createElement('table');
		    grid.className = 'grid';
		    for (var r=0;r<rows;++r){
		        var tr = grid.appendChild(document.createElement('tr'));
		        for (var c=0;c<cols;++c){
		            var cell = tr.appendChild(document.createElement('td'));
		            var point = [r, c];
		            allPoints.push(point);
		        }
		    }
		    return grid;
		}
		//print allPoints.toString();
		//var points = document.createElement('p');
		//document.getElementById(points).innerHTML = allPoints;		
	</script>

	<?php
		if ($_SERVER["REQUEST_METHOD"] == "POST"){ 
			$gridWidth = $_POST["width"];
			echo $gridWidth;
			$gridHeight = $_POST["height"];
			echo $gridHeight;
		}		
	?>

</body>
</html>