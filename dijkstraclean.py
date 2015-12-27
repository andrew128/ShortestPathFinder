#!/usr/bin/env python
import math
import sys
# =============================
# DOC: this function splices 'x,y' into [x,y]
def splice(string, divider):
	temp = '';
	i = 0;
	while (i<len(string) and string[i]!=divider):
		i=i+1;
	return i;
# =============================
# SECTION: Processing input
# DOC: getting parameter containing all points
points = sys.argv[1];
points = points.split("|");
a = points.pop();
grid = [];
# DOC: 2 for loops to parse all points
for val in points:
	val = val.split(",");
	val[0] = int(val[0]);
	val[1] = int(val[1]);
	grid.append(val);
for n in grid:
	index = grid.index(n)
	grid[index] = tuple(n);
# print grid;

# DOC: getting start and endpoints
start = sys.argv[2];
end = sys.argv[3];
startPointIndex = splice(start, ',');
endPointIndex = splice(end, ',');

startpoint = (int(start[:startPointIndex]), int(start[startPointIndex+1:]));
endpoint = (int(end[:endPointIndex]), int(end[endPointIndex+1:]));

startendpoints = [startpoint, endpoint];

#DOC: getting obstacles
# obstacles = [(0,0), (0,1)];

obstacles = [];
obstacleInput = sys.argv[4];
obstacleInput = obstacleInput.split("|");
obstacleInput.pop(0);
obstacleInput.pop(len(obstacleInput)-1);
for val in obstacleInput:
	val = val.split(",");
	val[0] = int(val[0]);
	val[1] = int(val[1]);
	obstacles.append(val);
for n in obstacles:
	index = obstacles.index(n);
	obstacles[index] = tuple(n);

# print startendpoints
# print obstacles
# =============================
# SECTION: dijkstra algorithm; prints output
def dijkstra(grid, startendpoints, obstacles):  
# initialize necessary lists, dictionaries, and variables
	graph = {} 
	queuedist = {}
	visiteddist = {}
	predecessors = {}
	start = startendpoints[0]; end = startendpoints[1]

	# remove obstacles from grid
	for v in grid:
		for i in obstacles:
		    if i == v:
				grid.remove(v)

	# create full graph dictionary; points with adjacencies and adjacent edge lengths
	for v1 in grid:
		graph[v1] = {} 
		for v2 in grid: 
		    if v2 != v1:
		        diffx = int(v1[0])-int(v2[0]) 
		        diffy = int(v1[1])-int(v2[1]) 
		        if abs(diffx) <= 1 and abs(diffy) <= 1: 
		            graph[v1][v2] = float(math.sqrt(abs(diffx)**2 + abs(diffy)**2)) 

	# initializes queuedist, giving start point 0 distance, all other points infinite distance
	for v in grid: 
		if v == start:
		    queuedist[v] = 0 
		else:
		    queuedist[v] = float("inf") 

	# implementation of Dijkstra's Algorithm; returns the shortest distance from start to end
	while True:
		minimalnode = min(queuedist, key=queuedist.get)
		adjacencies = graph[minimalnode]
		a = visiteddist.keys(); b = adjacencies.keys() # create lists of keys (points) in visiteddist and adjacencies
		# makes sure that there actually is a path to the end; if none of a point's adjacencies have been visited (and visiteddist not empty), the point is clearly blocked off from the rest of the grid
		# by obstacles, and if end is not yet in visiteddist, the end point is likewise blocked off from the rest of the grid and cannot be reached; below code checks these cases to make sure end
		# is reachable; if visiteddist is empty or some elements of visiteddist are in adjacencies, we're good to go, otherwise, if end is not yet visited, there is no path to the end
		if not visiteddist or len(set(a) & set(b)) != 0: 
		    for p in adjacencies: 
		        if p not in visiteddist:
		            if queuedist[minimalnode] + adjacencies[p] < queuedist[p]:
			        queuedist[p] = queuedist[minimalnode] + adjacencies[p]
			        predecessors[p] = minimalnode 
		    visiteddist[minimalnode] = queuedist.pop(minimalnode)
		    if end in visiteddist:
	             return (visiteddist[end], predecessors)
			     # break
		else:
		    print "There is no shortest path from start to finish. Obstacles are blocking the end off." # can possibly just delete this string, since same thing is said later on
		    return None
		    break


def shortestpath(grid, startendpoints, obstacles):
    if dijkstra(grid, startendpoints, obstacles) != None: # if there is no path from start to end, dijkstra will have type None; here we check that there is a path from start to end
        pathdistance, predecessors = dijkstra(grid, startendpoints, obstacles)
        start = startendpoints[0]; end = startendpoints[1]    
        path = []
        while True:
	    path.append(end)
	    if end == start:
	        break
 	    end = predecessors[end]
        path.reverse()
        return (path, pathdistance)
    else:
	    return None

if shortestpath(grid, startendpoints, obstacles) != None: # just checks to see that we can actually unpack shortestpath; if there is no path and type is None, we can't unpack/will be an error below
    c, d = shortestpath(grid, startendpoints, obstacles) # with the way the code is set up, shortestpath will be the function to actually output the path and the distance, not dijkstra
    for val in c:
    	print val;
    # print c; #print d;

    
 
 
