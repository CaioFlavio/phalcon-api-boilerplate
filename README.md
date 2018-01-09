# phalcon-api-boilerplate
Implementação de API REST PHP com Framework Phalcon

#### User Guide

1 For use this code you need to install cPhalcon in your webserver
  * Follow the [Phalcon Installation Guide](https://github.com/phalcon/cphalcon) instructions
  
2 Add configuration for in your apache vHosts
``` 
<VirtualHost *:80>
  ServerAdmin youremail@yourhost.com
  DocumentRoot "PATH_FOR_YOUR_API"
  ServerName www.yourapi.com.br
  ServerAlias www.yourapi.com.br
  ErrorLog "logs/api-error.log"
  CustomLog "logs/api-access.log" combined

  RewriteEngine On

  <Directory "PATH_FOR_YOUR_API">
      Order allow,deny
      Allow from all
      Require all granted
  </Directory>
</VirtualHost>
```

### To Do in Docs

- [x] Add User Guide
- [ ] Add Running Migrations Guide
- [ ] Add Methods Explanation

### To Do in Code
- [x] Table migrations
- [ ] Add Token authorization
- [x] Add Middleware Content-type Verification
- [ ] Add Middleware Authorization
- [ ] User ACL
