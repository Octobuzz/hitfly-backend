FROM nginx:stable
ARG user_uid
#RUN usermod -u ${user_uid? invalid argument} www-data
RUN usermod -u 9532 www-data
#RUN groupmod -g ${user_uid? invalid argument} www-data
RUN groupmod -g 9532 www-data