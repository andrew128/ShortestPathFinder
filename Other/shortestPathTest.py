#shortest path test
#TBI: dont check points moving away from end point, limit graph
#=====================
import random;
#=====================
allPoints = [];

# array of all points within an 11 x 11 graph 
for i in range(11):
	for j in range(11):
		point = [i, j];
		allPoints.append(point);

# for i in range(10):
# 	randX = random.randint(1,10);
# 	randY = random.randint(1,10);
# 	point = [randX, randY];
# 	allPoints.append(point);

print allPoints;
#=====================