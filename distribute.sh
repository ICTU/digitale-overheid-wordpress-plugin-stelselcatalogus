# sh /shared-paul-files/Webs/git-repos/ICTU---Digitale-Overheid-Plugin-Stelselcatalogus/distribute.sh

# clear the log file

> '/shared-paul-files/Webs/ICTU/Gebruiker Centraal/development/wp-content/debug.log'

# copy to temp dir
rsync -r -a --delete '/shared-paul-files/Webs/git-repos/ICTU---Digitale-Overheid-Plugin-Stelselcatalogus/' '/shared-paul-files/Webs/temp/'

# clean up temp dir
rm -rfv '/shared-paul-files/Webs/temp/.git/'
rm '/shared-paul-files/Webs/temp/.gitignore'
rm '/shared-paul-files/Webs/temp/distribute.sh'
rm '/shared-paul-files/Webs/temp/README.md'
rm '/shared-paul-files/Webs/temp/LICENSE'
rm '/shared-paul-files/Webs/temp/config.codekit3'


cd '/shared-paul-files/Webs/temp/'
find . -name ‘*.DS_Store’ -type f -delete


# copy from temp dir to dev-env
rsync -r -a --delete '/shared-paul-files/Webs/temp/' '/shared-paul-files/Webs/ICTU/Gebruiker Centraal/development/wp-content/plugins/rhswp-stelselcatalogus/' 

# remove temp dir
rm -rf '/shared-paul-files/Webs/temp/'