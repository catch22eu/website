#!/usr/bin/env python3
import fileinput 			# for inline substitutions
import re					# python's regex module
import shutil				# for copying files
import os					# absolute paths used for recursion
import glob					# for finding files recursively

cwd=os.getcwd()+"/"			# get current directory we're starting from

infile="index.php"
outfile="index.html"
oldroot="/home/deb103397n2/domains/catch22.eu/public_html/"
oldsite="//catch22.eu/"
newsite="//catch22eu.github.io/website/"
newroot=cwd
phpreg="<\?php include '(.*)'; \?>"  # Regular expression to find the <?php include 'phpfile'; ?> expresions. Space variations not implemented. '(.*)' matches 0 or more characters witthin single quotes, and stores the match within the quotation marks as group. question marks needed preceding "\", double quotes are needed to handle the single quotes within. 

# function to retrieve the path from a filename string
def getpath(filename):
	matchobj=re.search(r".*/",filename)
	return matchobj.group(0)

# get list of files recursively, including absolute path
def findfiles(filename):
	return glob.glob(cwd+"**/"+filename, recursive=True) # glob.glob **/ searches zero of more subdirectories, resursive=True needed to follow symlinks

# Find all index.php files and copy them to index.html files
infile_list=findfiles(infile)
for file in infile_list: 
	shutil.copyfile(file, getpath(file)+outfile) # note: we use absolute paths here

outfile_list=findfiles(outfile)

# The actual routine. Since we already copied the index.php files to index.html, we only have to process the index.html files.
# Processes all found index.html
# Replaces old hosting directory root to new hosting directory root
# Replaces old site URL's to new site URL's
# Inserts the <?php include'file'; ?>, iteratively (in case the inserted php file also contains <?php include'file'; ?>
#TODO: iterate through list of files, cd into directory, and see if we need to modify code below
for outfile in outfile_list:
	print(outfile)
	os.chdir(getpath(outfile))
	phpreplacements=1
	while phpreplacements:
		with fileinput.FileInput(files=(outfile), inplace=True, backup='.bak') as file:
			phpreplacements=0
			for line in file:
				line=re.sub(oldroot,newroot,line)
				line=re.sub(oldsite,newsite,line)
				matchobj=re.search(phpreg,line)
				line_new=re.sub(phpreg,'',line)
				print(line.replace(line,line_new), end='') # Does not go to std.out but to file
				if matchobj:
					phpreplacements += 1
#					filename=os.getcwd()+"/"+matchobj.group(1)	# only one match supposed per line; uses absolute paths. 
					filename=matchobj.group(1)	# only one match supposed per line; uses absolute paths. 
					with open(filename, 'r') as file:
						data = 	file.read()
					print(data) # Does not go to std.out but to file
