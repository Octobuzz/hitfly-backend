FROM nginx:stable
RUN usermod -u ${UID_USER:-1001} www-data
RUN groupmod -g ${UID_USER:-1001} www-data