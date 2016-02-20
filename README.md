New Media Design & Development III
==================================

```
nmdad3.local/
├── app/                            # Ionic App
├── www/                            # Symfony App
└── README.md
```

Benodigdheden
-------------

[Artevelde Laravel Homestead][artestead]

Installatie
-----------

```
$ mkdir -p ~/Code && $_
$ git clone https://github.com/gdmgent/nmdad3.local
$ cd nmdad3.local/
$ artestead make --type symfony
$ vagrant up && vagrant ssh
vagrant@nmdad3$ composer_update
vagrant@nmdad3$ nmdad3 && cd www/
vagrant@nmdad3$ composer update
vagrant@nmdad3$ console artevelde:database:init --seed --migrate
vagrant@nmdad3$ exit
$ _
```

URI's
-----

 - [www.nmdad3.local](http://www.nmdad3.local)
 
[artestead]: http://www.gdm.gent/artestead/