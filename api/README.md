CREATE USER 'mecanica_user'@'%' IDENTIFIED BY 'mecanica_pass';
GRANT ALL PRIVILEGES ON mecanica_db.* TO 'mecanica_user'@'%';



## Instalar PM2
Instalación i configuración PM2 para mantener ejecutando app nodejs 

```bash
npm install pm2 -g
```

```bash
pm2 start app.js

pm2 list
# pm2 start npm --name "mecanica_api" -- run pro
```