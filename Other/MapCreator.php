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
         $gridWidth = $_POST["width"];
         $gridHeight = $_POST["height"];
         $initialX = $_POST['sxPosition'];
         $initialY = $_POST['syPosition'];
         $endX = $_POST['exPosition'];
         $endY = $_POST['eyPosition'];
      }    
   ?>
 
   <script>
      //array that stores starting + ending location
      //array that stores barrier  
 
      var unvisited = []; //verified that this array contians all points
      var width = 10;//'<?php echo $gridWidth; ?>';
      var height =  10;//'<?php echo $gridHeight; ?>';
      var inY = 1;//'<?php echo $initialY; ?>'
      var inX = 3;//'<?php echo $initialX; ?>';
      var endX = 5;//'<?php echo $endX; ?>';
      var endY = 4;//'<?php echo $endY; ?>';
      var grid = clickableGrid(width, height, inY, inX, endY, endX);
      //on click change color of box to yellow
      grid.addEventListener('click', function(cell) {
         if (cell.target.nodeName.toUpperCase() !== "TD") {
            return;
         }
            var td = cell.target;
            td.className = 'clicked';
         });
      console.log(height);
      console.log(width);
      document.body.appendChild(grid);
 
      function clickableGrid(rows, cols, inY, inX, endY, endX){
        
         var grid = document.createElement('table');
         grid.className = 'grid';
         for (var r = 1; r <= rows; r++){
            var row = grid.appendChild(document.createElement('tr'));
            row.className = 'row';
            for (var c = 1; c <= cols; c++){
               var cell = document.createElement('td');
               cell.className = 'cell';
               if(r == inY && c == inX) {
                  cell.className = 'start';
               }
               else if(r == endY && c == endX) {
                  cell.className = 'end';
               }
               row.appendChild(cell);
            }
         }
         return grid;
      }
   </script>
</body>
</html>