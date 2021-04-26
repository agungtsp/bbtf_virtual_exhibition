## BBTF - Web CMS
BBTF - Web CMS

## Resources
CodeIgniter
<a href="https://github.com/bcit-ci/CodeIgniter">CodeIgniter </a><br />
 
### Github workflow

```
1. Create new issue on Github.
2. Create title and description on issue according to the task.
3. In your local, checkout to master.
4. Pull your master based on github remote to get the latest commit.
5. Create new branch based on master, naming the branch according to your issue number.
6. Working the issue on the new branch.
7. After the first commit, push your branch to github using git push "your remote" "your branch".
8. Create pull request on Frontend repository.
9. Continue your work, don't forget to commit and push regularly to your remote branch.
10. After you think your work is done:
    - commit your work
    - checkout to master
    - pull master to get the latest commit
    - checkout to your work branch
    - merge branch master to your branch (This step to make sure you handle conflict in your local first before pushing to the remote repository)
11. Request review your code to Tirta to make sure your work is the same as our standard code.
12. If your work failed in review, continue your work, comment the revision.
13. If your work passed the review, you can merge your branch to the master branch.
14. Delete your remote working branch.
15. Checkout to master, pull master to get the latest merging and delete your local working branch.
16. That's it!
```

### Commit convention

:hammer: `:hammer:` Use this emoji for every new feature or component.

:wrench: `:wrench:` This emoji for fixing or update your work.

:sparkles: `:sparkles:` Use this emoji if you had finishing new feature or component.

:construction: `:construction:` If you can't finish your work, or you get a blocker and cannot continuing your work, use this emoji for the last commit.
