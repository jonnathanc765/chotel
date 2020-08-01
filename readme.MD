# CHotel Booking App

Esta app de Booking consta de dos partes, Una API y el cliente.
para correrla, siga los siguientes comandos:



## API

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

## Cliente

```python
cd frontend
npm install
npm run serve
```
Y ya estará corriendo el sistema de CHotel Booking en un entorno de pruebas.

# Sistema interno
## Tablas

```
types
- description
- beds
```

```
rooms
- type_id
- price
- place
```

```
reservations
- check_in
- check_out
- room_id
```