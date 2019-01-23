FROM mariadb:10.2
RUN usermod --non-unique --uid ${UID_USER:-1000} mysql
RUN groupmod -g ${UID_USER:-1000} mysql
