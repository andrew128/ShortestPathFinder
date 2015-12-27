<!DOCTYPE html>
<html>
<head>
   <title>Map Creator</title>
   <style>
      .grid { margin:1em auto; border-collapse:collapse }
      .grid td {
          cursor:pointer;
          width:30px; height:30px;
          border:5px solid #ccc;
          text-align:center;
          font-family:sans-serif; font-size:13px
      }
      td.start { background-color:#00FF00; }
      td.end { background-color:#000000; }
      td.clicked { background-color:#FFFF00; }
      td.obstacle { background-color:#0000FF; }
      h1,p,h3 { text-align: center; } 
   </style>
</head>
<body>
   <h1>Map Creator</h1>
   <p>Description: This website finds the shortest path between to points on a grid</p>
   <?php
      $min = 1;
      $max = 40;
   ?>
   <form id="input" method = "GET">
      Width of Map(Range: <?=$min?> - <?=$max?> boxes):
      <br/>
      <input type="number" name="width" id="width" min=<?=$min?> max=<?=$max?> value='25'/>
      <br/>
      Height of Map(Range: <?=$min?> - <?=$max?> boxes):
      <br/>
      <input type="number" name="height" id="height" min=<?=$min?> max=<?=$max?> value='25'/>
      <br/>
      x-coordinate of Starting Location: <br/>
      <input type="number" name= "sxPosition" id="sxPosition" min=<?=$min?> max=<?=$max?> value='2'/> 
      <br/>
      y-coordinate of Starting Location: <br/>
      <input type="number" name="syPosition" id="syPosition" min=<?=$min?> max=<?=$max?> value='3'/> 
      <br/>
      x-coordinate of Ending Location: <br/>
      <input type="number" name="exPosition" id="exPosition" min=<?=$min?> max=<?=$max?> value='15'/> 
      <br/>
      y-coordinate of Ending Location: <br/>
      <input type="number" name="eyPosition" id="eyPosition" min=<?=$min?> max=<?=$max?> value='16'/> 
      <br/>
      Obstacles 
      <br/>
      <input type="text" name="obstacles" id="obstacles" value="|4,3|3,3|" style="width:300px;">
      <br/>
      <input type="submit" value="Submit" /> <br/> 
      <br/>
   </form>
   <hr/>
   <h3>Instructions:</h3> 
   <p>-Start location is green square</p>
   <p>-End location is black square</p>
   <p>-Obstacles are blue</p>

   <?php
      if ($_SERVER["REQUEST_METHOD"] == "GET") { 
         //DOC: get direct input from php form
         $gridWidth = $_GET['width'];
         $gridHeight = $_GET['height'];
         $initialX = $_GET['sxPosition'];
         $initialY = $_GET['syPosition'];
         $endX = $_GET['exPosition'];
         $endY = $_GET['eyPosition'];
         $obstacles = $_GET['obstacles'];

         //DOC: error check 

         //DOC: Creating full grid based on input dimensions
         $allPointsPHP = [];
         for($i = 1; $i<=$gridWidth; $i++){
            for($j = 1; $j<=$gridHeight; $j++){
               $point = [$i, $j];
               array_push($allPointsPHP, $point);
            }
         }
         
         //DOC: formatting input to be passed into dijkstra python script
         $start = $initialX . "," . $initialY;
         $end = $endX  . "," . $endY;
         $allPoints = "";
         for($i = 0; $i<count($allPointsPHP); $i++){
            $currentPoint = $allPointsPHP[$i][0] . "," . $allPointsPHP[$i][1] . "|";
            $allPoints = $allPoints . $currentPoint;
         }

         //DOC: running dijkstra with input
         $command = escapeshellcmd('python dijkstraclean.py ' . $allPoints . " " . $start . " " . $end . " " . $obstacles);
         $dijkstraOutput = shell_exec($command);
         // echo $dijkstraOutput;

         //DOC: cleaning output from dijkstra
         $dijkstraOutput = str_replace("\n", "", $dijkstraOutput);
         $dijkstraOutput = str_replace("\r", "", $dijkstraOutput);
      }     
   ?>

   <script>
      //DOC: parsing input from php containing the path in string form
      var input = "<?php echo $dijkstraOutput ?>";
      if(input=="There is no shortest path from start to finish. Obstacles are blocking the end off."){
         document.write("There is no shortest path from start to finish. Obstacles are blocking the end off.");
      }
      else{
         var res = input.split('(');
         res.splice(0,1);
         var path = [];
         for(var i = 0; i<res.length; i++){
            var toEdit = res[i];
            toEdit = res[i].split(', ');
            toEdit[0] = parseInt(toEdit[0]);
            //DOC: remove extra parenthese at end
            toEdit[1] = parseInt(toEdit[1].substring(0,toEdit[1].length-1));
            path.push(toEdit);
         }

         //DOC: parsing obstacles output
         var obstaclesInput = "<?php echo $obstacles ?>";
         var obstaclesInput = obstaclesInput.split('|');
         obstaclesInput.splice(0,1);
         obstaclesInput.splice(obstaclesInput.length-1,1);
         var obstacles = [];
         for(var i=0; i<obstaclesInput.length; i++){
            var toEdit = obstaclesInput[i].split(',');
            toEdit[0] = parseInt(toEdit[0], 10);
            toEdit[1] = parseInt(toEdit[1], 10);
            console.log(toEdit[0]);
            console.log(typeof toEdit[0]);
            console.log(toEdit[1]);
            console.log(typeof toEdit[1])
            if(isNaN(toEdit[0]) || isNaN(toEdit[1])){
               //stop everything and display error message
               alert("Input must be in form |x,y|x,y| where x and y are ints")
               throw new Error("Check alert box");
            } 
            obstacles.push(toEdit);
         }

         //DOC: getting all the php variables
         var allPoints = []; 
         var width = '<?php echo $gridWidth; ?>'; 
         var height =  '<?php echo $gridHeight; ?>';
         var inY = '<?php echo $initialY; ?>'
         var inX = '<?php echo $initialX; ?>';
         var endX = '<?php echo $endX; ?>';
         var endY = '<?php echo $endY; ?>';

         //DOC: function that creates grid to be displayed on webpage
         function clickableGrid(rows, cols, inY, inX, endY, endX){   
            var grid = document.createElement('table');
            grid.className = 'grid';
            for (var r = 1; r <= rows; r++){
               var row = grid.appendChild(document.createElement('tr'));
               row.className = 'row';
               for (var c = 1; c <= cols; c++){
                  var cell = document.createElement('td');
                  //DOC: giving id to each cell based off of x/y location
                  var id = r + ',' + c;
                  cell.setAttribute('id', id);
                  cell.className = 'cell';

                  if(r == inY && c == inX) {
                     cell.className = 'start';
                  }
                  else if(r == endY && c == endX) {
                     cell.className = 'end';
                  }
                  row.appendChild(cell);
                  var point = [c, r];
                  allPoints.push(point);
                 }
             }
             return grid;
         }

         //DOC: create grid
         var grid = clickableGrid(width, height, inY, inX, endY, endX);

         //DOC: on click change color of box to yellow
         // grid.addEventListener('click', function(cell) {
         //    if (cell.target.nodeName.toUpperCase() == "TD") {
         //       var td = cell.target;
         //       td.className = 'clicked';
         //    }   
         // });
         document.body.appendChild(grid);
         // function changeColor(el) {
         //    el.className = 'clicked';
         // }

         //DOC: highlighting the path 
         for(var i=1; i<path.length-1; i++){
            //DOC: reversing points because they come in reverse (row by column)
            var temp = path[i][1] + ',' + path[i][0];
            // console.log(temp);
            var currentCell = document.getElementById(temp);
            // console.log(currentCell);
            currentCell.className = 'clicked';
         }

         //DOC: highlighting obstacles
         for(var i=0; i<obstacles.length; i++){
            var temp = obstacles[i][1] + ',' + obstacles[i][0];
            // console.log(temp);
            var currentCell = document.getElementById(temp);
            // console.log(currentCell);
            currentCell.className = 'obstacle';
         }   
      }
  
   </script>
   <p>Authors: Andrew Eli Lucas</p>
</body>
</html>
