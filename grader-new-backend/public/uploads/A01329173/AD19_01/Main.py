s = input()
print((len(s) - (s == s[::-1])) * (len(set(s)) > 1))
