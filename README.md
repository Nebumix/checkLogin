checkLogin
==========

History login for Symfony2 and block account after 5 failed login.

##### edit config.yml file adding the service: 

``` yml
# app/config/config.yml
services:
    # ...

    login_authenticator:
        class:     Acme\DemoBundle\Security\LoginAuthenticator
        arguments: ["@security.encoder_factory", "@doctrine.orm.entity_manager"]
```

##### activate it in the firewalls section of the security configuration using the simple_form key: 

``` yml
# app/config/security.yml
security:
    # ...

    firewalls:
        secured_area:
            pattern: ^/admin
            # ...
            simple_form:
                authenticator: time_authenticator
                check_path:    login_check
                login_path:    login
```

##### if you are using fosUserBundle edit the configuration : 

``` yml
# app/config/security.yml
security:
    # ...

    firewalls:
        main:
            pattern: ^/
            simple_form:
                authenticator: login_authenticator
                provider: fos_userbundle
                csrf_provider: form.csrf_provider
            logout:       true
            anonymous:    true
```

