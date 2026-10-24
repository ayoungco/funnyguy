#!/usr/bin/env bash
# Local phpBB 3.3 dev stack (podman): MariaDB + php-apache on http://localhost:8081
# usage: dev.sh up | down | install | style | import | reset | shell | db
set -euo pipefail
cd "$(dirname "$0")"
POD=fgforum; WEB=$POD-web; DB=$POD-db; PW=devonly-not-a-secret
VER=3.3.19; SHA=1530e5cabf7ecc9ecfaed3fa13da79f9e8a514623beafababc8800b5ce966de9
fetch() {  # download the pinned phpBB release, verify, unpack to phpBB3/ (idempotent)
  [ -d phpBB3/install ] || [ -f phpBB3/config.php ] && return 0
  mkdir -p .cache; local z=.cache/phpBB-$VER.zip
  [ -f $z ] || curl -sfL -o $z https://download.phpbb.com/pub/release/3.3/$VER/phpBB-$VER.zip
  echo "$SHA  $z" | sha256sum -c - >/dev/null || { echo "checksum mismatch"; rm -f $z; exit 1; }
  rm -rf phpBB3; unzip -q $z
  chmod -R o+rwX phpBB3/cache phpBB3/files phpBB3/store phpBB3/images/avatars/upload; chmod o+w phpBB3 phpBB3/config.php
}
case "${1:-}" in
up)
  fetch
  podman build -q -t fgforum-php -f Containerfile . >/dev/null
  podman pod exists $POD || podman pod create --name $POD -p 127.0.0.1:8081:80
  podman container exists $DB || podman run -d --pod $POD --name $DB -e MARIADB_ROOT_PASSWORD=$PW -e MARIADB_DATABASE=phpbb \
      -v fgforum-db:/var/lib/mysql:Z docker.io/library/mariadb:11 --character-set-server=utf8mb4 >/dev/null
  podman container exists $WEB || podman run -d --pod $POD --name $WEB -v "$PWD/phpBB3:/var/www/html:Z" fgforum-php >/dev/null
  podman start $DB $WEB >/dev/null; echo "up: http://localhost:8081" ;;
install)
  sed "s/__PW__/$PW/" install-config.yml > phpBB3/install-config.yml
  until podman exec $DB mariadb -uroot -p$PW -e 'select 1' >/dev/null 2>&1; do sleep 2; done
  podman exec -u www-data -w /var/www/html $WEB php install/phpbbcli.php install install-config.yml
  rm -f phpBB3/install-config.yml
  podman exec -u www-data -w /var/www/html $WEB php bin/phpbbcli.php extension:disable phpbb/viglink >/dev/null 2>&1 || true
  podman exec $WEB rm -rf /var/www/html/install ;;
style)  # copy styles/elemental into the board and make it the default (prosilver stays installed)
  rm -rf phpBB3/styles/elemental; cp -r ../styles/elemental phpBB3/styles/elemental; chmod -R o+rX phpBB3/styles/elemental
  podman cp ../import/install_style.php $WEB:/tmp/install_style.php; podman exec $WEB chmod 644 /tmp/install_style.php
  podman exec -u www-data $WEB php /tmp/install_style.php elemental Elemental prosilver --default; podman exec $WEB rm -f /tmp/install_style.php ;;
import)  # load the archive: dev.sh import /path/to/import.json  (from scripts/forum_export_for_phpbb3.py)
  podman cp "${2:?import.json path}" $WEB:/tmp/import.json; podman cp ../import/import_archive.php $WEB:/tmp/import_archive.php
  podman exec $WEB chmod 644 /tmp/import.json /tmp/import_archive.php
  podman exec -u www-data $WEB php /tmp/import_archive.php /tmp/import.json
  podman cp ../import/resync.php $WEB:/tmp/resync.php; podman exec $WEB chmod 644 /tmp/resync.php; podman exec -u www-data $WEB php /tmp/resync.php
  podman exec $WEB rm -f /tmp/import.json /tmp/import_archive.php /tmp/resync.php ;;
down) podman pod stop $POD ;;
reset) podman pod rm -f $POD || true; podman volume rm -f fgforum-db; podman unshare rm -rf phpBB3 ;;
shell) podman exec -it $WEB bash ;;
db) podman exec -it $DB mariadb -uroot -p$PW phpbb ;;
*) sed -n 2,3p "$0" ;;
esac
