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
      td.start {
         background-color:#00FF00;
      }
      td.end {
         background-color:#000000;
      }
      td.clicked {
         background-color:#FFFF00;
      }
      p {
         text-align: center;
      }
   /* .grid td.clicked {
          background-color:yellow;
          font-weight:bold; color:red;
      }*/  
   </style>
</head>
<body>
   <h1>Map Creator</h1>

   <?php
      $min = 1;
      $max = 40;
   ?>

   <form id="input" method = "POST">
      Width of Map(Range: <?=$min?> - <?=$max?> pixels):
      <br/>
      <input type="number" name="width" id="width" min=<?=$min?> max=<?=$max?> />
      <br/>
      Height of Map(Range: <?=$min?> - <?=$max?> pixels):
      <br/>
      <input type="number" name="height" id="height" min=<?=$min?> max=<?=$max?>/>
      <br/>
      x-coordinate of Starting Location: <br/>
      <input type="number" name= "sxPosition" id="sxPosition" min=<?=$min?> max=<?=$max?> /> 
      <br/>
      y-coordinate of Starting Location: <br/>
      <input type="number" name="syPosition" id="syPosition" min=<?=$min?> max=<?=$max?> /> 
      <br/>
      x-coordinate of Ending Location: <br/>
      <input type="number" name="exPosition" id="exPosition" min=<?=$min?> max=<?=$max?> /> 
      <br/>
      y-coordinate of Ending Location: <br/>
      <input type="number" name="eyPosition" id="eyPosition" min=<?=$min?> max=<?=$max?> /> 
      <br/>
      <input type="submit" value="Submit" /> <br/> 
   </form>
   <hr/>
   <p>start location is green square end location is black square</p>


   <?php
      if ($_SERVER["REQUEST_METHOD"] == "POST") { 
         $gridWidth = 20;//$_POST["width"];
         $gridHeight = 20;//$_POST["height"];
         $initialX = 1;//$_POST['sxPosition'];
         $initialY = 1;//$_POST['syPosition'];
         $endX = 5;//$_POST['exPosition'];
         $endY = 10;//$_POST['eyPosition'];

         $startPoint = [$initialX, $initialY];
         $endPoint = [$endX, $endY];
         $allPointsPHP = [];
         for($i = 0; $i<$gridWidth; $i++){
            for($j = 0; $j<$gridHeight; $j++){
               $point = [$i, $j];
               array_push($allPointsPHP, $point);
            }
         }
         
         $start = $initialY . "," . $initialY;
         $end = $endX  . "," . $endY;

         $sashimi = "";
         for($i = 0; $i<count($allPointsPHP); $i++){
            $californiaRoll = $allPointsPHP[$i][0] . "," . $allPointsPHP[$i][1] . "|";
            $sashimi = $sashimi . $californiaRoll;
         }
         $test = 'python dijkstraclean.py ' . $sashimi . " " . $start . " " . $end;
         //echo $test;
         //echo $sashimi;
         $command = escapeshellcmd('python dijkstraclean.py ' . $sashimi . " " . $start . " " . $end);
         $temp = shell_exec($command);
         $temp = str_replace("\n", "", $temp);
         $temp = str_replace("\r", "", $temp);
         // echo $temp;
         // echo gettype($temp);

         // $arr = explode(" ", $temp);
         // echo $arr;
         // print_r($arr);
         //print_r($temp);
      }     
   ?>

   <script>
      
      //parsing input from string
      var input = "<?php echo $temp ?>";
      var res = input.split('(');
      res.splice(0,1);
      var path = [];
      for(var i = 0; i<res.length; i++){
         var toEdit = res[i];
         toEdit = res[i].split(', ');
         toEdit[0] = parseInt(toEdit[0]);
         toEdit[1] = parseInt(toEdit[1].substring(0,1));
         path.push(toEdit);
      }

      //array that stores starting + ending location
      //array that stores barrier   
      var allPoints = []; //verified that this array contains all points
      var width = '<?php echo $gridWidth; ?>'; 
      var height =  '<?php echo $gridHeight; ?>';
      var inY = '<?php echo $initialY; ?>'
      var inX = '<?php echo $initialX; ?>';
      var endX = '<?php echo $endX; ?>';
      var endY = '<?php echo $endY; ?>';
      var grid = clickableGrid(width, height, inY, inX, endY, endX);
      //on click change color of box to yellow
      grid.addEventListener('click', function(cell) {
         if (cell.target.nodeName.toUpperCase() == "TD") {
            var td = cell.target;
            td.className = 'clicked';
         } 
            
      });
      //console.log(height);
      //console.log(width);
      document.body.appendChild(grid);

      function changeColor(el) {
         el.className = 'clicked';
      }

      for(var i=0; i<path.length; i++){
         var temp = path[i][0] + ',' + path[i][1];
         var currentCell = document.getElementById(temp);
         currentCell.className = 'clicked';
      }

      function clickableGrid(rows, cols, inY, inX, endY, endX){   
         var grid = document.createElement('table');
         grid.className = 'grid';
         for (var r = 0; r < rows; r++){
            var row = grid.appendChild(document.createElement('tr'));
            row.className = 'row';
            for (var c = 0; c < cols; c++){
               var cell = document.createElement('td');
               var id = r + ',' + c;
               cell.setAttribute('id', id);
               // console.log(cell.id);
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
               //allCells.push(cell);
              }
          }
          return grid;
      }  

      //highlight points in path [(x,y), (x,y)]
      // for(var i = 0; i < path.length; i++){
      //    point = path[i];
      //    console.log(point);
      // }

   </script>


</body>
</html>
