Before using bath-import_new.mysql you will need to create a user with userID value of 9.

To remove IP addresses before upload, run:

sed -i -E 's/[0-9]{1,3}(\.[0-9]{1,3}){3}/anon/g' openbenc_benches_table_users.sql 

Do not update `openbenc_benches_table_mastodon_apps.sql`
