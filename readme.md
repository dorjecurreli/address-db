# Address DB

Address DB app dev env



## Setup Development Environment

- Clone this repo: `git@github.com:dorjecurreli/address-db.git`
- Move into it: `cd address-db`
- Setup env: `cp .env.local .env`
- Setup docker compose: `cp docker-compose.local.yml docker-compose.yml`
- Start environment: `docker-compose up -d`
- Move into docker container: `docker exec - it address-db bash`
- Change user: `su sindria`
- Install composer dependecies `composer install`
