# RIJK Aplicação Web e API

Caso não tenha é preciso instalar o docker e docker-compose.

- [Instalar Docker](https://docs.docker.com/install/)
- [Instalar Docker Compose](https://docs.docker.com/compose/install/)

Depois de instalar basta rodar no terminal na raiz aonde está o arquivo `docker-compose.yml` o seguinte comando.

```bash
sudo service docker start
docker rm $(docker ps -a -q) -f
docker rmi $(docker images -a -q) -f

//Subindo aplicação
docker-compose up -d

Verificar Dados da Rede
docker network ls
docker inspect network rijk_rijk.network

docker exec -it rijk sh

certbot --apache
docker tag

```
