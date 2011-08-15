
Authentication
==============

Configuration
-------------

1) Create app/config/services.yml

    services:
        security.authentication.provider.midgard:
        class:  Midgard\ConnectionBundle\Security\Authentication\Provider\AuthenticationProvider
        arguments: ['', %kernel.cache_dir%/security/nonces]
    
    security.authentication.listener.midgard:
        class:  Midgard\ConnectionBundle\Security\Firewall\AuthenticationListener
        arguments: [@security.context, @security.authentication.manager]

2) Create app/config/security.yml

    security:
        factories:
            - "%kernel.root_dir%/../vendor/Midgard/ConnectionBundle/Resources/config/security_factories.xml"
    
        role_hierarchy:
            ROLE_ADMIN:       ROLE_USER
            ROLE_SUPER_ADMIN: [ROLE_USER, ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]
    
        providers:
            midgard_provider:
                id: midgard.auth
    
        firewalls:
            dev:
                pattern:  ^/(_(profiler|wdt)|css|images|js)/
                security: false
     
            secured_area:
                pattern:    ^/demo/secured/login_check
                midgard: true

3) Import security.yml file in config.yml 

4) Add AuthenticationProvider service in Resources/config/connection.xml

    <services>
      <service id="midgard.auth" 
        class="Midgard\ConnectionBundle\Security\Authentication\Provider\AuthenticationProvider" 
        abstract="false" 
        public="true">
      </service>
    </services>

5) Create Resources/config/security_factories.xml (This file is imported in security.yml)

<container xmlns="http://symfony.com/schema/dic/services"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

  <services>
    <service id="security.authentication.factory.midgard"
      class="Midgard\ConnectionBundle\DependencyInjection\Security\Factory\MidgardSecurityFactory" 
      public="false">
      <tag name="security.listener.factory" />
  </service>
</services>
</container>

How does it work?
----------------

Symfony initializes configured Midgard\ConnectionBundle\Security\Firewall\AuthenticationListener.
This, is responsible to create authentication token, and get MidgardUser object from database.
At this point, user is fetched from database using its login and authentication type constraints.
AuthenticationListener assigns user object with token and sets token's password using given one.
Next, listener tries to authenticate user. This operation is actually done via Symfony's AuthenticationManager, 
which invokes authentication on configured authentication provider:
Midgard\ConnectionBundle\Security\Authentication\Provider\AuthenticationProvider.

AuthenticationProvider performs proper authentication, checking user's password.
To make this, it uses given token (which holds password from the form) and MidgardUser object (which 
holds password associated with that user) and. 





