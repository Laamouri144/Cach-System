# Cache System

A distributed caching and data processing system built with Docker, featuring Apache Kafka, MongoDB replica set, MySQL, Redis, Apache NiFi, and a Laravel application.

## Architecture Overview

This system demonstrates a complete data pipeline with caching, streaming, and ETL capabilities:

- **Laravel Application** (emp_app) - Web application with Kafka producer integration
- **Apache Kafka** - Message streaming platform for event-driven architecture
- **MongoDB Replica Set** - Distributed NoSQL database with 3 nodes for high availability
- **MySQL** - Relational database with persistent storage
- **Redis** - In-memory cache for performance optimization
- **Apache NiFi** - Data flow automation and ETL processing
- **Adminer** - Web-based database management tool

## Services

| Service | Container | Port | Purpose |
|---------|-----------|------|---------|
| Laravel App | `emp_app` | 8000 | PHP 8.2 web application |
| Kafka Broker | `broker` | 9092 | Message streaming |
| Zookeeper | `zookeeper` | 2181 | Kafka coordination |
| MongoDB #1 | `mongo1` | 27017 | Primary replica set node |
| MongoDB #2 | `mongo2` | 27018 | Secondary replica set node |
| MongoDB #3 | `mongo3` | 27019 | Secondary replica set node |
| MySQL | `mysql` | 3306 | Relational database |
| Redis | `redis` | 6379 | Cache server |
| Apache NiFi | `nifi120` | 9443 (HTTPS)<br>8080 (HTTP) | Data flow automation |
| Adminer | `adminer` | 8081 | Database admin UI |

## Prerequisites

- Docker Desktop installed
- Docker Compose installed
- At least 8GB of RAM available for containers
- Ports 2181, 3306, 6379, 8000, 8080, 8081, 9092, 9443, 27017-27019 available

## Setup Instructions

### 1. Create Docker Network

Before starting the containers, create the external network:

```powershell
docker network create cachesys
```

### 2. Start All Services

```powershell
docker-compose up -d
```

### 3. Initialize MongoDB Replica Set

After MongoDB containers are running, initialize the replica set:

```powershell
docker exec -it mongo1 mongosh --eval "rs.initiate({
  _id: 'rs0',
  members: [
    { _id: 0, host: 'mongo1:27017' },
    { _id: 1, host: 'mongo2:27018' },
    { _id: 2, host: 'mongo3:27019' }
  ]
})"
```

### 4. Import MySQL Sample Data (Optional)

If you have the sample database:

```powershell
docker exec -i mysql mysql -uyouness -pdaronbola cachesystem < mysqlsampledatabase.sql
```

### 5. Access Services

- **Laravel App**: http://localhost:8000
- **Apache NiFi**: https://localhost:9443
  - Username: `admin`
  - Password: `ctsBtRBKHRAx69EqUghvvgEvjnaLjFEB`
- **Adminer**: http://localhost:8081
  - Server: `mysql`
  - Username: `youness`
  - Password: `doronbola`
  - Database: `cachesystem`

## Project Structure

```
CacheSystem/
├── emp/                    # Laravel application code
│   ├── app/               # Laravel app directory
│   ├── routes/            # Application routes
│   ├── resources/         # Views and assets
│   ├── kafka_consumer.py  # Kafka consumer script
│   └── produce.py         # Kafka producer script
├── nifi/                  # NiFi persistent data
│   ├── database_repository/
│   ├── flowfile_repository/
│   ├── content_repository/
│   ├── provenance_repository/
│   ├── state/
│   ├── logs/
│   └── lib/               # NiFi extensions (e.g., MySQL connector)
├── data/                  # MySQL data directory
├── cache/                 # Redis data directory
├── db/                    # MongoDB data directories
│   ├── db1/              # mongo1 data
│   ├── db2/              # mongo2 data
│   └── db3/              # mongo3 data
├── docker/
│   └── mongo/            # MongoDB initialization scripts
├── docker-compose.yml    # Service orchestration
└── mysqlsampledatabase.sql # Sample MySQL data
```

## Database Credentials

### MySQL
- **Host**: localhost:3306 (or `mysql` from containers)
- **Root Password**: `Bgj5WL#F8Ztaz`
- **User**: `youness`
- **Password**: `doronbola`
- **Database**: `cachesystem`

### MongoDB Replica Set
- **Connection String**: `mongodb://localhost:27017,localhost:27018,localhost:27019/?replicaSet=rs0`
- **Replica Set Name**: `rs0`
- No authentication configured

### Redis
- **Host**: localhost:6379 (or `redis` from containers)
- No authentication configured

## Laravel Application

The Laravel application includes:
- Customer and Order models with Eloquent ORM
- Kafka integration for event streaming
- Python producer/consumer scripts
- Redis caching support

### Running Kafka Consumer

```powershell
docker exec -it emp_app python3 kafka_consumer.py
```

### Running Artisan Commands

```powershell
docker exec -it emp_app php artisan <command>
```

## Apache NiFi

NiFi is configured with:
- MySQL connector jar in the extensions directory
- Persistent repositories for flow files, content, and provenance
- Single-user authentication enabled

Access NiFi at https://localhost:9443 (accept self-signed certificate).

## Managing Services

### Stop All Services
```powershell
docker-compose down
```

### Stop and Remove Volumes
```powershell
docker-compose down -v
```

### View Logs
```powershell
docker-compose logs -f [service_name]
```

### Restart a Service
```powershell
docker-compose restart [service_name]
```

## Troubleshooting

### MongoDB Replica Set Not Initialized
If MongoDB containers are running but the replica set isn't working:
```powershell
docker exec -it mongo1 mongosh --eval "rs.status()"
```

### Laravel Permission Issues
If you encounter permission issues with Laravel:
```powershell
docker exec -it emp_app chmod -R 777 storage bootstrap/cache
```

### Kafka Connection Issues
Ensure Zookeeper is running before Kafka:
```powershell
docker-compose ps
```

### NiFi Certificate Warning
The NiFi UI uses a self-signed certificate. This is normal for development. Accept the certificate in your browser to proceed.

## Development Workflow

1. **Code Changes**: Edit files in the `emp/` directory
2. **Database Changes**: Run migrations with `docker exec -it emp_app php artisan migrate`
3. **Clear Cache**: `docker exec -it emp_app php artisan cache:clear`
4. **View Logs**: `docker-compose logs -f emp_app`

## License

This is a development/educational project demonstrating distributed system architecture with caching and streaming capabilities.
