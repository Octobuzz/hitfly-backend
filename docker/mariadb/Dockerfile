FROM mariadb:10.2
ARG base_env
RUN usermod --non-unique --uid ${base_env} www-data
RUN groupmod -g ${base_env} www-data
