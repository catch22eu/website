<div class="content">

<h3>Create a Github Repository</h3>
By far, the easiest and error-free method of using a Github Repository, is by starting with a new repository on github.com itself. Of course, you need a github account first. <br>

<h3>Clone the Github Repository to Local</h3>
To clone a Github repository, use the following command:
<code>git clone https://github.com/catch22eu/project-directory.git</code>
This wil create a new directory project-directory, and copy all the repository content on Github to this directory. Besides, it will also create a .git directory containing the administrative files needed to keep track of your changes.<br><br>

<h3>Add files or edit the Local Repository</h3>
cd into the directory that has just been created, and edit or add files to your preference. In order to update the changes to the local repository database, use the command
<code>git add .</code>
This will synchronise the new or added files (to what is called the staging area). <br><br>

<h3>Committing updates to the Local Repository</h3>
By now, the local repository is aware of the changes, but they need committing by you as a user to make the system also aware of different versions:
<code>git commit -m "Info of the changes and / or versioning here"</code>
Beware, that prior to this, you'll have to use two commands to define your "global" user name and email address if you did not do this before:
<code>git config -global user.name "your name"<br>
git config -global user.email you@email-address</code>
<br>

<h3>Pushing the changes to Github</h3>
Simply use:
<code>git push</code>
This will push the changes to the github server. The command will ask for your Github username and password before sending the files. A look at the github.com will then show your changes or additions as well.   <br><br>

<h3>Updating the Local Repository with a newer version from Github</h3>
First, archive any changes made on the local repository (if there are no changes, it will say so as well):
<code>git stash</code>
Then pull the newer version from Github and update the local repository:
<code>git pull</code>
<br>

<h3>Conclusion</h3>
That's all there is. For more information, and other options, see github.com. 


</div>
