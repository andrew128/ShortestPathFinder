# another way to splice string: store locations of every divider, store substrings inbetween into list using loop
def splice(string, divider):
	temp = '';
	i = 0;
	while (i<len(string) and string[i]!=divider):
		i = i+1;

	return i;

str = '012,213';

divideIndex = splice(str, ',');
print(str[divideIndex+1:]);
print(str[:divideIndex]);



