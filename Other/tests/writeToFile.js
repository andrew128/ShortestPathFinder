var path = './text.txt';
//var file = new File(txtFile);
var file = new File([""], path);
var str = 'test string';

file.open('w');
file.writeln(str);
