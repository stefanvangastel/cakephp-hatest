FROM tutum/apache-php
RUN DEBIAN_FRONTEND=noninteractive apt-get update && apt-get install -yq git php5-intl && rm -rf /var/lib/apt/lists/*
RUN service apache2 restart
RUN rm -fr /app
RUN export ALLOW_OVERRIDE=true
ADD . /app
RUN composer install
