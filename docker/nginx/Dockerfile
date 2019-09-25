FROM nginx:stable
ARG base_env
RUN usermod --non-unique --uid ${base_env} www-data
RUN groupmod -g ${base_env} www-data
