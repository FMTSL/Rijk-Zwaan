FROM postgres:13-alpine
# RUN usermod -u 1000 postgres
RUN echo "host all  all    0.0.0.0/0  md5" >> /var/lib/postgresql/data/pg_hba.conf

CMD ["postgres"]

EXPOSE 5432