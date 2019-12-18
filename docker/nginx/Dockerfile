FROM nginx:stable
ARG user_uid
RUN usermod -u ${user_uid? invalid argument} www-data
RUN groupmod -g ${user_uid? invalid argument} www-data
