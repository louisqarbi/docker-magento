
# Set up magento project

1) mkdir project_name && cd $_

2) Change .composer permission
You don't need to run this command if you did once for previous projects
mkdir ~/.composer && sudo chown -R $USER:$USER ~/.composer

3) curl -s https://raw.githubusercontent.com/louisqarbi/docker-magento/master/lib/template | bash

4) mv ~/Downloads/marketplace-latest.zip .

5) bin/louis domain

