cd /scripts
mariadb -u openbenches -pbadpassword -D openbenc_benches < openbenc_benches_database.sql 
for f in openbenc_benches_table_*;do mariadb -u openbenches -pbadpassword -D openbenc_benches < "${f}";done
mariadb -u openbenches -pbadpassword -D openbenc_benches < openbenc_benches_extra.sql 