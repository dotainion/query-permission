to initialize namespace when new file is created in php run:
composer dump-autoload -o

update for tag version
delete all local files:
git ls-remote --tags origin

delete all files on git: 
for /f "tokens=*" %i in ('git ls-remote --tags origin ^| findstr /R "refs/tags/"') do git push --delete origin %i:refs/tags/%i
